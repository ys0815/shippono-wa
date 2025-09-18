<x-guest-layout>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
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
                    @endguest

                    @auth
                    <!-- ログイン時ユーザー情報 -->
                    <div>
                        <div class="text-xs font-semibold text-amber-700 mb-2">ユーザー</div>
                        <div class="p-3 bg-amber-50 rounded-lg">
                            <div class="text-sm font-medium text-gray-800">{{ Auth::user()->display_name ?? Auth::user()->name }}</div>
                            <div class="text-xs text-gray-600">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <!-- マイページ -->
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

                    <!-- サイト情報 -->
                    <div>
                        <div class="text-xs font-semibold text-amber-700 mb-2">サイト</div>
                        <ul class="space-y-1">
                            <li><a @click="sidebar=false" href="/" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">#しっぽのわとは？</a></li>
                            <li><a @click="sidebar=false" href="{{ route('pets.search', 'dog') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">犬の家族を見る</a></li>
                            <li><a @click="sidebar=false" href="{{ route('pets.search', 'cat') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">猫の家族を見る</a></li>
                            <li><a @click="sidebar=false" href="{{ route('pets.search', 'rabbit') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">うさぎの家族を見る</a></li>
                            <li><a @click="sidebar=false" href="{{ route('pets.search', 'other') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">その他の家族を見る</a></li>
                            <li><a @click="sidebar=false; scrollToShelters()" href="#shelters" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">保護団体リンク集</a></li>
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
                            <label class="block text-xs text-gray-700 mb-1">性別</label>
                            <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                                <option>すべて</option>
                                <option>オス</option>
                                <option>メス</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-700 mb-1">保護施設</label>
                            <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                                <option>すべて</option>
                                <option>施設A</option>
                                <option>施設B</option>
                                <option>施設C</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-amber-500 text-white text-sm rounded-md hover:bg-amber-600 transition">検索</button>
                        <button type="button" @click="search=false" class="px-4 py-2 text-sm border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">閉じる</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Hero Section - 全面画像版 -->
        <section class="relative w-full overflow-hidden" style="max-height: 400px;">
            <!-- 背景画像 -->
            <div class="absolute inset-0">
                <img src="{{ asset('images/' . $heroImage) }}" 
                     alt="保護動物と家族の幸せ" 
                     class="w-full h-full object-cover"
                     style="width: 100%; max-height: 400px;">
                <!-- オーバーレイ（テキストの可読性向上） -->
                <div class="absolute inset-0 bg-black/40"></div>
            </div>
            
            <!-- コンテンツ（画像の上に重ねて表示） -->
            <div class="relative z-10 h-full flex items-center justify-center" style="min-height: 400px;">
                <div class="text-center text-white px-4 max-w-4xl">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 leading-tight">
                        保護動物と家族の幸せを<br class="hidden sm:block">共有するプラットフォーム
                    </h1>
                    <p class="text-base sm:text-lg mb-6 opacity-90 max-w-2xl mx-auto">
                        温かいストーリーをお届けします。
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('login') }}" 
                           class="px-6 py-2 bg-white/20 backdrop-blur-sm border border-white/30 rounded-full text-white hover:bg-white/30 transition-all duration-300 font-semibold text-sm">
                           ログイン
                        </a>
                        <a href="{{ route('register') }}" 
                           class="px-6 py-2 bg-amber-500 hover:bg-amber-600 rounded-full text-white transition-all duration-300 font-semibold text-sm shadow-lg hover:shadow-xl">
                           新規登録
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-20">

            <!-- 新着の保護動物一覧 -->
            <section class="relative bg-white border-t border-b border-gray-200 py-12">
                <div class="text-center mb-10">
                    <h3 class="text-3xl font-bold text-gray-800 mb-3 relative inline-block">
                        <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent font-sans">
                            家族になった子たち新着
                        </span>
                        <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 w-24 h-1.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    </h3>
                    <p class="text-sm text-gray-600">かわいい家族たちがあなたを待っています</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($recentPets as $pet)
                        <div class="text-center group">
                            <!-- ペット画像（正円・大きく表示） -->
                            <div class="relative mb-4">
                                <a href="{{ route('pets.show', $pet->id) }}" class="block w-40 h-40 mx-auto rounded-full overflow-hidden border-4 border-white shadow-xl group-hover:shadow-2xl transition-shadow duration-300">
                                    @if($pet->profile_image_url)
                                        <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                            <span class="text-amber-600 text-2xl font-bold">{{ mb_substr($pet->name, 0, 2) }}</span>
                                        </div>
                                    @endif
                                </a>
                                <!-- 装飾的な背景 -->
                                <div class="absolute -inset-4 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                            </div>
                            
                            <!-- ペット情報 -->
                            <div class="space-y-2">
                                <!-- 品種 -->
                                @if($pet->breed)
                                    <div class="text-sm text-amber-600 font-medium">
                                        {{ $pet->breed }}
                                    </div>
                                @endif
                                
                                <!-- 名前・性別・推定年齢 -->
                                <div class="text-xl font-bold text-gray-800">
                                    {{ $pet->name }} 
                                    <span class="text-lg font-normal {{ $pet->gender === 'male' ? 'text-blue-500' : ($pet->gender === 'female' ? 'text-pink-500' : 'text-gray-500') }}">
                                        {{ __(['male' => '♂', 'female' => '♀', 'unknown' => '?'][$pet->gender] ?? '?') }}
                                    </span>
                                    @if($pet->age_years !== null || $pet->age_months !== null)
                                        <span class="text-sm text-gray-500 ml-1">
                                            @if($pet->age_years > 0 && $pet->age_months > 0)
                                                (推定{{ $pet->age_years }}歳{{ $pet->age_months }}ヶ月)
                                            @elseif($pet->age_years > 0)
                                                (推定{{ $pet->age_years }}歳)
                                            @elseif($pet->age_months > 0)
                                                (推定{{ $pet->age_months }}ヶ月)
                                            @endif
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- プロフィール説明 -->
                                @if($pet->profile_description)
                                    <div class="text-sm text-gray-600 leading-relaxed max-w-xs mx-auto">
                                        {{ Str::limit($pet->profile_description, 60) }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- ボタンエリア -->
                            <div class="flex gap-2 mt-6 justify-center">
                                @if($pet->shelter && $pet->shelter->website_url)
                                    <a href="{{ $pet->shelter->website_url }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="px-4 py-2 text-sm rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition-all duration-200 font-medium shadow-sm">
                                        保護団体サイトへ
                                    </a>
                                @else
                                    <button disabled class="px-4 py-2 text-sm rounded-full border-2 border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">
                                        保護団体サイトへ
                                    </button>
                                @endif
                                
                                @php
                                    $interviewPost = $pet->posts()->where('type','interview')->where('status','published')->latest()->first();
                                @endphp

                                @if($interviewPost)
                                    <a href="{{ route('interviews.show', $interviewPost) }}" 
                                       class="px-4 py-2 text-sm rounded-full border-2 border-pink-400 text-pink-700 bg-white hover:bg-pink-50 hover:border-pink-500 transition-all duration-200 font-medium shadow-sm">
                                        しっぽのわを読む
                                    </a>
                                @else
                                    <button disabled class="px-4 py-2 text-sm rounded-full border-2 border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">
                                        しっぽのわを読む
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- サービスコンセプト -->
            <section class="relative bg-gray-50 border-t border-b border-gray-200 py-12">
                <div class="text-center mb-10">
                    <h3 class="text-3xl font-bold text-gray-800 mb-3 relative inline-block">
                        <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent font-sans">
                            サービスコンセプト
                        </span>
                        <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 w-24 h-1.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    </h3>
                    <p class="text-sm text-gray-600">私たちが大切にしている想い</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                    <div class="text-center group">
                        <div class="relative mb-6">
                            <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 text-3xl group-hover:scale-110 transition-transform duration-300">
                                ❤
                            </div>
                            <div class="absolute -inset-2 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-3">幸せの可視化</h4>
                        <p class="text-gray-600 leading-relaxed">保護動物と家族のストーリーを写真と文章で。</p>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-6">
                            <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 text-3xl group-hover:scale-110 transition-transform duration-300">
                                🤝
                            </div>
                            <div class="absolute -inset-2 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-3">共感の輪</h4>
                        <p class="text-gray-600 leading-relaxed">思いに触れて、優しい支援の循環を。</p>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-6">
                            <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 text-3xl group-hover:scale-110 transition-transform duration-300">
                                🔗
                            </div>
                            <div class="absolute -inset-2 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-3">命をつなぐ</h4>
                        <p class="text-gray-600 leading-relaxed">施設や里親とつながる導線をわかりやすく。</p>
                    </div>
                </div>
            </section>

            <!-- 統計情報 -->
            <section class="relative bg-white border-t border-b border-gray-200 py-12">
                <div class="text-center mb-10">
                    <h3 class="text-3xl font-bold text-gray-800 mb-3 relative inline-block">
                        <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent font-sans">
                            統計情報
                        </span>
                        <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 w-24 h-1.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    </h3>
                    <p class="text-sm text-gray-600">みんなの活動を数字で見てみよう</p>
                    @if(isset($stats['updated_at']))
                        <p class="text-xs text-gray-500 mt-2">最終更新: {{ \Carbon\Carbon::parse($stats['updated_at'])->format('Y年n月j日 H:i') }}</p>
                    @endif
                    @auth
                        <div class="mt-3">
                            @if(session('status') === 'stats-updated')
                                <div class="text-xs text-green-600 mb-2">統計情報が更新されました</div>
                            @endif
                            <form action="{{ route('stats.update') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1 text-xs bg-amber-500 text-white rounded hover:bg-amber-600 transition">
                                    統計情報を更新
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
                    <div class="text-center group">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 group-hover:scale-110 transition-transform duration-300">
                                📝
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['posts_gallery']) }}</div>
                        <div class="text-sm text-gray-600">投稿数</div>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 group-hover:scale-110 transition-transform duration-300">
                                💬
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['posts_interview']) }}</div>
                        <div class="text-sm text-gray-600">インタビュー数</div>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 group-hover:scale-110 transition-transform duration-300">
                                🐾
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['pets']) }}</div>
                        <div class="text-sm text-gray-600">登録動物数</div>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 group-hover:scale-110 transition-transform duration-300">
                                🏢
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['shelters']) }}</div>
                        <div class="text-sm text-gray-600">掲載団体数</div>
                    </div>
                    <div class="text-center group col-span-2 sm:col-span-1">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 group-hover:scale-110 transition-transform duration-300">
                                ❤️
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['likes']) }}</div>
                        <div class="text-sm text-gray-600">いいね数</div>
                    </div>
                </div>
            </section>

            <!-- 保護団体リンク集 -->
            <section id="shelters" class="relative bg-gray-50 border-t border-b border-gray-200 py-12" x-data="shelterFilter()">
                <div class="text-center mb-10">
                    <h3 class="text-3xl font-bold text-gray-800 mb-3 relative inline-block">
                        <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent font-sans">
                            保護団体リンク集
                        </span>
                        <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 w-24 h-1.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    </h3>
                    <p class="text-sm text-gray-600">協力いただいている保護団体の皆様</p>
                </div>
                
                <!-- 地域選択フィルター -->
                <div class="mb-8">
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <label for="region-filter" class="text-sm font-medium text-gray-700 whitespace-nowrap">地域別表示</label>
                        <select id="region-filter" 
                                x-model="selectedRegion" 
                                @change="filterShelters()"
                                class="px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200 w-full sm:w-auto sm:min-w-[200px]">
                            <option value="all">全国</option>
                            <option value="hokkaido-tohoku">北海道・東北</option>
                            <option value="kanto">関東</option>
                            <option value="chubu-tokai">中部・東海</option>
                            <option value="kinki">近畿</option>
                            <option value="chugoku-shikoku">中国・四国</option>
                            <option value="kyushu-okinawa">九州・沖縄</option>
                        </select>
                    </div>
                    
                    <!-- フィルター結果表示 -->
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600" x-show="selectedRegion !== 'all'">
                            <span x-text="getRegionName(selectedRegion)"></span>の保護団体を表示中
                            <span x-text="'（' + filteredShelters.length + '件中' + Math.min(currentPage * itemsPerPage, filteredShelters.length) + '件表示）'"></span>
                        </p>
                        <p class="text-sm text-gray-600" x-show="selectedRegion === 'all'">
                            里親募集サイトを表示中
                            <span x-text="'（' + filteredShelters.length + '件中' + Math.min(currentPage * itemsPerPage, filteredShelters.length) + '件表示）'"></span>
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($shelters as $index => $shelter)
                        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-300 group shelter-card"
                             x-show="isVisible('{{ $shelter->prefecture ? $shelter->prefecture->name : '' }}', '{{ $shelter->kind }}') && isInCurrentPage({{ $index }})"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             data-prefecture="{{ $shelter->prefecture ? $shelter->prefecture->name : '' }}"
                             data-kind="{{ $shelter->kind }}"
                             data-index="{{ $index }}">
                            <!-- カード全体をflexboxで縦方向に配置 -->
                            <div class="flex flex-col h-full">
                                <!-- 上部コンテンツ -->
                                <div class="flex items-start gap-4 flex-1">
                                    <!-- 団体ロゴ・アイコン -->
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center text-amber-600 text-xl group-hover:scale-110 transition-transform duration-300">
                                            🏢
                                        </div>
                                    </div>
                                    
                                    <!-- 団体情報 -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-amber-700 transition-colors">
                                            {{ $shelter->name }}
                                        </h4>
                                        
                                        <!-- 地域・種類 -->
                                        <div class="flex flex-wrap gap-2">
                                            @if($shelter->prefecture)
                                                <span class="px-2 py-1 text-xs bg-amber-100 text-amber-700 rounded-full">
                                                    {{ $shelter->prefecture->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- 下部の公式サイトボタン -->
                                <div class="mt-4 flex justify-end">
                                    @if($shelter->website_url)
                                        <a href="{{ $shelter->website_url }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-200 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            公式サイト
                                        </a>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            公式サイト
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-2xl">
                                🏢
                            </div>
                            <p class="text-gray-500">登録されている保護団体はありません</p>
                        </div>
                    @endforelse
                    
                    <!-- フィルター結果が空の場合 -->
                    <div class="col-span-full text-center py-12" x-show="selectedRegion !== 'all' && filteredShelters.length === 0">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-400 text-2xl">
                            🔍
                        </div>
                        <p class="text-gray-500" x-text="getRegionName(selectedRegion) + 'には保護団体が登録されていません'"></p>
                    </div>
                </div>
                
                <!-- もっと見るボタン -->
                <div class="text-center mt-8" x-show="shouldShowLoadMore()">
                    <button @click="loadMore()" 
                            class="px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-200 font-medium">
                        もっと見る
                    </button>
                </div>
            </section>
        </main>
    </div>

    <script>
        // スムーズスクロール機能
        function scrollToShelters() {
            const sheltersSection = document.getElementById('shelters');
            if (sheltersSection) {
                // ヘッダーの高さを考慮してオフセットを調整
                const headerHeight = 56; // h-14 = 56px
                const offsetTop = sheltersSection.offsetTop - headerHeight - 20; // 20pxの余白
                
                // スムーズスクロール実行
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        }

        function shelterFilter() {
            return {
                selectedRegion: 'all',
                itemsPerPage: 5,
                currentPage: 1,
                filteredShelters: [],
                
                // 地域マッピング
                regionMapping: {
                    'hokkaido-tohoku': ['北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県'],
                    'kanto': ['茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県'],
                    'chubu-tokai': ['新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県'],
                    'kinki': ['三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県'],
                    'chugoku-shikoku': ['鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県'],
                    'kyushu-okinawa': ['福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県']
                },
                
                init() {
                    // 初期化時に全件表示
                    this.updateFilteredShelters();
                },
                
                filterShelters() {
                    // フィルタリング時にページをリセット
                    this.currentPage = 1;
                    this.updateFilteredShelters();
                },
                
                updateFilteredShelters() {
                    const allCards = document.querySelectorAll('.shelter-card');
                    this.filteredShelters = Array.from(allCards).filter(card => {
                        const prefecture = card.getAttribute('data-prefecture');
                        const kind = card.getAttribute('data-kind');
                        return this.isVisible(prefecture, kind);
                    });
                },
                
                isVisible(prefectureName, shelterKind) {
                    if (this.selectedRegion === 'all') {
                        // 全国選択時は里親募集サイト（kind=site）のみ表示
                        return shelterKind === 'site';
                    }
                    
                    if (!prefectureName || prefectureName === '') {
                        return false;
                    }
                    
                    const targetPrefectures = this.regionMapping[this.selectedRegion] || [];
                    return targetPrefectures.includes(prefectureName);
                },
                
                getRegionName(regionKey) {
                    const regionNames = {
                        'all': '全国',
                        'hokkaido-tohoku': '北海道・東北',
                        'kanto': '関東',
                        'chubu-tokai': '中部・東海',
                        'kinki': '近畿',
                        'chugoku-shikoku': '中国・四国',
                        'kyushu-okinawa': '九州・沖縄'
                    };
                    return regionNames[regionKey] || '全国';
                },
                
                hasVisibleShelters() {
                    const cards = document.querySelectorAll('.shelter-card');
                    return Array.from(cards).some(card => !card.hasAttribute('style') || !card.style.display.includes('none'));
                },
                
                isInCurrentPage(index) {
                    // フィルタリング後の表示順序で判定
                    const visibleCards = this.getVisibleCards();
                    const visibleIndex = visibleCards.findIndex(card => 
                        parseInt(card.getAttribute('data-index')) === index
                    );
                    
                    if (visibleIndex === -1) return false;
                    
                    const maxIndex = this.currentPage * this.itemsPerPage - 1;
                    return visibleIndex <= maxIndex;
                },
                
                loadMore() {
                    this.currentPage++;
                },
                
                shouldShowLoadMore() {
                    const totalVisible = this.filteredShelters.length;
                    const currentlyShowing = this.currentPage * this.itemsPerPage;
                    
                    return totalVisible > currentlyShowing;
                },
                
                getVisibleCards() {
                    return this.filteredShelters;
                }
            }
        }
    </script>
</x-guest-layout>


