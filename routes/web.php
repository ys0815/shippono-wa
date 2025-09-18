<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MyPageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShelterController;
use App\Http\Controllers\InterviewController;
use Illuminate\Support\Facades\Artisan;

Route::get('/', [HomeController::class, 'index'])->name('home');

// ペットプロフィール詳細（公開ページ）
Route::get('/pets/{pet}', [PetController::class, 'show'])->name('pets.show');

// シェア用URL生成（認証必要）
Route::post('/pets/{pet}/generate-share-link', [PetController::class, 'generateShareLink'])->name('pets.generate-share-link');

// QRコード生成（認証必要）
Route::get('/pets/{pet}/qr-code', [PetController::class, 'generateQrCode'])->name('pets.qr-code');

// シェア用URL（公開ページ）
Route::get('/share/{token}', [PetController::class, 'share'])->name('pets.share');

// ダッシュボードも同じ表示に統一
Route::get('/mypage', [MyPageController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('mypage');

Route::middleware('auth')->group(function () {
    // マイページ（コントローラ）
    Route::get('/mypage', [MyPageController::class, 'show'])->name('mypage');

    // 投稿管理画面（typeはクエリで判定: /mypage/posts?type=gallery|interview）
    Route::get('/mypage/posts', [PostController::class, 'index'])->name('mypage.posts');

    // 今日の幸せ投稿
    Route::get('/mypage/posts/gallery/create', [PostController::class, 'createGallery'])->name('mypage.posts.gallery.create');
    Route::post('/mypage/posts/gallery', [PostController::class, 'storeGallery'])->name('mypage.posts.gallery.store');

    // 里親インタビュー投稿
    Route::get('/mypage/posts/interview/create', [PostController::class, 'createInterview'])->name('mypage.posts.interview.create');
    Route::post('/mypage/posts/interview', [PostController::class, 'storeInterview'])->name('mypage.posts.interview.store');
    Route::get('/mypage/posts/{post}/edit', [PostController::class, 'edit'])->name('mypage.posts.edit');
    Route::put('/mypage/posts/{post}', [PostController::class, 'update'])->name('mypage.posts.update');
    Route::delete('/mypage/posts/{post}', [PostController::class, 'destroy'])->name('mypage.posts.destroy');
    Route::patch('/mypage/posts/{post}/toggle-visibility', [PostController::class, 'toggleVisibility'])->name('mypage.posts.toggle-visibility');

    // ペットプロフィール管理
    Route::get('/mypage/pets', [PetController::class, 'index'])->name('mypage.pets');
    // 互換用エイリアス（指定の t/mypage/pets でもアクセス可）
    Route::get('/t/mypage/pets', [PetController::class, 'index']);
    Route::get('/mypage/pets/new', [PetController::class, 'create'])->name('mypage.pets.create');
    Route::post('/mypage/pets', [PetController::class, 'store'])->name('mypage.pets.store');
    Route::get('/mypage/pets/{pet_id}/edit', [PetController::class, 'edit'])->name('mypage.pets.edit');
    Route::put('/mypage/pets/{pet_id}', [PetController::class, 'update'])->name('mypage.pets.update');
    Route::get('/mypage/pets/links', [PetController::class, 'links'])->name('mypage.pets.links');
    Route::post('/mypage/pets/links', [PetController::class, 'saveLinks'])->name('mypage.pets.links.store');
    Route::delete('/mypage/pets/links', [PetController::class, 'destroyLinks'])->name('mypage.pets.links.destroy');

    // ペットプロフィール詳細（公開ページ） → 公開に変更済み

    // ペット登録ガイド（ワイヤーフレーム準拠の詳細ページ）
    Route::view('/mypage/pets/detail', 'pets.detail_guide')->name('mypage.pets.detail_guide');

    // 保護団体選択画面（必要に応じて導線から遷移）
    Route::get('/shelters/select', function () {
        return view('shelters.select');
    })->name('shelters.select');

    // いいね一覧
    Route::get('/mypage/likes', [LikeController::class, 'index'])->name('mypage.likes');
    Route::post('/likes', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/likes/{petId}', [LikeController::class, 'destroy'])->name('likes.destroy');

    // プロフィール編集（マイページ配下）
    Route::get('/mypage/profile/edit', [ProfileController::class, 'edit'])->name('mypage.profile.edit');
    Route::get('/mypage/profile/email', [ProfileController::class, 'editEmail'])->name('mypage.profile.email');
    Route::get('/mypage/profile/password', [ProfileController::class, 'editPassword'])->name('mypage.profile.password');

    // Breeze互換のプロフィールルート（既存ビューのroute('profile.*')対応）
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 保護団体選択API（SPA/フォーム補助用）
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/shelters/areas', [ShelterController::class, 'areas']);
    Route::get('/shelters', [ShelterController::class, 'index']);
});

// ペット投稿API（無限スクロール用）
Route::get('/api/pets/{pet}/posts', [PetController::class, 'getPosts'])->name('api.pets.posts');

require __DIR__ . '/auth.php';

// 里親インタビュー（公開）
Route::get('/interviews', [InterviewController::class, 'index'])->name('interviews.index');
Route::get('/interviews/{post}', [InterviewController::class, 'show'])->name('interviews.show');

// 投稿詳細ページ（公開）
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// 統計情報更新（認証必要）
Route::post('/stats/update', function () {
    Artisan::call('stats:update');
    return redirect()->back()->with('status', 'stats-updated');
})->middleware('auth')->name('stats.update');

// ペット検索結果ページ
Route::get('/pets/search/{species}', [PetController::class, 'search'])->name('pets.search');

// ペット検索API（無限スクロール用）
Route::get('/api/pets/search/{species}', [PetController::class, 'searchApi'])->name('api.pets.search');
