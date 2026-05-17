<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Check expired subscriptions - run hourly
Schedule::command('subscriptions:check-expired')->hourly();

// Send notifications - run daily at 9 AM
Schedule::command('notifications:send-scheduled')->dailyAt('09:00');

// Optional: Run queue worker (if not using supervisor)
Schedule::command('queue:work --stop-when-empty')->everyMinute();
