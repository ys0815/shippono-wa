<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

/**
 * 里親インタビューコントローラー
 * 
 * 里親インタビューの公開表示機能を提供します：
 * - 里親インタビュー一覧の表示（検索・フィルタリング機能付き）
 * - 里親インタビュー詳細ページの表示
 * - 関連インタビューの表示
 */
class InterviewController extends Controller
{
    /**
     * 里親インタビュー一覧（公開）
     * 
     * @param Request $request 検索条件を含むリクエスト
     * @return \Illuminate\View\View インタビュー一覧ページ
     */
    public function index(Request $request)
    {
        // 公開済みのインタビューのみを取得
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

        // ページネーション（9件ずつ表示）
        $interviews = $query->paginate(9)->withQueryString();

        return view('interviews.index', compact('interviews'));
    }

    /**
     * 里親インタビュー詳細（公開）
     * 
     * @param Post $post 表示するインタビュー投稿
     * @return \Illuminate\View\View インタビュー詳細ページ
     */
    public function show(Post $post)
    {
        // インタビュー投稿かつ公開済みのもののみ表示
        abort_unless($post->type === 'interview' && $post->status === 'published', 404);

        // 関連データを読み込み（ペット、ユーザー、メディア、インタビュー内容）
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
