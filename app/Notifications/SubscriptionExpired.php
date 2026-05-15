<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionExpired extends Notification
{
    use Queueable;

    protected Subscription $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Subscription Has Expired')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your premium subscription has expired.')
            ->line('You no longer have access to:')
            ->line('• Premium tips')
            ->line('• Telegram group')
            ->line('')
            ->line('But don\'t worry! You can renew anytime.')
            ->action('Renew Now', route('bettor.plans'))
            ->line('We hope to see you back soon!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'subscription_expired',
            'subscription_id' => $this->subscription->id,
            'message' => 'Your subscription has expired. Renew to regain access.',
            'action_url' => route('bettor.plans'),
        ];
    }
}
