<?php

namespace App\Services;

use App\Models\Like;
use App\Models\Pet;
use App\Models\Post;
use App\Models\Shelter;

class SiteStatsService
{
    public const CACHE_KEY = 'site_stats:v1';

    public static function compute(): array
    {
        $postsGallery = Post::where('type', 'gallery')->count();
        $postsInterview = Post::where('type', 'interview')->count();
        $pets = Pet::count();
        $shelters = Shelter::count();
        $likes = Like::count();

        return [
            'posts_gallery' => $postsGallery,
            'posts_interview' => $postsInterview,
            'pets' => $pets,
            'shelters' => $shelters,
            'likes' => $likes,
        ];
    }
}
