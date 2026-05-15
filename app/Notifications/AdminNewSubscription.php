<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminNewSubscription extends Notification
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
        $user = $this->subscription->user;

        return (new MailMessage)
            ->subject('New Premium Subscription')
            ->greeting('Hello Admin!')
            ->line('A new premium subscription has been created.')
            ->line('Subscriber Details:')
            ->line('• Name: ' . $user->name)
            ->line('• Email: ' . $user->email)
            ->line('• Telegram: ' . $user->telegram_number)
            ->line('• Amount: ₦' . number_format($this->subscription->amount_paid, 2))
            ->line('• Method: ' . ucfirst($this->subscription->payment_method))
            ->line('• Duration: ' . $this->subscription->starts_at->format('d M Y') . ' to ' . $this->subscription->ends_at->format('d M Y'))
            ->action('View Subscription', route('admin.subscriptions.show', $this->subscription))
            ->line('The user has been added to the Telegram group.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_subscription',
            'subscription_id' => $this->subscription->id,
            'user_name' => $this->subscription->user->name,
            'amount' => $this->subscription->amount_paid,
            'message' => 'New subscription from ' . $this->subscription->user->name . ' (₦' . number_format($this->subscription->amount_paid, 2) . ')',
            'action_url' => route('admin.subscriptions.show', $this->subscription),
        ];
    }
}
