<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>#しっぽのわ - {{ $post->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans JP', sans-serif;
        }
        .notebook-lines {
            background-image: repeating-linear-gradient(
                transparent,
                transparent 1.4em,
                #e5e7eb 1.4em,
                #e5e7eb calc(1.4em + 1px)
            );
            line-height: 1.4em;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 min-h-screen">
    <!-- ヘッダー -->
    <header class="bg-white shadow-sm border-b border-amber-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- ハンバーガーメニュー -->
                <button id="menu-toggle" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-amber-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- ロゴ -->
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-800">#しっぽのわ</h1>
                </div>

                <!-- 検索ボタン -->
                <button id="search-toggle" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-amber-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- メインコンテンツ -->
    <main class="max-w-4xl mx-auto">
        <!-- メディア表示（Instagram風 - 上部に全面表示） -->
        @if($post->media->count() > 0)
            <div class="relative">
                @if($post->media->count() === 1)
                    <!-- 単一メディア -->
                    @php $media = $post->media->first(); @endphp
                    <div onclick="openMediaModal('{{ $media->type }}', '{{ Storage::url($media->url) }}', 0)" class="cursor-pointer">
                        @if($media->type === 'image')
                            <img src="{{ Storage::url($media->url) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover max-h-[400px]">
                        @elseif($media->type === 'video')
                            <video src="{{ Storage::url($media->url) }}" class="w-full h-auto object-cover max-h-[400px]" muted>
                                お使いのブラウザは動画をサポートしていません。
                            </video>
                        @endif
                    </div>
                @else
                    <!-- 複数メディア - カルーセル -->
                    <div class="relative">
                        <div id="media-carousel" class="flex transition-transform duration-300 ease-in-out" style="width: {{ $post->media->count() * 100 }}%;">
                            @foreach($post->media as $index => $media)
                                <div class="w-full h-auto flex-shrink-0 max-h-[400px] overflow-hidden" onclick="openMediaModal('{{ $media->type }}', '{{ Storage::url($media->url) }}', {{ $index }})">
                                    @if($media->type === 'image')
                                        <img src="{{ Storage::url($media->url) }}" alt="{{ $post->title }}" class="w-full h-full object-cover max-h-[400px]">
                                    @elseif($media->type === 'video')
                                        <video src="{{ Storage::url($media->url) }}" class="w-full h-full object-cover max-h-[400px]" muted>
                                            お使いのブラウザは動画をサポートしていません。
                                        </video>
                                    @endif
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
                        <div class="flex justify-center space-x-2 mt-4">
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
            <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

            <!-- 内容 -->
            <div class="text-gray-700 mb-6 whitespace-pre-wrap">{{ $post->content }}</div>

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
                                <img src="{{ $imageUrl }}" alt="{{ $post->pet->name }}" class="w-full h-full object-cover" onerror="console.error('Image load error:', this.src); this.style.display='none';">
                            @else
                                <span class="text-gray-500 font-medium">{{ substr($post->pet->name, 0, 1) }}</span>
                            @endif
                        </a>
                        <div>
                            <div class="font-medium" style="color: rgb(217 119 6);">名前: {{ $post->pet->name }}</div>
                            @if($post->pet->user)
                                <div class="text-sm" style="color: rgb(217 119 6);">飼い主さん: {{ $post->pet->user->display_name ?? $post->pet->user->name }}</div>
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
    <div id="media-modal" class="fixed inset-0 z-[9999] hidden bg-black bg-opacity-90 flex items-center justify-center p-4" onclick="closeMediaModal(event)">
        <div class="relative w-full h-full max-w-screen-xl max-h-screen-xl flex items-center justify-center" onclick="event.stopPropagation()">
            <button onclick="closeMediaModal()" class="absolute top-4 right-4 text-white text-4xl z-10">
                &times;
            </button>
            
            <div id="modal-media-container" class="relative w-full h-full flex items-center justify-center">
                <!-- メディアはJavaScriptで動的に挿入されます -->
            </div>

            <!-- モーダル内のナビゲーションボタン -->
            @if($post->media->count() > 1)
                <button onclick="modalPreviousMedia()" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-3 hover:bg-opacity-70 transition-all">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                    </svg>
                </button>
                <button onclick="modalNextMedia()" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-3 hover:bg-opacity-70 transition-all">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                    </svg>
                </button>
            @endif

            <!-- モーダル内のインジケーター -->
            @if($post->media->count() > 1)
                <div class="absolute bottom-4 flex justify-center space-x-2">
                    @foreach($post->media as $index => $media)
                        <button onclick="modalGoToMedia({{ $index }})" 
                                class="w-3 h-3 rounded-full transition-all {{ $index === 0 ? 'bg-amber-500' : 'bg-gray-300' }}"
                                id="modal-indicator-{{ $index }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- サイドバー -->
    <div id="sidebar" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeSidebar()"></div>
        <div class="fixed left-0 top-0 h-full w-80 bg-white shadow-xl transform -translate-x-full transition-transform duration-300 ease-in-out" id="sidebar-content">
            <div class="p-6">
                <!-- ヘッダー -->
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-lg font-semibold text-gray-800">メニュー</h2>
                    <button onclick="closeSidebar()" class="p-2 rounded-md text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- メニュー項目 -->
                <nav class="space-y-6">
                    <!-- ゲスト用アカウント項目 -->
                    <div>
                        <div class="text-xs font-semibold text-amber-700 mb-2">アカウント</div>
                        <div class="flex gap-2">
                            <a href="{{ route('register') }}" class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded text-gray-700 bg-white hover:bg-gray-50 text-center">新規登録</a>
                            <a href="{{ route('login') }}" class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded text-gray-700 bg-white hover:bg-gray-50 text-center">ログイン</a>
                        </div>
                    </div>

                    <!-- メニュー項目 -->
                    <div>
                        <div class="text-xs font-semibold text-amber-700 mb-2">メニュー</div>
                        <div class="space-y-2">
                            <a href="{{ route('home') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-md">ホーム</a>
                            <a href="{{ route('interviews.index') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-amber-50 rounded-md">里親インタビュー</a>
                        </div>
                    </div>

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
            </div>
        </div>
    </div>

    <!-- 検索モーダル -->
    <div id="search-modal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeSearchModal()"></div>
        <div class="fixed top-0 left-0 right-0 bg-white shadow-lg transform -translate-y-full transition-transform duration-300 ease-in-out" id="search-modal-content">
            <div class="p-4">
                <div class="flex items-center space-x-4">
                    <div class="flex-1">
                        <input type="text" 
                               placeholder="投稿を検索..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    </div>
                    <button onclick="closeSearchModal()" class="p-2 text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // サイドバーの制御
        function openSidebar() {
            document.getElementById('sidebar').classList.remove('hidden');
            document.getElementById('sidebar-content').classList.remove('-translate-x-full');
        }

        function closeSidebar() {
            document.getElementById('sidebar-content').classList.add('-translate-x-full');
            setTimeout(() => {
                document.getElementById('sidebar').classList.add('hidden');
            }, 300);
        }

        // 検索モーダルの制御
        function openSearchModal() {
            document.getElementById('search-modal').classList.remove('hidden');
            document.getElementById('search-modal-content').classList.remove('-translate-y-full');
            document.querySelector('#search-modal input').focus();
        }

        function closeSearchModal() {
            document.getElementById('search-modal-content').classList.add('-translate-y-full');
            setTimeout(() => {
                document.getElementById('search-modal').classList.add('hidden');
            }, 300);
        }

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
                carousel.style.transform = `translateX(-${currentMediaIndex * 100}%)`;
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
                    title: '{{ $post->title }}',
                    text: '{{ Str::limit($post->content, 100) }}',
                    url: window.location.href
                });
            } else {
                // フォールバック: URLをクリップボードにコピー
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('URLをクリップボードにコピーしました');
                });
            }
        }

        // イベントリスナー
        document.getElementById('menu-toggle').addEventListener('click', openSidebar);
        document.getElementById('search-toggle').addEventListener('click', openSearchModal);

        // メディアモーダル制御
        let modalCurrentMediaIndex = 0;
        const modalTotalMedia = {{ $post->media->count() }};
        const mediaData = @json($post->media->map(fn($m) => ['type' => $m->type, 'url' => Storage::url($m->url)]));

        function openMediaModal(type, src, initialIndex) {
            const modal = document.getElementById('media-modal');
            const mediaContainer = document.getElementById('modal-media-container');
            mediaContainer.innerHTML = ''; // Clear previous media

            modalCurrentMediaIndex = initialIndex;
            updateModalMediaDisplay();

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent scrolling background
        }

        function closeMediaModal(event) {
            // 背景クリック時のみ閉じる
            if (event && event.target.id !== 'media-modal') {
                return;
            }
            const modal = document.getElementById('media-modal');
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
            const mediaContainer = document.getElementById('modal-media-container');
            mediaContainer.innerHTML = ''; // Clear previous media

            const media = mediaData[modalCurrentMediaIndex];
            let mediaElement;

            if (media.type === 'image') {
                mediaElement = `<img src="${media.url}" class="max-w-full max-h-full object-contain" alt="Full screen image">`;
            } else if (media.type === 'video') {
                mediaElement = `<video src="${media.url}" class="max-w-full max-h-full object-contain" controls autoplay muted></video>`;
            }
            mediaContainer.innerHTML = mediaElement;
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
                        indicator.className = 'w-3 h-3 rounded-full transition-all bg-amber-500';
                    } else {
                        indicator.className = 'w-3 h-3 rounded-full transition-all bg-gray-300';
                    }
                }
            }
        }
    </script>
</body>
</html>
