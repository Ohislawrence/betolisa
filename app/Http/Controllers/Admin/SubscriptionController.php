<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Services\SubscriptionService;
use App\Services\TelegramService;
use App\Jobs\AddToTelegramGroup;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    protected SubscriptionService $subscriptionService;
    protected TelegramService $telegramService;

    public function __construct(SubscriptionService $subscriptionService, TelegramService $telegramService)
    {
        $this->subscriptionService = $subscriptionService;
        $this->telegramService = $telegramService;
    }

    /**
     * Approve a pending bank transfer and activate subscription
     */
    public function approveTransfer(Transaction $transaction)
    {
        if ($transaction->payment_channel !== 'bank_transfer' || $transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'This transaction cannot be approved.');
        }

        $subscription = $this->subscriptionService->processSuccessfulPayment(
            $transaction->reference,
            [
                'channel'  => 'bank_transfer',
                'status'   => 'success',
                'paid_at'  => now()->toIso8601String(),
                'amount'   => $transaction->amount * 100, // kobo
                'currency' => 'NGN',
            ]
        );

        if (!$subscription) {
            return redirect()->back()->with('error', 'Failed to activate subscription. Check logs.');
        }

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Transfer approved and subscription activated for ' . $transaction->user->name . '.');
    }

    /**
     * Reject (delete) a pending bank transfer
     */
    public function rejectTransfer(Transaction $transaction)
    {
        if ($transaction->payment_channel !== 'bank_transfer' || $transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'This transaction cannot be rejected.');
        }

        $userName = $transaction->user->name;
        $transaction->update(['status' => 'failed']);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Transfer from ' . $userName . ' has been rejected.');
    }

    /**
     * List all subscriptions
     */
    public function index(Request $request)
    {
        $query = Subscription::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by user
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $subscriptions = $query->latest()->paginate(15);
        $stats = $this->subscriptionService->getStats();

        $pendingTransfers = Transaction::with('user')
            ->where('payment_channel', 'bank_transfer')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.subscriptions.index', compact('subscriptions', 'stats', 'pendingTransfers'));
    }

    /**
     * Show subscription details
     */
    public function show(Subscription $subscription)
    {
        $subscription->load(['user', 'transaction', 'creator']);
        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Manual subscribe bettor
     */
    public function create()
    {
        $bettors = User::role('bettor')
            ->where('is_active', true)
            ->whereDoesntHave('activeSubscription')
            ->get();

        return view('admin.subscriptions.create', compact('bettors'));
    }

    /**
     * Store manual subscription
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'duration_days' => 'required|integer|min:1|max:365',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,bank_transfer,manual,other',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $user = User::findOrFail($validated['user_id']);

        // Generate reference
        $reference = 'ADMIN-' . strtoupper(uniqid()) . '-MANUAL';

        // Create pending transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'reference' => $reference,
            'amount' => $validated['amount_paid'],
            'currency' => 'NGN',
            'status' => 'successful',
            'payment_channel' => $validated['payment_method'],
            'paid_at' => now(),
        ]);

        // Create subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'starts_at' => now(),
            'ends_at' => now()->addDays($validated['duration_days']),
            'status' => 'active',
            'is_active' => true,
            'transaction_ref' => $reference,
            'amount_paid' => $validated['amount_paid'],
            'payment_method' => $validated['payment_method'],
            'payment_details' => [
                'method' => $validated['payment_method'],
                'notes' => $validated['admin_notes'] ?? '',
                'created_by_admin' => auth()->user()->name,
            ],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'created_by' => auth()->id(),
        ]);

        // Link transaction to subscription
        $transaction->update(['subscription_id' => $subscription->id]);

        // Queue Telegram addition
        AddToTelegramGroup::dispatch($user);

        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Manual subscription created successfully.');
    }

    /**
     * Cancel subscription
     */
    public function cancel(Subscription $subscription)
    {
        if ($subscription->status !== 'active') {
            return redirect()->back()->with('error', 'Only active subscriptions can be cancelled.');
        }

        $subscription->update([
            'status' => 'cancelled',
            'is_active' => false,
        ]);

        // Queue Telegram removal
        \App\Jobs\RemoveFromTelegramGroup::dispatch($subscription->user);

        return redirect()->back()->with('success', 'Subscription cancelled successfully.');
    }

    /**
     * Extend subscription
     */
    public function extend(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'additional_days' => 'required|integer|min:1|max:365',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        if ($subscription->status !== 'active') {
            return redirect()->back()->with('error', 'Only active subscriptions can be extended.');
        }

        $subscription->update([
            'ends_at' => $subscription->ends_at->addDays($validated['additional_days']),
            'amount_paid' => $subscription->amount_paid + $validated['amount_paid'],
            'admin_notes' => ($subscription->admin_notes ? $subscription->admin_notes . "\n" : '')
                . "Extended by {$validated['additional_days']} days on " . now()->format('d M Y'),
        ]);

        return redirect()->back()->with('success', 'Subscription extended successfully.');
    }

    /**
     * Revenue report
     */
    public function revenue(Request $request)
    {
        $period = $request->get('period', 'monthly');

        switch ($period) {
            case 'daily':
                $transactions = Transaction::successful()
                    ->whereDate('created_at', '>=', now()->subDays(30))
                    ->selectRaw('DATE(created_at) as date, SUM(amount) as total, COUNT(*) as count')
                    ->groupBy('date')
                    ->orderBy('date', 'desc')
                    ->get();
                break;
            case 'weekly':
                $transactions = Transaction::successful()
                    ->whereDate('created_at', '>=', now()->subWeeks(12))
                    ->selectRaw('YEARWEEK(created_at) as week, SUM(amount) as total, COUNT(*) as count')
                    ->groupBy('week')
                    ->orderBy('week', 'desc')
                    ->get();
                break;
            case 'yearly':
                $transactions = Transaction::successful()
                    ->whereDate('created_at', '>=', now()->subYears(5))
                    ->selectRaw('YEAR(created_at) as year, SUM(amount) as total, COUNT(*) as count')
                    ->groupBy('year')
                    ->orderBy('year', 'desc')
                    ->get();
                break;
            default: // monthly
                $transactions = Transaction::successful()
                    ->whereDate('created_at', '>=', now()->subMonths(12))
                    ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(amount) as total, COUNT(*) as count')
                    ->groupBy('month')
                    ->orderBy('month', 'desc')
                    ->get();
        }

        $totalRevenue = Transaction::successful()->sum('amount');
        $thisMonthRevenue = Transaction::successful()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        $thisMonthSubscriptions = Subscription::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return view('admin.subscriptions.revenue', compact(
            'transactions', 'period', 'totalRevenue',
            'thisMonthRevenue', 'thisMonthSubscriptions'
        ));
    }
}
