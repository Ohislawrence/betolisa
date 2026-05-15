<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AddToTelegramGroup implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;
    public int $tries = 3;
    public int $backoff = 60; // Wait 60 seconds between retries

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(TelegramService $telegramService): void
    {
        if (!$this->user->telegram_number) {
            Log::warning('User has no Telegram number', ['user_id' => $this->user->id]);
            return;
        }

        Log::info('Processing Telegram add for user', [
            'user_id' => $this->user->id,
            'telegram' => $this->user->telegram_number,
            'attempt' => $this->attempts()
        ]);

        $result = $telegramService->addMemberToGroup($this->user->telegram_number);

        if (!$result['success']) {
            Log::error('Failed to add user to Telegram group', [
                'user_id' => $this->user->id,
                'telegram' => $this->user->telegram_number,
                'result' => $result,
                'attempt' => $this->attempts()
            ]);

            // If we have retries left, throw exception to retry
            if ($this->attempts() < $this->tries) {
                throw new \Exception($result['message']);
            }
        } else {
            Log::info('User added to Telegram group successfully', [
                'user_id' => $this->user->id,
                'telegram' => $this->user->telegram_number
            ]);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('AddToTelegramGroup job failed permanently', [
            'user_id' => $this->user->id,
            'telegram' => $this->user->telegram_number,
            'error' => $exception->getMessage()
        ]);
    }
}
