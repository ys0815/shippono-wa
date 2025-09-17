<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $post->title ?? '里親インタビュー' }} | #しっぽのわ</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ノート風の横罫線 */
        .notebook-lines {
            background-image: linear-gradient(to bottom, #e5e7eb 1px, transparent 1px);
            background-size: 100% 1.8em;
            background-position: 0 1.2em;
            line-height: 1.8em;
        }
        
        /* 手書き風の装飾 */
        .handwritten-border {
            border: 2px solid #f59e0b;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* ノート風の角の装飾 */
        .notebook-corner {
            position: relative;
        }
        
        .notebook-corner::before {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 20px;
            height: 20px;
            background: #f59e0b;
            border-radius: 0 8px 0 20px;
        }
        
        .notebook-corner::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 15px;
            height: 15px;
            background: #fbbf24;
            border-radius: 0 6px 0 15px;
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased" style="font-family: 'Noto Sans JP', sans-serif;">
<div x-data="{ sidebar:false, search:false }" class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="sticky top-0 z-[900] bg-white/90 backdrop-blur border-b border-amber-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
            <button type="button" @click="sidebar=true" class="p-2 rounded hover:bg-amber-50 text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            <h1 class="text-lg font-semibold text-gray-900"># しっぽのわ</h1>
            <button type="button" @click="search=true" class="p-2 rounded hover:bg-amber-50 text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.387a1 1 0 01-1.414 1.414l-4.387-4.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd"/></svg>
            </button>
        </div>
    </header>

    <!-- Sidebar -->
    <div x-cloak x-show="sidebar" @keydown.escape.window="sidebar=false">
        <div class="fixed inset-0 bg-black/50 z-[1100]" @click="sidebar=false"></div>
        <aside class="fixed top-0 left-0 z-[1200] w-72 md:w-80 max-w-[85vw] h-full bg-white shadow-lg overflow-y-auto"
               x-transition:enter="transition ease-in-out duration-300 transform"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in-out duration-300 transform"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full">
            
            <!-- Header -->
            <div class="p-4 border-b bg-amber-50 border-amber-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/icon.png') }}" alt="# しっぽのわ" class="w-12 h-12">
                    <div>
                        <div class="text-lg font-bold text-gray-800"># しっぽのわ</div>
                        <div class="text-sm text-gray-600">保護動物と家族の幸せを共有</div>
                    </div>
                </div>
                <button @click="sidebar=false" aria-label="メニューを閉じる" 
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-amber-700 hover:bg-amber-100 focus:outline-none transition">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Menu blocks -->
            <nav class="p-4 space-y-6" aria-label="サイドバー">
                @guest
                <!-- ゲスト用アカウント項目 -->
                <div>
                    <div class="text-xs font-semibold text-amber-700 mb-2">アカウント</div>
                    <div class="flex gap-2">
                        <a href="{{ route('register') }}" class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded text-gray-700 bg-white hover:bg-gray-50 text-center">新規登録</a>
                        <a href="{{ route('login') }}" class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded text-gray-700 bg-white hover:bg-gray-50 text-center">ログイン</a>
                    </div>
                </div>
                @else
                <!-- ログイン済み用メイン項目 -->
                <div>
                    <div class="text-xs font-semibold text-amber-700 mb-2">メイン</div>
                    <ul class="space-y-1">
                        <li><a @click="sidebar=false" href="{{ route('mypage') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">マイページ</a></li>
                        <li><a @click="sidebar=false" href="{{ route('mypage.posts') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">投稿管理</a></li>
                        <li><a @click="sidebar=false" href="{{ route('mypage.pets') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">ペットプロフィール管理</a></li>
                        <li><a @click="sidebar=false" href="{{ route('mypage.likes') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">いいね一覧</a></li>
                    </ul>
                </div>

                <!-- 作成 -->
                <div>
                    <div class="text-xs font-semibold text-amber-700 mb-2">作成</div>
                    <ul class="space-y-1">
                        <li><a @click="sidebar=false" href="{{ route('mypage.posts.gallery.create') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">今日の幸せを投稿</a></li>
                        <li><a @click="sidebar=false" href="{{ route('mypage.posts.interview.create') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">里親インタビューを投稿</a></li>
                        <li><a @click="sidebar=false" href="{{ route('mypage.pets.create') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">ペットを登録</a></li>
                    </ul>
                </div>
                @endauth

                <!-- サイト -->
                <div>
                    <div class="text-xs font-semibold text-amber-700 mb-2">サイト</div>
                    <ul class="space-y-1">
                        <li><a @click="sidebar=false" href="/" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">#しっぽのわとは？</a></li>
                        <li><a @click="sidebar=false" href="#recent-pets" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">犬の卒業生を見る</a></li>
                        <li><a @click="sidebar=false" href="#recent-pets" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">猫の卒業生を見る</a></li>
                        <li><a @click="sidebar=false" href="#recent-pets" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">うさぎの卒業生を見る</a></li>
                        <li><a @click="sidebar=false" href="#recent-pets" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">その他の卒業生を見る</a></li>
                        <li><a @click="sidebar=false" href="#" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">保護団体リンク集</a></li>
                    </ul>
                </div>

                @auth
                <!-- 設定 -->
                <div>
                    <div class="text-xs font-semibold text-amber-700 mb-2">設定</div>
                    <ul class="space-y-1">
                        <li><a @click="sidebar=false" href="{{ route('profile.edit') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">プロフィール編集</a></li>
                        <li><a @click="sidebar=false" href="{{ route('mypage.profile.email') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">メールアドレス変更</a></li>
                        <li><a @click="sidebar=false" href="{{ route('mypage.profile.password') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">パスワード変更</a></li>
                    </ul>
                </div>

                <!-- ログアウト -->
                <div>
                    <div class="text-xs font-semibold text-amber-700 mb-2">その他</div>
                    <ul class="space-y-1">
                        <li><a @click="sidebar=false" href="#" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">ヘルプ・サポート</a></li>
                        <li><a @click="sidebar=false" href="#" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">お問い合わせ</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">ログアウト</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @endauth

                <!-- ソーシャルメディア -->
                <div>
                    <div class="text-xs font-semibold text-amber-700 mb-2">フォロー</div>
                    <div class="flex gap-3">
                        <!-- X (旧Twitter) -->
                        <a href="#" target="_blank" rel="noopener noreferrer" 
                           class="w-8 h-8 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800 hover:scale-110 transition-all duration-200 shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        
                        <!-- Instagram -->
                        <a href="#" target="_blank" rel="noopener noreferrer" 
                           class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full flex items-center justify-center hover:from-purple-600 hover:to-pink-600 hover:scale-110 transition-all duration-200 shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        
                        <!-- Facebook -->
                        <a href="#" target="_blank" rel="noopener noreferrer" 
                           class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 hover:scale-110 transition-all duration-200 shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>
    </div>

    <main class="w-full">
        <!-- 統合インタビューコンテンツ -->
        <section class="w-full">
            <!-- メイン画像 -->
            <div class="relative h-64 sm:h-80 lg:h-96 bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 overflow-hidden">
                @php
                    $mainImage = null;
                    // インタビュー投稿時のメディアから画像を取得
                    if ($post->media && $post->media->count() > 0) {
                        $imageMedia = $post->media->where('type', 'image')->first();
                        if ($imageMedia) {
                            $mainImage = $imageMedia->url;
                            if (strpos($mainImage, 'http') !== 0) {
                                $mainImage = '/storage/' . $mainImage;
                            }
                        }
                    }
                @endphp

                @if($mainImage)
                    <img src="{{ $mainImage }}" alt="メイン画像" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-24 h-24 sm:w-32 sm:h-32 mx-auto mb-4 rounded-full overflow-hidden border-4 border-white shadow-lg bg-amber-100">
                                @if($post->pet && $post->pet->profile_image_url)
                                    <img src="{{ $post->pet->profile_image_url }}" alt="{{ $post->pet->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                        <span class="text-amber-600 text-2xl sm:text-3xl font-bold">{{ mb_substr($post->pet->name ?? 'Pet', 0, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- インタビュー情報ヘッダー -->
            <div class="text-center py-8 px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">{{ $post->title ?? ($post->pet->name.'の里親インタビュー') }}</h1>
                <div class="flex items-center justify-center gap-3 text-gray-600 mb-4">
                    <span class="font-medium text-gray-700">{{ $post->pet->name ?? '名無し' }}</span>
                    @if($post->pet)
                        <span class="{{ $post->pet->gender === 'male' ? 'text-blue-400' : ($post->pet->gender === 'female' ? 'text-pink-400' : 'text-gray-400') }}">
                            {{ ['male'=>'♂','female'=>'♀','unknown'=>'?'][$post->pet->gender] ?? '?' }}
                        </span>
                        <span>飼い主さん：{{ $post->pet->user->display_name ?? $post->pet->user->name }}</span>
                    @endif
                </div>
                <div class="text-sm text-gray-500">
                    投稿日時：{{ $post->created_at->format('Y年n月j日 H:i') }}
                </div>
            </div>

            <!-- インタビューコンテンツ -->
            <div class="space-y-8 px-4 sm:px-6 lg:px-8 pb-8">
            @if($post->interviewContent)
                <!-- ① 新しい家族との出会い -->
                @if($post->interviewContent->question1)
                    <div class="mb-8">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">① 新しい家族との出会い</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        <div class="notebook-lines text-gray-700 p-4 bg-gray-50 rounded-lg">
                            {!! nl2br(e($post->interviewContent->question1)) !!}
                        </div>
                    </div>
                @endif

                <!-- ② 迎える前の不安と準備 -->
                @if($post->interviewContent->question2)
                    <div class="mb-8">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">② 迎える前の不安と準備</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        <div class="notebook-lines text-gray-700 p-4 bg-gray-50 rounded-lg">
                            {!! nl2br(e($post->interviewContent->question2)) !!}
                        </div>
                    </div>
                @endif

                <!-- ③ 迎えた後の変化と喜び -->
                @if($post->interviewContent->question3)
                    <div class="mb-8">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">③ 迎えた後の変化と喜び</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        <div class="notebook-lines text-gray-700 p-4 bg-gray-50 rounded-lg">
                            {!! nl2br(e($post->interviewContent->question3)) !!}
                        </div>
                    </div>
                @endif

                <!-- ④ 未来の里親へのメッセージ -->
                @if($post->interviewContent->question4)
                    <div class="mb-8">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">④ 未来の里親へのメッセージ</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        <div class="notebook-lines text-gray-700 p-4 bg-gray-50 rounded-lg">
                            {!! nl2br(e($post->interviewContent->question4)) !!}
                        </div>
                    </div>
                @endif

                <!-- ⑤ 最後に一言 -->
                @if($post->interviewContent->question5)
                    <div class="mb-8">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">⑤ 最後に一言</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        <div class="notebook-lines text-gray-700 p-4 bg-gray-50 rounded-lg">
                            {!! nl2br(e($post->interviewContent->question5)) !!}
                        </div>
                    </div>
                @endif
            @else
                <!-- フォールバック：通常のコンテンツ表示 -->
                <div class="notebook-lines text-gray-700 p-4 bg-gray-50 rounded-lg">
                    {!! nl2br(e($post->content)) !!}
                </div>
            @endif
            </div>
        </section>

        <!-- ペットプロフィール表示 -->
        <section class="mt-8">
            <div class="bg-white handwritten-border rounded-lg p-6 sm:p-8 notebook-corner mx-4 mb-4 sm:mx-6 lg:mx-8">
                <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6 text-center relative inline-block w-full">
                    <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">この子について</span>
                    <span class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                </h2>
                
                <div class="flex flex-col sm:flex-row items-center gap-6">
                    <!-- ペットアイコン -->
                    <a href="{{ route('pets.show', $post->pet->id) }}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-full overflow-hidden border-4 border-amber-200 flex-shrink-0 hover:border-amber-400 transition-all duration-200 hover:scale-105">
                        @if($post->pet && $post->pet->profile_image_url)
                            <img src="{{ $post->pet->profile_image_url }}" alt="{{ $post->pet->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                <span class="text-amber-600 text-2xl font-bold">{{ mb_substr($post->pet->name ?? 'Pet', 0, 2) }}</span>
                            </div>
                        @endif
                    </a>
                    
                    <!-- ペット情報 -->
                    <div class="flex-1 text-center sm:text-left">
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">
                            {{ $post->pet->name ?? '名無し' }}
                            @if($post->pet)
                                <span class="{{ $post->pet->gender === 'male' ? 'text-blue-400' : ($post->pet->gender === 'female' ? 'text-pink-400' : 'text-gray-400') }} text-2xl sm:text-3xl ml-2">
                                    {{ ['male'=>'♂','female'=>'♀','unknown'=>'?'][$post->pet->gender] ?? '?' }}
                                </span>
                            @endif
                        </h3>
                        <p class="text-gray-600 text-lg mb-2">
                            <span class="font-medium text-amber-600">飼い主さん：</span>
                            {{ $post->pet->user->display_name ?? $post->pet->user->name }}さん
                        </p>
                        <div class="text-gray-600 text-lg">
                            <span class="font-medium text-amber-600">お迎え先：</span>
                            {{ $post->pet->shelter->name ?? '情報なし' }}
                            @if($post->pet->shelter && $post->pet->shelter->website_url)
                                <a href="{{ $post->pet->shelter->website_url }}" target="_blank" rel="noopener noreferrer" 
                                   class="ml-2 px-3 py-1 text-sm rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition font-medium">
                                    保護団体サイトへ
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <!-- SNSシェアボタン -->
                    <div class="flex gap-3">
                        @php
                            $shareUrl = request()->url();
                            $shareTitle = $post->title ?? ($post->pet->name . 'の里親インタビュー');
                        @endphp
                        
                        <!-- X -->
                        <a href="https://x.com/intent/tweet?text={{ urlencode($shareTitle . ' - #しっぽのわ') }}&url={{ urlencode($shareUrl) }}" 
                           target="_blank" rel="noopener noreferrer"
                           class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800 hover:scale-110 transition-all duration-200 shadow-lg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        
                        <!-- Facebook -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" 
                           target="_blank" rel="noopener noreferrer"
                           class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 hover:scale-110 transition-all duration-200 shadow-lg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        
                        <!-- Instagram -->
                        <a href="https://www.instagram.com/" 
                           target="_blank" rel="noopener noreferrer"
                           class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full flex items-center justify-center hover:from-purple-600 hover:to-pink-600 hover:scale-110 transition-all duration-200 shadow-lg">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Search modal -->
    <div x-cloak x-show="search" @keydown.escape.window="search=false">
        <div class="fixed inset-0 bg-black/50 z-[950]" @click="search=false"></div>
        <div class="fixed top-16 right-4 left-4 sm:left-auto sm:w-[28rem] bg-white z-[960] rounded-lg shadow-xl p-4"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
            <h3 class="text-sm font-semibold text-gray-800 mb-3">絞り込み検索</h3>
            <form class="space-y-3">
                <div>
                    <div class="text-xs text-gray-700 mb-1">動物の種類</div>
                    <div class="flex flex-wrap gap-3 text-sm">
                        <label class="flex items-center gap-1"><input type="checkbox"> 犬</label>
                        <label class="flex items-center gap-1"><input type="checkbox"> 猫</label>
                        <label class="flex items-center gap-1"><input type="checkbox"> うさぎ</label>
                        <label class="flex items-center gap-1"><input type="checkbox"> その他</label>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs text-gray-700 mb-1">地域</label>
                        <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                            <option>すべて</option>
                            <option>北海道・東北</option>
                            <option>関東</option>
                            <option>中部・東海</option>
                            <option>関西</option>
                            <option>中国・四国</option>
                            <option>九州・沖縄</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-700 mb-1">年齢</label>
                        <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                            <option>すべて</option>
                            <option>0-1歳</option>
                            <option>2-3歳</option>
                            <option>4-6歳</option>
                            <option>7-10歳</option>
                            <option>11歳以上</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs text-gray-700 mb-1">キーワード</label>
                    <input type="text" placeholder="名前や特徴で検索..." class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-amber-500 text-white text-sm rounded-md hover:bg-amber-600 transition">検索</button>
                    <button type="button" @click="search=false" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-50 transition">キャンセル</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>


