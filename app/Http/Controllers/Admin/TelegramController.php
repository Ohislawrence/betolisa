<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Http\Request;


class TelegramController extends Controller
{
    protected TelegramService $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }

    /**
     * Show Telegram settings page
     */
    public function settings()
    {
        $botToken = Setting::getValue('telegram_bot_token', '');
        $groupId = Setting::getValue('telegram_group_id', '');
        $groupLink = Setting::getValue('telegram_group_link', '');
        $memberCount = $this->telegramService->isConfigured()
            ? $this->telegramService->getGroupMemberCount()
            : 0;

        $freeGroupName       = Setting::getValue('free_telegram_group_name', '');
        $freeGroupLink       = Setting::getValue('free_telegram_group_link', '');
        $freeGroupMessage    = Setting::getValue('free_telegram_popup_message', 'Join our FREE Telegram group for daily football tips!');
        $freeGroupEnabled    = (bool) Setting::getValue('free_telegram_popup_enabled', false);
        $adminTelegramHandle = Setting::getValue('admin_telegram_handle', '');

        // Users with an active subscription — these are the expected channel members
        $channelMembers = User::with(['activeSubscription'])
            ->whereHas('activeSubscription')
            ->orderBy('name')
            ->get();

        return view('admin.telegram.settings', compact(
            'botToken', 'groupId', 'groupLink', 'memberCount',
            'freeGroupName', 'freeGroupLink', 'freeGroupMessage', 'freeGroupEnabled',
            'adminTelegramHandle', 'channelMembers'
        ));
    }

    /**
     * Update Telegram settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'telegram_bot_token'   => 'required|string',
            'telegram_group_id'    => 'required|string',
            'admin_telegram_handle' => 'nullable|string|max:100',
        ]);

        Setting::setValue('telegram_bot_token', $validated['telegram_bot_token'], 'string');
        Setting::setValue('telegram_group_id', $validated['telegram_group_id'], 'string');
        Setting::setValue('admin_telegram_handle', $validated['admin_telegram_handle'] ?? '', 'string');

        return redirect()->back()->with('success', 'Telegram settings updated successfully.');
    }

    /**
     * Update free Telegram group popup settings
     */
    public function updateFreeGroup(Request $request)
    {
        $validated = $request->validate([
            'free_telegram_group_name'    => 'required|string|max:255',
            'free_telegram_group_link'    => 'required|url|max:500',
            'free_telegram_popup_message' => 'required|string|max:500',
            'free_telegram_popup_enabled' => 'nullable|boolean',
        ]);

        Setting::setValue('free_telegram_group_name', $validated['free_telegram_group_name'], 'string');
        Setting::setValue('free_telegram_group_link', $validated['free_telegram_group_link'], 'string');
        Setting::setValue('free_telegram_popup_message', $validated['free_telegram_popup_message'], 'string');
        Setting::setValue('free_telegram_popup_enabled', $request->boolean('free_telegram_popup_enabled') ? '1' : '0', 'boolean');

        return redirect()->back()->with('success', 'Free Telegram group popup settings saved.');
    }

    /**
     * Test Telegram connection
     */
    public function testConnection()
    {
        $result = $this->telegramService->testConnection();

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Get invite link
     */
    public function getInviteLink()
    {
        $inviteLink = $this->telegramService->getGroupInviteLink();

        if ($inviteLink) {
            Setting::setValue('telegram_group_link', $inviteLink, 'string');
            return redirect()->back()->with('success', 'Invite link generated: ' . $inviteLink);
        }

        return redirect()->back()->with('error', 'Failed to generate invite link.');
    }

    /**
     * Resend Telegram invite to a specific subscriber.
     * Generates a fresh, single-use, expiring invite link — not the shared global link.
     */
    public function resendInvite(User $user)
    {
        if (!$this->telegramService->isConfigured()) {
            return redirect()->back()->with('error', 'Telegram is not configured. Check bot token and group ID.');
        }

        // Per-user, one-time link that expires with their subscription
        $inviteLink = $this->telegramService->createUserInviteLink($user);

        if (!$inviteLink) {
            return redirect()->back()->with('error', "Could not generate an invite link for {$user->name}. Check bot permissions.");
        }

        // Try to DM the link via the bot
        $dmSent = false;
        if ($user->telegram_number) {
            $result  = $this->telegramService->addMemberToGroup($user->telegram_number, $inviteLink);
            $dmSent  = $result['success'];
        }

        if ($dmSent) {
            return redirect()->back()->with('success', "Invite re-sent to {$user->name} via Telegram.");
        }

        // Bot couldn't DM them — show the link to admin for manual sharing
        return redirect()->back()
            ->with('warning', "Could not DM {$user->name} (they may not have messaged the bot yet). Share this one-time link manually: {$inviteLink}");
    }
}
