<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentConfirmation extends Notification
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
            ->subject('Payment Confirmed - Premium Subscription Activated')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your payment has been confirmed and your premium subscription is now active.')
            ->line('Here are your subscription details:')
            ->line('• Amount Paid: ₦' . number_format($this->subscription->amount_paid, 2))
            ->line('• Start Date: ' . $this->subscription->starts_at->format('d M Y, H:i'))
            ->line('• Expiry Date: ' . $this->subscription->ends_at->format('d M Y, H:i'))
            ->line('• Days Remaining: ' . $this->subscription->daysRemaining() . ' days')
            ->line('')
            ->line('You now have access to all premium tips and the exclusive Telegram group.')
            ->action('View Premium Tips', route('bettor.tips.premium'))
            ->line('Thank you for subscribing!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'payment_confirmation',
            'subscription_id' => $this->subscription->id,
            'amount' => $this->subscription->amount_paid,
            'message' => 'Payment of ₦' . number_format($this->subscription->amount_paid, 2) . ' confirmed. Subscription active.',
            'action_url' => route('bettor.payment.history'),
        ];
    }
}
