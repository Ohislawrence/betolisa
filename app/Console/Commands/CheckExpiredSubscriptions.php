<?php

namespace App\Console\Commands;

use App\Jobs\RemoveFromTelegramGroup;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:check-expired';
    protected $description = 'Check and process expired subscriptions, removing users from Telegram group';

    public function handle(SubscriptionService $subscriptionService): int
    {
        $this->info('Checking for expired subscriptions...');

        // Get subscriptions that have expired but are still marked as active
        $expiredSubscriptions = Subscription::where('is_active', true)
            ->where('status', 'active')
            ->where('ends_at', '<', now())
            ->get();

        $this->info("Found {$expiredSubscriptions->count()} expired subscriptions.");

        $processedCount = 0;
        foreach ($expiredSubscriptions as $subscription) {
            $this->processExpiredSubscription($subscription);
            $processedCount++;
        }

        // Also run the general expiry check
        $autoExpired = $subscriptionService->expireOverdueSubscriptions();

        $this->info("Processed {$processedCount} subscriptions manually.");
        $this->info("Auto-expired {$autoExpired} additional subscriptions.");

        Log::info('Expired subscriptions check completed', [
            'manually_processed' => $processedCount,
            'auto_expired' => $autoExpired
        ]);

        return Command::SUCCESS;
    }

    protected function processExpiredSubscription(Subscription $subscription): void
    {
        $this->line("Processing subscription #{$subscription->id} for user #{$subscription->user_id}");

        // Update subscription status
        $subscription->update([
            'status' => 'expired',
            'is_active' => false,
        ]);

        // Run synchronously — no queue worker needed
        try {
            RemoveFromTelegramGroup::dispatchSync($subscription->user);
            $this->line("✓ Removed user #{$subscription->user_id} from Telegram group");
        } catch (\Exception $e) {
            Log::error('Telegram removal failed', [
                'user_id' => $subscription->user_id,
                'error'   => $e->getMessage(),
            ]);
            $this->warn("! Telegram removal failed for user #{$subscription->user_id}: {$e->getMessage()}");
        }
    }
}
