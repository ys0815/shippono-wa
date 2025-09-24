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

        <!-- ペットプロフィール -->
        @if($post->pet)
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-6 mt-6 shadow-lg">
                <div class="flex flex-col lg:flex-row items-center gap-6">
                    <!-- ペットアイコン（正円） - クリック可能 -->
                    <a href="{{ route('pets.show', $post->pet) }}" class="flex-shrink-0 group">
                        @if($post->pet->profile_image_url)
                            <img src="{{ $post->pet->profile_image_url }}" alt="{{ $post->pet->name }}" class="w-24 h-24 sm:w-28 sm:h-28 rounded-full object-cover border-4 border-white shadow-lg ring-2 ring-amber-200 group-hover:ring-amber-400 transition-all duration-300 group-hover:scale-105">
                        @else
                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center border-4 border-white shadow-lg ring-2 ring-amber-200 group-hover:ring-amber-400 transition-all duration-300 group-hover:scale-105">
                                <span class="text-amber-600 text-2xl sm:text-3xl font-bold">{{ mb_substr($post->pet->name, 0, 2) }}</span>
                            </div>
                        @endif
                    </a>
                    
                    <!-- ペット情報 -->
                    <div class="flex-1 text-center lg:text-left">
                        <!-- ペット名 -->
                        <h3 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-3">
                            {{ $post->pet->name }}
                            @if($post->pet->gender)
                                <span class="text-xl ml-2 {{ $post->pet->gender === 'male' ? 'text-blue-500' : ($post->pet->gender === 'female' ? 'text-pink-500' : 'text-gray-500') }}">
                                    {{ ['male'=>'♂','female'=>'♀','unknown'=>'?'][$post->pet->gender] ?? '?' }}
                                </span>
                            @endif
                        </h3>

                        <!-- 情報カード（コンパクト化） -->
                        <div class="space-y-3">
                            <!-- 飼い主さん情報 -->
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-amber-100">
                                <div class="flex items-center justify-center lg:justify-start gap-2">
                                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-600">飼い主さん：</span>
                                    <span class="font-semibold text-gray-800">{{ $post->pet->user->display_name ?? $post->pet->user->name }}</span>
                                </div>
                            </div>

                            <!-- お迎え先情報（リンク化） -->
                            @if($post->pet->shelter)
                                <div class="bg-white rounded-lg p-3 shadow-sm border border-amber-100">
                                    <div class="flex items-center justify-center lg:justify-start gap-2">
                                        <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600">お迎え先：</span>
                                        @if($post->pet->shelter->website_url)
                                            <a href="{{ $post->pet->shelter->website_url }}" target="_blank" class="font-semibold text-amber-600 hover:text-amber-700 hover:underline transition-colors">
                                                {{ $post->pet->shelter->name }}
                                            </a>
                                        @else
                                            <span class="font-semibold text-gray-600">
                                                {{ $post->pet->shelter->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- SNSシェアボタン（コンパクト化） -->
                        <div class="bg-white rounded-lg p-3 mt-4 shadow-sm border border-amber-100">
                            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3">
                                <span class="text-xs font-medium text-gray-600 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                    </svg>
                                    シェア
                                </span>
                                <div class="flex gap-2">
                                    <!-- X（旧Twitter） -->
                                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title . ' - #しっぽのわ') }}&url={{ urlencode(request()->url()) }}" 
                                       target="_blank" 
                                       class="w-10 h-10 bg-black rounded-full flex items-center justify-center hover:bg-gray-800 transition-all duration-300 transform hover:scale-110 shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Facebook -->
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                                       target="_blank" 
                                       class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition-all duration-300 transform hover:scale-110 shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                    
                                    <!-- Instagram -->
                                    <a href="https://www.instagram.com/" 
                                       target="_blank" 
                                       class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center hover:from-purple-600 hover:to-pink-600 transition-all duration-300 transform hover:scale-110 shadow-md">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 6.62 5.367 11.987 11.988 11.987s11.987-5.367 11.987-11.987C24.014 5.367 18.647.001 12.017.001zM8.449 16.988c-1.297 0-2.448-.49-3.323-1.297C4.198 14.895 3.708 13.744 3.708 12.447s.49-2.448 1.418-3.323c.875-.807 2.026-1.297 3.323-1.297s2.448.49 3.323 1.297c.928.875 1.418 2.026 1.418 3.323s-.49 2.448-1.418 3.244c-.875.807-2.026 1.297-3.323 1.297zm7.83-9.281H7.83c-.807 0-1.418.611-1.418 1.418v7.83c0 .807.611 1.418 1.418 1.418h8.449c.807 0 1.418-.611 1.418-1.418v-7.83c0-.807-.611-1.418-1.418-1.418zM17.723 4.5c-.807 0-1.418.611-1.418 1.418s.611 1.418 1.418 1.418 1.418-.611 1.418-1.418-.611-1.418-1.418-1.418z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
</x-guest-layout>