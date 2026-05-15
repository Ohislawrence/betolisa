<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionExpiryWarning extends Notification
{
    use Queueable;

    protected Subscription $subscription;
    protected int $daysLeft;

    public function __construct(Subscription $subscription, int $daysLeft)
    {
        $this->subscription = $subscription;
        $this->daysLeft = $daysLeft;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->daysLeft <= 1
            ? '⚠️ Your Subscription Expires Tomorrow!'
            : "Your Subscription Expires in {$this->daysLeft} Days";

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line("Your premium subscription will expire in {$this->daysLeft} day(s).")
            ->line('Subscription Details:')
            ->line('• Expiry Date: ' . $this->subscription->ends_at->format('d M Y, H:i'))
            ->line('')
            ->line('Renew now to continue enjoying:')
            ->line('✓ Daily premium tips')
            ->line('✓ Exclusive Telegram group access')
            ->line('✓ High odds selections')
            ->action('Renew Subscription', route('bettor.plans'))
            ->line('Don\'t miss out on our expert predictions!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'subscription_expiry_warning',
            'subscription_id' => $this->subscription->id,
            'days_left' => $this->daysLeft,
            'message' => "Your subscription expires in {$this->daysLeft} day(s). Renew now!",
            'action_url' => route('bettor.plans'),
        ];
    }
}
