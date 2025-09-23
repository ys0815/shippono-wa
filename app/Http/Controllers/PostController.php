<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Pet;
use App\Models\Media;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

/**
 * 投稿管理コントローラー
 * 
 * 投稿の作成、編集、削除、表示機能を提供します：
 * - 今日の幸せ投稿（ギャラリー投稿）の管理
 * - 里親インタビュー投稿の管理
 * - 投稿の公開/非公開切り替え
 * - 投稿の検索・フィルタリング
 * - メディアファイル（画像・動画）のアップロード管理
 */
class PostController extends Controller
{
    /**
     * 投稿管理画面の表示
     * 
     * @param Request $request フィルター条件を含むリクエスト
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse 投稿管理ページまたはログインページ
     */
    public function index(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // フィルター条件を取得
        $type = $request->get('type', 'gallery'); // デフォルトはgallery
        $keyword = $request->get('keyword');
        $period = $request->get('period', 'all');
        $status = $request->get('status', 'all');

        $query = $user->posts()->with(['pet', 'media']);

        // 投稿タイプでフィルタ
        if ($type !== 'all') {
            $query->where('type', $type);
        }

        // キーワード検索
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('content', 'like', "%{$keyword}%");
            });
        }

        // 期間でフィルタ
        if ($period !== 'all') {
            $now = now();
            switch ($period) {
                case 'month':
                    $query->where('created_at', '>=', $now->subMonth());
                    break;
                case 'half_year':
                    $query->where('created_at', '>=', $now->subMonths(6));
                    break;
                case 'year':
                    $query->where('created_at', '>=', $now->subYear());
                    break;
            }
        }

        // 状態でフィルタ
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('posts.index', compact('posts', 'type', 'keyword', 'period', 'status'));
    }

    /**
     * 今日の幸せ投稿フォーム表示
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse 投稿作成フォームまたはログインページ
     */
    public function createGallery()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // ユーザーのペット一覧を取得
        $pets = $user->pets;

        return view('posts.create-gallery', compact('pets'));
    }

    /**
     * 今日の幸せ投稿の保存
     * 
     * @param Request $request 投稿データを含むリクエスト
     * @return \Illuminate\Http\RedirectResponse 投稿管理ページへのリダイレクト
     */
    public function storeGallery(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // バリデーション（メディアファイルは1-2個、10MBまで）
        $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'title' => 'required|string|max:30',
            'content' => 'required|string|max:300',
            'media' => 'required|array|min:1|max:2',
            'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240', // 10MBまで
            'status' => 'required|in:draft,published',
        ]);

        // 投稿作成
        $post = Post::create([
            'user_id' => $user->id,
            'pet_id' => $request->pet_id,
            'type' => 'gallery',
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
        ]);

        // メディアアップロード処理（画像・動画）
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('posts', 'public');

                // ファイルタイプを判定
                $type = $this->getMediaType($file);

                Media::create([
                    'post_id' => $post->id,
                    'url' => $path,
                    'type' => $type,
                ]);
            }
        }

        return redirect()->route('mypage.posts', ['type' => 'gallery'])
            ->with('success', '投稿を保存しました。');
    }

    /**
     * 里親インタビュー投稿フォーム表示
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse インタビュー投稿フォームまたはログインページ
     */
    public function createInterview()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // ユーザーのペット一覧を取得
        $pets = $user->pets;

        return view('posts.create-interview', compact('pets'));
    }

    /**
     * 里親インタビュー投稿保存
     * 
     * @param Request $request インタビュー投稿データを含むリクエスト
     * @return \Illuminate\Http\RedirectResponse 投稿管理ページへのリダイレクト
     */
    public function storeInterview(Request $request)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // バリデーション（全質問は1000文字以内）
        $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'title' => 'required|string|max:30',
            'main_image' => 'required|file|mimes:jpeg,png,jpg,gif|max:10240', // 10MBまで
            'question1' => 'required|string|max:1000',
            'question2' => 'required|string|max:1000',
            'question3' => 'required|string|max:1000',
            'question4' => 'required|string|max:1000',
            'question5' => 'required|string|max:1000',
            'status' => 'required|in:draft,published',
        ], [
            'question1.max' => '新しい家族との出会いについて1000文字以内でご記入ください。',
            'question2.max' => '迎える前の不安と準備について1000文字以内でご記入ください。',
            'question3.max' => '迎えた後の変化と喜びについて1000文字以内でご記入ください。',
            'question4.max' => '未来の里親へのメッセージについて1000文字以内でご記入ください。',
            'question5.max' => '最後に一言について1000文字以内でご記入ください。',
        ]);

        $post = Post::create([
            'user_id' => $user->id,
            'pet_id' => $request->pet_id,
            'type' => 'interview',
            'title' => $request->title,
            'content' => $request->question1, // メインコンテンツとして最初の質問を使用
            'status' => $request->status,
        ]);

        // メイン画像をアップロード
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('posts', 'public');
            Media::create([
                'post_id' => $post->id,
                'url' => $path,
                'type' => 'image'
            ]);
        }

        // インタビュー内容を保存（最初の質問をメインコンテンツとして保存）
        $post->update(['content' => $request->question1]);

        // インタビュー詳細内容を保存
        $post->interviewContent()->create([
            'question1' => $request->question1,
            'question2' => $request->question2,
            'question3' => $request->question3,
            'question4' => $request->question4,
            'question5' => $request->question5,
        ]);

        return redirect()->route('mypage.posts', ['type' => 'interview'])->with('success', '里親インタビューを保存しました。');
    }

    /**
     * 投稿の編集フォーム表示
     * 
     * @param Post $post 編集する投稿
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse 編集フォームまたはリダイレクト
     */
    public function edit(Post $post)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 投稿の所有者かチェック
        if ($post->user_id !== $user->id) {
            abort(403, 'この投稿を編集する権限がありません。');
        }

        $pets = $user->pets;

        // 投稿タイプに応じて適切なビューを返す
        if ($post->type === 'interview') {
            // インタビュー内容を読み込み
            $post->load('interviewContent');
            return view('posts.edit-interview', compact('post', 'pets'));
        } else {
            return view('posts.edit-gallery', compact('post', 'pets'));
        }
    }

    /**
     * 投稿の更新
     * 
     * @param Request $request 更新データを含むリクエスト
     * @param Post $post 更新する投稿
     * @return \Illuminate\Http\RedirectResponse 投稿管理ページへのリダイレクト
     */
    public function update(Request $request, Post $post)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 投稿の所有者かチェック
        if ($post->user_id !== $user->id) {
            abort(403, 'この投稿を編集する権限がありません。');
        }

        if ($post->type === 'interview') {
            // 里親インタビューの更新
            $request->validate([
                'pet_id' => 'required|exists:pets,id',
                'title' => 'required|string|max:30',
                'main_image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10240',
                'question1' => 'required|string|max:1000',
                'question2' => 'required|string|max:1000',
                'question3' => 'required|string|max:1000',
                'question4' => 'required|string|max:1000',
                'question5' => 'required|string|max:1000',
                'status' => 'required|in:draft,published',
            ], [
                'question1.max' => '新しい家族との出会いについて1000文字以内でご記入ください。',
                'question2.max' => '迎える前の不安と準備について1000文字以内でご記入ください。',
                'question3.max' => '迎えた後の変化と喜びについて1000文字以内でご記入ください。',
                'question4.max' => '未来の里親へのメッセージについて1000文字以内でご記入ください。',
                'question5.max' => '最後に一言について1000文字以内でご記入ください。',
            ]);

            // 投稿更新
            $post->update([
                'pet_id' => $request->pet_id,
                'title' => $request->title,
                'content' => $request->question1,
                'status' => $request->status,
            ]);

            // インタビュー詳細内容を更新
            if ($post->interviewContent) {
                $post->interviewContent->update([
                    'question1' => $request->question1,
                    'question2' => $request->question2,
                    'question3' => $request->question3,
                    'question4' => $request->question4,
                    'question5' => $request->question5,
                ]);
            } else {
                $post->interviewContent()->create([
                    'question1' => $request->question1,
                    'question2' => $request->question2,
                    'question3' => $request->question3,
                    'question4' => $request->question4,
                    'question5' => $request->question5,
                ]);
            }

            // 新しいメイン画像がある場合の処理
            if ($request->hasFile('main_image')) {
                // 既存のメディアを削除
                foreach ($post->media as $media) {
                    Storage::disk('public')->delete($media->url);
                    $media->delete();
                }

                // 新しいメイン画像をアップロード
                $path = $request->file('main_image')->store('posts', 'public');
                Media::create([
                    'post_id' => $post->id,
                    'url' => $path,
                    'type' => 'image'
                ]);
            }
        } else {
            // 今日の幸せ投稿の更新
            $request->validate([
                'pet_id' => 'required|exists:pets,id',
                'title' => 'required|string|max:30',
                'content' => 'required|string|max:300',
                'media' => 'nullable|array|max:2',
                'media.*' => 'file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:10240',
                'status' => 'required|in:draft,published',
            ]);

            // 投稿更新
            $post->update([
                'pet_id' => $request->pet_id,
                'title' => $request->title,
                'content' => $request->content,
                'status' => $request->status,
            ]);

            // 新しいメディアがある場合の処理
            if ($request->hasFile('media')) {
                // 既存のメディアを削除
                foreach ($post->media as $media) {
                    Storage::disk('public')->delete($media->url);
                    $media->delete();
                }

                // 新しいメディアをアップロード
                foreach ($request->file('media') as $file) {
                    $path = $file->store('posts', 'public');
                    $type = $this->getMediaType($file);
                    Media::create([
                        'post_id' => $post->id,
                        'url' => $path,
                        'type' => $type,
                    ]);
                }
            }
        }

        $redirectType = $post->type === 'interview' ? 'interview' : 'gallery';
        return redirect()->route('mypage.posts', ['type' => $redirectType])
            ->with('success', '投稿を更新しました。');
    }

    /**
     * 投稿の削除
     * 
     * @param Post $post 削除する投稿
     * @return \Illuminate\Http\RedirectResponse 投稿管理ページへのリダイレクト
     */
    public function destroy(Post $post)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 投稿の所有者かチェック
        if ($post->user_id !== $user->id) {
            abort(403, 'この投稿を削除する権限がありません。');
        }

        // 関連するメディアを削除
        foreach ($post->media as $media) {
            Storage::disk('public')->delete($media->url);
            $media->delete();
        }

        $post->delete();

        return redirect()->route('mypage.posts')
            ->with('success', '投稿を削除しました。');
    }

    /**
     * 投稿の非表示/表示切り替え
     * 
     * @param Post $post 切り替える投稿
     * @return \Illuminate\Http\RedirectResponse 前のページへのリダイレクト
     */
    public function toggleVisibility(Post $post)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 投稿の所有者かチェック
        if ($post->user_id !== $user->id) {
            abort(403, 'この投稿を操作する権限がありません。');
        }

        // 状態を切り替え
        $post->status = $post->status === 'published' ? 'draft' : 'published';
        $post->save();

        $message = $post->status === 'published' ? '投稿を公開しました。' : '投稿を非公開にしました。';

        return redirect()->back()->with('success', $message);
    }

    /**
     * ファイルタイプを判定する（プライベートメソッド）
     * 
     * @param \Illuminate\Http\UploadedFile $file アップロードされたファイル
     * @return string メディアタイプ（'image' または 'video'）
     */
    private function getMediaType($file)
    {
        $mimeType = $file->getMimeType();

        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }

        // デフォルトは画像として扱う
        return 'image';
    }

    /**
     * 投稿詳細ページの表示（公開）
     * 
     * @param Post $post 表示する投稿
     * @return \Illuminate\View\View 投稿詳細ページ
     */
    public function show(Post $post)
    {
        // 公開済みの投稿のみ表示
        abort_unless($post->status === 'published', 404);

        // 閲覧数をカウント
        $post->incrementViewCount();

        // 関連データを読み込み（ペット、ユーザー、メディア）
        $post->load(['pet.user', 'media', 'user']);

        return view('posts.show', compact('post'));
    }
}
