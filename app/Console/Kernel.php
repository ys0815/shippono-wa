<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // 日本時間の 8:00 / 18:00 に統計情報を更新
        $schedule->command('stats:update')
            ->twiceDaily(8, 18)
            ->timezone('Asia/Tokyo')
            ->withoutOverlapping();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
    }
}
