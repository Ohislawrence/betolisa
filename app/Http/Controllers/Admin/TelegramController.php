<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
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

        $freeGroupName    = Setting::getValue('free_telegram_group_name', '');
        $freeGroupLink    = Setting::getValue('free_telegram_group_link', '');
        $freeGroupMessage = Setting::getValue('free_telegram_popup_message', 'Join our FREE Telegram group for daily football tips!');
        $freeGroupEnabled = (bool) Setting::getValue('free_telegram_popup_enabled', false);

        return view('admin.telegram.settings', compact(
            'botToken', 'groupId', 'groupLink', 'memberCount',
            'freeGroupName', 'freeGroupLink', 'freeGroupMessage', 'freeGroupEnabled'
        ));
    }

    /**
     * Update Telegram settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'telegram_bot_token' => 'required|string',
            'telegram_group_id' => 'required|string',
        ]);

        Setting::setValue('telegram_bot_token', $validated['telegram_bot_token'], 'string');
        Setting::setValue('telegram_group_id', $validated['telegram_group_id'], 'string');

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
}
