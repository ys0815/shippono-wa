<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Shelter;
use App\Services\SiteStatsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * ホームページコントローラー
 * 
 * サイトのトップページを表示し、以下の機能を提供します：
 * - サイト統計情報の表示（投稿数、インタビュー数、登録動物数、掲載団体数、いいね数）
 * - 新着ペット情報の表示（最新6件）
 * - 保護団体一覧の表示
 * - ヒーロー画像のランダム表示
 */
class HomeController extends Controller
{
    /**
     * ホームページの表示
     * 
     * @return View ホームページビュー
     */
    public function index(): View
    {
        // サイト統計情報をキャッシュから取得
        $stats = Cache::get(SiteStatsService::CACHE_KEY);

        // キャッシュがない場合、または古いデータ（24時間以上前）の場合は更新
        $shouldUpdate = false;
        if ($stats === null) {
            $shouldUpdate = true;
        } else {
            $lastUpdated = \Carbon\Carbon::parse($stats['updated_at']);
            $shouldUpdate = $lastUpdated->diffInHours(now()) >= 24;
        }

        if ($shouldUpdate) {
            try {
                // 統計情報を計算してキャッシュに保存（24時間の有効期限）
                $stats = SiteStatsService::compute();
                Cache::put(SiteStatsService::CACHE_KEY, $stats, now()->addDay());
            } catch (\Exception $e) {
                // エラーが発生した場合は既存のキャッシュを使用（なければデフォルト値）
                if ($stats === null) {
                    $stats = [
                        'posts_gallery' => 0,
                        'posts_interview' => 0,
                        'pets' => 0,
                        'shelters' => 0,
                        'likes' => 0,
                        'updated_at' => now()->toDateTimeString(),
                        'computed_at' => now()->toDateTimeString(),
                        'error' => true,
                    ];
                }
            }
        }

        // 新着ペット情報を取得（最新6件、ユーザー情報と保護団体情報も含む）
        $recentPets = Pet::with(['user:id,name', 'shelter:id,name,area,kind,website_url'])
            ->latest()
            ->take(6)
            ->get();

        // 保護団体一覧を取得（都道府県情報も含む）
        $shelters = Shelter::select('id', 'name', 'area', 'kind', 'prefecture_id', 'website_url')
            ->with('prefecture:id,name')
            ->latest()
            ->get();

        // ヒーロー画像のランダム選択（10枚の中から1枚をランダムに選択）
        $heroImages = [
            'hero-01.jpeg',
            'hero-02.jpeg',
            'hero-03.jpeg',
            'hero-04.jpeg',
            'hero-05.jpeg',
            'hero-06.jpeg',
            'hero-07.jpeg',
            'hero-08.jpeg',
            'hero-09.jpeg',
            'hero-10.jpeg'
        ];
        $randomHeroImage = $heroImages[array_rand($heroImages)];

        return view('home.index', [
            'stats' => $stats,
            'recentPets' => $recentPets,
            'shelters' => $shelters,
            'heroImage' => $randomHeroImage,
        ]);
    }
}
