@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            マイページ
        </h2>
    </x-slot>

    <div class="py-4 px-4 max-w-2xl mx-auto bg-gray-50 min-h-screen">
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
            <div class="grid grid-cols-2 gap-4 sm:gap-6">
                <!-- 1行目 -->
                <div class="bg-white rounded-lg p-4 text-center border">
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['pet_count'] ?? 0 }}</div>
                    <div class="text-xs text-gray-600">登録ペット</div>
                </div>
                <div class="bg-white rounded-lg p-4 text-center border">
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['posts'] ?? 0 }}</div>
                    <div class="text-xs text-gray-600">投稿数</div>
                </div>
                <!-- 2行目 -->
                <div class="bg-white rounded-lg p-4 text-center border">
                    <div class="text-2xl font-bold text-gray-800">{{ $stats['likes_got'] ?? 0 }}</div>
                    <div class="text-xs text-gray-600">もらったいいね</div>
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
                        <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if($pet->profile_image_url)
                                <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-gray-600 text-sm font-bold">{{ mb_substr($pet->name,0,2) }}</span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800">{{ $pet->name }}</h3>
                            <p class="text-xs text-gray-600">
                                {{ __([ 'dog' => '犬', 'cat' => '猫', 'rabbit' => 'うさぎ', 'other' => 'その他'][$pet->species] ?? $pet->species) }}・{{ __([ 'male' => 'オス', 'female' => 'メス', 'unknown' => '不明'][$pet->gender] ?? $pet->gender) }}
                                @if($pet->age_years !== null || $pet->age_months !== null)
                                    @if($pet->age_years > 0 && $pet->age_months > 0)
                                        （{{ $pet->age_years }}歳{{ $pet->age_months }}ヶ月）
                                    @elseif($pet->age_years > 0)
                                        （{{ $pet->age_years }}歳）
                                    @elseif($pet->age_months > 0)
                                        （{{ $pet->age_months }}ヶ月）
                                    @endif
                                @endif
                            </p>
                            @if($pet->shelter)
                                <p class="text-xs text-gray-500 mt-1">保護施設: {{ $pet->shelter->name }}</p>
                            @endif
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

            <!-- 詳細を見るボタン（ペットプロフィール管理＝一覧ページへ遷移） -->
            <a href="{{ route('mypage.pets') }}" class="block w-full text-center bg-amber-600 text-white py-3 rounded-lg font-medium hover:bg-amber-700 transition duration-200">
                詳細を見る→
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

            <!-- アクションボタン -->
            <div class="space-y-3">
                <a href="{{ route('mypage.posts.gallery.create') }}" class="block w-full text-center bg-amber-600 text-white py-3 rounded-lg font-medium hover:bg-amber-700 transition duration-200">
                    今日の幸せを投稿
                </a>
                <a href="{{ route('mypage.posts.interview.create') }}" class="block w-full text-center bg-amber-600 text-white py-3 rounded-lg font-medium hover:bg-amber-700 transition duration-200">
                    里親インタビューを投稿
                </a>
                <a href="{{ route('mypage.posts') }}" class="block w-full text-center bg-gray-200 text-gray-800 py-3 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                    すべての投稿を見る→
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
