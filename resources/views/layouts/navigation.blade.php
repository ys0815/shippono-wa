<nav x-data="{ sidebarOpen: false }" 
     x-init="sidebarOpen = false; $nextTick(() => { sidebarOpen = false; })" 
     @keydown.escape.window="sidebarOpen=false" 
     class="fixed top-0 left-0 right-0 z-[100] bg-white/90 backdrop-blur border-b border-amber-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Hamburger Menu Button -->
                <div class="flex items-center mr-4">
                    <button @click="sidebarOpen = ! sidebarOpen" aria-label="Open menu" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-amber-700 hover:bg-amber-50 focus:outline-none focus:bg-amber-50 focus:text-amber-700 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('mypage') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links (desktop) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('mypage')" :active="request()->routeIs('mypage')">
                        {{ __('マイページ') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
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
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div x-cloak 
         x-show="sidebarOpen" 
         x-transition.opacity
         class="fixed inset-0 z-[200] bg-black/50" 
         @click="sidebarOpen = false"
         style="display: none;"></div>

    <!-- Sidebar Menu -->
    <aside x-cloak 
           x-show="sidebarOpen"
           x-transition:enter="transition ease-in-out duration-300 transform"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in-out duration-300 transform"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed top-0 left-0 z-[210] w-72 h-full bg-white shadow-lg overflow-y-auto"
           style="display: none;">
        
        <!-- Header -->
        <div class="p-4 border-b bg-amber-50 border-amber-200">
            <div class="text-lg font-bold text-gray-800">{{ Auth::user()->display_name ?? Auth::user()->name }}</div>
            <div class="text-sm text-gray-600">{{ Auth::user()->email }}</div>
        </div>

        <!-- Menu blocks -->
        <nav class="p-4 space-y-6">
            <div>
                <div class="text-xs font-semibold text-amber-700 mb-2">メイン</div>
                <ul class="space-y-1">
                    <li><a @click="sidebarOpen=false" href="{{ route('mypage') }}" class="flex items-center p-2 rounded hover:bg-amber-50">🏠<span class="ml-3">マイページ</span></a></li>
                    <li><a @click="sidebarOpen=false" href="{{ route('mypage.posts') }}" class="flex items-center p-2 rounded hover:bg-amber-50">📝<span class="ml-3">投稿管理</span></a></li>
                    <li><a @click="sidebarOpen=false" href="{{ route('mypage.pets') }}" class="flex items-center p-2 rounded hover:bg-amber-50">🐾<span class="ml-3">動物プロフィール管理</span></a></li>
                    <li><a @click="sidebarOpen=false" href="{{ route('mypage.likes') }}" class="flex items-center p-2 rounded hover:bg-amber-50">❤️<span class="ml-3">いいね一覧</span></a></li>
                </ul>
            </div>

            <div>
                <div class="text-xs font-semibold text-amber-700 mb-2">作成</div>
                <ul class="space-y-1">
                    <li><a @click="sidebarOpen=false" href="#" class="flex items-center p-2 rounded hover:bg-gray-100">✏️<span class="ml-3">新規投稿</span></a></li>
                    <li><a @click="sidebarOpen=false" href="{{ route('mypage.pets.create') }}" class="flex items-center p-2 rounded hover:bg-gray-100">➕<span class="ml-3">動物を登録</span></a></li>
                </ul>
            </div>

            <div>
                <div class="text-xs font-semibold text-amber-700 mb-2">設定</div>
                <ul class="space-y-1">
                    <li><a @click="sidebarOpen=false" href="{{ route('profile.edit') }}" class="flex items-center p-2 rounded hover:bg-gray-100">👤<span class="ml-3">プロフィール編集</span></a></li>
                    <li><a @click="sidebarOpen=false" href="{{ route('mypage.profile.email') }}" class="flex items-center p-2 rounded hover:bg-gray-100">✉️<span class="ml-3">メールアドレス変更</span></a></li>
                    <li><a @click="sidebarOpen=false" href="{{ route('mypage.profile.password') }}" class="flex items-center p-2 rounded hover:bg-gray-100">🔒<span class="ml-3">パスワード変更</span></a></li>
                </ul>
            </div>

            <div>
                <div class="text-xs font-semibold text-amber-700 mb-2">その他</div>
                <ul class="space-y-1">
                    <li><a @click="sidebarOpen=false" href="#" class="flex items-center p-2 rounded hover:bg-gray-100">❓<span class="ml-3">ヘルプ・サポート</span></a></li>
                    <li><a @click="sidebarOpen=false" href="#" class="flex items-center p-2 rounded hover:bg-gray-100">📞<span class="ml-3">お問い合わせ</span></a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center p-2 rounded hover:bg-gray-100">🚪<span class="ml-3">ログアウト</span></button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </aside>
</nav>
