<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule Provider Sync
Schedule::command('smm:sync-orders')->everyMinute();
Schedule::command('providers:sync-currency-rates')->dailyAt('00:10');
// Auto sync provider services (price updates & clean dead services) every night at 2:00 AM
Schedule::command('smm:sync-services')->dailyAt('02:00');
