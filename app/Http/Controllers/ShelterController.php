<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use Illuminate\Http\Request;

class ShelterController extends Controller
{
    /**
     * GET /api/shelters
     * kind/areaでフィルタした保護団体一覧を返す（公開API）
     */
    public function index(Request $request)
    {
        $kind = $request->query('kind');
        $area = $request->query('area');

        $query = Shelter::query()->select(['id', 'name', 'kind', 'area']);

        if (!empty($kind) && $kind !== 'all') {
            $query->where('kind', $kind);
        }
        if (!empty($area) && $area !== 'all') {
            $query->where('area', $area);
        }

        $shelters = $query->orderBy('name')->get();

        return response()->json($shelters);
    }

    /**
     * GET /api/shelters/areas
     * 返す: 所在地カテゴリ一覧（固定）
     */
    public function areas()
    {
        return response()->json([
            'hokkaido_tohoku',
            'kanto',
            'chubu_tokai',
            'kinki',
            'chugoku_shikoku',
            'kyushu_okinawa',
            'national',
        ]);
    }


    /**
     * 保護団体詳細ページ
     */
    public function show(Shelter $shelter)
    {
        // 公開された投稿を持つペットを取得
        $pets = $shelter->pets()
            ->whereHas('posts', function ($query) {
                $query->where('status', 'published');
            })
            ->with(['user', 'posts' => function ($query) {
                $query->where('status', 'published')->latest()->limit(1);
            }])
            ->latest()
            ->limit(12)
            ->get();

        return view('shelters.show', compact('shelter', 'pets'));
    }
}
