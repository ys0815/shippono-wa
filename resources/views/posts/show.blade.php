<x-guest-layout>
    <!-- メインコンテンツ -->
    <main class="max-w-4xl mx-auto">
        <!-- メディア表示 -->
        @if($post->media->count() > 0)
            <div class="relative">
                @if($post->media->count() === 1)
                    <!-- 単一メディア（端末に合わせてアスペクト比を自動切替） -->
                    @php $media = $post->media->first(); @endphp
                    <div class="media-item cursor-pointer overflow-hidden" data-media-type="{{ e($media->type) }}" data-media-url="{{ e(Storage::url($media->url)) }}" data-media-index="0">
                        <div class="w-full" id="single-media-container" style="display: block;">
                            @if($media->type === 'image')
                                <img src="{{ e(Storage::url($media->url)) }}" alt="{{ e($post->title) }}" loading="lazy" decoding="async" id="single-media-image" style="display: block; width: 100%; height: auto; max-height: 80vh; object-fit: contain;">
                            @elseif($media->type === 'video')
                                <video src="{{ e(Storage::url($media->url)) }}" muted playsinline id="single-media-video" controls style="display: block; width: 100%; height: auto; max-height: 80vh; object-fit: contain;">
                                    お使いのブラウザは動画をサポートしていません。
                                </video>
                            @endif
                        </div>
                    </div>
                @elseif($post->media->count() === 2)
                    @php
                        $allImages = true;
                        foreach ($post->media as $m) { if ($m->type !== 'image') { $allImages = false; break; } }
                    @endphp
                    @if($allImages)
                        <!-- 2枚画像はスライド式表示 -->
                        <div class="relative overflow-hidden">
                            <div id="media-carousel" class="flex transition-transform duration-300 ease-in-out">
                                @foreach($post->media as $index => $media)
                                    <div class="media-item w-full flex-shrink-0 cursor-pointer" data-media-type="image" data-media-url="{{ e(Storage::url($media->url)) }}" data-media-index="{{ $index }}">
                                        <div class="w-full h-80 sm:h-96 overflow-hidden">
                                            <img src="{{ e(Storage::url($media->url)) }}" alt="{{ e($post->title) }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <!-- ナビゲーションボタン -->
                            <button onclick="previousMedia()" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-70 transition-all z-10">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                                </svg>
                            </button>
                            <button onclick="nextMedia()" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-70 transition-all z-10">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                                </svg>
                            </button>
                            
                            <!-- インジケーター -->
                            <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex justify-center space-x-2">
                                @foreach($post->media as $index => $media)
                                    <button onclick="goToMedia({{ $index }})" 
                                            class="w-2 h-2 rounded-full transition-all bg-gray-300 hover:bg-gray-400" 
                                            id="indicator-{{ $index }}">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <!-- 2枚以上または画像以外を含む場合は既存カルーセルを使用 -->
                        <div class="relative overflow-hidden">
                            <div id="media-carousel" class="flex transition-transform duration-300 ease-in-out">
                                @foreach($post->media as $index => $media)
                                    <div class="media-item w-full flex-shrink-0 cursor-pointer" data-media-type="{{ e($media->type) }}" data-media-url="{{ e(Storage::url($media->url)) }}" data-media-index="{{ $index }}">
                                        <div class="w-full h-80 sm:h-96 overflow-hidden">
                                            @if($media->type === 'image')
                                                <img src="{{ e(Storage::url($media->url)) }}" alt="{{ e($post->title) }}" loading="lazy" decoding="async" class="w-full h-full object-cover ">
                                            @elseif($media->type === 'video')
                                                <video src="{{ e(Storage::url($media->url)) }}" class="w-full h-full object-cover " muted>
                                                    お使いのブラウザは動画をサポートしていません。
                                                </video>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
                @else
                    <!-- 複数メディア - カルーセル -->
                    <div class="relative overflow-hidden">
                        <div id="media-carousel" class="flex transition-transform duration-300 ease-in-out">
                            @foreach($post->media as $index => $media)
                                <div class="media-item w-full flex-shrink-0 cursor-pointer" data-media-type="{{ e($media->type) }}" data-media-url="{{ e(Storage::url($media->url)) }}" data-media-index="{{ $index }}">
                                    <div class="w-full h-80 sm:h-96 overflow-hidden">
                                        @if($media->type === 'image')
                                            <img src="{{ e(Storage::url($media->url)) }}" alt="{{ e($post->title) }}" loading="lazy" decoding="async" class="w-full h-full object-cover ">
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
        <div class="bg-white px-4 py-6">
            <!-- 日時 -->
            <div class="text-sm text-amber-600 mb-4 font-medium">
                {{ $post->created_at->setTimezone('Asia/Tokyo')->format('Y年n月j日 H:i') }}
            </div>

            <!-- タイトル -->
            <h1 class="text-2xl font-bold text-main-text mb-4">{{ e($post->title) }}</h1>

            <!-- 内容 -->
            <div class="text-main-text mb-6 whitespace-pre-wrap leading-relaxed">{!! nl2br(e($post->content)) !!}</div>

            <!-- ペット情報 -->
            @if($post->pet)
                <div class="border-t border-main-border pt-4">
                    <div class="flex items-center space-x-3 mb-4">
                        <a href="{{ route('pets.show', $post->pet) }}" class="w-12 h-12 rounded-full overflow-hidden border-2 border-amber-400 bg-amber-100 flex items-center justify-center hover:opacity-80 transition-opacity duration-200">
                            @if($post->pet && $post->pet->profile_image_url)
                                @php
                                    $imageUrl = $post->pet->profile_image_url;
                                    // /storage/で始まっていない場合は追加
                                    if (!str_starts_with($imageUrl, '/storage/')) {
                                        $imageUrl = '/storage/' . ltrim($imageUrl, '/');
                                    }
                                @endphp
                                <img src="{{ $imageUrl }}" alt="{{ e($post->pet->name) }}" loading="lazy" decoding="async" class="w-full h-full object-cover" onerror="console.error('Image load error:', this.src); this.style.display='none';">
                            @elseif($post->pet)
                                <span class="text-amber-600 font-medium">{{ substr($post->pet->name, 0, 1) }}</span>
                            @endif
                        </a>
                        <div>
                            <div class="text-lg font-bold text-main-text leading-tight">
                                {{ e($post->pet->name ?? '') }} 
                                <span class="text-lg font-normal {{ $post->pet->gender === 'male' ? 'text-blue-500' : ($post->pet->gender === 'female' ? 'text-pink-500' : 'text-sub-text') }}">
                                    {{ ['male' => '♂', 'female' => '♀', 'unknown' => '?'][$post->pet->gender] ?? '?' }}
                                </span>
                            </div>
                            @if($post->pet->user)
                                <div class="text-sm mt-1">
                                    <span class="text-amber-700 font-medium">飼い主さん:</span> {{ e($post->pet->user->display_name ?? $post->pet->user->name) }}さん
                                </div>
                            @endif
                            @if($post->pet->shelter)
                                <div class="text-sm mt-1">
                                    <span class="text-amber-700 font-medium">お迎え先の保護団体:</span> {{ e($post->pet->shelter->name) }}
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
                               class="flex-1 px-6 py-3 text-sm rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 hover:shadow-lg transition-all duration-200 font-semibold text-center">
                                保護団体サイトへ
                            </a>
                        @else
                            <button disabled class="flex-1 px-6 py-3 text-sm rounded-full border-2 border-main-border text-gray-400 bg-main-bg cursor-not-allowed font-medium text-center">
                                保護団体サイトへ
                            </button>
                        @endif

                        <!-- シェア -->
                        <button onclick="openShareModal()" 
                                class="flex-1 px-6 py-3 text-sm rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 hover:shadow-lg transition-all duration-200 font-semibold text-center">
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
                                <img src="{{ e(Storage::url($media->url)) }}" alt="{{ e($post->title) }}" style="display: block; width: 100%; height: auto; max-height: 90vh; object-fit: contain;">
                            @elseif($media->type === 'video')
                                <video src="{{ e(Storage::url($media->url)) }}" controls muted style="display: block; width: 100%; height: auto; max-height: 90vh; object-fit: contain;">
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
    <div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 border border-main-border">
                <div class="text-center">
                    <h3 class="text-lg font-bold text-main-text mb-6">シェアしよう</h3>
            
                    <div class="flex flex-wrap justify-center gap-6 mb-8">
                        <!-- リンクコピー -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToCopy()">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-2 hover:bg-gray-200 transition-colors">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-main-text">リンクコピー</span>
                        </div>
                        
                        <!-- X (旧Twitter) -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToX()">
                            <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center mb-2 hover:bg-gray-800 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-main-text">X</span>
                        </div>
                        
                        <!-- LINE -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToLine()">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mb-2 hover:bg-green-600 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.349 0 .63.285.63.63 0 .346-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .63.285.63.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.281.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                                </svg>
                            </div>
                            <span class="text-xs text-main-text">LINE</span>
                        </div>
                        
                        <!-- Facebook -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToFacebook()">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mb-2 hover:bg-blue-700 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-main-text">Facebook</span>
                        </div>
                        
                        <!-- Instagram -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToInstagram()">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mb-2 hover:from-purple-600 hover:to-pink-600 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-main-text">Instagram</span>
                        </div>
                    </div>
                    
                    <button onclick="closeShareModal()" class="w-full py-3 px-6 bg-gray-100 text-main-text rounded-lg hover:bg-gray-200 transition-colors">
                        閉じる
                    </button>
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

        // 単体メディアのアスペクト比を動的に調整（シンプル版）
        function adjustSingleMediaAspect() {
            console.log('adjustSingleMediaAspect called - using full-width responsive container');
            // レスポンシブな w-full コンテナを使用するため、特別な処理は不要
        }
        
        // ページ読み込み時にアスペクト比を調整
        document.addEventListener('DOMContentLoaded', function() {
            adjustSingleMediaAspect();
        });

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
            const postTitle = '{{ Str::limit(strip_tags(str_replace(['"', "'", "\n", "\r"], '', $post->title)), 50) }}';
            const petName = '{{ $post->pet ? ($post->pet->name ?? '') : "" }}';
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
