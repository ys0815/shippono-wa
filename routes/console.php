<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 日本時間で2時間ごとに統計情報を更新
Schedule::command('stats:update')
    ->everyTwoHours()
    ->timezone('Asia/Tokyo')
    ->withoutOverlapping();
