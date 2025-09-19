<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    /**
     * 里親インタビュー一覧（公開）
     */
    public function index(Request $request)
    {
        $query = Post::with(['pet.user'])
            ->where('type', 'interview')
            ->where('status', 'published')
            ->latest();

        // ペット種別やキーワードなど、将来の拡張に備えた簡易フィルタ
        if ($keyword = $request->get('q')) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        $interviews = $query->paginate(9)->withQueryString();

        return view('interviews.index', compact('interviews'));
    }

    /**
     * 里親インタビュー詳細（公開）
     */
    public function show(Post $post)
    {
        abort_unless($post->type === 'interview' && $post->status === 'published', 404);

        $post->load(['pet.user', 'media', 'interviewContent']);

        // 関連する里親インタビューを取得（同じペットの他のインタビュー、または同じ種別のペットのインタビュー）
        $relatedPosts = Post::with(['pet.user', 'media'])
            ->where('type', 'interview')
            ->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post) {
                // 同じペットの他のインタビュー
                $query->where('pet_id', $post->pet_id)
                    // または同じ種別のペットのインタビュー
                    ->orWhereHas('pet', function ($petQuery) use ($post) {
                        $petQuery->where('species', $post->pet->species);
                    });
            })
            ->latest()
            ->limit(3)
            ->get();

        return view('interviews.show', compact('post', 'relatedPosts'));
    }
}
