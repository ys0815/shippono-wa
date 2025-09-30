<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script src="{{ asset('js/shelter-picker.js') }}"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50" style="font-family: 'Noto Sans JP', sans-serif;">
        <div x-data="{ 
            sidebar:false, 
            search:false,
            scrollToShelters() {
                // サイドバーを閉じる
                this.sidebar = false;
                
                // 現在のページがトップページかチェック
                if (window.location.pathname === '/' || window.location.pathname === '') {
                    // トップページの場合、直接スクロール
                    setTimeout(() => {
                        const element = document.getElementById('shelters');
                        if (element) {
                            element.scrollIntoView({ 
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }, 100);
                } else {
                    // 他のページの場合、トップページに遷移してからスクロール
                    window.location.href = '/#shelters';
                }
            }
        }" class="min-h-screen bg-gray-50">
            <!-- Header -->
            <header class="sticky top-0 z-[900] bg-white/90 backdrop-blur border-b border-amber-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
                    <button type="button" @click="sidebar=true" class="flex flex-col items-center p-2 rounded hover:bg-amber-50 text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        <span class="text-xs mt-1">menu</span>
                    </button>
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/icon.png') }}" 
                             alt="# しっぽのわ" 
                             class="h-12 sm:h-16 md:h-18 w-auto">
                    </a>
                    <button type="button" @click="search=true" class="flex flex-col items-center p-2 rounded hover:bg-amber-50 text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.387a1 1 0 01-1.414 1.414l-4.387-4.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd"/></svg>
                        <span class="text-xs mt-1">search</span>
                    </button>
                </div>
            </header>

            {{ $slot }}

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
                            <img src="{{ asset('images/icon.png') }}" alt="# しっぽのわ" class="w-16 h-16">
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
                                <li><a @click="sidebar=false" href="/about" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">#しっぽのわとは？</a></li>
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
                <div class="fixed top-16 right-4 left-4 sm:left-auto sm:w-[32rem] bg-white z-[960] rounded-lg shadow-xl p-6"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">絞り込み検索</h3>
                    <form action="{{ route('pets.search', 'all') }}" method="GET" x-data="ShelterPicker.create({
                        init() {
                            this.kind = '';
                            this.area = '';
                            this.shelterId = '';
                            this.list = [];
                        }
                    })" x-init="init()" class="space-y-4">
                        <!-- 動物の種類 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">動物の種類</label>
                            <div class="grid grid-cols-2 gap-2">
                                <label class="flex items-center p-2 border border-gray-300 rounded-md hover:bg-amber-50 cursor-pointer">
                                    <input type="radio" name="species" value="dog" class="mr-2 text-amber-600 focus:ring-amber-500">
                                    <span class="text-sm">犬</span>
                                </label>
                                <label class="flex items-center p-2 border border-gray-300 rounded-md hover:bg-amber-50 cursor-pointer">
                                    <input type="radio" name="species" value="cat" class="mr-2 text-amber-600 focus:ring-amber-500">
                                    <span class="text-sm">猫</span>
                                </label>
                                <label class="flex items-center p-2 border border-gray-300 rounded-md hover:bg-amber-50 cursor-pointer">
                                    <input type="radio" name="species" value="rabbit" class="mr-2 text-amber-600 focus:ring-amber-500">
                                    <span class="text-sm">うさぎ</span>
                                </label>
                                <label class="flex items-center p-2 border border-gray-300 rounded-md hover:bg-amber-50 cursor-pointer">
                                    <input type="radio" name="species" value="other" class="mr-2 text-amber-600 focus:ring-amber-500">
                                    <span class="text-sm">その他</span>
                                </label>
                            </div>
                        </div>

                        <!-- 性別 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">性別</label>
                            <select name="gender" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                                <option value="">すべて</option>
                                <option value="male">オス</option>
                                <option value="female">メス</option>
                                <option value="unknown">不明</option>
                            </select>
                        </div>

                        <!-- 保護施設の種別 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">保護施設の種別</label>
                            <select name="shelter_kind" x-model="kind" @change="handleKindChange()" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                                <option value="">すべて</option>
                                <option value="facility">保護団体・施設</option>
                                <option value="site">里親サイト</option>
                                <option value="unknown">不明</option>
                            </select>
                        </div>

                        <!-- 保護施設の所在地 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">保護施設の所在地</label>
                            <select name="shelter_area" x-model="area" @change="handleAreaChange()" :disabled="kind==='unknown'" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                                <option value="">すべて</option>
                                <template x-for="a in filteredAreas" :key="a">
                                    <option :value="a" x-text="labels[a]"></option>
                                </template>
                            </select>
                        </div>

                        <!-- 保護施設名 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">保護施設名</label>
                            <select name="shelter_id" x-model="shelterId" :disabled="kind==='unknown'" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                                <option value="">すべて</option>
                                <option value="" x-show="loading">読み込み中...</option>
                                <template x-for="s in list" :key="s.id">
                                    <option :value="s.id" x-text="s.name"></option>
                                </template>
                            </select>
                            <p class="text-xs text-gray-500 mt-1" x-show="kind==='unknown'">※ 不明を選んだ場合は未選択のままで構いません。</p>
                        </div>

                        

                        <div class="flex gap-3 pt-2">
                            <button type="submit" class="flex-1 px-4 py-2 bg-amber-500 text-white text-sm rounded-md hover:bg-amber-600 transition font-medium">
                                検索する
                            </button>
                            <button type="button" @click="search=false" class="px-4 py-2 text-sm border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50 transition">
                                閉じる
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Footer (global) -->
            <x-footer />
        </div>

    </body>
</html>
