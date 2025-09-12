<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use Illuminate\Http\Request;

class ShelterController extends Controller
{
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
     * GET /api/shelters
     * クエリ: kind=facility|site|unknown, area=...
     */
    public function index(Request $request)
    {
        $request->validate([
            'kind' => ['required', 'in:facility,site,unknown'],
            // kind が unknown 以外のときは area 必須
            'area' => ['required_unless:kind,unknown', 'nullable', 'in:hokkaido_tohoku,kanto,chubu_tokai,kinki,chugoku_shikoku,kyushu_okinawa,national'],
        ]);

        if ($request->string('kind') === 'unknown') {
            return response()->json([]);
        }

        $query = Shelter::query()
            ->where('kind', $request->string('kind'))
            ->where('area', $request->string('area'));

        $shelters = $query
            ->orderByRaw("CASE area 
                WHEN 'hokkaido_tohoku' THEN 1
                WHEN 'kanto' THEN 2
                WHEN 'chubu_tokai' THEN 3
                WHEN 'kinki' THEN 4
                WHEN 'chugoku_shikoku' THEN 5
                WHEN 'kyushu_okinawa' THEN 6
                WHEN 'national' THEN 7
                ELSE 99 END")
            ->orderBy('prefecture_id')
            ->orderBy('name')
            ->get(['id', 'name', 'prefecture_id', 'website_url', 'area', 'kind']);

        return response()->json($shelters);
    }
}
