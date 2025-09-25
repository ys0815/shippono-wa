<x-guest-layout>
    <x-slot name="title">里親インタビュー | #しっぽのわ</x-slot>

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
        <div class="bg-white shadow-lg rounded-lg p-6">
            <!-- タイトル -->
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-4 text-center lg:text-left">
                {{ $post->title }}
            </h1>

            <!-- メタ情報 -->
            <div class="flex flex-col sm:flex-row items-center justify-between mb-6 text-sm text-gray-600 gap-2">
                <div class="flex flex-col sm:flex-row items-center space-y-1 sm:space-y-0 sm:space-x-4">
                    <span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-medium">
                        {{ $post->created_at->format('Y年m月d日') }} 投稿
                    </span>
                </div>
            </div>

            <!-- インタビューコンテンツ -->
            @if($post->interviewContent)
                <div class="space-y-8">
                <!-- ① 新しい家族との出会い -->
                @if($post->interviewContent->question1)
                        <div class="px-2">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <span class="bg-amber-100 text-amber-800 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-3 flex-shrink-0">①</span>
                                <span class="break-words">新しい家族との出会い</span>
                            </h3>
                            <div class="prose max-w-none text-gray-700 text-base sm:text-lg leading-[2.4em] bg-gradient-to-b from-[#e5e7eb] to-transparent bg-[length:100%_2.4em] bg-[position:0_1.2em] p-6 rounded-lg" style="background-image: linear-gradient(to bottom, #e5e7eb 1px, transparent 1px);">
                            {!! nl2br(e($post->interviewContent->question1)) !!}
                        </div>
                    </div>
                @endif

                <!-- ② 迎える前の不安と準備 -->
                @if($post->interviewContent->question2)
                        <div class="px-2">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <span class="bg-blue-100 text-blue-800 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-3 flex-shrink-0">②</span>
                                <span class="break-words">迎える前の不安と準備</span>
                            </h3>
                            <div class="prose max-w-none text-gray-700 text-base sm:text-lg leading-[2.4em] bg-gradient-to-b from-[#e5e7eb] to-transparent bg-[length:100%_2.4em] bg-[position:0_1.2em] p-6 rounded-lg" style="background-image: linear-gradient(to bottom, #e5e7eb 1px, transparent 1px);">
                            {!! nl2br(e($post->interviewContent->question2)) !!}
                        </div>
                    </div>
                @endif

                <!-- ③ 迎えた後の変化と喜び -->
                @if($post->interviewContent->question3)
                        <div class="px-2">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <span class="bg-green-100 text-green-800 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-3 flex-shrink-0">③</span>
                                <span class="break-words">迎えた後の変化と喜び</span>
                            </h3>
                            <div class="prose max-w-none text-gray-700 text-base sm:text-lg leading-[2.4em] bg-gradient-to-b from-[#e5e7eb] to-transparent bg-[length:100%_2.4em] bg-[position:0_1.2em] p-6 rounded-lg" style="background-image: linear-gradient(to bottom, #e5e7eb 1px, transparent 1px);">
                            {!! nl2br(e($post->interviewContent->question3)) !!}
                        </div>
                    </div>
                @endif

                <!-- ④ 未来の里親へのメッセージ -->
                @if($post->interviewContent->question4)
                        <div class="px-2">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <span class="bg-purple-100 text-purple-800 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-3 flex-shrink-0">④</span>
                                <span class="break-words">未来の里親へのメッセージ</span>
                            </h3>
                            <div class="prose max-w-none text-gray-700 text-base sm:text-lg leading-[2.4em] bg-gradient-to-b from-[#e5e7eb] to-transparent bg-[length:100%_2.4em] bg-[position:0_1.2em] p-6 rounded-lg" style="background-image: linear-gradient(to bottom, #e5e7eb 1px, transparent 1px);">
                            {!! nl2br(e($post->interviewContent->question4)) !!}
                        </div>
                    </div>
                @endif

                <!-- ⑤ 最後に一言 -->
                @if($post->interviewContent->question5)
                        <div class="px-2">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <span class="bg-pink-100 text-pink-800 rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold mr-3 flex-shrink-0">⑤</span>
                                <span class="break-words">最後に一言</span>
                            </h3>
                            <div class="prose max-w-none text-gray-700 text-base sm:text-lg leading-[2.4em] bg-gradient-to-b from-[#e5e7eb] to-transparent bg-[length:100%_2.4em] bg-[position:0_1.2em] p-6 rounded-lg" style="background-image: linear-gradient(to bottom, #e5e7eb 1px, transparent 1px);">
                            {!! nl2br(e($post->interviewContent->question5)) !!}
                        </div>
                    </div>
                @endif

                <!-- ペットプロフィール -->
                @if($post->pet)
                    <div class="border-t border-gray-200 pt-4 mt-6">
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
            @else
                <!-- フォールバック: 従来のcontent表示 -->
                <div class="prose max-w-none">
                    {!! nl2br(e($post->content)) !!}
                </div>
            @endif

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

    <!-- シェアモーダル -->
    <div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">シェアしよう</h3>
                    
                    <div class="flex flex-wrap justify-center gap-6 mb-8">
                        <!-- リンクコピー -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToCopy()">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-2 hover:bg-gray-200 transition-colors">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600">リンクコピー</span>
                        </div>
                        
                        <!-- X (旧Twitter) -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToX()">
                            <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center mb-2 hover:bg-gray-800 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600">X</span>
                        </div>
                        
                        <!-- LINE -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToLine()">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mb-2 hover:bg-green-600 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.349 0 .63.285.63.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.626-.285-.626-.629V8.108c0-.345.281-.63.63-.63.346 0 .63.285.63.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.281.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600">LINE</span>
                        </div>
                        
                        <!-- Facebook -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToFacebook()">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mb-2 hover:bg-blue-700 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600">Facebook</span>
                        </div>
                        
                        <!-- Instagram -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToInstagram()">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mb-2 hover:from-purple-600 hover:to-pink-600 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600">Instagram</span>
                        </div>
                    </div>
                    
                    <button onclick="closeShareModal()" class="w-full py-3 px-6 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        閉じる
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
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
            
            if (navigator.clipboard && window.isSecureContext) {
                // モダンブラウザでHTTPS環境の場合
                navigator.clipboard.writeText(shareUrl).then(() => {
                    alert('URLをクリップボードにコピーしました');
                    closeShareModal();
                }).catch(err => {
                    console.error('Failed to copy URL:', err);
                    // フォールバック
                    fallbackCopyTextToClipboard(shareUrl);
                });
            } else {
                // フォールバック
                fallbackCopyTextToClipboard(shareUrl);
            }
        }
        
        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            
            // テキストエリアを画面外に配置
            textArea.style.top = '0';
            textArea.style.left = '0';
            textArea.style.position = 'fixed';
            textArea.style.opacity = '0';
            
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    alert('URLをクリップボードにコピーしました');
                    closeShareModal();
                } else {
                    alert('コピーに失敗しました。手動でコピーしてください。\n\n' + text);
                }
            } catch (err) {
                console.error('Fallback: Failed to copy URL:', err);
                alert('コピーに失敗しました。手動でコピーしてください。\n\n' + text);
            }
            
            document.body.removeChild(textArea);
        }

        function shareToX() {
            const url = window.location.href;
            const text = '{{ $post->title }} - #しっぽのわ';
            const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
            window.open(twitterUrl, '_blank');
            closeShareModal();
        }

        function shareToLine() {
            const url = window.location.href;
            const text = '{{ $post->title }} - #しっぽのわ';
            const lineUrl = `https://social-plugins.line.me/lineit/share?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
            window.open(lineUrl, '_blank');
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

        // モーダル外クリックで閉じる
        document.getElementById('shareModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeShareModal();
            }
        });
    </script>
</x-guest-layout>