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

        return view('home.index', [
            'stats' => $stats,
            'recentPets' => $recentPets,
            'shelters' => $shelters,
        ]);
    }
}
