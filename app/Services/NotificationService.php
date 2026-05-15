<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\User;
use App\Notifications\PaymentConfirmation;
use App\Notifications\SubscriptionExpiryWarning;
use App\Notifications\SubscriptionExpired;
use App\Notifications\WelcomeBettor;
use App\Notifications\AdminNewSubscription;
use App\Notifications\SubscriptionRenewalReminder;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send welcome notification to new bettor
     */
    public function sendWelcomeNotification(User $user): void
    {
        try {
            $user->notify(new WelcomeBettor($user));
            Log::info('Welcome notification sent', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Failed to send welcome notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send payment confirmation
     */
    public function sendPaymentConfirmation(Subscription $subscription): void
    {
        try {
            $subscription->user->notify(new PaymentConfirmation($subscription));

            // Also notify admins
            $admins = User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new AdminNewSubscription($subscription));
            }

            Log::info('Payment confirmation sent', [
                'subscription_id' => $subscription->id,
                'user_id' => $subscription->user_id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment confirmation', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send expiry warnings
     */
    public function sendExpiryWarnings(): void
    {
        $warningDays = [7, 3, 1]; // Send warnings 7, 3, and 1 day before expiry

        foreach ($warningDays as $days) {
            $expiringSoon = Subscription::where('is_active', true)
                ->where('status', 'active')
                ->whereDate('ends_at', now()->addDays($days))
                ->with('user')
                ->get();

            foreach ($expiringSoon as $subscription) {
                try {
                    $subscription->user->notify(
                        new SubscriptionExpiryWarning($subscription, $days)
                    );

                    Log::info('Expiry warning sent', [
                        'user_id' => $subscription->user_id,
                        'days_left' => $days
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send expiry warning', [
                        'user_id' => $subscription->user_id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }

    /**
     * Send expiry notifications
     */
    public function sendExpiryNotifications(): void
    {
        $justExpired = Subscription::where('is_active', false)
            ->where('status', 'expired')
            ->whereDate('ends_at', now()->subDay())
            ->with('user')
            ->get();

        foreach ($justExpired as $subscription) {
            try {
                $subscription->user->notify(new SubscriptionExpired($subscription));

                Log::info('Expiry notification sent', [
                    'user_id' => $subscription->user_id
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send expiry notification', [
                    'user_id' => $subscription->user_id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Send renewal reminders to expired users
     */
    public function sendRenewalReminders(): void
    {
        // Get users whose subscription expired 7 or 30 days ago
        $reminderDays = [7, 30];

        foreach ($reminderDays as $days) {
            $expiredUsers = User::role('bettor')
                ->whereHas('subscriptions', function ($query) use ($days) {
                    $query->where('status', 'expired')
                        ->whereDate('ends_at', now()->subDays($days));
                })
                ->whereDoesntHave('activeSubscription')
                ->get();

            foreach ($expiredUsers as $user) {
                try {
                    $user->notify(new SubscriptionRenewalReminder($user));

                    Log::info('Renewal reminder sent', [
                        'user_id' => $user->id,
                        'days_since_expiry' => $days
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send renewal reminder', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }

    /**
     * Process all scheduled notifications
     */
    public function processScheduledNotifications(): array
    {
        $results = [
            'expiry_warnings' => 0,
            'expiry_notifications' => 0,
            'renewal_reminders' => 0,
        ];

        $this->sendExpiryWarnings();
        $results['expiry_warnings'] = Subscription::where('is_active', true)
            ->where('status', 'active')
            ->whereIn(\DB::raw('DATEDIFF(ends_at, NOW())'), [7, 3, 1])
            ->count();

        $this->sendExpiryNotifications();
        $results['expiry_notifications'] = Subscription::where('is_active', false)
            ->where('status', 'expired')
            ->whereDate('ends_at', now()->subDay())
            ->count();

        $this->sendRenewalReminders();
        $results['renewal_reminders'] = User::role('bettor')
            ->whereDoesntHave('activeSubscription')
            ->whereHas('subscriptions', function ($query) {
                $query->where('status', 'expired')
                    ->whereIn(\DB::raw('DATEDIFF(NOW(), ends_at)'), [7, 30]);
            })
            ->count();

        return $results;
    }
}
