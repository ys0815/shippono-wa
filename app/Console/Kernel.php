<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // 日本時間の朝8時に統計情報を更新
        $schedule->command('stats:update')
            ->dailyAt('08:00')
            ->timezone('Asia/Tokyo')
            ->withoutOverlapping()
            ->runInBackground();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
