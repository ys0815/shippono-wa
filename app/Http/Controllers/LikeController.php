<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;

/**
 * いいね管理コントローラー
 * 
 * ペットへのいいね機能を提供します：
 * - いいね一覧の表示（フィルタリング機能付き）
 * - いいねの追加
 * - いいねの削除
 */
class LikeController extends Controller
{
    /**
     * いいね一覧の表示
     * 
     * @param Request $request フィルター条件を含むリクエスト
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse いいね一覧ページまたはログインページ
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // フィルター条件を取得
        $species = $request->get('species', 'all');
        $period = $request->get('period', 'all');

        // ユーザーのいいね一覧を取得（ペット情報も含む）
        $query = $user->likes()->with(['pet.user']);

        // 動物種でフィルタ
        if ($species !== 'all') {
            $query->whereHas('pet', function ($q) use ($species) {
                $q->where('species', $species);
            });
        }

        // 期間でフィルタ
        if ($period !== 'all') {
            $now = now();
            switch ($period) {
                case 'week':
                    $query->where('created_at', '>=', $now->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', $now->subMonth());
                    break;
                case 'year':
                    $query->where('created_at', '>=', $now->subYear());
                    break;
            }
        }

        // ページネーション（20件ずつ表示）
        $likes = $query->latest()->paginate(20);

        return view('likes.index', compact('likes', 'species', 'period'));
    }

    /**
     * いいねの追加
     * 
     * @param Request $request ペットIDを含むリクエスト
     * @return \Illuminate\Http\RedirectResponse 前のページへのリダイレクト
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'pet_id' => 'required|exists:pets,id',
        ]);

        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $pet = Pet::findOrFail($request->pet_id);

        // 既にいいねしているかチェック
        $existingLike = Like::where('user_id', $user->id)
            ->where('pet_id', $pet->id)
            ->first();

        if ($existingLike) {
            return back()->with('error', '既にいいねしています。');
        }

        // いいねを作成
        Like::create([
            'user_id' => $user->id,
            'pet_id' => $pet->id,
        ]);

        return back()->with('success', 'いいねしました！');
    }

    /**
     * いいねの削除
     * 
     * @param Request $request
     * @param int $petId いいねを削除するペットのID
     * @return \Illuminate\Http\RedirectResponse 前のページへのリダイレクト
     */
    public function destroy(Request $request, $petId)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // ユーザーのいいねを検索
        $like = Like::where('user_id', $user->id)
            ->where('pet_id', $petId)
            ->first();

        if (!$like) {
            return back()->with('error', 'いいねが見つかりません。');
        }

        // いいねを削除
        $like->delete();

        return back()->with('success', 'いいねを取り消しました。');
    }
}
