<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use Illuminate\Http\Request;

/**
 * 保護団体管理コントローラー
 * 
 * 保護団体の情報表示機能を提供します：
 * - 保護団体一覧のAPI（フィルタリング機能付き）
 * - 地域別保護団体の取得
 * - 保護団体詳細ページの表示
 */
class ShelterController extends Controller
{
    /**
     * 保護団体一覧API（公開）
     * 
     * @param Request $request フィルター条件を含むリクエスト
     * @return \Illuminate\Http\JsonResponse 保護団体一覧のJSONレスポンス
     */
    public function index(Request $request)
    {
        // クエリパラメータからフィルター条件を取得
        $kind = $request->query('kind');
        $area = $request->query('area');

        // 保護団体の基本情報のみを取得
        $query = Shelter::query()->select(['id', 'name', 'kind', 'area']);

        // 種別でフィルタリング
        if (!empty($kind) && $kind !== 'all') {
            $query->where('kind', $kind);
        }
        // 地域でフィルタリング
        if (!empty($area) && $area !== 'all') {
            $query->where('area', $area);
        }

        // 名前順でソートして取得
        $shelters = $query->orderBy('name')->get();

        return response()->json($shelters);
    }

    /**
     * 地域一覧API（公開）
     * 
     * @return \Illuminate\Http\JsonResponse 地域カテゴリ一覧のJSONレスポンス
     */
    public function areas()
    {
        // 固定の地域カテゴリ一覧を返す
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
     * 保護団体詳細ページ表示
     * 
     * @param Shelter $shelter 表示する保護団体
     * @return \Illuminate\View\View 保護団体詳細ページ
     */
    public function show(Shelter $shelter)
    {
        // 公開された投稿を持つペットを取得（最新12件）
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
