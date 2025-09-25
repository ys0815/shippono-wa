<x-guest-layout>
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
                                <img src="{{ e(Storage::url($media->url)) }}" alt="{{ e($post->title) }}" class="w-full h-full object-cover ">
                            @elseif($media->type === 'video')
                                <video src="{{ e(Storage::url($media->url)) }}" class="w-full h-full object-cover " muted>
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
                                            <img src="{{ e(Storage::url($media->url)) }}" alt="{{ e($post->title) }}" class="w-full h-full object-cover ">
                                        @elseif($media->type === 'video')
                                            <video src="{{ e(Storage::url($media->url)) }}" class="w-full h-full object-cover " muted>
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
                    <div class="flex items-center space-x-3 mb-4">
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
                            <div class="text-xl font-bold text-gray-800 leading-tight">
                                {{ e($post->pet->name) }} 
                                <span class="text-lg font-normal {{ $post->pet->gender === 'male' ? 'text-blue-500' : ($post->pet->gender === 'female' ? 'text-pink-500' : 'text-gray-500') }}">
                                    {{ ['male' => '♂', 'female' => '♀', 'unknown' => '?'][$post->pet->gender] ?? '?' }}
                                </span>
                            </div>
                            @if($post->pet->user)
                                <div class="text-sm mt-1">
                                    <span class="text-amber-600">飼い主さん:</span> {{ e($post->pet->user->display_name ?? $post->pet->user->name) }}
                                </div>
                            @endif
                            @if($post->pet->shelter)
                                <div class="text-sm mt-1">
                                    <span class="text-amber-600">お迎え先の保護団体:</span> {{ e($post->pet->shelter->name) }}
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- アクションボタン -->
                    <div class="flex flex-row gap-4">
                        <!-- 保護団体サイトへ -->
                        @if($post->pet->shelter && $post->pet->shelter->website_url)
                            <a href="{{ $post->pet->shelter->website_url }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="flex-1 px-6 py-3 text-sm rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition-all duration-200 font-medium shadow-sm text-center">
                                保護団体サイトへ
                            </a>
                        @else
                            <button disabled class="flex-1 px-6 py-3 text-sm rounded-full border-2 border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed font-medium text-center">
                                保護団体サイトへ
                            </button>
                        @endif

                        <!-- シェア -->
                        <button onclick="openShareModal()" 
                                class="flex-1 px-6 py-3 text-sm rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition-all duration-200 font-medium shadow-sm text-center">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                            </svg>
                            シェア
                        </button>
                    </div>
                </div>
            @endif
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

    <!-- シェアモーダル -->
    <div id="shareModal" class="fixed inset-0 z-[9999] hidden bg-black bg-opacity-50 flex items-center justify-center p-4" onclick="closeShareModal()">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">シェア</h3>
                <button onclick="closeShareModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="flex flex-wrap justify-center gap-2 sm:gap-4 md:gap-6">
                <!-- リンクをコピー -->
                <div class="text-center flex-shrink-0">
                    <button onclick="shareToCopy()" class="w-12 h-12 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center transition-colors mx-auto mb-2">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                    <span class="text-xs text-gray-600">リンクをコピー</span>
                </div>

                <!-- X -->
                <div class="text-center flex-shrink-0">
                    <button onclick="shareToX()" class="w-12 h-12 bg-black hover:bg-gray-800 rounded-full flex items-center justify-center transition-colors mx-auto mb-2">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </button>
                    <span class="text-xs text-gray-600">X</span>
                </div>

                <!-- LINE -->
                <div class="text-center flex-shrink-0">
                    <button onclick="shareToLine()" class="w-12 h-12 bg-green-500 hover:bg-green-600 rounded-full flex items-center justify-center transition-colors mx-auto mb-2">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.349 0 .63.285.63.63 0 .346-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .63.285.63.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.281.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                        </svg>
                    </button>
                    <span class="text-xs text-gray-600">LINE</span>
                </div>

                <!-- Facebook -->
                <div class="text-center flex-shrink-0">
                    <button onclick="shareToFacebook()" class="w-12 h-12 bg-blue-600 hover:bg-blue-700 rounded-full flex items-center justify-center transition-colors mx-auto mb-2">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </button>
                    <span class="text-xs text-gray-600">Facebook</span>
                </div>

                <!-- Instagram -->
                <div class="text-center flex-shrink-0">
                    <button onclick="shareToInstagram()" class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 rounded-full flex items-center justify-center transition-colors mx-auto mb-2">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </button>
                    <span class="text-xs text-gray-600">Instagram</span>
                </div>
            </div>
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

        // シェアモーダル制御
        function openShareModal() {
            document.getElementById('shareModal').classList.remove('hidden');
        }

        function closeShareModal() {
            document.getElementById('shareModal').classList.add('hidden');
        }

        // シェア機能
        function shareToCopy() {
            const shareUrl = window.location.href;
            navigator.clipboard.writeText(shareUrl).then(() => {
                alert('URLをクリップボードにコピーしました');
                closeShareModal();
            }).catch(err => {
                console.error('Failed to copy URL:', err);
                alert('コピーに失敗しました');
            });
        }

        function shareToX() {
            const postTitle = '{{ Str::limit(strip_tags(str_replace(['"', "'", "\n", "\r"], '', $post->title)), 50) }}';
            const petName = '{{ $post->pet ? $post->pet->name : "" }}';
            const shareUrl = window.location.href;
            const text = `#しっぽのわ ${petName ? `「${petName}」` : ''}の幸せなストーリーを読んでみませんか？\n\n${postTitle}\n\n${shareUrl}`;
            const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}`;
            window.open(twitterUrl, '_blank', 'width=600,height=400');
            closeShareModal();
        }

        function shareToLine() {
            const shareUrl = window.location.href;
            const lineUrl = `https://social-plugins.line.me/lineit/share?url=${encodeURIComponent(shareUrl)}`;
            window.open(lineUrl, '_blank', 'width=600,height=400');
            closeShareModal();
        }

        function shareToFacebook() {
            const url = window.location.href;
            const text = '{{ $post->title }} - #しっぽのわ';
            
            // Facebookアプリがインストールされているかチェック
            const isFacebookInstalled = /FBAN|FBAV/i.test(navigator.userAgent) || 
                (navigator.platform === 'iPhone' || navigator.platform === 'iPad') ||
                (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
            
            if (isFacebookInstalled) {
                // Facebookアプリがインストールされている場合、アプリを開く
                const facebookAppUrl = `fb://share?link=${encodeURIComponent(url)}&quote=${encodeURIComponent(text)}`;
                window.location.href = facebookAppUrl;
                
                // フォールバック: アプリが開かない場合はブラウザで開く
                setTimeout(() => {
                    const facebookWebUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}&quote=${encodeURIComponent(text)}`;
                    window.open(facebookWebUrl, '_blank', 'width=600,height=400');
                }, 1000);
            } else {
                // Facebookアプリがインストールされていない場合、ブラウザで開く
                const facebookWebUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}&quote=${encodeURIComponent(text)}`;
                window.open(facebookWebUrl, '_blank', 'width=600,height=400');
            }
            closeShareModal();
        }

        function shareToInstagram() {
            const url = window.location.href;
            const text = '{{ $post->title }} - #しっぽのわ';
            
            // Web Share APIを使用してInstagramの通常のシェア機能に遷移
            if (navigator.share) {
                navigator.share({
                    title: text,
                    text: text,
                    url: url
                }).then(() => {
                    console.log('Instagramシェアが成功しました');
                }).catch(() => {
                    // フォールバック: リンクをコピー
                    copyToClipboard(text, url);
                });
            } else {
                // Web Share APIが利用できない場合、リンクをコピー
                copyToClipboard(text, url);
            }
            closeShareModal();
        }
        
        function copyToClipboard(text, url) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(`${text}\n${url}`).then(() => {
                    alert('リンクをコピーしました。Instagramアプリで「リンクをシェア」して投稿してください。');
                });
            } else {
                alert('以下のリンクをコピーしてInstagramアプリで「リンクをシェア」して投稿してください。\n\n' + text + '\n' + url);
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
            // デベロッパーツールでのスクロールを阻害しないよう、bodyのoverflow制御を削除
            
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
            // デベロッパーツールでのスクロールを阻害しないよう、bodyのoverflow制御を削除

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
</x-guest-layout>
