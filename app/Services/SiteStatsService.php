<?php

namespace App\Services;

use App\Models\Like;
use App\Models\Pet;
use App\Models\Post;
use App\Models\Shelter;

class SiteStatsService
{
    public const CACHE_KEY = 'site_stats:v2';

    public static function compute(): array
    {
        // 公開済みの投稿のみをカウント
        $postsGallery = Post::where('type', 'gallery')
            ->where('status', 'published')
            ->count();

        $postsInterview = Post::where('type', 'interview')
            ->where('status', 'published')
            ->count();

        // 登録されているペット数
        $pets = Pet::count();

        // 重複した団体名を除外してカウント
        $shelters = Shelter::select('name')
            ->distinct()
            ->count();

        // いいね数
        $likes = Like::count();

        return [
            'posts_gallery' => $postsGallery,
            'posts_interview' => $postsInterview,
            'pets' => $pets,
            'shelters' => $shelters,
            'likes' => $likes,
            'updated_at' => now()->toDateTimeString(),
        ];
    }
}
