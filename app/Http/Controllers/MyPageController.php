<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Pet;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

/**
 * マイページコントローラー
 * 
 * ユーザーのマイページ機能を提供します：
 * - ユーザー統計情報の表示（投稿数、いいね数、ペット数など）
 * - ペット一覧の表示（最新5件）
 * - 最近の投稿の表示（最新2件）
 */
class MyPageController extends Controller
{
    /**
     * マイページの表示
     * 
     * @return \Illuminate\View\View マイページビュー
     */
    public function show()
    {
        $user = Auth::user();

        // 統計値の計算（実データ連動）
        $postsCount = Post::where('user_id', $user->id)->count();
        $likesGot = Like::whereIn('pet_id', Pet::where('user_id', $user->id)->pluck('id'))->count();
        $petCount = Pet::where('user_id', $user->id)->count();
        $likesGiven = Like::where('user_id', $user->id)->count();

        // ペット一覧（最新5件、保護団体情報も含む）
        $pets = Pet::where('user_id', $user->id)
            ->with('shelter:id,name,area,kind')
            ->latest()
            ->take(5)
            ->get();

        // 最近の投稿（最新2件）
        $recentPosts = Post::where('user_id', $user->id)->latest()->take(2)->get();

        return view('mypage', [
            'stats' => [
                'posts' => $postsCount,
                'likes_got' => $likesGot,
                'pet_count' => $petCount,
                'likes_given' => $likesGiven,
            ],
            'pets' => $pets,
            'recentPosts' => $recentPosts,
        ]);
    }
}
