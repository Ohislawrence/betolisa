<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeBettor extends Notification
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
        $telegramLink = \App\Models\Setting::getValue('telegram_group_link', '');

        return (new MailMessage)
            ->subject('Welcome to ' . config('app.name') . '! 🎉')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Welcome to ' . config('app.name') . ' - Your trusted source for football tips!')
            ->line('')
            ->line('Here\'s what you can do:')
            ->line('✓ Browse free daily tips')
            ->line('✓ Upgrade to premium for exclusive content')
            ->line('✓ Join our Telegram community')
            ->line('✓ Track your betting performance')
            ->line('')
            ->line('Get started with our free tips:')
            ->action('View Free Tips', route('bettor.tips.free'))
            ->line('')
            ->line('Ready for more? Upgrade to premium:')
            ->action('View Plans', route('bettor.plans'))
            ->line('')
            ->line('If you have any questions, feel free to reach out to our support team.')
            ->line('Good luck with your bets! 🍀');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'welcome',
            'message' => 'Welcome to ' . config('app.name') . '! Start exploring free tips.',
            'action_url' => route('bettor.tips.free'),
        ];
    }
}
