<x-app-layout>
    <!-- いいね取り消し確認ダイアログ -->
    <x-confirmation-modal 
        id="unlike-modal"
        title="いいねを取り消しますか?"
        message="この動物プロフィールへのいいねを取り消します。"
        confirm-text="取り消す"
        cancel-text="キャンセル"
        confirm-class="bg-orange-600 hover:bg-orange-700 text-white"
        icon="💔" />

    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('mypage') }}" class="mr-3 text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">いいね一覧</h2>
        </div>
    </x-slot>

    <div class="py-4 px-4 max-w-2xl mx-auto bg-gray-50 min-h-screen">
        <!-- フィルター -->
        <div class="mb-6">
            <form method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex items-center">
                    <label class="text-sm font-medium text-gray-700 mr-2">動物種:</label>
                    <select name="species" class="border border-gray-300 rounded-md px-3 py-1 text-sm" onchange="this.form.submit()">
                        <option value="all" {{ $species === 'all' ? 'selected' : '' }}>すべて</option>
                        <option value="dog" {{ $species === 'dog' ? 'selected' : '' }}>犬</option>
                        <option value="cat" {{ $species === 'cat' ? 'selected' : '' }}>猫</option>
                        <option value="rabbit" {{ $species === 'rabbit' ? 'selected' : '' }}>うさぎ</option>
                        <option value="other" {{ $species === 'other' ? 'selected' : '' }}>その他</option>
                    </select>
                </div>
                <div class="flex items-center">
                    <label class="text-sm font-medium text-gray-700 mr-2">期間:</label>
                    <select name="period" class="border border-gray-300 rounded-md px-3 py-1 text-sm" onchange="this.form.submit()">
                        <option value="all" {{ $period === 'all' ? 'selected' : '' }}>すべて</option>
                        <option value="week" {{ $period === 'week' ? 'selected' : '' }}>1週間</option>
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>1ヶ月</option>
                        <option value="year" {{ $period === 'year' ? 'selected' : '' }}>1年</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- いいねした動物プロフィール -->
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">いいねした動物プロフィール</h2>
            
            @if($likes->count() > 0)
                <div class="space-y-4">
                    @foreach($likes as $like)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <!-- プロフィール画像 -->
                            <div class="relative h-32 bg-gray-100">
                                @if($like->pet->header_image_url)
                                    <img src="{{ $like->pet->header_image_url }}" 
                                         alt="{{ $like->pet->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-400 text-sm">プロフィール画像</span>
                                    </div>
                                @endif
                                
                                <!-- ペットアイコン -->
                                <div class="absolute -bottom-6 left-4">
                                    <div class="w-12 h-12 bg-white rounded-full border-2 border-white shadow-md flex items-center justify-center">
                                        @if($like->pet->profile_image_url)
                                            <img src="{{ $like->pet->profile_image_url }}" 
                                                 alt="{{ $like->pet->name }}" 
                                                 class="w-full h-full object-cover rounded-full">
                                        @else
                                            <span class="text-gray-500 text-sm font-medium">
                                                {{ mb_substr($like->pet->name, 0, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- ペット情報 -->
                            <div class="pt-8 px-4 pb-4">
                                <div class="mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $like->pet->name }}
                                        @if($like->pet->breed)
                                            <span class="text-sm font-normal text-gray-600">({{ $like->pet->breed }})</span>
                                        @endif
                                    </h3>
                                </div>
                                
                                <div class="text-sm text-gray-600 mb-3">
                                    <p>
                                        {{ __(['dog' => 'オス', 'cat' => 'メス', 'rabbit' => 'オス', 'other' => 'オス'][$like->pet->species] ?? 'オス') }}・
                                        @if($like->pet->age_years && $like->pet->age_months)
                                            {{ $like->pet->age_years }}歳{{ $like->pet->age_months }}ヶ月
                                        @elseif($like->pet->age_years)
                                            {{ $like->pet->age_years }}歳
                                        @else
                                            {{ $like->pet->age_months }}ヶ月
                                        @endif
                                        ・飼い主: {{ $like->pet->user->display_name ?? $like->pet->user->name }}さん
                                    </p>
                                    <p class="text-gray-500">いいね日: {{ $like->created_at->format('Y/m/d') }}</p>
                                </div>
                                
                                <!-- アクションボタン -->
                                <div class="flex space-x-2">
                                    <a href="{{ route('pets.show', $like->pet->id) }}" 
                                       class="flex-1 bg-amber-600 text-white text-center py-2 px-4 rounded-md text-sm font-medium hover:bg-amber-700 transition duration-200">
                                        プロフィールを見る
                                    </a>
                                    <button type="button" 
                                            @click="$dispatch('open-modal', { id: 'unlike-modal', formId: 'unlike-form-{{ $like->pet->id }}' })"
                                            class="flex-1 bg-gray-200 text-gray-700 py-2 px-4 rounded-md text-sm font-medium hover:bg-gray-300 transition duration-200">
                                        いいね取消
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
                    @endforeach
                </div>
                
                <!-- ページネーション -->
                <div class="mt-6">
                    {{ $likes->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">❤️</div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">いいねがありません</h3>
                    <p class="text-gray-500">他のペットプロフィールにいいねをしてみましょう！</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
