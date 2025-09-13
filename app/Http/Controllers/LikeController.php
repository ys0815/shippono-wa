<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Pet;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * いいね一覧の表示
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $likes = $user->likes()
            ->with(['pet.user'])
            ->latest()
            ->paginate(20);

        return view('likes.index', compact('likes'));
    }

    /**
     * いいねの追加
     */
    public function store(Request $request)
    {
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
     */
    public function destroy(Request $request, $petId)
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $like = Like::where('user_id', $user->id)
            ->where('pet_id', $petId)
            ->first();

        if (!$like) {
            return back()->with('error', 'いいねが見つかりません。');
        }

        $like->delete();

        return back()->with('success', 'いいねを取り消しました。');
    }
}
