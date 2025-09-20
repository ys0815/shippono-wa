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
        // サイト統計情報をキャッシュから取得（なければ計算してキャッシュに保存）
        $stats = Cache::get(SiteStatsService::CACHE_KEY);
        if ($stats === null) {
            $stats = SiteStatsService::compute();
            Cache::forever(SiteStatsService::CACHE_KEY, $stats);
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
