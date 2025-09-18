<?php

namespace App\Console\Commands;

use App\Services\SiteStatsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateSiteStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:update {--force : Force update even if cache exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update site statistics and cache them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating site statistics...');

        // 統計情報を計算
        $stats = SiteStatsService::compute();

        // キャッシュに保存
        Cache::forever(SiteStatsService::CACHE_KEY, $stats);

        // 結果を表示
        $this->info('Site statistics updated successfully:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Gallery Posts', number_format($stats['posts_gallery'])],
                ['Interview Posts', number_format($stats['posts_interview'])],
                ['Pets', number_format($stats['pets'])],
                ['Shelters', number_format($stats['shelters'])],
                ['Likes', number_format($stats['likes'])],
            ]
        );

        $this->info('Statistics cached with key: ' . SiteStatsService::CACHE_KEY);

        return 0;
    }
}
