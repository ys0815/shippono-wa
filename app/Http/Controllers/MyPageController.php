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

        // 7日以内の新しいいいね数（通知用）
        $recentLikesGot = Like::whereIn('pet_id', Pet::where('user_id', $user->id)->pluck('id'))
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        // ペット一覧（最新5件、保護団体情報と各ペットのいいね数も含む）
        $pets = Pet::where('user_id', $user->id)
            ->with('shelter:id,name,area,kind')
            ->withCount('likes')
            ->withCount(['likes as recent_likes_count' => function ($query) {
                $query->where('created_at', '>=', now()->subDays(7));
            }])
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
                'recent_likes_got' => $recentLikesGot,
            ],
            'pets' => $pets,
            'recentPosts' => $recentPosts,
        ]);
    }
}
