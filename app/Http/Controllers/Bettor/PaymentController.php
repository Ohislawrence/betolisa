<?php

namespace App\Http\Controllers\Bettor;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Transaction;
use App\Services\PaystackService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected PaystackService $paystackService;
    protected SubscriptionService $subscriptionService;

    public function __construct(PaystackService $paystackService, SubscriptionService $subscriptionService)
    {
        $this->paystackService = $paystackService;
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Show subscription plans/pricing page
     */
    public function plans()
    {
        $cost = Setting::getValue('subscription_cost', 5000);
        $duration = Setting::getValue('subscription_duration_days', 30);
        $user = auth()->user();
        $activeSubscription = $user->activeSubscription;

        return view('bettor.plans', compact('cost', 'duration', 'activeSubscription'));
    }

    /**
     * Initialize payment
     */
    public function initialize(Request $request)
    {
        $user = auth()->user();
        $amount = Setting::getValue('subscription_cost', 5000);

        // Generate unique reference
        $reference = 'SUB-' . strtoupper(Str::random(10)) . '-' . time();

        try {
            // Create pending transaction
            $this->subscriptionService->createTransaction(
                $user,
                $reference,
                $amount,
                [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'subscription_type' => 'premium_monthly',
                ]
            );

            // Initialize Paystack transaction
            $response = $this->paystackService->initializeTransaction([
                'email' => $user->email,
                'amount' => $amount,
                'reference' => $reference,
                'callback_url' => route('bettor.payment.callback'),
                'metadata' => [
                    'user_id' => $user->id,
                    'reference' => $reference,
                ],
            ]);

            if (!$response['success']) {
                return redirect()->back()->with('error', $response['message']);
            }

            // Redirect to Paystack payment page
            return redirect($response['data']['authorization_url']);

        } catch (\Exception $e) {
            Log::error('Payment initialization failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Failed to initialize payment. Please try again.');
        }
    }

    /**
     * Handle payment callback
     */
    public function callback(Request $request)
    {
        $reference = $request->get('reference');

        if (!$reference) {
            return redirect()->route('bettor.payment.history')
                ->with('error', 'Invalid payment reference.');
        }

        try {
            // Verify transaction
            $response = $this->paystackService->verifyTransaction($reference);

            if (!$response['success']) {
                Log::error('Payment verification failed', [
                    'reference' => $reference,
                    'response' => $response
                ]);
                return redirect()->route('bettor.payment.history')
                    ->with('error', 'Payment verification failed.');
            }

            $paymentData = $response['data'];

            // Check if payment was successful
            if ($paymentData['status'] !== 'success') {
                // Update transaction status
                Transaction::where('reference', $reference)->update([
                    'status' => 'failed',
                    'gateway_response' => $paymentData,
                ]);

                return redirect()->route('bettor.payment.history')
                    ->with('error', 'Payment was not successful. Please try again.');
            }

            // Process successful payment
            $subscription = $this->subscriptionService->processSuccessfulPayment(
                $reference,
                $paymentData
            );

            if (!$subscription) {
                return redirect()->route('bettor.payment.history')
                    ->with('error', 'Failed to activate subscription. Please contact support.');
            }

            return redirect()->route('bettor.payment.success')
                ->with('subscription', $subscription);

        } catch (\Exception $e) {
            Log::error('Payment callback error', [
                'reference' => $reference,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('bettor.payment.history')
                ->with('error', 'An error occurred while processing your payment. Please contact support.');
        }
    }

    /**
     * Payment success page
     */
    public function success()
    {
        $subscription = session('subscription');

        if (!$subscription) {
            $subscription = auth()->user()->activeSubscription;
        }

        return view('bettor.payment-success', compact('subscription'));
    }

    /**
     * Payment history
     */
    public function history()
    {
        $transactions = auth()->user()->transactions()
            ->with('subscription')
            ->latest()
            ->paginate(10);

        $activeSubscription = auth()->user()->activeSubscription;

        return view('bettor.payment-history', compact('transactions', 'activeSubscription'));
    }
}
