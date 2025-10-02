<x-app-layout>

    <div class="min-h-screen bg-gray-50">
        <!-- ヘッダー（固定） -->
        <div class="bg-white/90 backdrop-blur border-b border-amber-100 shadow-sm sticky top-16 z-[900]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="{{ route('mypage') }}" class="mr-4">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h1 class="text-lg font-semibold text-gray-900">いいね一覧</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- メインコンテンツ -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- 検索機能 -->
        <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
            <form method="GET" id="likesFilterForm" class="space-y-3">
                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- ペットの種類フィルタ -->
                    <div class="flex-1">
                        <label for="species" class="block text-sm md:text-lg font-medium text-gray-700 mb-1">ペットの種類:</label>
                        <select id="species" 
                                name="species" 
                                class="w-full px-2 py-1.5 text-base md:text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                onchange="this.form.submit()">
                            <option value="all" {{ $species === 'all' ? 'selected' : '' }}>すべて</option>
                            <option value="dog" {{ $species === 'dog' ? 'selected' : '' }}>犬</option>
                            <option value="cat" {{ $species === 'cat' ? 'selected' : '' }}>猫</option>
                            <option value="rabbit" {{ $species === 'rabbit' ? 'selected' : '' }}>うさぎ</option>
                            <option value="other" {{ $species === 'other' ? 'selected' : '' }}>その他</option>
                        </select>
                    </div>
                    
                    <!-- 期間フィルタ -->
                    <div class="flex-1">
                        <label for="period" class="block text-sm md:text-lg font-medium text-gray-700 mb-1">期間:</label>
                        <select id="period" 
                                name="period" 
                                class="w-full px-2 py-1.5 text-base md:text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                onchange="this.form.submit()">
                            <option value="all" {{ $period === 'all' ? 'selected' : '' }}>すべて</option>
                            <option value="week" {{ $period === 'week' ? 'selected' : '' }}>1週間</option>
                            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>1ヶ月</option>
                            <option value="year" {{ $period === 'year' ? 'selected' : '' }}>1年</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

            <!-- いいねした動物プロフィール -->
            @if($likes->count() > 0)
                <div class="space-y-4">
            
                    @foreach($likes as $like)
                        <div class="bg-white rounded-lg shadow-sm border p-6">
                            <!-- プロフィール画像（背景画像） -->
                            <div class="relative h-40 bg-gradient-to-br from-amber-50 to-orange-100 flex items-center justify-center">
                                @if($like->pet->header_image_url)
                                    <div class="w-full h-full overflow-hidden">
                                        <img src="{{ $like->pet->header_image_url }}" alt="プロフィール画像" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                @else
                                    <div class="text-amber-300 text-sm flex items-center">
                                        <svg class="w-8 h-8 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                        プロフィール画像
                                    </div>
                                @endif
                                
                                <!-- アイコン画像（正円） -->
                                <div style="position: absolute; bottom: -16px; left: 50%; transform: translateX(-50%); z-index: 20;">
                                    <div style="width: 96px; height: 96px; border-radius: 50%; background-color: white; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 4px solid white; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
                                        @if($like->pet->profile_image_url)
                                            <img src="{{ $like->pet->profile_image_url }}" alt="{{ $like->pet->name }}" loading="lazy" decoding="async" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div style="width: 100%; height: 100%; background: linear-gradient(to bottom right, #fbbf24, #f97316); display: flex; align-items: center; justify-content: center;">
                                                <span style="color: white; font-size: 1.25rem; font-weight: bold;">{{ mb_substr($like->pet->name,0,2) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- いいね・投稿数バッジ -->
                                <div class="absolute top-3 right-3 flex space-x-2">
                                    <div class="bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-medium text-gray-800 flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-red-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $like->pet->likes->count() }}
                                    </div>
                                    <div class="bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-medium text-gray-800 flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $like->pet->posts->count() }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- ペット情報 -->
                            <div class="px-6 pb-6 flex-1 flex flex-col" style="padding-top: 2rem;">
                                <div class="text-center mb-4">
                                    <h3 class="font-bold text-xl text-gray-800 mb-1">{{ $like->pet->name }}</h3>
                                    <div class="text-sm text-amber-600 font-medium">
                                        {{ __([ 'dog' => '犬', 'cat' => '猫', 'rabbit' => 'うさぎ', 'other' => 'その他'][$like->pet->species] ?? $like->pet->species) }}
                                        @if($like->pet->breed)
                                            <span class="text-gray-500">・{{ $like->pet->breed }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ __([ 'male' => 'オス', 'female' => 'メス', 'unknown' => '不明'][$like->pet->gender] ?? $like->pet->gender) }}
                                        @if($like->pet->age_years !== null || $like->pet->age_months !== null)
                                            @if($like->pet->age_years > 0 && $like->pet->age_months > 0)
                                                ・{{ $like->pet->age_years }}歳{{ $like->pet->age_months }}ヶ月
                                            @elseif($like->pet->age_years > 0)
                                                ・{{ $like->pet->age_years }}歳
                                            @elseif($like->pet->age_months > 0)
                                                ・{{ $like->pet->age_months }}ヶ月
                                            @endif
                                        @endif
                                    </div>
                                    
                                    @if($like->pet->rescue_date)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        お迎え日: {{ \Illuminate\Support\Carbon::parse($like->pet->rescue_date)->format('Y/m/d') }}
                                    </div>
                                    @endif
                                    
                                    @if($like->pet->shelter)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $like->pet->shelter->name }}
                                    </div>
                                    @endif
                                    
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        いいね日: {{ $like->created_at->format('Y/m/d') }}
                                    </div>
                                </div>
                                
                                <!-- アクションボタン -->
                                <div class="mt-auto space-y-2">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('pets.show', $like->pet->id) }}" 
                                           class="flex-1 bg-amber-50 text-amber-700 py-2 px-3 rounded-lg text-sm font-medium text-center hover:bg-amber-100 transition-colors duration-200 flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            見る
                                        </a>
                                        <button type="button" 
                                                onclick="window.dispatchEvent(new CustomEvent('open-confirm', {
                                                    detail: { 
                                                        id: 'confirmation-modal',
                                                        title: 'いいねを取り消しますか？',
                                                        message: 'この投稿の『いいね』を取り消しますか？',
                                                        confirmText: '取り消す',
                                                        cancelText: 'キャンセル',
                                                        confirmClass: 'bg-orange-600 hover:bg-orange-700 text-white',
                                                        icon: '💔',
                                                        formId: 'unlike-form-{{ $like->pet->id }}'
                                                    }
                                                }))"
                                                class="flex-1 bg-gray-100 text-gray-700 py-2 px-3 rounded-lg text-sm font-medium text-center hover:bg-gray-200 transition-colors duration-200 flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                            お気に入り
                                        </button>
                                        <form id="unlike-form-{{ $like->pet->id }}" 
                                              action="{{ route('likes.destroy', $like->pet->id) }}" 
                                              method="POST" 
                                              class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-amber-100 to-orange-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">まだいいねがありません</h3>
                    <p class="text-gray-500 mb-6">素敵な家族を探してみませんか？</p>
                    <a href="{{ route('home') }}" 
                       class="btn btn-brand px-6 py-3">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        素敵な出会いを探す
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
