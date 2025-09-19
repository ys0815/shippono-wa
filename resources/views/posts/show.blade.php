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
