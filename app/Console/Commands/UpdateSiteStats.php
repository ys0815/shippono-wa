<?php

namespace App\Console\Commands;

use App\Services\SiteStatsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
        $startTime = now();

        try {
            // 統計情報を計算
            $stats = SiteStatsService::compute();

            // エラーが発生した場合は警告を表示
            if (isset($stats['error']) && $stats['error']) {
                $this->warn('Statistics computed with errors. Check logs for details.');
            }

            // キャッシュに保存（24時間の有効期限を設定）
            Cache::put(SiteStatsService::CACHE_KEY, $stats, now()->addDay());

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
            $this->info('Updated at: ' . $stats['updated_at']);
            $this->info('Computed at: ' . $stats['computed_at']);
            $this->info('Execution time: ' . $startTime->diffForHumans(now(), true));

            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to update site statistics: ' . $e->getMessage());
            Log::error('UpdateSiteStats command failed: ' . $e->getMessage());
            return 1;
        }
    }
}
