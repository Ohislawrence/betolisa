<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\AddToTelegramGroup;
use App\Services\NotificationService;

class SubscriptionService
{
    /**
     * Create a new subscription
     */
    public function createSubscription(User $user, string $reference, float $amount, array $paymentDetails = [], ?User $creator = null): Subscription
    {
        $durationDays = Setting::getValue('subscription_duration_days', 30);

        // Check if user has existing active subscription
        $existingSubscription = $user->activeSubscription;

        if ($existingSubscription) {
            // Extend from current end date if still active
            $startsAt = $existingSubscription->ends_at;
            $endsAt = $existingSubscription->ends_at->copy()->addDays($durationDays);

            // Deactivate old subscription
            $existingSubscription->update([
                'is_active' => false,
                'status' => 'expired'
            ]);
        } else {
            $startsAt = now();
            $endsAt = now()->addDays($durationDays);
        }

        // Create new subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'active',
            'is_active' => true,
            'transaction_ref' => $reference,
            'amount_paid' => $amount,
            'payment_method' => 'paystack',
            'payment_details' => $paymentDetails,
            'created_by' => $creator ? $creator->id : null,
        ]);

        if ($subscription) {
            // Run synchronously — no queue worker needed
            AddToTelegramGroup::dispatchSync($user);
        }

        if ($subscription) {
        // Send notifications
            $notificationService = app(NotificationService::class);
            $notificationService->sendPaymentConfirmation($subscription);
        }

        return $subscription;
    }

    /**
     * Create a transaction record
     */
    public function createTransaction(User $user, string $reference, float $amount, array $metadata = []): Transaction
    {
        return Transaction::create([
            'user_id' => $user->id,
            'reference' => $reference,
            'amount' => $amount,
            'currency' => 'NGN',
            'status' => 'pending',
            'metadata' => $metadata,
        ]);
    }

    /**
     * Process successful payment
     */
    public function processSuccessfulPayment(string $reference, array $paymentData): ?Subscription
    {
        return DB::transaction(function () use ($reference, $paymentData) {
            // Find transaction
            $transaction = Transaction::where('reference', $reference)->first();

            if (!$transaction) {
                Log::error('Transaction not found for reference: ' . $reference);
                return null;
            }

            // Check if already processed
            if ($transaction->status === 'successful') {
                Log::info('Transaction already processed: ' . $reference);
                return $transaction->subscription;
            }

            // Update transaction
            $transaction->update([
                'status' => 'successful',
                'payment_channel' => $paymentData['channel'] ?? null,
                'gateway_response' => $paymentData,
                'paid_at' => $paymentData['paid_at'] ?? now(),
            ]);

            // Get subscription amount
            $amount = Setting::getValue('subscription_cost', 5000);

            // Create subscription
            $subscription = $this->createSubscription(
                $transaction->user,
                $reference,
                $amount,
                $paymentData
            );

            // Link transaction to subscription
            $transaction->update(['subscription_id' => $subscription->id]);

            Log::info('Subscription created successfully', [
                'user_id' => $subscription->user_id,
                'subscription_id' => $subscription->id,
                'reference' => $reference
            ]);

            return $subscription;
        });
    }

    /**
     * Check and expire overdue subscriptions
     */
    public function expireOverdueSubscriptions(): int
    {
        $expiredCount = Subscription::where('is_active', true)
            ->where('status', 'active')
            ->where('ends_at', '<', now())
            ->update([
                'status' => 'expired',
                'is_active' => false,
            ]);

        Log::info('Expired subscriptions processed', ['count' => $expiredCount]);

        return $expiredCount;
    }

    /**
     * Get subscription statistics
     */
    public function getStats(): array
    {
        return [
            'total_active' => Subscription::active()->count(),
            'total_expired' => Subscription::where('status', 'expired')->count(),
            'total_revenue' => Transaction::successful()->sum('amount'),
            'revenue_this_month' => Transaction::successful()
                ->whereMonth('created_at', now()->month)
                ->sum('amount'),
        ];
    }
}
