@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight">
            マイページ
        </h2>
    </x-slot>

    <div class="pt-12 pb-4 px-4 max-w-2xl mx-auto bg-gray-50 min-h-screen">
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
                                   class="px-3 py-1 text-white text-xs rounded transition duration-200 bg-amber-500 hover:bg-amber-600">編集</a>
                                <a href="{{ route('pets.show', $pet->id) }}" 
                                   class="px-3 py-1 text-white text-xs rounded transition duration-200 bg-blue-500 hover:bg-blue-600">詳細を見る</a>
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
                        <span class="text-xs text-white px-2 py-1 rounded" 
                              style="background-color: #f59e0b;">{{ $post->type === 'gallery' ? '今日の幸せ' : '里親インタビュー' }}</span>
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
                <div class="text-sm text-gray-600">まだ投稿はありません。</div>
            @endforelse

            <!-- アクションボタン -->
            <div class="space-y-3">
                <div style="background: linear-gradient(to right, #f59e0b, #f97316); color: white; border-radius: 0.5rem; padding: 1rem; text-align: center;">
                    <p style="font-size: 1rem; font-weight: 500; margin-bottom: 0.5rem;">今日の幸せをシェアしませんか？</p>
                    <a href="{{ route('mypage.posts.gallery.create') }}" 
                       style="display: inline-block; background-color: white; color: #d97706; padding: 0.5rem 1.5rem; border-radius: 0.5rem; font-weight: 500; transition: background-color 0.2s;"
                       onmouseover="this.style.backgroundColor='#fef3c7'" 
                       onmouseout="this.style.backgroundColor='white'">
                        今日の幸せを投稿する
                    </a>
                </div>
                
                <div style="background: linear-gradient(to right, #3b82f6, #8b5cf6); color: white; border-radius: 0.5rem; padding: 1rem; text-align: center;">
                    <p style="font-size: 1rem; font-weight: 500; margin-bottom: 0.5rem;">体験談をシェアして希望を届けませんか？</p>
                    <a href="{{ route('mypage.posts.interview.create') }}" 
                       style="display: inline-block; background-color: white; color: #2563eb; padding: 0.5rem 1.5rem; border-radius: 0.5rem; font-weight: 500; transition: background-color 0.2s;"
                       onmouseover="this.style.backgroundColor='#eff6ff'" 
                       onmouseout="this.style.backgroundColor='white'">
                        里親インタビューを投稿する
                    </a>
                </div>
                <a href="{{ route('mypage.posts') }}" class="block w-full text-center bg-gray-200 text-gray-800 py-3 rounded-lg font-medium hover:bg-gray-300 transition duration-200">
                    すべての投稿を見る→
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
