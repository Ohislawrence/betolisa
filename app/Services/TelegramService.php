<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Subscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    protected string $botToken;
    protected string $groupId;
    protected string $apiUrl;

    public function __construct()
    {
        $this->botToken = Setting::getValue('telegram_bot_token', '');
        $this->groupId = Setting::getValue('telegram_group_id', '');
        $this->apiUrl = 'https://api.telegram.org/bot' . $this->botToken;
    }

    /**
     * Check if Telegram is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->botToken) && !empty($this->groupId);
    }

    /**
     * Add member to group using invite link
     */
    public function addMemberToGroup(string $telegramNumber): array
    {
        if (!$this->isConfigured()) {
            Log::error('Telegram not configured');
            return [
                'success' => false,
                'message' => 'Telegram is not configured properly.'
            ];
        }

        try {
            // First, get the invite link if we don't have one
            $inviteLink = $this->getGroupInviteLink();

            if (!$inviteLink) {
                return [
                    'success' => false,
                    'message' => 'Could not generate invite link.'
                ];
            }

            // Send message to user with invite link
            // Note: For this to work, the user must have started a chat with the bot
            $userId = $this->getUserIdFromTelegramNumber($telegramNumber);

            if ($userId) {
                $this->sendMessage(
                    $userId,
                    "🎉 Welcome to the Premium Tips Group!\n\n" .
                    "Here's your exclusive invite link:\n" .
                    $inviteLink . "\n\n" .
                    "This link will expire when your subscription ends.\n" .
                    "Enjoy premium tips! 📊⚽"
                );
            }

            Log::info('Member added to Telegram group', [
                'telegram_number' => $telegramNumber,
                'invite_link' => $inviteLink
            ]);

            return [
                'success' => true,
                'message' => 'Invite link generated and sent successfully.',
                'data' => [
                    'invite_link' => $inviteLink,
                ]
            ];
        } catch (\Exception $e) {
            Log::error('Telegram add member error', [
                'telegram_number' => $telegramNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to add member to group: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Remove member from group
     */
    public function removeMemberFromGroup(string $telegramNumber): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Telegram is not configured properly.'
            ];
        }

        try {
            $userId = $this->getUserIdFromTelegramNumber($telegramNumber);

            if (!$userId) {
                // If we can't find the user, try to revoke the invite link
                return [
                    'success' => true,
                    'message' => 'User not found in Telegram. They may have left already.'
                ];
            }

            // Ban and unban to remove from group
            $this->banChatMember($this->groupId, $userId);
            $this->unbanChatMember($this->groupId, $userId);

            // Send notification
            $this->sendMessage(
                $userId,
                "⚠️ Your premium subscription has expired.\n\n" .
                "You have been removed from the Premium Tips Group.\n" .
                "To rejoin, please renew your subscription on our platform."
            );

            Log::info('Member removed from Telegram group', [
                'telegram_number' => $telegramNumber,
                'user_id' => $userId
            ]);

            return [
                'success' => true,
                'message' => 'Member removed successfully.'
            ];
        } catch (\Exception $e) {
            Log::error('Telegram remove member error', [
                'telegram_number' => $telegramNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to remove member: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create a single-use invite link for one specific user.
     * The link expires when their subscription ends (or in 48 h if no subscription found).
     * This ensures unpaid users cannot reuse the same link.
     */
    public function createUserInviteLink(\App\Models\User $user): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $subscription = $user->activeSubscription;
            $expireDate   = $subscription
                ? $subscription->ends_at->timestamp
                : now()->addHours(48)->timestamp;

            $response = Http::post($this->apiUrl . '/createChatInviteLink', [
                'chat_id'              => $this->groupId,
                'member_limit'         => 1,
                'expire_date'          => $expireDate,
                'creates_join_request' => false,
            ]);

            $data = $response->json();

            if ($response->successful() && $data['ok']) {
                Log::info('Per-user Telegram invite link created', [
                    'user_id'   => $user->id,
                    'expire_at' => $expireDate,
                ]);
                return $data['result']['invite_link'];
            }

            Log::error('Failed to create per-user invite link', [
                'user_id'  => $user->id,
                'response' => $data,
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('createUserInviteLink error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get group invite link
     */
    public function getGroupInviteLink(): ?string
    {
        try {
            // Check if we have a stored invite link
            $storedLink = Setting::getValue('telegram_group_link', '');

            if (!empty($storedLink)) {
                return $storedLink;
            }

            // Create a new invite link via API
            $response = Http::post($this->apiUrl . '/createChatInviteLink', [
                'chat_id' => $this->groupId,
                'member_limit' => 1,
                'creates_join_request' => false,
            ]);

            $data = $response->json();

            if ($response->successful() && $data['ok']) {
                $inviteLink = $data['result']['invite_link'];

                // Store the invite link
                Setting::setValue('telegram_group_link', $inviteLink, 'string');

                return $inviteLink;
            }

            Log::error('Failed to create invite link', ['response' => $data]);
            return null;
        } catch (\Exception $e) {
            Log::error('Get invite link error', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get user ID from Telegram number/username
     */
    protected function getUserIdFromTelegramNumber(string $telegramNumber): ?string
    {
        // Remove @ if present
        $telegramNumber = ltrim($telegramNumber, '@');

        try {
            // Try to get chat info
            $response = Http::get($this->apiUrl . '/getChat', [
                'chat_id' => '@' . $telegramNumber,
            ]);

            $data = $response->json();

            if ($response->successful() && $data['ok']) {
                return $data['result']['id'];
            }

            // If it's a numeric ID, try direct lookup
            if (is_numeric($telegramNumber)) {
                return $telegramNumber;
            }

            Log::warning('Could not find Telegram user', [
                'telegram_number' => $telegramNumber,
                'response' => $data
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Get user ID error', [
                'telegram_number' => $telegramNumber,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Send message to a user
     */
    public function sendMessage(string $chatId, string $text): bool
    {
        try {
            $response = Http::post($this->apiUrl . '/sendMessage', [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => 'HTML',
            ]);

            $data = $response->json();
            return $response->successful() && $data['ok'];
        } catch (\Exception $e) {
            Log::error('Send message error', [
                'chat_id' => $chatId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Ban chat member
     */
    protected function banChatMember(string $chatId, string $userId): bool
    {
        try {
            $response = Http::post($this->apiUrl . '/banChatMember', [
                'chat_id' => $chatId,
                'user_id' => $userId,
            ]);

            $data = $response->json();
            return $response->successful() && $data['ok'];
        } catch (\Exception $e) {
            Log::error('Ban member error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Unban chat member
     */
    protected function unbanChatMember(string $chatId, string $userId): bool
    {
        try {
            $response = Http::post($this->apiUrl . '/unbanChatMember', [
                'chat_id' => $chatId,
                'user_id' => $userId,
            ]);

            $data = $response->json();
            return $response->successful() && $data['ok'];
        } catch (\Exception $e) {
            Log::error('Unban member error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Check if user is member of group
     */
    public function isMemberOfGroup(string $telegramNumber): bool
    {
        try {
            $userId = $this->getUserIdFromTelegramNumber($telegramNumber);

            if (!$userId) {
                return false;
            }

            $response = Http::get($this->apiUrl . '/getChatMember', [
                'chat_id' => $this->groupId,
                'user_id' => $userId,
            ]);

            $data = $response->json();

            if ($response->successful() && $data['ok']) {
                $status = $data['result']['status'];
                return in_array($status, ['creator', 'administrator', 'member']);
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Check membership error', [
                'telegram_number' => $telegramNumber,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get group member count
     */
    public function getGroupMemberCount(): int
    {
        try {
            $response = Http::get($this->apiUrl . '/getChatMemberCount', [
                'chat_id' => $this->groupId,
            ]);

            $data = $response->json();

            if ($response->successful() && $data['ok']) {
                return $data['result'];
            }

            return 0;
        } catch (\Exception $e) {
            Log::error('Get member count error', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    /**
     * Test Telegram connection
     */
    public function testConnection(): array
    {
        try {
            $response = Http::get($this->apiUrl . '/getMe');
            $data = $response->json();

            if ($response->successful() && $data['ok']) {
                return [
                    'success' => true,
                    'message' => 'Connected successfully! Bot: @' . $data['result']['username'],
                    'data' => $data['result']
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to connect: ' . ($data['description'] ?? 'Unknown error')
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }
}
