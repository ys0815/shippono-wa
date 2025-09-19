<!-- Header -->
<header class="sticky top-0 z-[900] bg-white/90 backdrop-blur border-b border-amber-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
        <button type="button" @click="sidebar=true" class="p-2 rounded hover:bg-amber-50 text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        </button>
        <a href="{{ route('home') }}" class="flex items-center">
            <img src="{{ asset('images/icon.png') }}" alt="# しっぽのわ" class="h-8 sm:h-12 w-auto">
        </a>
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
                <div class="space-y-2">
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-md transition">ログイン</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-md transition">新規登録</a>
                </div>
            </div>
            @else
            <!-- ログイン済み用アカウント項目 -->
            <div>
                <div class="text-xs font-semibold text-amber-700 mb-2">アカウント</div>
                <div class="space-y-2">
                    <a href="{{ route('mypage') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-md transition">マイページ</a>
                    <a href="{{ route('mypage.pets') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-md transition">ペット管理</a>
                    <a href="{{ route('mypage.posts') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-md transition">投稿管理</a>
                    <a href="{{ route('mypage.likes') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-md transition">いいね一覧</a>
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-md transition">プロフィール編集</a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-md transition">ログアウト</button>
                    </form>
                </div>
            </div>
            @endguest

            <!-- コンテンツ項目 -->
            <div>
                <div class="text-xs font-semibold text-amber-700 mb-2">コンテンツ</div>
                <div class="space-y-2">
                    <a href="{{ route('home') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-md transition">トップページ</a>
                    <a href="{{ route('interviews.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-md transition">里親インタビュー</a>
                    <a href="{{ route('pets.search', 'all') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-md transition">ペット検索</a>
                </div>
            </div>
        </nav>
    </aside>
</div>

<!-- Search Modal -->
<div x-cloak x-show="search" @keydown.escape.window="search=false">
    <div class="fixed inset-0 bg-black/50 z-[1100]" @click="search=false"></div>
    <div class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-[1200] w-full max-w-md mx-4">
        <div class="bg-white rounded-lg shadow-xl p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">検索</h3>
            <div class="space-y-4">
                <!-- インタビュー検索 -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">里親インタビュー</h4>
                    <form method="GET" action="{{ route('interviews.index') }}" class="space-y-2">
                        <div>
                            <input type="text" name="q" placeholder="キーワードで検索..." 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <button type="submit" class="w-full px-4 py-2 bg-amber-500 text-white rounded-md hover:bg-amber-600 transition">検索</button>
                    </form>
                </div>
                
                <!-- ペット検索 -->
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">ペット検索</h4>
                    <div class="space-y-2">
                        <a href="{{ route('pets.search', 'all') }}" class="block w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition text-center">ペットを探す</a>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <button type="button" @click="search=false" class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition">閉じる</button>
                </div>
            </div>
        </div>
    </div>
</div>
