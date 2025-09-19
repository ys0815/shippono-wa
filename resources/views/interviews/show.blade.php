<x-guest-layout>
    <style>
        /* ノート風の横罫線 */
        .notebook-lines {
            background-image: linear-gradient(to bottom, #e5e7eb 1px, transparent 1px);
            background-size: 100% 1.8em;
            background-position: 0 1.2em;
            line-height: 1.8em;
        }
        
        /* 手書き風の装飾 */
        .handwritten-border {
            border: 2px solid #f59e0b;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* ノート風の角の装飾 */
        .notebook-corner {
            position: relative;
        }
        
        .notebook-corner::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 20px;
            height: 20px;
            background: #f59e0b;
            border-radius: 0 8px 0 20px;
        }
        
        .notebook-corner::after {
            content: '';
            position: absolute;
            top: -2px;
            right: -2px;
            width: 15px;
            height: 15px;
            background: #fbbf24;
            border-radius: 0 6px 0 15px;
        }
    </style>
    <div x-data="{ sidebar:false, search:false }" class="min-h-screen bg-gray-50">

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
                    <!-- 複数メディア -->
                    <div class="relative">
                        <div class="w-full h-80 sm:h-96 overflow-hidden">
                            @foreach($post->media as $index => $media)
                                <div class="media-item cursor-pointer {{ $index === 0 ? '' : 'hidden' }}" 
                                     data-media-type="{{ e($media->type) }}" 
                                     data-media-url="{{ e(Storage::url($media->url)) }}" 
                                     data-media-index="{{ $index }}">
                                    @if($media->type === 'image')
                                        <img src="{{ e(Storage::url($media->url)) }}" alt="{{ e($post->title) }}" class="w-full h-full object-cover">
                                    @elseif($media->type === 'video')
                                        <video src="{{ e(Storage::url($media->url)) }}" class="w-full h-full object-cover" muted>
                                            お使いのブラウザは動画をサポートしていません。
                                        </video>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- ナビゲーションボタン -->
                        @if($post->media->count() > 1)
                            <button class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all" 
                                    onclick="previousMedia()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-75 transition-all" 
                                    onclick="nextMedia()">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        <!-- 投稿内容 -->
        <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
            <!-- タイトル -->
            <h1 class="text-3xl font-bold text-gray-800 mb-4 notebook-corner handwritten-border p-6">
                {{ $post->title }}
            </h1>

            <!-- メタ情報 -->
            <div class="flex items-center justify-between mb-6 text-sm text-gray-600">
                <div class="flex items-center space-x-4">
                    <span>投稿日: {{ $post->created_at->format('Y年m月d日') }}</span>
                    @if($post->pet)
                        <span>ペット: {{ $post->pet->name }}</span>
                    @endif
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="toggleLike({{ $post->id }})" 
                            class="flex items-center space-x-1 px-3 py-1 rounded-full {{ $post->is_liked ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600' }} hover:bg-red-100 hover:text-red-600 transition-colors">
                        <svg class="w-4 h-4" fill="{{ $post->is_liked ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span>{{ $post->likes_count }}</span>
                    </button>
                </div>
            </div>

            <!-- 本文 -->
            <div class="prose max-w-none notebook-lines handwritten-border p-6">
                {!! nl2br(e($post->content)) !!}
            </div>

            <!-- タグ -->
            @if($post->tags)
                <div class="mt-6">
                    <div class="flex flex-wrap gap-2">
                        @foreach(explode(',', $post->tags) as $tag)
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-sm">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- 関連投稿 -->
        @if($relatedPosts->count() > 0)
            <div class="mt-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">関連する里親インタビュー</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedPosts as $relatedPost)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            @if($relatedPost->media->count() > 0)
                                <div class="h-48 overflow-hidden">
                                    <img src="{{ Storage::url($relatedPost->media->first()->url) }}" 
                                         alt="{{ $relatedPost->title }}" 
                                         class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">{{ $relatedPost->title }}</h3>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-3">{{ Str::limit($relatedPost->content, 100) }}</p>
                                <a href="{{ route('interviews.show', $relatedPost) }}" 
                                   class="inline-flex items-center text-amber-600 hover:text-amber-700 font-medium">
                                    続きを読む
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </main>

    <!-- メディアビューアーモーダル -->
    <div id="mediaViewer" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center">
        <div class="relative max-w-4xl max-h-full p-4">
            <button onclick="closeMediaViewer()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 z-10">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div id="mediaContent" class="max-w-full max-h-full">
                <!-- メディアコンテンツがここに表示される -->
            </div>
        </div>
    </div>

    <script>
        let currentMediaIndex = 0;
        let mediaItems = [];

        // メディアアイテムをクリックした時の処理
        document.addEventListener('DOMContentLoaded', function() {
            const mediaItemsElements = document.querySelectorAll('.media-item');
            mediaItems = Array.from(mediaItemsElements);
            
            mediaItems.forEach((item, index) => {
                item.addEventListener('click', () => {
                    currentMediaIndex = index;
                    openMediaViewer();
                });
            });
        });

        function openMediaViewer() {
            const viewer = document.getElementById('mediaViewer');
            const content = document.getElementById('mediaContent');
            const currentItem = mediaItems[currentMediaIndex];
            
            if (currentItem) {
                const mediaType = currentItem.dataset.mediaType;
                const mediaUrl = currentItem.dataset.mediaUrl;
                
                if (mediaType === 'image') {
                    content.innerHTML = `<img src="${mediaUrl}" class="max-w-full max-h-full object-contain" alt="メディア">`;
                } else if (mediaType === 'video') {
                    content.innerHTML = `<video src="${mediaUrl}" class="max-w-full max-h-full object-contain" controls autoplay></video>`;
                }
                
                viewer.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeMediaViewer() {
            const viewer = document.getElementById('mediaViewer');
            viewer.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function nextMedia() {
            if (currentMediaIndex < mediaItems.length - 1) {
                currentMediaIndex++;
            } else {
                currentMediaIndex = 0;
            }
            updateMediaDisplay();
        }

        function previousMedia() {
            if (currentMediaIndex > 0) {
                currentMediaIndex--;
            } else {
                currentMediaIndex = mediaItems.length - 1;
            }
            updateMediaDisplay();
        }

        function updateMediaDisplay() {
            mediaItems.forEach((item, index) => {
                if (index === currentMediaIndex) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        }

        function toggleLike(postId) {
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // キーボードナビゲーション
        document.addEventListener('keydown', function(e) {
            const viewer = document.getElementById('mediaViewer');
            if (!viewer.classList.contains('hidden')) {
                if (e.key === 'Escape') {
                    closeMediaViewer();
                } else if (e.key === 'ArrowLeft') {
                    previousMedia();
                } else if (e.key === 'ArrowRight') {
                    nextMedia();
                }
            }
        });
    </script>
    </div>
</x-guest-layout>