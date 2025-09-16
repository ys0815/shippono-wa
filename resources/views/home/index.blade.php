<x-guest-layout>
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
                        <div class="flex gap-2">
                            <a href="#" class="w-8 h-8 border border-gray-300 rounded text-xs flex items-center justify-center text-gray-700 bg-gray-100 hover:bg-gray-200">X</a>
                            <a href="#" class="w-8 h-8 border border-gray-300 rounded text-xs flex items-center justify-center text-gray-700 bg-gray-100 hover:bg-gray-200">IG</a>
                            <a href="#" class="w-8 h-8 border border-gray-300 rounded text-xs flex items-center justify-center text-gray-700 bg-gray-100 hover:bg-gray-200">FB</a>
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
                            <select class="w-full border rounded-md px-2 py-1.5 focus:ring-amber-500 focus:border-amber-500">
                                <option>地域を選択</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-700 mb-1">保護施設</label>
                            <select class="w-full border rounded-md px-2 py-1.5 focus:ring-amber-500 focus:border-amber-500">
                                <option>施設を選択</option>
                            </select>
                        </div>
                    </div>
                    <div class="pt-1">
                        <button type="button" class="w-full btn btn-brand">絞り込む</button>
                    </div>
                </form>
            </div>
        </div>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-20">
            <!-- Hero -->
            <section class="relative bg-gray-50 border border-gray-200 rounded-2xl p-8 shadow-sm">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-center">
                    <div class="sm:col-span-1">
                        <img src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?q=80&w=1080&auto=format&fit=crop" alt="main" class="w-full h-40 object-cover rounded-xl shadow">
                    </div>
                    <div class="sm:col-span-2">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 font-sans">保護動物と家族の幸せを共有するプラットフォーム</h2>
                        <p class="text-sm text-gray-700 mt-3">新着の保護動物情報や里親インタビューを、やさしいUIでお届けします。</p>
                        <div class="mt-4 flex gap-3">
                            <a href="{{ route('login') }}" class="btn btn-outline">ログイン</a>
                            <a href="{{ route('register') }}" class="btn btn-brand">新規登録</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 新着の保護動物一覧 -->
            <section class="relative bg-white border-t border-b border-gray-200 py-12">
                <div class="text-center mb-10">
                    <h3 class="text-3xl font-bold text-gray-800 mb-3 relative inline-block">
                        <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent font-sans">
                            新着の保護動物一覧
                        </span>
                        <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 w-24 h-1.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    </h3>
                    <p class="text-sm text-gray-600">かわいいペットたちがあなたを待っています</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($recentPets as $pet)
                        <div class="text-center group">
                            <!-- ペット画像（正円・大きく表示） -->
                            <div class="relative mb-4">
                                <div class="w-40 h-40 mx-auto rounded-full overflow-hidden border-4 border-white shadow-xl group-hover:shadow-2xl transition-shadow duration-300">
                                    @if($pet->profile_image_url)
                                        <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                            <span class="text-amber-600 text-2xl font-bold">{{ mb_substr($pet->name, 0, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
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
                                    $hasInterview = $pet->posts()->where('type', 'interview')->exists();
                                @endphp
                                
                                @if($hasInterview)
                                    <a href="{{ route('pets.show', $pet->id) }}#interview" 
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
                        <span class="bg-gradient-to-r from-pink-500 to-purple-500 bg-clip-text text-transparent font-sans">
                            サービスコンセプト
                        </span>
                        <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 w-24 h-1.5 bg-gradient-to-r from-pink-500 to-purple-500 rounded-full"></div>
                    </h3>
                    <p class="text-sm text-gray-600">私たちが大切にしている想い</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                    <div class="text-center group">
                        <div class="relative mb-6">
                            <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-pink-100 to-pink-200 text-pink-600 text-3xl group-hover:scale-110 transition-transform duration-300">
                                ❤
                            </div>
                            <div class="absolute -inset-2 bg-gradient-to-br from-pink-200 to-pink-300 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-3">幸せの可視化</h4>
                        <p class="text-gray-600 leading-relaxed">保護動物と家族のストーリーを写真と文章で。</p>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-6">
                            <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-amber-200 text-amber-600 text-3xl group-hover:scale-110 transition-transform duration-300">
                                🤝
                            </div>
                            <div class="absolute -inset-2 bg-gradient-to-br from-amber-200 to-amber-300 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-3">共感の輪</h4>
                        <p class="text-gray-600 leading-relaxed">思いに触れて、優しい支援の循環を。</p>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-6">
                            <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-blue-100 to-blue-200 text-blue-600 text-3xl group-hover:scale-110 transition-transform duration-300">
                                🔗
                            </div>
                            <div class="absolute -inset-2 bg-gradient-to-br from-blue-200 to-blue-300 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
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
                        <span class="bg-gradient-to-r from-green-500 to-blue-500 bg-clip-text text-transparent font-sans">
                            統計情報
                        </span>
                        <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 w-24 h-1.5 bg-gradient-to-r from-green-500 to-blue-500 rounded-full"></div>
                    </h3>
                    <p class="text-sm text-gray-600">みんなの活動を数字で見てみよう</p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
                    <div class="text-center group">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-green-100 to-green-200 text-green-600 group-hover:scale-110 transition-transform duration-300">
                                📝
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-green-200 to-green-300 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['posts_gallery']) }}</div>
                        <div class="text-sm text-gray-600">投稿数</div>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-blue-100 to-blue-200 text-blue-600 group-hover:scale-110 transition-transform duration-300">
                                💬
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-blue-200 to-blue-300 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['posts_interview']) }}</div>
                        <div class="text-sm text-gray-600">インタビュー数</div>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-amber-200 text-amber-600 group-hover:scale-110 transition-transform duration-300">
                                🐾
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-amber-200 to-amber-300 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['pets']) }}</div>
                        <div class="text-sm text-gray-600">登録動物数</div>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-purple-100 to-purple-200 text-purple-600 group-hover:scale-110 transition-transform duration-300">
                                🏢
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-purple-200 to-purple-300 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['shelters']) }}</div>
                        <div class="text-sm text-gray-600">掲載団体数</div>
                    </div>
                    <div class="text-center group col-span-2 sm:col-span-1">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-pink-100 to-pink-200 text-pink-600 group-hover:scale-110 transition-transform duration-300">
                                ❤️
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-pink-200 to-pink-300 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['likes']) }}</div>
                        <div class="text-sm text-gray-600">いいね数</div>
                    </div>
                </div>
            </section>

            <!-- 保護団体リンク集 -->
            <section class="relative bg-gray-50 border-t border-b border-gray-200 py-12" x-data="shelterFilter()">
                <div class="text-center mb-10">
                    <h3 class="text-3xl font-bold text-gray-800 mb-3 relative inline-block">
                        <span class="bg-gradient-to-r from-indigo-500 to-purple-500 bg-clip-text text-transparent font-sans">
                            保護団体リンク集
                        </span>
                        <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 w-24 h-1.5 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full"></div>
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
                                class="px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 w-full sm:w-auto sm:min-w-[200px]">
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
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($shelters as $shelter)
                        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-300 group shelter-card"
                             x-show="isVisible('{{ $shelter->prefecture ? $shelter->prefecture->name : '' }}')"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             data-prefecture="{{ $shelter->prefecture ? $shelter->prefecture->name : '' }}">
                            <div class="flex items-start gap-4">
                                <!-- 団体ロゴ・アイコン -->
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-600 text-xl group-hover:scale-110 transition-transform duration-300">
                                        🏢
                                    </div>
                                </div>
                                
                                <!-- 団体情報 -->
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-indigo-700 transition-colors">
                                        {{ $shelter->name }}
                                    </h4>
                                    
                                    @if($shelter->area)
                                        <p class="text-sm text-gray-600 mb-3 leading-relaxed">
                                            {{ $shelter->area }}
                                        </p>
                                    @endif
                                    
                                    <!-- 地域・種類 -->
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @if($shelter->prefecture)
                                            <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-700 rounded-full">
                                                {{ $shelter->prefecture->name }}
                                            </span>
                                        @endif
                                        @if($shelter->kind)
                                            <span class="px-2 py-1 text-xs bg-purple-100 text-purple-700 rounded-full">
                                                {{ $shelter->kind }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- ウェブサイトリンク -->
                                    @if($shelter->website_url)
                                        <a href="{{ $shelter->website_url }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200 font-medium">
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
                    <div class="col-span-full text-center py-12" x-show="selectedRegion !== 'all' && !hasVisibleShelters()">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-400 text-2xl">
                            🔍
                        </div>
                        <p class="text-gray-500" x-text="getRegionName(selectedRegion) + 'には保護団体が登録されていません'"></p>
                    </div>
                </div>
                
                <!-- もっと見るボタン -->
                @if($shelters->count() > 6)
                    <div class="text-center mt-8">
                        <button class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200 font-medium">
                            もっと見る
                        </button>
                    </div>
                @endif
            </section>
        </main>
    </div>

    <script>
        function shelterFilter() {
            return {
                selectedRegion: 'all',
                
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
                    this.filterShelters();
                },
                
                filterShelters() {
                    // フィルタリング処理は isVisible メソッドで行う
                },
                
                isVisible(prefectureName) {
                    if (this.selectedRegion === 'all') {
                        return true;
                    }
                    
                    if (!prefectureName) {
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
                }
            }
        }
    </script>
</x-guest-layout>


