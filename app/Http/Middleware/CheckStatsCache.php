<?php

namespace App\Http\Middleware;

use App\Jobs\UpdateSiteStatsJob;
use App\Services\SiteStatsService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckStatsCache
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ホームページのみで統計情報のキャッシュをチェック
        if ($request->is('/') || $request->is('home')) {
            $this->checkAndUpdateStats();
        }

        return $next($request);
    }

    /**
     * 統計情報のキャッシュをチェックし、必要に応じて更新ジョブをディスパッチ
     */
    private function checkAndUpdateStats(): void
    {
        try {
            $stats = Cache::get(SiteStatsService::CACHE_KEY);

            // キャッシュが存在しない場合
            if ($stats === null) {
                Log::info('CheckStatsCache: No cached stats found, dispatching update job');
                dispatch(new UpdateSiteStatsJob());
                return;
            }

            // キャッシュが古い場合（2時間以上経過）
            $lastUpdated = \Carbon\Carbon::parse($stats['updated_at']);
            if ($lastUpdated->diffInHours(now()) >= 2) {
                Log::info('CheckStatsCache: Cached stats are stale, dispatching update job', [
                    'last_updated' => $stats['updated_at'],
                    'hours_ago' => $lastUpdated->diffInHours(now())
                ]);
                dispatch(new UpdateSiteStatsJob());
                return;
            }

            Log::debug('CheckStatsCache: Cached stats are fresh, no update needed', [
                'last_updated' => $stats['updated_at'],
                'hours_ago' => $lastUpdated->diffInHours(now())
            ]);
        } catch (\Exception $e) {
            Log::error('CheckStatsCache: Error checking stats cache', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // エラーが発生した場合は安全のため更新ジョブをディスパッチ
            dispatch(new UpdateSiteStatsJob());
        }
    }
}
