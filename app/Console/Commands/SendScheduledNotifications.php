<?php

namespace App\Console\Commands;

use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendScheduledNotifications extends Command
{
    protected $signature = 'notifications:send-scheduled';
    protected $description = 'Send scheduled notifications (expiry warnings, reminders, etc.)';

    public function handle(NotificationService $notificationService): int
    {
        $this->info('Sending scheduled notifications...');

        try {
            $results = $notificationService->processScheduledNotifications();

            $this->info('Notification Summary:');
            $this->table(
                ['Type', 'Count'],
                [
                    ['Expiry Warnings', $results['expiry_warnings']],
                    ['Expiry Notifications', $results['expiry_notifications']],
                    ['Renewal Reminders', $results['renewal_reminders']],
                ]
            );

            Log::info('Scheduled notifications sent', $results);

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            Log::error('Failed to send scheduled notifications', ['error' => $e->getMessage()]);

            return Command::FAILURE;
        }
    }
}
