<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Pet;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class MyPageController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        // 統計値（暫定: 実データ連動）
        $postsCount = Post::where('user_id', $user->id)->count();
        $likesGot = Like::whereIn('pet_id', Pet::where('user_id', $user->id)->pluck('id'))->count();
        $petCount = Pet::where('user_id', $user->id)->count();
        $likesGiven = Like::where('user_id', $user->id)->count();

        // ペット一覧（簡易）
        $pets = Pet::where('user_id', $user->id)
            ->with('shelter:id,name,area,kind')
            ->latest()
            ->take(5)
            ->get();

        // 最近の投稿（2件）
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
