@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight">
            マイページ
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-2xl mx-auto bg-gray-50 min-h-screen">
        <!-- ユーザー情報ヘッダー -->
        <div class="text-center mb-6">
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-6 border border-amber-200">
                <h1 class="text-xl font-bold text-amber-800 mb-2">
                    🌟 おかえりなさい、{{ Auth::user()->display_name ?? Auth::user()->name }}さん
                </h1>
                <p class="text-amber-700 text-sm">
                    今日も大切な家族との幸せな時間を過ごしていますね。<br>
                    あなたの体験が、新しい家族を待つ誰かの希望になっています。
                </p>
            </div>
        </div>

        <!-- 通知バナー（7日以内の新しいいいね数表示） -->
        @if($stats['recent_likes_got'] > 0)
            <div class="bg-amber-100 border border-amber-200 rounded-lg p-3 mb-6 text-center">
                @if($pets->isNotEmpty())
                    @foreach($pets as $pet)
                        @if($pet->recent_likes_count > 0)
                            <p class="text-sm text-amber-800 font-medium">{{ $pet->name }}に新しい「いいね」が{{ $pet->recent_likes_count }}件届いています！</p>
                        @endif
                    @endforeach
                    @if($pets->where('recent_likes_count', '>', 0)->isEmpty())
                        <p class="text-sm text-amber-700">過去7日間で新しい「いいね」が{{ $stats['recent_likes_got'] }}件届いています！</p>
                    @endif
                @else
                    <p class="text-sm text-amber-700">過去7日間で新しい「いいね」が{{ $stats['recent_likes_got'] }}件届いています！</p>
                @endif
            </div>
        @endif

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
                                <a href="{{ route('mypage.pets.edit', ['pet_id' => $pet->id]) }}" 
                                   class="px-3 py-1 text-white text-sm rounded transition duration-200 bg-amber-500 hover:bg-amber-600">編集</a>
                                <a href="{{ route('pets.show', $pet->id) }}" 
                                   class="px-3 py-1 text-white text-sm rounded transition duration-200 bg-orange-500 hover:bg-orange-600">詳細を見る</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-amber-100 to-orange-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">まだ家族の一員を登録していませんね</h3>
                    <p class="text-gray-500 mb-6">大切な家族の情報を登録して、みんなでシェアしましょう！</p>
                    <a href="{{ route('mypage.pets.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-medium rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        家族の一員を登録する
                    </a>
                </div>
            @endforelse

            <!-- 詳細を見るボタン（ペットプロフィール管理＝一覧ページへ遷移） -->
            @if($pets->isNotEmpty())
                <a href="{{ route('mypage.pets') }}" class="btn btn-brand w-full py-3">
                    ペット一覧を見る→
                </a>
            @endif
        </div>

        <!-- 最近の投稿 -->
        <div class="mb-6">
            <h2 class="text-base font-semibold text-gray-800 mb-3">最近の投稿</h2>
            @forelse ($recentPosts as $post)
                <div class="bg-white rounded-lg p-3 mb-3 border">
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-xs text-white px-2 py-1 rounded bg-amber-500">{{ $post->type === 'gallery' ? '今日の幸せ' : '里親インタビュー' }}</span>
                        <span class="text-xs text-gray-500">{{ $post->created_at->setTimezone('Asia/Tokyo')->format('Y/m/d') }}</span>
                    </div>
                    <h3 class="font-medium text-gray-800 text-sm">{{ $post->title }}</h3>
                    @if($post->content)
                        <div class="text-xs text-gray-600 mt-1 whitespace-pre-wrap">{{ Str::limit($post->content, 30) }}</div>
                    @endif
                    <div class="flex items-center mt-2 text-xs text-gray-500">
                        <span class="mr-3">👁️ {{ $post->view_count ?? 0 }}</span>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-amber-100 to-orange-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">まだ投稿はありません</h3>
                    <p class="text-gray-500 mb-6">大切な家族との幸せな瞬間をシェアしてみませんか？</p>
                </div>
            @endforelse
            
            @if($recentPosts->isNotEmpty())
            <a href="{{ route('mypage.posts') }}" class="block w-full text-center bg-gray-200 text-gray-800 py-3 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                すべての投稿を見る→
            </a>
        @endif

            <!-- アクションボタン -->
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg border border-amber-200 p-6 text-center">
                <p class="text-amber-800 mb-4 text-sm font-medium">今日の幸せ・お迎え体験を投稿しませんか？</p>
                <div class="space-y-3">
                    <a href="{{ route('mypage.posts.gallery.create') }}" 
                       class="block w-full inline-flex items-center justify-center px-4 py-3 text-sm bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors duration-200 font-medium shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        今日の幸せを投稿する
                    </a>
                    <a href="{{ route('mypage.posts.interview.create') }}" 
                       class="block w-full inline-flex items-center justify-center px-4 py-3 text-sm bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors duration-200 font-medium shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        里親インタビューを投稿する
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
