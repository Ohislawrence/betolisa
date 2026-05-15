<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_id')->constrained()->onDelete('cascade');
            $table->string('home_team');
            $table->string('away_team');
            $table->text('tip_content');
            $table->decimal('odds', 8, 2)->nullable();
            $table->enum('type', ['free', 'premium'])->default('free');
            $table->enum('status', ['pending', 'won', 'lost', 'void'])->default('pending');
            $table->dateTime('match_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tips');
    }
};
