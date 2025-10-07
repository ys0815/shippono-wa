@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>

    <div class="min-h-screen bg-main-bg">
        <!-- ヘッダー（固定） -->
        <div class="bg-white/90 backdrop-blur border-b border-amber-100 shadow-sm sticky top-16 z-[900]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="{{ route('mypage') }}" class="mr-4">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h1 class="text-lg font-semibold text-amber-900">投稿管理画面</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- タブナビゲーション（固定） -->
        <div class="bg-white/90 backdrop-blur border-b border-amber-100 sticky top-[8rem] z-[900]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex justify-center space-x-6 sm:space-x-8 md:space-x-12">
                    <a href="{{ route('mypage.posts', ['type' => 'all']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-base md:text-lg {{ $type === 'all' ? 'border-amber-500 text-amber-600' : 'border-transparent text-sub-text hover:text-main-text hover:border-sub-border' }}">
                        すべて
                    </a>
                    <a href="{{ route('mypage.posts', ['type' => 'gallery']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-base md:text-lg {{ $type === 'gallery' ? 'border-amber-500 text-amber-600' : 'border-transparent text-sub-text hover:text-main-text hover:border-sub-border' }}">
                        今日の幸せ
                    </a>
                    <a href="{{ route('mypage.posts', ['type' => 'interview']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-base md:text-lg {{ $type === 'interview' ? 'border-amber-500 text-amber-600' : 'border-transparent text-sub-text hover:text-main-text hover:border-sub-border' }}">
                        里親インタビュー
                    </a>
                </nav>
            </div>
        </div>

        <!-- メインコンテンツ -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- 新規投稿ボタン -->
            <div class="mb-6">
                <div class="flex space-x-4">
                        <a href="{{ route('mypage.posts.gallery.create') }}" 
                       class="flex-1 inline-flex items-center justify-center px-3 py-4 text-sm bg-gradient-to-r from-amber-500 to-orange-500 text-white font-medium rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        幸せをシェアする
                    </a>
                    <a href="{{ route('mypage.posts.interview.create') }}" 
                       class="flex-1 inline-flex items-center justify-center px-3 py-4 text-sm bg-gradient-to-r from-amber-500 to-orange-500 text-white font-medium rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        体験をシェアする
                    </a>
                </div>
            </div>

            <!-- 検索機能 -->
            <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
                <form method="GET" action="{{ route('mypage.posts') }}" id="searchForm" class="space-y-3">
                    <input type="hidden" name="type" value="{{ $type }}">
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <!-- キーワード検索 -->
                        <div class="flex-1">
                            <label for="keyword" class="block text-sm md:text-lg font-medium text-main-text mb-1">検索:</label>
                            <div class="relative">
                                <input type="text" 
                                       id="keyword" 
                                       name="keyword" 
                                       value="{{ $keyword }}"
                                       placeholder="キーワード" 
                                       class="w-full px-3 py-1.5 text-base md:text-lg border border-sub-border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                <div id="searchLoading" class="absolute right-2 top-1.5 hidden">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-amber-600"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 期間と状態を横並び -->
                        <div class="flex gap-3">
                            <!-- 期間フィルタ -->
                            <div class="flex-1">
                                <label for="period" class="block text-sm md:text-lg font-medium text-main-text mb-1">期間:</label>
                                <select id="period" 
                                        name="period" 
                                        class="w-full px-2 py-1.5 text-base md:text-lg border border-sub-border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                    <option value="all" {{ $period === 'all' ? 'selected' : '' }}>すべて</option>
                                    <option value="month" {{ $period === 'month' ? 'selected' : '' }}>今月</option>
                                    <option value="half_year" {{ $period === 'half_year' ? 'selected' : '' }}>半年</option>
                                    <option value="year" {{ $period === 'year' ? 'selected' : '' }}>一年</option>
                                </select>
                            </div>
                            
                            <!-- 状態フィルタ -->
                            <div class="flex-1">
                                <label for="status" class="block text-sm md:text-lg font-medium text-main-text mb-1">状態:</label>
                                <select id="status" 
                                        name="status" 
                                        class="w-full px-2 py-1.5 text-base md:text-lg border border-sub-border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>すべて</option>
                                    <option value="published" {{ $status === 'published' ? 'selected' : '' }}>公開</option>
                                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>下書き</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 投稿一覧 -->
            @if($posts->count() > 0)
                <div class="space-y-4">
                    @foreach($posts as $post)
                        <div class="bg-white rounded-lg shadow-sm border p-6">
                            <!-- 投稿タイプタグと日付 -->
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center">
                                    <span class="text-sm md:text-base text-white px-2 py-1 rounded bg-amber-500">
                                        {{ $post->type === 'gallery' ? '今日の幸せ' : '里親インタビュー' }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- タイトルと本文 -->
                            <h3 class="text-lg font-medium text-main-text mb-2">{{ $post->title }}</h3>
                            <div class="text-main-text mb-4 whitespace-pre-wrap">{{ Str::limit($post->content, 100) }}</div>
                            
                            <!-- メディア表示（画像・動画） -->
                            @if($post->media->count() > 0)
                                <div class="flex space-x-2 mb-4">
                                    @foreach($post->media->take(2) as $media)
                                        @if($media->type === 'image')
                                            <img src="{{ Storage::url($media->url) }}" 
                                                 alt="投稿画像" 
                                                 loading="lazy" decoding="async"
                                                 class="w-20 h-20 object-cover rounded-lg max-h-[80px]">
                                        @elseif($media->type === 'video')
                                            <video class="w-20 h-20 object-cover rounded-lg max-h-[80px]" 
                                                   controls muted playsinline preload="metadata" 
                                                   style="opacity: 1;">
                                                <source src="{{ Storage::url($media->url) }}" type="video/mp4">
                                                お使いのブラウザは動画をサポートしていません。
                                            </video>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            
                            <!-- 閲覧数と日時 -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4 text-sm text-sub-text">
                                    <span class="flex items-center">
                                        <span class="mr-1">👀</span>
                                        {{ $post->view_count ?? 0 }}
                                    </span>
                                    <span class="flex items-center">
                                        <span class="mr-1">📅</span>
                                        {{ $post->created_at->setTimezone('Asia/Tokyo')->format('Y/m/d') }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- アクションボタン -->
                            <div class="flex space-x-2">
                                <a href="{{ route('mypage.posts.edit', $post) }}" 
                                   class="px-3 py-1 text-sm rounded text-white transition duration-200" 
                                   style="background-color: rgb(59 130 246); hover:background-color: rgb(37 99 235);">
                                    編集
                                </a>
                                <button type="button" 
                                        onclick="window.dispatchEvent(new CustomEvent('open-confirm', {
                                            detail: { 
                                                id: 'confirmation-modal',
                                                title: '投稿を削除しますか？',
                                                message: 'この投稿を削除してもよろしいですか？この操作は元に戻せません。',
                                                confirmText: '削除',
                                                cancelText: 'キャンセル',
                                                confirmClass: 'bg-red-600 hover:bg-red-700 text-white',
                                                icon: '🗑️',
                                                formId: 'delete-form-{{ $post->id }}'
                                            }
                                        }))"
                                        class="px-3 py-1 text-sm rounded text-white transition duration-200" 
                                        style="background-color: rgb(239 68 68); hover:background-color: rgb(220 38 38);">
                                    削除
                                </button>
                                <form id="delete-form-{{ $post->id }}" 
                                      action="{{ route('mypage.posts.destroy', $post) }}" 
                                      method="POST" 
                                      class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                
                                @if($post->status === 'published')
                                    <button type="button" 
                                            onclick="window.dispatchEvent(new CustomEvent('open-confirm', {
                                                detail: { 
                                                    id: 'confirmation-modal',
                                                    title: '投稿を非公開にしますか？',
                                                    message: 'この投稿を非公開にしますか？',
                                                    confirmText: '非公開にする',
                                                    cancelText: 'キャンセル',
                                                    confirmClass: 'bg-orange-600 hover:bg-orange-700 text-white',
                                                    icon: '👁️',
                                                    formId: 'hide-form-{{ $post->id }}'
                                                }
                                            }))"
                                            class="px-3 py-1 text-sm rounded text-white transition duration-200" 
                                            style="background-color: rgb(234 179 8); hover:background-color: rgb(217 119 6);">
                                        非公開
                                    </button>
                                    <form id="hide-form-{{ $post->id }}" 
                                          action="{{ route('mypage.posts.toggle-visibility', $post) }}" 
                                          method="POST" 
                                          class="hidden">
                                        @csrf
                                        @method('PATCH')
                                    </form>
                                @else
                                    <form action="{{ route('mypage.posts.toggle-visibility', $post) }}" 
                                          method="POST" 
                                          class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="px-3 py-1 text-sm rounded text-white transition duration-200" 
                                                style="background-color: #10b981; hover:background-color: #059669;">
                                            公開
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- ページネーション -->
                <div class="mt-6">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-amber-100 to-orange-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-main-text mb-2">
                        @if($type === 'gallery')
                            まだ今日の幸せを投稿していませんね
                        @elseif($type === 'interview')
                            まだインタビューを投稿していませんね
                        @else
                            まだ投稿をしていませんね
                        @endif
                    </h3>
                    <p class="text-sub-text mb-6">
                        @if($type === 'gallery')
                        小さな幸せをシェアしてみませんか？
                        @elseif($type === 'interview')
                        あなたの体験が誰かの心に届きます。
                        @else
                            最初の一歩を踏み出してみませんか？
                        @endif
                    </p>
                    @if($type === 'gallery')
                        <a href="{{ route('mypage.posts.gallery.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-medium rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            幸せをシェアする
                        </a>
                    @elseif($type === 'interview')
                        <a href="{{ route('mypage.posts.interview.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-medium rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            体験をシェアする
                        </a>
                    @else
                        <a href="{{ route('mypage.posts.gallery.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-medium rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            幸せをシェアする
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            const keywordInput = document.getElementById('keyword');
            const periodSelect = document.getElementById('period');
            const statusSelect = document.getElementById('status');
            const searchLoading = document.getElementById('searchLoading');
            
            let searchTimeout;
            
            function showLoading() {
                searchLoading.classList.remove('hidden');
            }
            
            function hideLoading() {
                searchLoading.classList.add('hidden');
            }
    
            searchForm.addEventListener('submit', function() {
                showLoading();
            });
            
            keywordInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    showLoading();
                    searchForm.submit();
                }, 500);
            });
            
            keywordInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    showLoading();
                    searchForm.submit();
                }
            });
            
            periodSelect.addEventListener('change', function() {
                showLoading();
                searchForm.submit();
            });
            
            statusSelect.addEventListener('change', function() {
                showLoading();
                searchForm.submit();
            });
        });

        // 画面内に入ったvideoの先頭フレームをposter化（thumbnail_urlが無い場合のみ）
        document.addEventListener('DOMContentLoaded', function() {
            if (!('IntersectionObserver' in window)) return;

            const processed = new WeakSet();
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    const video = entry.target;
                    if (processed.has(video)) return;
                    // 既に有効なposterがあればスキップ
                    if (video.getAttribute('poster')) {
                        processed.add(video);
                        observer.unobserve(video);
                        return;
                    }
                    try {
                        // メタデータ読み込み後に先頭付近へシーク
                        const onMeta = () => {
                            video.removeEventListener('loadedmetadata', onMeta);
                            try { video.currentTime = 0.1; } catch(e) {}
                        };
                        const onSeeked = () => {
                            video.removeEventListener('seeked', onSeeked);
                            try {
                                const canvas = document.createElement('canvas');
                                canvas.width = video.videoWidth || 480;
                                canvas.height = video.videoHeight || 270;
                                const ctx = canvas.getContext('2d');
                                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                                const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                                if (dataUrl && !video.getAttribute('poster')) {
                                    video.setAttribute('poster', dataUrl);
                                }
                            } catch(e) {}
                            processed.add(video);
                            observer.unobserve(video);
                        };

                        video.addEventListener('loadedmetadata', onMeta, { once: true });
                        video.addEventListener('seeked', onSeeked, { once: true });
                        // メタデータが既に読み込まれているケース
                        if (video.readyState >= 1) onMeta();
                    } catch(e) {
                        // 失敗しても静かにフォールバック（プレースホルダーや既定挙動）
                        processed.add(video);
                        observer.unobserve(video);
                    }
                });
            }, { rootMargin: '200px 0px', threshold: 0.1 });

            // 投稿管理画面のvideo要素を監視
            document.querySelectorAll('video').forEach(v => observer.observe(v));
        });
    </script>
</x-app-layout>
