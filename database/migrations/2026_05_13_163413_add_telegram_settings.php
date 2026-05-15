<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        Setting::create([
            'key' => 'telegram_bot_token',
            'value' => '',
            'type' => 'string',
            'description' => 'Telegram Bot Token from BotFather'
        ]);

        Setting::create([
            'key' => 'telegram_group_id',
            'value' => '',
            'type' => 'string',
            'description' => 'Telegram Group/Chat ID for premium subscribers'
        ]);

        Setting::create([
            'key' => 'telegram_group_link',
            'value' => '',
            'type' => 'string',
            'description' => 'Telegram Group invite link'
        ]);
    }

    public function down(): void
    {
        Setting::whereIn('key', [
            'telegram_bot_token',
            'telegram_group_id',
            'telegram_group_link'
        ])->delete();
    }
};
