<x-guest-layout>
    <div x-data="{ sidebar:false, search:false }" class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="sticky top-0 z-[900] bg-white/90 backdrop-blur border-b border-amber-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
                <button type="button" @click="sidebar=true" class="p-2 rounded hover:bg-amber-50 text-gray-700" aria-label="メニューを開く">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <h1 class="text-lg font-semibold text-gray-900"># しっぽのわ</h1>
                <button type="button" @click="search=true" class="p-2 rounded hover:bg-amber-50 text-gray-700" aria-label="検索を開く">
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
                                <option>近畿</option>
                                <option>中国・四国</option>
                                <option>九州・沖縄</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-700 mb-1">年齢</label>
                            <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                                <option>すべて</option>
                                <option>子犬・子猫（1歳未満）</option>
                                <option>成犬・成猫（1-7歳）</option>
                                <option>シニア（7歳以上）</option>
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

        <main class="w-full">
            <!-- 統合プロフィールヘッダー -->
            <section class="bg-white border border-gray-200 p-5 sm:p-8 mb-8">
                <!-- 背景画像エリア -->
                <div class="relative h-36 sm:h-40 bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 mb-8">
                    @if($pet->header_image_url)
                        <img src="{{ $pet->header_image_url }}" alt="header" class="absolute inset-0 w-full h-full object-cover">
                        <!-- オーバーレイ -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent"></div>
                    @endif
                    
                    <!-- 装飾的な要素 -->
                    <div class="absolute top-2 right-2 w-12 h-12 bg-white/20 rounded-full blur-xl"></div>
                    <div class="absolute bottom-2 left-2 w-8 h-8 bg-amber-200/30 rounded-full blur-lg"></div>

                    <!-- アイコンを背景バナー内に配置 -->
                    <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 w-32 h-32 sm:w-36 sm:h-36 rounded-full overflow-hidden border-4 border-white shadow-lg bg-amber-100">
                        @if($pet->profile_image_url)
                            <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                <span class="text-amber-600 text-3xl sm:text-4xl font-bold">{{ mb_substr($pet->name, 0, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- プロフィール情報 -->
                <div class="pt-14">
                    <!-- ペット名と性別 -->
                    <div class="flex items-center justify-center sm:justify-start gap-4 mb-6">
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 leading-tight">{{ $pet->name }}</h1>
                        <span class="text-2xl sm:text-3xl {{ $pet->gender === 'male' ? 'text-blue-400' : ($pet->gender === 'female' ? 'text-pink-400' : 'text-gray-400') }}">
                            {{ __(['male' => '♂', 'female' => '♀', 'unknown' => '?'][$pet->gender] ?? '?') }}
                        </span>
                    </div>
                    
                    <!-- 飼い主さん -->
                    <div class="mb-6">
                        <p class="text-gray-600 text-lg leading-relaxed">
                            <span class="font-medium text-amber-600">飼い主さん：</span>
                            {{ $pet->user->display_name ?? $pet->user->name }}さん
                        </p>
                    </div>

                    <!-- SNSリンク -->
                    @if($pet->user->sns_x || $pet->user->sns_instagram || $pet->user->sns_facebook)
                        <div class="mb-6">
                            <p class="text-sm text-amber-600 font-medium mb-3">SNSアカウント</p>
                            <div class="flex justify-center sm:justify-start gap-3">
                                @if($pet->user->sns_x)
                                    <a href="{{ $pet->user->sns_x }}" target="_blank" rel="noopener noreferrer" 
                                       class="w-8 h-8 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800 hover:scale-110 transition-all duration-200 shadow-lg">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if($pet->user->sns_instagram)
                                    <a href="{{ $pet->user->sns_instagram }}" target="_blank" rel="noopener noreferrer" 
                                       class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full flex items-center justify-center hover:from-purple-600 hover:to-pink-600 hover:scale-110 transition-all duration-200 shadow-lg">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if($pet->user->sns_facebook)
                                    <a href="{{ $pet->user->sns_facebook }}" target="_blank" rel="noopener noreferrer" 
                                       class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 hover:scale-110 transition-all duration-200 shadow-lg">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- いいねボタン -->
                    <div class="flex justify-center sm:justify-start mb-8">
                        @auth
                            @if($isLiked)
                                <form action="{{ route('likes.destroy', $pet->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-400 to-pink-500 text-white rounded-full hover:from-red-500 hover:to-pink-600 hover:scale-105 transition-all duration-200 shadow-lg">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                            <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                        </svg>
                                        <span class="font-medium text-sm">{{ $likeCount }}</span>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('likes.store') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="pet_id" value="{{ $pet->id }}">
                                    <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-100 to-orange-100 text-amber-600 rounded-full hover:from-amber-200 hover:to-orange-200 hover:scale-105 transition-all duration-200 shadow-lg border border-amber-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        <span class="font-medium text-sm">{{ $likeCount }}</span>
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-400 rounded-full shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <span class="font-medium text-sm">{{ $likeCount }}</span>
                            </div>
                        @endauth
                    </div>

                    <!-- 区切り線 -->
                    <div class="border-t border-gray-200 mb-8"></div>

                    <!-- 基本情報 -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">基本情報</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-base text-gray-700">
                            @if($pet->breed)
                                <div>
                                    <dt class="text-gray-500">品種</dt>
                                    <dd class="mt-1 font-medium">{{ $pet->breed }}</dd>
                                </div>
                            @endif
                            @if($pet->birth_date)
                                <div>
                                    <dt class="text-gray-500">誕生日</dt>
                                    <dd class="mt-1 font-medium">{{ \Carbon\Carbon::parse($pet->birth_date)->format('Y年n月j日') }}</dd>
                                </div>
                            @endif
                            @if($pet->rescue_date)
                                <div>
                                    <dt class="text-gray-500">お迎え記念日</dt>
                                    <dd class="mt-1 font-medium">{{ \Carbon\Carbon::parse($pet->rescue_date)->format('Y年n月j日') }}</dd>
                                </div>
                            @endif
                        </div>

                        <!-- プロフィール本文 -->
                        @if($pet->profile_description)
                            <div class="mt-6">
                                <dt class="text-gray-500 mb-3 text-base">プロフィール</dt>
                                <dd class="prose prose-base max-w-none text-gray-700 leading-relaxed">{{ $pet->profile_description }}</dd>
                            </div>
                        @endif
                    </div>

                    <!-- 区切り線 -->
                    <div class="border-t border-gray-200 mb-8"></div>

                    <!-- お迎え先団体情報 -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">お迎え先の保護団体</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                            <div class="text-gray-700">
                                <div class="font-semibold text-lg">{{ $pet->shelter->name ?? '情報なし' }}</div>
                            </div>
                            <div class="flex gap-3">
                                @if($pet->shelter && $pet->shelter->website_url)
                                    <a href="{{ $pet->shelter->website_url }}" target="_blank" rel="noopener noreferrer" class="px-5 py-3 text-base rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition font-medium">保護団体サイトへ</a>
                                @else
                                    <button disabled class="px-5 py-3 text-base rounded-full border-2 border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">保護団体サイトへ</button>
                                @endif
                                @php $interviewPost = $pet->posts()->where('type','interview')->where('status','published')->latest()->first(); @endphp
                                @if($interviewPost)
                                    <a href="{{ route('interviews.show', $interviewPost) }}" class="px-5 py-3 text-base rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition font-medium">しっぽのわを読む</a>
                                @else
                                    <button disabled class="px-5 py-3 text-base rounded-full border-2 border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">しっぽのわを読む</button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 区切り線 -->
                    <div class="border-t border-gray-200 mb-8"></div>

                    <!-- 一緒に暮らす家族 -->
                    @if($familyPets->count() > 0)
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-800 mb-6 relative inline-block">
                                <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">一緒に暮らす家族</span>
                                <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                            </h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                                @foreach($familyPets as $familyPet)
                                    <a href="{{ route('pets.show', $familyPet->id) }}" class="group">
                                        <div class="bg-gray-50 rounded-lg p-5 hover:bg-gray-100 transition">
                                            <div class="w-20 h-20 mx-auto rounded-full overflow-hidden border-2 border-amber-200 mb-4">
                                                @if($familyPet->profile_image_url)
                                                    <img src="{{ $familyPet->profile_image_url }}" alt="{{ $familyPet->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                                        <span class="text-amber-600 text-lg font-bold">{{ mb_substr($familyPet->name, 0, 2) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-center">
                                                <h3 class="font-medium text-gray-800 group-hover:text-amber-600 transition text-base">{{ $familyPet->name }}</h3>
                                                <p class="text-sm text-gray-500 leading-relaxed">
                                                    {{ __('dog' === $familyPet->species ? '犬' : ('cat' === $familyPet->species ? '猫' : ('rabbit' === $familyPet->species ? 'うさぎ' : 'その他'))) }}
                                                    <span class="{{ $familyPet->gender === 'male' ? 'text-blue-400' : ($familyPet->gender === 'female' ? 'text-pink-400' : 'text-gray-400') }}">
                                                        {{ __(['male' => '♂', 'female' => '♀', 'unknown' => '?'][$familyPet->gender] ?? '?') }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- 区切り線 -->
                        <div class="border-t border-gray-200 mb-8"></div>
                    @endif

                    <!-- シェア機能 -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">シェア用URL発行</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        
                        @auth
                            @if($pet->user_id === Auth::id())
                                <div class="space-y-6">
                                    <!-- シェア用URL生成ボタン -->
                                    <form action="{{ route('pets.generate-share-link', $pet) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-full hover:from-amber-600 hover:to-orange-600 transition font-medium">
                                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                            </svg>
                                            シェア用URL発行
                                        </button>
                                    </form>

                                    <!-- 生成されたURL表示 -->
                                    @if(session('share_url'))
                                        <div class="mt-6 p-5 bg-gray-50 rounded-lg">
                                            <label class="block text-base font-medium text-gray-700 mb-3">シェア用URL:</label>
                                            <div class="flex gap-3">
                                                <input type="text" value="{{ session('share_url') }}" readonly class="flex-1 px-4 py-3 border border-gray-300 rounded-md text-base" id="share-url">
                                                <button onclick="copyToClipboard('share-url')" class="px-5 py-3 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition text-base">
                                                    コピー
                                                </button>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- QRコード生成ボタン -->
                                    @if(session('share_url'))
                                        <div class="mt-6">
                                            <p class="text-base text-gray-600 mb-4">QRコード:</p>
                                            <a href="{{ route('pets.qr-code', $pet) }}" target="_blank" 
                                               class="inline-flex items-center gap-2 px-6 py-3 bg-purple-500 text-white rounded-full hover:bg-purple-600 transition text-base">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                                </svg>
                                                QRコード生成
                                            </a>
                                        </div>
                                    @endif

                                    <!-- SNS共有ボタン -->
                                    @if(session('share_url'))
                                        <div class="mt-4">
                                            <p class="text-sm text-gray-600 mb-3">SNSで共有:</p>
                                            <div class="flex gap-3">
                                                <!-- X -->
                                                <a href="https://x.com/intent/tweet?text={{ urlencode($pet->name . 'のプロフィールをチェック！') }}&url={{ urlencode(session('share_url')) }}" 
                                                   target="_blank" rel="noopener noreferrer"
                                                   class="flex items-center gap-2 px-4 py-2 bg-black text-white rounded-full hover:bg-gray-800 transition text-sm">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                                    </svg>
                                                    X
                                                </a>

                                                <!-- Facebook -->
                                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(session('share_url')) }}" 
                                                   target="_blank" rel="noopener noreferrer"
                                                   class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition text-sm">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                    </svg>
                                                    Facebook
                                                </a>

                                                <!-- Instagram -->
                                                <a href="https://www.instagram.com/" 
                                                   target="_blank" rel="noopener noreferrer"
                                                   class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full hover:from-purple-600 hover:to-pink-600 transition text-sm">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                    </svg>
                                                    Instagram
                                                </a>

                                                <!-- LINE -->
                                                <a href="https://social-plugins.line.me/lineit/share?url={{ urlencode(session('share_url')) }}" 
                                                   target="_blank" rel="noopener noreferrer"
                                                   class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition text-sm">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.349 0 .63.285.63.63 0 .346-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .63.285.63.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.281.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                                                    </svg>
                                                    LINE
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">このペットのシェア用URLを発行するには、ペットの所有者である必要があります。</p>
                            @endif
                        @else
                            <p class="text-gray-500 text-sm">
                                <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-800">ログイン</a>してシェア用URLを発行できます。
                            </p>
                        @endauth
                    </div>

                    <!-- 区切り線 -->
                    <div class="border-t border-gray-200 mb-8"></div>

                    <!-- 今日の幸せシェアしよう -->
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 mb-6 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">今日の幸せ、シェアしよう</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        <div id="posts-container" class="space-y-6">
                            <!-- 投稿はJavaScriptで動的に読み込まれます -->
                        </div>
                        <div id="loading-indicator" class="text-center py-6 hidden">
                            <div class="inline-flex items-center px-5 py-3 text-base text-gray-600">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                読み込み中...
                            </div>
                        </div>
                        <div id="no-more-posts" class="text-center py-6 text-gray-500 text-base hidden">
                            これ以上投稿はありません
                        </div>
                    </div>
                </div>
            </section>


        </main>
    </div>


    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            element.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand('copy');
            
            // コピー完了のフィードバック
            const button = element.nextElementSibling;
            const originalText = button.textContent;
            button.textContent = 'コピー完了!';
            button.classList.add('bg-green-600', 'hover:bg-green-700');
            button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-600', 'hover:bg-green-700');
                button.classList.add('bg-gray-600', 'hover:bg-gray-700');
            }, 2000);
        }


        // 無限スクロール機能
        let currentPage = 1;
        let isLoading = false;
        let hasMorePosts = true;

        // 初期投稿読み込み
        loadPosts();

        function loadPosts() {
            if (isLoading || !hasMorePosts) return;
            
            isLoading = true;
            document.getElementById('loading-indicator').classList.remove('hidden');
            
            fetch(`/api/pets/{{ $pet->id }}/posts?page=${currentPage}`)
                .then(response => response.json())
                .then(data => {
                    if (data.posts.length === 0) {
                        hasMorePosts = false;
                        document.getElementById('no-more-posts').classList.remove('hidden');
                    } else {
                        data.posts.forEach(post => {
                            addPostToContainer(post);
                        });
                        currentPage++;
                        hasMorePosts = data.hasMore;
                    }
                })
                .catch(error => {
                    console.error('Error loading posts:', error);
                })
                .finally(() => {
                    isLoading = false;
                    document.getElementById('loading-indicator').classList.add('hidden');
                });
        }

        function addPostToContainer(post) {
            const container = document.getElementById('posts-container');
            const postElement = document.createElement('div');
            postElement.className = 'bg-gray-50 rounded-lg p-4';
            
            let mediaHtml = '';
            if (post.media.length > 0) {
                mediaHtml = '<div class="mb-4">';
                post.media.slice(0, 3).forEach(media => {
                    // API側で既に正しいURLが設定されているので、そのまま使用
                    let mediaUrl = media.url;
                    console.log('Media URL:', mediaUrl, 'Type:', media.type);
                    
                    if (media.type === 'image') {
                        mediaHtml += `<div class="w-24 h-24 object-cover rounded-lg inline-block mr-2 overflow-hidden">
                                        <img src="${mediaUrl}" alt="${post.title}" class="w-full h-full object-cover" 
                                             onerror="console.error('Image load error:', this.src); this.style.display='none';">
                                      </div>`;
                    } else if (media.type === 'video') {
                        mediaHtml += `<div class="w-24 h-24 object-cover rounded-lg inline-block mr-2 overflow-hidden relative">
                                        <video src="${mediaUrl}" class="w-full h-full object-cover" muted 
                                               onerror="console.error('Video load error:', this.src); this.style.display='none';">
                                        </video>
                                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                      </div>`;
                    }
                });
                if (post.media.length > 3) {
                    mediaHtml += `<div class="w-24 h-24 bg-gray-200 rounded-lg inline-flex items-center justify-center text-gray-500 text-sm">
                                    +${post.media.length - 3}
                                  </div>`;
                }
                mediaHtml += '</div>';
            }

            let actionButtons = '';
            @auth
                if (post.user_id === {{ Auth::id() }}) {
                    actionButtons = `
                        <div class="flex gap-2">
                            <a href="/mypage/posts/${post.id}/edit" 
                               class="px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                編集
                            </a>
                            <form action="/mypage/posts/${post.id}" method="POST" class="inline">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" 
                                        class="px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600 transition"
                                        onclick="return confirm('この投稿を削除してもよろしいですか？この操作は元に戻せません。')">
                                    削除
                                </button>
                            </form>
                            <form action="/mypage/posts/${post.id}/toggle-visibility" method="POST" class="inline">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" 
                                        class="px-3 py-1 text-xs bg-yellow-500 hover:bg-yellow-600 text-white rounded transition">
                                    非公開
                                </button>
                            </form>
                        </div>
                    `;
                }
            @endauth

            postElement.innerHTML = `
                <div class="flex justify-between items-start mb-3">
                    <div class="flex items-center">
                        <span class="text-xs text-white px-2 py-1 rounded" style="background-color: #f59e0b;">
                            今日の幸せ
                        </span>
                    </div>
                    <div class="text-sm text-gray-500">
                        ${post.created_at}
                    </div>
                </div>
                
                <h3 class="text-lg font-medium text-gray-900 mb-2">${post.title}</h3>
                ${post.content ? `<p class="text-gray-600 mb-4">${post.content.length > 100 ? post.content.substring(0, 100) + '...' : post.content}</p>` : ''}
                
                ${mediaHtml}
                
                ${actionButtons}
            `;
            
            container.appendChild(postElement);
        }

        // スクロールイベントリスナー
        window.addEventListener('scroll', function() {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 1000) {
                loadPosts();
            }
        });
    </script>
</x-guest-layout>
