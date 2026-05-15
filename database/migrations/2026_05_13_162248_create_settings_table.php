<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default subscription settings
        \App\Models\Setting::create([
            'key' => 'subscription_cost',
            'value' => '5000',
            'type' => 'integer',
            'description' => 'Monthly subscription cost in Naira (NGN)'
        ]);

        \App\Models\Setting::create([
            'key' => 'subscription_duration_days',
            'value' => '30',
            'type' => 'integer',
            'description' => 'Subscription duration in days'
        ]);

        \App\Models\Setting::create([
            'key' => 'paystack_public_key',
            'value' => '',
            'type' => 'string',
            'description' => 'Paystack Public Key'
        ]);

        \App\Models\Setting::create([
            'key' => 'paystack_secret_key',
            'value' => '',
            'type' => 'string',
            'description' => 'Paystack Secret Key'
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
