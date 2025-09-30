<!-- Header + Sidebar (shared Alpine scope) -->
<div x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen=false">

<!-- Top Navigation -->
<nav class="fixed top-0 left-0 right-0 z-[1000] bg-white/90 backdrop-blur border-b border-amber-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Hamburger Menu Button (mobile only) -->
                <div class="flex items-center mr-4 sm:hidden">
                    <button @click="sidebarOpen = ! sidebarOpen" aria-label="Open menu" 
                        class="flex flex-col items-center justify-center p-2 rounded-md text-gray-500 hover:text-amber-700 hover:bg-amber-50 focus:outline-none focus:bg-amber-50 focus:text-amber-700 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span class="text-xs mt-1">menu</span>
                    </button>
                </div>


                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/icon.png') }}" 
                             alt="# しっぽのわ" 
                             class="h-12 sm:h-14 w-auto">
                    </a>
                </div>

                <!-- Navigation Links (desktop) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('ホーム') }}
                    </x-nav-link>
                    <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
                        {{ __('#しっぽのわとは？') }}
                    </x-nav-link>
                    <x-nav-link :href="route('before_adoption')" :active="request()->routeIs('before_adoption')">
                        {{ __('家族をお迎えする前に') }}
                    </x-nav-link>
                    <x-nav-link :href="route('interviews.index')" :active="request()->routeIs('interviews.index')">
                        {{ __('里親インタビュー') }}
                    </x-nav-link>
                    @auth
                    <x-nav-link :href="route('mypage')" :active="request()->routeIs('mypage')">
                        {{ __('マイページ') }}
                    </x-nav-link>
                    <x-nav-link :href="route('mypage.posts')" :active="request()->routeIs('mypage.posts*')">
                        {{ __('投稿管理') }}
                    </x-nav-link>
                    <x-nav-link :href="route('mypage.pets')" :active="request()->routeIs('mypage.pets*')">
                        {{ __('ペット管理') }}
                    </x-nav-link>
                    <x-nav-link :href="route('mypage.likes')" :active="request()->routeIs('mypage.likes*')">
                        {{ __('いいね一覧') }}
                    </x-nav-link>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            @auth
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-amber-200 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-amber-50 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @endauth
            
            @guest
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-amber-600 px-3 py-2 rounded-md">ログイン</a>
                <a href="{{ route('register') }}" class="ml-4 bg-amber-600 text-white px-4 py-2 rounded-md text-sm hover:bg-amber-700">新規登録</a>
            </div>
            @endguest
        </div>
    </div>
</nav>

<!-- Sidebar Overlay -->
<div x-cloak 
     x-show="sidebarOpen" 
     x-transition.opacity
     class="fixed inset-0 z-[1100] bg-black/50" 
     @click="sidebarOpen = false"></div>

<!-- Sidebar Menu -->
<aside x-cloak 
       x-show="sidebarOpen"
       x-transition:enter="transition ease-in-out duration-300 transform"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in-out duration-300 transform"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       class="fixed top-0 left-0 z-[1200] w-72 md:w-80 max-w-[85vw] h-full bg-white shadow-lg overflow-y-auto">
    
    <!-- Header -->
    <div class="p-4 border-b bg-amber-50 border-amber-200 flex items-center justify-between">
        @auth
        <div>
            <div class="text-lg font-bold text-gray-800">{{ Auth::user()->display_name ?? Auth::user()->name }}</div>
            <div class="text-sm text-gray-600">{{ Auth::user()->email }}</div>
        </div>
        @else
        <div>
            <div class="text-lg font-bold text-gray-800">#しっぽのわ</div>
            <div class="text-sm text-gray-600">保護動物と家族の幸せを分かち合う</div>
        </div>
        @endauth
        <button @click="sidebarOpen=false" aria-label="メニューを閉じる" 
            class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-amber-700 hover:bg-amber-100 focus:outline-none transition">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    <!-- Menu blocks -->
    <nav class="p-4 space-y-6" aria-label="サイドバー">
        <div>
            <div class="text-xs font-semibold text-amber-700 mb-2">サイト</div>
            <ul class="space-y-1">
                <li><a @click="sidebarOpen=false" href="{{ route('home') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">ホーム</a></li>
                <li><a @click="sidebarOpen=false" href="{{ route('about') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">#しっぽのわとは？</a></li>
                <li><a @click="sidebarOpen=false" href="{{ route('before_adoption') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">家族をお迎えする前に</a></li>
                <li><a @click="sidebarOpen=false" href="{{ route('interviews.index') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">里親インタビュー</a></li>
            </ul>
        </div>

        @auth
        <div>
            <div class="text-xs font-semibold text-amber-700 mb-2">マイページ</div>
            <ul class="space-y-1">
                <li><a @click="sidebarOpen=false" href="{{ route('mypage') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">マイページ</a></li>
                <li><a @click="sidebarOpen=false" href="{{ route('mypage.posts') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">投稿管理</a></li>
                <li><a @click="sidebarOpen=false" href="{{ route('mypage.pets') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">ペットプロフィール管理</a></li>
                <li><a @click="sidebarOpen=false" href="{{ route('mypage.likes') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">いいね一覧</a></li>
            </ul>
        </div>

        <div>
            <div class="text-xs font-semibold text-amber-700 mb-2">作成</div>
            <ul class="space-y-1">
                <li><a @click="sidebarOpen=false" href="{{ route('mypage.posts.gallery.create') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">今日の幸せを投稿</a></li>
                <li><a @click="sidebarOpen=false" href="{{ route('mypage.posts.interview.create') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">里親インタビューを投稿</a></li>
                <li><a @click="sidebarOpen=false" href="{{ route('mypage.pets.create') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">ペットを登録</a></li>
            </ul>
        </div>

        <div>
            <div class="text-xs font-semibold text-amber-700 mb-2">設定</div>
            <ul class="space-y-1">
                <li><a @click="sidebarOpen=false" href="{{ route('profile.edit') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">プロフィール編集</a></li>
                <li><a @click="sidebarOpen=false" href="{{ route('mypage.profile.email') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">メールアドレス変更</a></li>
                <li><a @click="sidebarOpen=false" href="{{ route('mypage.profile.password') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">パスワード変更</a></li>
            </ul>
        </div>

        <div>
            <div class="text-xs font-semibold text-amber-700 mb-2">その他</div>
            <ul class="space-y-1">
                <li><a @click="sidebarOpen=false" href="#" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">ヘルプ・サポート</a></li>
                <li><a @click="sidebarOpen=false" href="#" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">お問い合わせ</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">ログアウト</button>
                    </form>
                </li>
            </ul>
        </div>
        @endauth
        
        @guest
        <div>
            <div class="text-xs font-semibold text-amber-700 mb-2">アカウント</div>
            <ul class="space-y-1">
                <li><a @click="sidebarOpen=false" href="{{ route('login') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">ログイン</a></li>
                <li><a @click="sidebarOpen=false" href="{{ route('register') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">新規登録</a></li>
            </ul>
        </div>
        @endguest
    </nav>
</aside>

</div>
