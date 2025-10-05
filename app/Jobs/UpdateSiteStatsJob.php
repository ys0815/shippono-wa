<?php

namespace App\Jobs;

use App\Services\SiteStatsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UpdateSiteStatsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('UpdateSiteStatsJob: Starting statistics update');

            // 統計情報を計算
            $stats = SiteStatsService::compute();

            // キャッシュに保存（24時間の有効期限を設定）
            Cache::put(SiteStatsService::CACHE_KEY, $stats, now()->addDay());

            Log::info('UpdateSiteStatsJob: Statistics updated successfully', [
                'updated_at' => $stats['updated_at'],
                'posts_gallery' => $stats['posts_gallery'],
                'posts_interview' => $stats['posts_interview'],
                'pets' => $stats['pets'],
                'shelters' => $stats['shelters'],
                'likes' => $stats['likes']
            ]);
        } catch (\Exception $e) {
            Log::error('UpdateSiteStatsJob: Failed to update statistics', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // ジョブを失敗としてマーク
            throw $e;
        }
    }
}
