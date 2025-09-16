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
            <div class="fixed inset-0 bg-black/40 z-[950]" @click="sidebar=false"></div>
            <aside class="fixed inset-y-0 left-0 w-72 max-w-[85vw] bg-white z-[960] shadow-xl p-4 overflow-y-auto"
                   x-transition:enter="transition ease-out duration-200"
                   x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in duration-200"
                   x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                <div class="text-sm text-gray-500 mb-2">メニュー</div>
                <nav class="space-y-1">
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded hover:bg-amber-50 text-gray-700">ログイン</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded hover:bg-amber-50 text-gray-700">新規登録</a>
                    <a href="{{ route('mypage') }}" class="block px-3 py-2 rounded hover:bg-amber-50 text-gray-700">マイページへ</a>
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

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-10">
            <!-- Hero -->
            <section class="bg-amber-50 border border-amber-100 rounded-xl p-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 items-center">
                    <div class="sm:col-span-1">
                        <img src="https://images.unsplash.com/photo-1548199973-03cce0bbc87b?q=80&w=1080&auto=format&fit=crop" alt="main" class="w-full h-40 object-cover rounded-lg">
                    </div>
                    <div class="sm:col-span-2">
                        <h2 class="text-lg font-semibold text-gray-900">保護動物と家族の幸せを共有するプラットフォーム</h2>
                        <p class="text-sm text-gray-700 mt-2">新着の保護動物情報や里親インタビューを、やさしいUIでお届けします。</p>
                        <div class="mt-4 flex gap-3">
                            <a href="{{ route('login') }}" class="btn btn-outline">ログイン</a>
                            <a href="{{ route('register') }}" class="btn btn-brand">新規登録</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 新着の保護動物一覧 -->
            <section>
                <h3 class="text-base font-semibold text-gray-900 mb-4">新着の保護動物一覧</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($recentPets as $pet)
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                            <!-- ペット画像（正円・上部配置） -->
                            <div class="flex justify-center pt-6 pb-4">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-amber-200">
                                    @if($pet->profile_image_url)
                                        <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-500 text-lg font-bold">{{ mb_substr($pet->name, 0, 2) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- ペット詳細情報 -->
                            <div class="px-4 pb-4">
                                <!-- 品種 -->
                                <div class="text-sm text-gray-600 mb-1 text-center">
                                    {{ __(['dog' => '雑種 (ミックス犬)', 'cat' => '雑種 (ミックス猫)', 'rabbit' => '雑種 (ミックスうさぎ)', 'other' => '雑種 (ミックス)'][$pet->species] ?? '雑種') }}
                                </div>
                                
                                <!-- 年齢 -->
                                <div class="text-sm text-gray-600 mb-1 text-center">
                                    @if($pet->age_years !== null || $pet->age_months !== null)
                                        @if($pet->age_years > 0 && $pet->age_months > 0)
                                            {{ $pet->age_years }}歳{{ $pet->age_months }}ヶ月
                                        @elseif($pet->age_years > 0)
                                            {{ $pet->age_years }}歳
                                        @elseif($pet->age_months > 0)
                                            {{ $pet->age_months }}ヶ月
                                        @endif
                                    @else
                                        年齢不明
                                    @endif
                                </div>
                                
                                <!-- 名前・性別 -->
                                <div class="text-base font-semibold text-gray-900 mb-1 text-center">
                                    {{ $pet->name }} 
                                    <span class="text-sm font-normal">
                                        {{ __(['male' => '♂', 'female' => '♀', 'unknown' => '?'][$pet->gender] ?? '?') }}
                                    </span>
                                </div>
                                
                                <!-- プロフィール説明 -->
                                <div class="text-sm text-gray-700 text-center mb-4">
                                    @if($pet->description)
                                        {{ Str::limit($pet->description, 50) }}
                                    @else
                                        元気いっぱい
                                    @endif
                                </div>
                            
                                <!-- ボタンエリア -->
                                <div class="flex gap-2">
                                @if($pet->shelter && $pet->shelter->website_url)
                                    <a href="{{ $pet->shelter->website_url }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="flex-1 px-3 py-2 text-sm rounded-lg border border-amber-500 text-amber-600 bg-white hover:bg-amber-50 transition-colors duration-200 text-center">
                                        保護団体サイトへ
                                    </a>
                                @else
                                    <button disabled class="flex-1 px-3 py-2 text-sm rounded-lg border border-gray-300 text-gray-400 bg-gray-50 cursor-not-allowed">
                                        保護団体サイトへ
                                    </button>
                                @endif
                                
                                @php
                                    $hasInterview = $pet->posts()->where('type', 'interview')->exists();
                                @endphp
                                
                                @if($hasInterview)
                                    <a href="{{ route('pets.show', $pet->id) }}#interview" 
                                       class="flex-1 px-3 py-2 text-sm rounded-lg border border-amber-500 text-amber-600 bg-white hover:bg-amber-50 transition-colors duration-200 text-center">
                                        しっぽのわを読む
                                    </a>
                                @else
                                    <button disabled class="flex-1 px-3 py-2 text-sm rounded-lg border border-gray-300 text-gray-400 bg-gray-50 cursor-not-allowed">
                                        しっぽのわを読む
                                    </button>
                                @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- サービスコンセプト -->
            <section>
                <h3 class="text-base font-semibold text-gray-900 mb-3">サービスコンセプト</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="bg-white border rounded-lg p-4 text-center">
                        <div class="mx-auto w-12 h-12 flex items-center justify-center rounded-full bg-pink-50 text-pink-600">❤</div>
                        <div class="mt-2 font-medium">幸せの可視化</div>
                        <p class="text-sm text-gray-600 mt-1">保護動物と家族のストーリーを写真と文章で。</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4 text-center">
                        <div class="mx-auto w-12 h-12 flex items-center justify-center rounded-full bg-amber-50 text-amber-600">🤝</div>
                        <div class="mt-2 font-medium">共感の輪</div>
                        <p class="text-sm text-gray-600 mt-1">思いに触れて、優しい支援の循環を。</p>
                    </div>
                    <div class="bg-white border rounded-lg p-4 text-center">
                        <div class="mx-auto w-12 h-12 flex items-center justify-center rounded-full bg-blue-50 text-blue-600">🔗</div>
                        <div class="mt-2 font-medium">命をつなぐ</div>
                        <p class="text-sm text-gray-600 mt-1">施設や里親とつながる導線をわかりやすく。</p>
                    </div>
                </div>
            </section>

            <!-- 統計情報 -->
            <section>
                <h3 class="text-base font-semibold text-gray-900 mb-3">統計情報</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white border rounded-lg p-6 text-center">
                        <div class="text-xs text-gray-500 mb-1">投稿数</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['posts_gallery']) }}</div>
                    </div>
                    <div class="bg-white border rounded-lg p-6 text-center">
                        <div class="text-xs text-gray-500 mb-1">インタビュー数</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['posts_interview']) }}</div>
                    </div>
                    <div class="bg-white border rounded-lg p-6 text-center">
                        <div class="text-xs text-gray-500 mb-1">登録動物数</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['pets']) }}</div>
                    </div>
                    <div class="bg-white border rounded-lg p-6 text-center">
                        <div class="text-xs text-gray-500 mb-1">掲載団体数</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['shelters']) }}</div>
                    </div>
                    <div class="bg-white border rounded-lg p-6 text-center sm:col-span-2 lg:col-span-1">
                        <div class="text-xs text-gray-500 mb-1">いいね数</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['likes']) }}</div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</x-guest-layout>


