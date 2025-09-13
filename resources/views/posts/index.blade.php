@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <!-- 削除確認ダイアログ -->
    <x-confirmation-modal 
        id="delete-post-modal"
        title="投稿を削除しますか?"
        message="この操作は取り消せません。本当に削除してもよろしいですか?"
        confirm-text="削除"
        cancel-text="キャンセル"
        confirm-class="bg-red-600 hover:bg-red-700 text-white"
        icon="🗑️" />

    <!-- 非公開確認ダイアログ -->
    <x-confirmation-modal 
        id="hide-post-modal"
        title="投稿を非公開にしますか?"
        message="この投稿は他のユーザーから見えなくなります。"
        confirm-text="非公開にする"
        cancel-text="キャンセル"
        confirm-class="bg-orange-600 hover:bg-orange-700 text-white"
        icon="👁️" />
    <div class="min-h-screen bg-gray-50">
        <!-- ヘッダー -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <a href="{{ route('mypage') }}" class="mr-4">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h1 class="text-xl font-semibold text-gray-900">投稿管理画面</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- タブナビゲーション -->
        <div class="bg-white border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <nav class="flex space-x-8">
                    <a href="{{ route('mypage.posts', ['type' => 'all']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ $type === 'all' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        すべて
                    </a>
                    <a href="{{ route('mypage.posts', ['type' => 'gallery']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ $type === 'gallery' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        今日の幸せ
                    </a>
                    <a href="{{ route('mypage.posts', ['type' => 'interview']) }}" 
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ $type === 'interview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        しっぽのわ
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
                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        今日の幸せを投稿
                    </a>
                    <a href="{{ route('mypage.posts.interview.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        里親インタビューを投稿
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
                            <label for="keyword" class="block text-xs font-medium text-gray-700 mb-1">検索:</label>
                            <div class="relative">
                                <input type="text" 
                                       id="keyword" 
                                       name="keyword" 
                                       value="{{ $keyword }}"
                                       placeholder="キーワード" 
                                       class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <div id="searchLoading" class="absolute right-2 top-1.5 hidden">
                                    <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 期間と状態を横並び -->
                        <div class="flex gap-3">
                            <!-- 期間フィルタ -->
                            <div class="flex-1">
                                <label for="period" class="block text-xs font-medium text-gray-700 mb-1">期間:</label>
                                <select id="period" 
                                        name="period" 
                                        class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="all" {{ $period === 'all' ? 'selected' : '' }}>すべて</option>
                                    <option value="month" {{ $period === 'month' ? 'selected' : '' }}>今月</option>
                                    <option value="half_year" {{ $period === 'half_year' ? 'selected' : '' }}>半年</option>
                                    <option value="year" {{ $period === 'year' ? 'selected' : '' }}>一年</option>
                                </select>
                            </div>
                            
                            <!-- 状態フィルタ -->
                            <div class="flex-1">
                                <label for="status" class="block text-xs font-medium text-gray-700 mb-1">状態:</label>
                                <select id="status" 
                                        name="status" 
                                        class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-700">
                                        {{ $post->type === 'gallery' ? '今日の幸せ' : '里親インタビュー' }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $post->created_at->format('Y/m/d') }}
                                </div>
                            </div>
                            
                            <!-- タイトルと本文 -->
                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $post->title }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($post->content, 100) }}</p>
                            
                            <!-- メディア表示（画像・動画） -->
                            @if($post->media->count() > 0)
                                <div class="flex space-x-2 mb-4">
                                    @foreach($post->media->take(2) as $media)
                                        @if($media->type === 'image')
                                            <img src="{{ Storage::url($media->url) }}" 
                                                 alt="投稿画像" 
                                                 class="w-20 h-20 object-cover rounded-lg">
                                        @elseif($media->type === 'video')
                                            <video src="{{ Storage::url($media->url) }}" 
                                                   class="w-20 h-20 object-cover rounded-lg"
                                                   controls>
                                                お使いのブラウザは動画をサポートしていません。
                                            </video>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                            
                            <!-- 閲覧数と日時 -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <span class="mr-1">👀</span>
                                        {{ rand(100, 2000) }} <!-- 仮の閲覧数 -->
                                    </span>
                                    <span class="flex items-center">
                                        <span class="mr-1">📅</span>
                                        {{ $post->created_at->format('Y/m/d') }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- アクションボタン -->
                            <div class="flex space-x-2">
                                <a href="{{ route('mypage.posts.edit', $post) }}" 
                                   class="px-3 py-1 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition duration-200">
                                    編集
                                </a>
                                <button type="button" 
                                        @click="$dispatch('open-modal', { id: 'delete-post-modal', formId: 'delete-form-{{ $post->id }}' })"
                                        class="px-3 py-1 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition duration-200">
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
                                            @click="$dispatch('open-modal', { id: 'hide-post-modal', formId: 'hide-form-{{ $post->id }}' })"
                                            class="px-3 py-1 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition duration-200">
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
                                                class="px-3 py-1 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition duration-200">
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
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">投稿がありません</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if($type === 'gallery')
                            今日の幸せを投稿してみましょう。
                        @elseif($type === 'interview')
                            里親インタビューを投稿してみましょう。
                        @else
                            まだ投稿がありません。
                        @endif
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('mypage.posts.gallery.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            投稿してみる
                        </a>
                    </div>
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
            
            // ローディング表示の制御
            function showLoading() {
                searchLoading.classList.remove('hidden');
            }
            
            function hideLoading() {
                searchLoading.classList.add('hidden');
            }
            
            // フォーム送信時のローディング表示
            searchForm.addEventListener('submit', function() {
                showLoading();
            });
            
            // キーワード検索の自動実行（入力後500ms待機）
            keywordInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    showLoading();
                    searchForm.submit();
                }, 500);
            });
            
            // キーワード検索のEnterキー実行
            keywordInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    showLoading();
                    searchForm.submit();
                }
            });
            
            // 期間選択の自動実行
            periodSelect.addEventListener('change', function() {
                showLoading();
                searchForm.submit();
            });
            
            // 状態選択の自動実行
            statusSelect.addEventListener('change', function() {
                showLoading();
                searchForm.submit();
            });
        });
    </script>
</x-app-layout>
