<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('telegram_number')->nullable()->after('email');
            $table->string('phone')->nullable()->after('telegram_number');
            $table->string('username')->unique()->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telegram_number', 'phone', 'username', 'is_active']);
        });
    }
};
