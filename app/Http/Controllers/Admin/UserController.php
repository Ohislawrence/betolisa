<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * List all bettors
     */
    public function index(Request $request)
    {
        $query = User::role('bettor')->with('activeSubscription');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telegram_number', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter by subscription status
        if ($request->filled('subscription_status')) {
            if ($request->subscription_status === 'active') {
                $query->whereHas('activeSubscription');
            } elseif ($request->subscription_status === 'inactive') {
                $query->whereDoesntHave('activeSubscription');
            }
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === '1');
        }

        $bettors = $query->latest()->paginate(15);

        // Get stats
        $stats = [
            'total' => User::role('bettor')->count(),
            'active' => User::role('bettor')->where('is_active', true)->count(),
            'subscribed' => User::role('bettor')->whereHas('activeSubscription')->count(),
            'unsubscribed' => User::role('bettor')->whereDoesntHave('activeSubscription')->count(),
        ];

        return view('admin.users.index', compact('bettors', 'stats'));
    }

    /**
     * Show create bettor form
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store new bettor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telegram_number' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users'],
            'password' => ['required', Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        $user = User::create($validated);
        $user->assignRole('bettor');

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Bettor created successfully.');
    }

    /**
     * Show bettor details
     */
    public function show(User $user)
    {
        if (!$user->hasRole('bettor')) {
            abort(404);
        }

        $user->load(['subscriptions' => function($query) {
            $query->latest()->take(10);
        }, 'transactions' => function($query) {
            $query->latest()->take(10);
        }]);

        $activeSubscription = $user->activeSubscription;
        $totalSpent = $user->transactions()->successful()->sum('amount');

        return view('admin.users.show', compact('user', 'activeSubscription', 'totalSpent'));
    }

    /**
     * Show edit bettor form
     */
    public function edit(User $user)
    {
        if (!$user->hasRole('bettor')) {
            abort(404);
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update bettor
     */
    public function update(Request $request, User $user)
    {
        if (!$user->hasRole('bettor')) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'telegram_number' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'is_active' => ['boolean'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Bettor updated successfully.');
    }

    /**
     * Toggle bettor active status
     */
    public function toggleStatus(User $user)
    {
        if (!$user->hasRole('bettor')) {
            abort(404);
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Bettor {$status} successfully.");
    }

    /**
     * Reset bettor password
     */
    public function resetPassword(Request $request, User $user)
    {
        if (!$user->hasRole('bettor')) {
            abort(404);
        }

        $validated = $request->validate([
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->back()->with('success', 'Password reset successfully.');
    }

    /**
     * Export bettors list
     */
    public function export(Request $request)
    {
        $bettors = User::role('bettor')
            ->with('activeSubscription')
            ->get()
            ->map(function($user) {
                return [
                    'Name' => $user->name,
                    'Email' => $user->email,
                    'Telegram' => $user->telegram_number,
                    'Phone' => $user->phone,
                    'Username' => $user->username,
                    'Status' => $user->is_active ? 'Active' : 'Inactive',
                    'Subscription' => $user->hasActiveSubscription() ? 'Active' : 'None',
                    'Registered' => $user->created_at->format('Y-m-d'),
                ];
            });

        // Generate CSV
        $filename = 'bettors-' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($bettors) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($bettors->first()));
            foreach ($bettors as $bettor) {
                fputcsv($file, $bettor);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete bettor
     */
    public function destroy(User $user)
    {
        if (!$user->hasRole('bettor')) {
            abort(404);
        }

        // Don't allow deleting if they have active subscription
        if ($user->hasActiveSubscription()) {
            return redirect()->back()
                ->with('error', 'Cannot delete bettor with active subscription. Cancel subscription first.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Bettor deleted successfully.');
    }
}
