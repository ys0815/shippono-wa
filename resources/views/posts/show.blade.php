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

    <!-- メインコンテンツ -->
    <main class="max-w-4xl mx-auto">
        <!-- メディア表示 -->
        @if($post->media->count() > 0)
            <div class="relative">
                @if($post->media->count() === 1)
                    <!-- 単一メディア -->
                    @php $media = $post->media->first(); @endphp
                    <div class="media-item cursor-pointer overflow-hidden" data-media-type="{{ e($media->type) }}" data-media-url="{{ e(Storage::url($media->url)) }}" data-media-index="0">
                        <div class="w-full h-80 sm:h-96 overflow-hidden">
                            @if($media->type === 'image')
                                <img src="{{ e(Storage::url($media->url)) }}" alt="{{ e($post->title) }}" class="w-full h-full object-cover pointer-events-none">
                            @elseif($media->type === 'video')
                                <video src="{{ e(Storage::url($media->url)) }}" class="w-full h-full object-cover pointer-events-none" muted>
                                    お使いのブラウザは動画をサポートしていません。
                                </video>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- 複数メディア - カルーセル -->
                    <div class="relative overflow-hidden">
                        <div id="media-carousel" class="flex transition-transform duration-300 ease-in-out">
                            @foreach($post->media as $index => $media)
                                <div class="media-item w-full flex-shrink-0 cursor-pointer" data-media-type="{{ e($media->type) }}" data-media-url="{{ e(Storage::url($media->url)) }}" data-media-index="{{ $index }}">
                                    <div class="w-full h-80 sm:h-96 overflow-hidden">
                                        @if($media->type === 'image')
                                            <img src="{{ e(Storage::url($media->url)) }}" alt="{{ e($post->title) }}" class="w-full h-full object-cover pointer-events-none">
                                        @elseif($media->type === 'video')
                                            <video src="{{ e(Storage::url($media->url)) }}" class="w-full h-full object-cover pointer-events-none" muted>
                                                お使いのブラウザは動画をサポートしていません。
                                            </video>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- ナビゲーションボタン -->
                        @if($post->media->count() > 1)
                            <button onclick="previousMedia()" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-70 transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                                </svg>
                            </button>
                            <button onclick="nextMedia()" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-70 transition-all">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                                </svg>
                            </button>
                        @endif
                    </div>
                    
                    <!-- インジケーター -->
                    @if($post->media->count() > 1)
                        <div class="flex justify-center space-x-2 mt-0 bg-white px-4 py-2 rounded-lg">
                            @foreach($post->media as $index => $media)
                                <button onclick="goToMedia({{ $index }})" 
                                        class="w-2 h-2 rounded-full transition-all {{ $index === 0 ? 'bg-amber-500' : 'bg-gray-300' }}"
                                        id="indicator-{{ $index }}"></button>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        @endif

        <!-- 投稿情報（メディアの下に配置） -->
        <div class="bg-white px-4 sm:px-6 lg:px-8 py-6">
            <!-- 日時 -->
            <div class="text-sm text-gray-500 mb-4">
                {{ $post->created_at->setTimezone('Asia/Tokyo')->format('Y年n月j日 H:i') }}
            </div>

            <!-- タイトル -->
            <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ e($post->title) }}</h1>

            <!-- 内容 -->
            <div class="text-gray-700 mb-6 whitespace-pre-wrap">{!! nl2br(e($post->content)) !!}</div>

            <!-- ペット情報 -->
            @if($post->pet)
                <div class="border-t border-gray-200 pt-4">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('pets.show', $post->pet) }}" class="w-12 h-12 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center hover:opacity-80 transition-opacity duration-200">
                            @if($post->pet->profile_image_url)
                                @php
                                    $imageUrl = $post->pet->profile_image_url;
                                    // /storage/で始まっていない場合は追加
                                    if (!str_starts_with($imageUrl, '/storage/')) {
                                        $imageUrl = '/storage/' . ltrim($imageUrl, '/');
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}" alt="{{ e($post->pet->name) }}" class="w-full h-full object-cover" onerror="console.error('Image load error:', this.src); this.style.display='none';">
                            @else
                                <span class="text-gray-500 font-medium">{{ substr($post->pet->name, 0, 1) }}</span>
                            @endif
                        </a>
                        <div>
                            <div class="font-medium" style="color: rgb(217 119 6);">名前: {{ e($post->pet->name) }}</div>
                                @if($post->pet->user)
                                    <div class="text-sm" style="color: rgb(217 119 6);">飼い主さん: {{ e($post->pet->user->display_name ?? $post->pet->user->name) }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- アクションボタン -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4">
            <!-- 保護団体へ -->
            @if($post->pet && $post->pet->shelter)
                <a href="{{ $post->pet->shelter->website_url ?? '#' }}" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="flex-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white px-6 py-3 rounded-lg text-center font-medium hover:from-amber-600 hover:to-orange-600 transition-all duration-200 shadow-sm">
                    保護団体へ
                </a>
            @endif

            <!-- シェア -->
            <button onclick="sharePost()" 
                    class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-lg text-center font-medium hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-sm">
                シェア
            </button>
        </div>
    </main>

    <!-- メディア表示用モーダル -->
    <div id="media-modal" class="fixed inset-0 z-[9999] hidden bg-black bg-opacity-95 flex items-center justify-center p-2 sm:p-4" onclick="closeMediaModal(event)">
        <div class="relative w-full h-full max-w-screen-2xl max-h-screen flex items-center justify-center" onclick="event.stopPropagation()">
            <!-- 閉じるボタン -->
            <button onclick="closeMediaModal()" class="absolute top-2 right-2 sm:top-4 sm:right-4 text-white text-3xl sm:text-4xl z-10 bg-black bg-opacity-50 rounded-full w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center hover:bg-opacity-70 transition-all">
                &times;
            </button>
            
            <!-- メディアコンテナ -->
            <div id="modal-media-container" class="relative w-full h-full flex items-center justify-center overflow-hidden">
                <!-- カルーセル表示 -->
                <div id="modal-media-carousel" class="flex transition-transform duration-300 ease-in-out h-full">
                    @foreach($post->media as $index => $media)
                        <div class="w-full flex-shrink-0 flex items-center justify-center">
                            @if($media->type === 'image')
                                <img src="{{ e(Storage::url($media->url)) }}" alt="{{ e($post->title) }}" class="max-w-full max-h-full object-contain" style="max-height: 90vh;">
                            @elseif($media->type === 'video')
                                <video src="{{ e(Storage::url($media->url)) }}" class="max-w-full max-h-full object-contain" controls muted style="max-height: 90vh;">
                                    お使いのブラウザは動画をサポートしていません。
                                </video>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- モーダル内のナビゲーションボタン -->
            @if($post->media->count() > 1)
                <button onclick="modalPreviousMedia()" class="absolute left-2 sm:left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 sm:p-3 hover:bg-opacity-70 transition-all z-10">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                    </svg>
                </button>
                <button onclick="modalNextMedia()" class="absolute right-2 sm:right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 sm:p-3 hover:bg-opacity-70 transition-all z-10">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                    </svg>
                </button>
            @endif

            <!-- モーダル内のインジケーター -->
            @if($post->media->count() > 1)
                <div class="absolute bottom-2 sm:bottom-4 left-1/2 transform -translate-x-1/2 flex justify-center space-x-2 bg-white px-4 py-2 rounded-lg">
                    @foreach($post->media as $index => $media)
                        <button onclick="modalGoToMedia({{ $index }})" 
                                class="w-2 h-2 sm:w-3 sm:h-3 rounded-full transition-all bg-gray-300 hover:bg-gray-400" 
                                id="modal-indicator-{{ $index }}">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

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

    @php
        $safeMediaData = $post->media->map(function($m) {
            return [
                'type' => $m->type,
                'url' => Storage::url($m->url)
            ];
        });
    @endphp

    <script>
        // メディアカルーセルの制御
        let currentMediaIndex = 0;
        const totalMedia = {{ $post->media->count() }};

        function previousMedia() {
            if (totalMedia <= 1) return;
            currentMediaIndex = currentMediaIndex > 0 ? currentMediaIndex - 1 : totalMedia - 1;
            updateMediaDisplay();
        }

        function nextMedia() {
            if (totalMedia <= 1) return;
            currentMediaIndex = currentMediaIndex < totalMedia - 1 ? currentMediaIndex + 1 : 0;
            updateMediaDisplay();
        }

        function goToMedia(index) {
            if (totalMedia <= 1) return;
            currentMediaIndex = index;
            updateMediaDisplay();
        }

        function updateMediaDisplay() {
            const carousel = document.getElementById('media-carousel');
            if (carousel) {
                const translateX = -(currentMediaIndex * 100);
                carousel.style.transform = `translateX(${translateX}%)`;
            }
            
            // インジケーターの更新
            for (let i = 0; i < totalMedia; i++) {
                const indicator = document.getElementById(`indicator-${i}`);
                if (indicator) {
                    if (i === currentMediaIndex) {
                        indicator.className = 'w-2 h-2 rounded-full transition-all bg-amber-500';
                    } else {
                        indicator.className = 'w-2 h-2 rounded-full transition-all bg-gray-300';
                    }
                }
            }
        }

        // シェア機能
        function sharePost() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ Str::limit(strip_tags(str_replace(['"', "'", "\n", "\r"], '', $post->title)), 50) }}',
                    text: '{{ Str::limit(strip_tags(str_replace(['"', "'", "\n", "\r"], '', $post->content)), 100) }}',
                    url: window.location.href
                }).catch(err => console.log('Error sharing:', err));
            } else {
                // フォールバック: URLをクリップボードにコピー
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('URLをクリップボードにコピーしました');
                }).catch(err => {
                    console.error('Failed to copy URL:', err);
                    alert('シェアに失敗しました');
                });
            }
        }

        // メディアモーダル制御
        let modalCurrentMediaIndex = 0;
        const modalTotalMedia = {{ $post->media->count() }};
        
        const mediaData = @json($safeMediaData);

        // メディアモーダル関数
        function openMediaModal(type, src, initialIndex) {
            console.log('openMediaModal called:', { type, src, initialIndex });
            
            // パラメータの検証
            if (!type || !src) {
                console.error('Invalid parameters:', { type, src, initialIndex });
                return;
            }
            
            const modal = document.getElementById('media-modal');
            const mediaContainer = document.getElementById('modal-media-container');
            
            console.log('Modal elements:', { modal, mediaContainer });
            
            if (!modal) {
                console.error('Modal element not found');
                return;
            }
            
            if (!mediaContainer) {
                console.error('Media container not found');
                return;
            }
            
            modalCurrentMediaIndex = initialIndex;
            console.log('Setting modal index to:', modalCurrentMediaIndex);
            
            // カルーセル位置を直接設定
            const carousel = document.getElementById('modal-media-carousel');
            if (carousel) {
                const translateX = -(modalCurrentMediaIndex * 100);
                carousel.style.transform = `translateX(${translateX}%)`;
            }
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling background
            
            console.log('Modal opened successfully');
        }

        function closeMediaModal(event) {
            // イベントが渡された場合のみ背景クリックチェック
            if (event && event.target && event.target.id !== 'media-modal') {
                return;
            }
            
            const modal = document.getElementById('media-modal');
            if (!modal) return;
            
            modal.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling

            // Stop all videos in the modal
            const videos = modal.querySelectorAll('video');
            videos.forEach(video => {
                video.pause();
                video.currentTime = 0;
            });
        }

        function updateModalMediaDisplay() {
            const carousel = document.getElementById('modal-media-carousel');
            if (carousel) {
                const translateX = -(modalCurrentMediaIndex * 100);
                carousel.style.transform = `translateX(${translateX}%)`;
            }
            
            // 動画の自動再生制御
            const videos = document.querySelectorAll('#modal-media-carousel video');
            videos.forEach((video, index) => {
                if (index === modalCurrentMediaIndex) {
                    video.play().catch(e => console.log('Video autoplay failed:', e));
                } else {
                    video.pause();
                    video.currentTime = 0;
                }
            });
            
            updateModalIndicators();
        }

        function modalPreviousMedia() {
            if (modalTotalMedia <= 1) return;
            modalCurrentMediaIndex = modalCurrentMediaIndex > 0 ? modalCurrentMediaIndex - 1 : modalTotalMedia - 1;
            updateModalMediaDisplay();
        }

        function modalNextMedia() {
            if (modalTotalMedia <= 1) return;
            modalCurrentMediaIndex = modalCurrentMediaIndex < modalTotalMedia - 1 ? modalCurrentMediaIndex + 1 : 0;
            updateModalMediaDisplay();
        }

        function modalGoToMedia(index) {
            if (modalTotalMedia <= 1) return;
            modalCurrentMediaIndex = index;
            updateModalMediaDisplay();
        }

        function updateModalIndicators() {
            for (let i = 0; i < modalTotalMedia; i++) {
                const indicator = document.getElementById(`modal-indicator-${i}`);
                if (indicator) {
                    if (i === modalCurrentMediaIndex) {
                        indicator.className = 'w-2 h-2 sm:w-3 sm:h-3 rounded-full transition-all bg-amber-500';
                    } else {
                        indicator.className = 'w-2 h-2 sm:w-3 sm:h-3 rounded-full transition-all bg-gray-300 hover:bg-gray-400';
                    }
                }
            }
        }

        // メディアクリックイベントリスナー
        document.addEventListener('click', function(event) {
            console.log('Click detected on:', event.target);
            const mediaItem = event.target.closest('.media-item');
            console.log('Media item found:', mediaItem);
            
            if (mediaItem) {
                const type = mediaItem.dataset.mediaType;
                const url = mediaItem.dataset.mediaUrl;
                const index = parseInt(mediaItem.dataset.mediaIndex);
                console.log('Media click detected:', { type, url, index });
                
                // データの検証
                if (!type || !url) {
                    console.error('Invalid media data:', { type, url, index });
                    return;
                }
                
                try {
                    openMediaModal(type, url, index);
                } catch (error) {
                    console.error('Error opening modal:', error);
                    alert('ポップアップの表示に失敗しました。');
                }
            }
        });

        // メディアクリックイベント
        document.addEventListener('click', function(event) {
            const mediaItem = event.target.closest('.media-item');
            
            if (mediaItem) {
                event.preventDefault();
                event.stopPropagation();
                
                const type = mediaItem.dataset.mediaType;
                const url = mediaItem.dataset.mediaUrl;
                const index = parseInt(mediaItem.dataset.mediaIndex);
                
                console.log('Media click detected:', { type, url, index });
                
                // データの検証
                if (!type || !url || isNaN(index)) {
                    console.error('Invalid media data:', { type, url, index });
                    alert('メディアの読み込みに失敗しました。');
                    return;
                }
                
                // インデックスの範囲チェック
                if (index < 0 || index >= mediaData.length) {
                    console.error('Invalid media index:', index, 'max:', mediaData.length - 1);
                    return;
                }
                
                try {
                    openMediaModal(type, url, index);
                } catch (error) {
                    console.error('Error opening modal:', error);
                    alert('ポップアップの表示に失敗しました。');
                }
            }
        });

        // キーボード操作
        document.addEventListener('keydown', function(event) {
            const modal = document.getElementById('media-modal');
            if (!modal || modal.classList.contains('hidden')) return;
            
            switch(event.key) {
                case 'Escape':
                    closeMediaModal();
                    break;
                case 'ArrowLeft':
                    if (modalTotalMedia > 1) {
                        modalPreviousMedia();
                    }
                    break;
                case 'ArrowRight':
                    if (modalTotalMedia > 1) {
                        modalNextMedia();
                    }
                    break;
            }
        });
    </script>
    </div>
</x-guest-layout>
