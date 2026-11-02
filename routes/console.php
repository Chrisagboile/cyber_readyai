<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Send Pension Account Guard security reminders daily at 9:00 AM.
Schedule::command('pension-guard:send-reminders')
    ->dailyAt('09:00')
    ->withoutOverlapping();
