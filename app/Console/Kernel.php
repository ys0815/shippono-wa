<?php

namespace App\Console;

use App\Services\SiteStatsService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $stats = SiteStatsService::compute();
            Cache::forever(SiteStatsService::CACHE_KEY, $stats);
        })->cron('0 8,12,18 * * *');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
