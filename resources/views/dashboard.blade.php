<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            マイページ
        </h2>
    </x-slot>

    <div class="py-4 px-4 max-w-md mx-auto bg-gray-50 min-h-screen">
        <!-- ユーザー情報ヘッダー -->
        <div class="text-center mb-4">
            <h1 class="text-lg font-bold text-gray-800">{{ Auth::user()->display_name ?? Auth::user()->name }}さんのマイページ</h1>
        </div>

        <!-- 通知バナー（将来: 未読通知数に連動） -->
        <div class="bg-gray-200 rounded-lg p-3 mb-6 text-center">
            <p class="text-sm text-gray-700">新しい「いいね」が{{ $stats['likes_got'] ?? 0 }}件届いています！</p>
        </div>

        <!-- 活動統計 -->
        <div class="mb-6">
            <h2 class="text-base font-semibold text-gray-800 mb-3">活動統計</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-lg p-4 text-center border">
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['posts'] ?? 0 }}</div>
                    <div class="text-xs text-gray-600">投稿数</div>
                </div>
                <div class="bg-white rounded-lg p-4 text-center border">
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['likes_got'] ?? 0 }}</div>
                    <div class="text-xs text-gray-600">もらったいいね</div>
                </div>
                <div class="bg-white rounded-lg p-4 text-center border">
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['pet_count'] ?? 0 }}</div>
                    <div class="text-xs text-gray-600">登録ペット</div>
                </div>
                <div class="bg-white rounded-lg p-4 text-center border">
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['likes_given'] ?? 0 }}</div>
                    <div class="text-xs text-gray-600">あげたいいね</div>
                </div>
            </div>
        </div>

        <!-- 登録ペット一覧 -->
        <div class="mb-6">
            <h2 class="text-base font-semibold text-gray-800 mb-3">登録ペット一覧</h2>
            @forelse ($pets as $pet)
                <div class="bg-white rounded-lg p-4 mb-3 border">
                    <div class="flex items-start space-x-3">
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-gray-600 text-sm font-bold">{{ $pet->name }}</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800">{{ $pet->name }}</h3>
                            <p class="text-xs text-gray-600">{{ $pet->species }}・{{ $pet->gender }}</p>
                            <p class="text-xs text-gray-600">いいね：{{ $pet->likes()->count() }}｜投稿：{{ $pet->posts()->count() }}</p>
                            <div class="flex space-x-2 mt-2">
                                <a href="{{ route('mypage.pets.edit', ['pet_id' => $pet->id]) }}" class="px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded">編集</a>
                                <a href="#" class="px-3 py-1 bg-gray-200 text-gray-700 text-xs rounded">投稿</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-sm text-gray-600">登録されたペットはいません。</div>
            @endforelse

            <!-- 新しいペットを登録ボタン -->
            <a href="{{ route('mypage.pets.create') }}" class="block w-full text-center bg-gray-800 text-white py-3 rounded-lg font-medium hover:bg-gray-700 transition duration-200">
                ＋新しいペットを登録
            </a>
        </div>

        <!-- 最近の投稿 -->
        <div class="mb-6">
            <h2 class="text-base font-semibold text-gray-800 mb-3">最近の投稿</h2>
            @forelse ($recentPosts as $post)
                <div class="bg-white rounded-lg p-3 mb-3 border">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $post->type === 'gallery' ? '今日の幸せ' : '里親インタビュー' }}</span>
                        <span class="text-xs text-gray-500">{{ $post->created_at->format('Y/m/d') }}</span>
                    </div>
                    <h3 class="font-medium text-gray-800 text-sm">{{ $post->title }}</h3>
                    @if($post->content)
                        <p class="text-xs text-gray-600 mt-1">{{ Str::limit($post->content, 30) }}</p>
                    @endif
                    <div class="flex items-center mt-2 text-xs text-gray-500">
                        <span class="mr-3">👁️ 0</span>
                        <span>❤️ 0</span>
                    </div>
                </div>
            @empty
                <div class="text-sm text-gray-600">まだ投稿はありません。</div>
            @endforelse

            <!-- すべての投稿を見るボタン -->
            <a href="{{ route('mypage.posts') }}" class="block w-full text-center bg-gray-800 text-white py-3 rounded-lg font-medium hover:bg-gray-700 transition duration-200">
                すべての投稿を見る→
            </a>
        </div>
    </div>
</x-app-layout>

