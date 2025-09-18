<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\Shelter;
use App\Services\SiteStatsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $stats = Cache::get(SiteStatsService::CACHE_KEY);
        if ($stats === null) {
            $stats = SiteStatsService::compute();
            Cache::forever(SiteStatsService::CACHE_KEY, $stats);
        }

        $recentPets = Pet::with(['user:id,name', 'shelter:id,name,area,kind,website_url'])
            ->latest()
            ->take(6)
            ->get();

        $shelters = Shelter::select('id', 'name', 'area', 'kind', 'prefecture_id', 'website_url')
            ->with('prefecture:id,name')
            ->latest()
            ->get();

        // ヒーロー画像のランダム選択
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
