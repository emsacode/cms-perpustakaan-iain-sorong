<?php

use App\Jobs\HarvestEprintsJob;
use App\Jobs\HarvestSlimsJob;
use App\Jobs\HarvestOjsJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule the background queue jobs to harvest catalog data daily
Schedule::job(new HarvestEprintsJob)->daily();
Schedule::job(new HarvestSlimsJob)->daily();
Schedule::job(new HarvestOjsJob)->daily();

// Cancel reservations that haven't picked up key after 15 minutes of session start
Schedule::call(function () {
    \App\Models\Reservation::checkAndCancelExpiredReservations();
})->everyTenMinutes();
