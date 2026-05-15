<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionRenewalReminder extends Notification
{
    use Queueable;

    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $daysSinceExpiry = $this->user->subscriptions()
            ->where('status', 'expired')
            ->latest()
            ->first()
            ?->ends_at
            ->diffInDays(now()) ?? 0;

        return (new MailMessage)
            ->subject('We Miss You! Special Offer Inside 🎯')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line("It's been {$daysSinceExpiry} days since your subscription expired.")
            ->line('We\'ve missed you! Here\'s what you\'ve been missing:')
            ->line('✓ Expert daily predictions')
            ->line('✓ High-odds selections')
            ->line('✓ Exclusive Telegram group insights')
            ->line('')
            ->line('Come back and start winning again!')
            ->action('Renew Subscription', route('bettor.plans'))
            ->line('Current subscription cost: ₦' . number_format(\App\Models\Setting::getValue('subscription_cost', 5000)))
            ->line('We look forward to having you back!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'renewal_reminder',
            'message' => 'We miss you! Renew your subscription to access premium tips again.',
            'action_url' => route('bettor.plans'),
        ];
    }
}
