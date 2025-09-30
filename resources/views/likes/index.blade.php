<x-app-layout>

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

    <div class="pt-12 pb-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- フィルター（投稿管理画面と統一） -->
        <div class="bg-white rounded-lg shadow-sm border p-4 mb-6">
            <form method="GET" id="likesFilterForm" class="space-y-3">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-700 mb-1">ペットの種類:</label>
                        <select name="species" 
                                class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                onchange="this.form.submit()">
                            <option value="all" {{ $species === 'all' ? 'selected' : '' }}>すべて</option>
                            <option value="dog" {{ $species === 'dog' ? 'selected' : '' }}>犬</option>
                            <option value="cat" {{ $species === 'cat' ? 'selected' : '' }}>猫</option>
                            <option value="rabbit" {{ $species === 'rabbit' ? 'selected' : '' }}>うさぎ</option>
                            <option value="other" {{ $species === 'other' ? 'selected' : '' }}>その他</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-medium text-gray-700 mb-1">期間:</label>
                        <select name="period" 
                                class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
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
        <div class="bg-white p-6 shadow sm:rounded-lg">
            <h3 class="text-base font-semibold text-gray-800 mb-3">いいねした動物プロフィール</h3>
            
            @if($likes->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($likes as $like)
                        <div class="border rounded-lg overflow-hidden h-full flex flex-col">
                            <!-- プロフィール画像（背景画像） -->
                            <div class="relative h-32 bg-gray-200 flex items-center justify-center">
                                @if($like->pet->header_image_url)
                                    <img src="{{ $like->pet->header_image_url }}" alt="プロフィール画像" class="w-full h-full object-cover">
                                @else
                                    <div class="text-gray-400 text-sm">プロフィール画像</div>
                                @endif
                                
                                <!-- アイコン画像（正円） -->
                                <div class="absolute -bottom-6 left-4">
                                    <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center overflow-hidden border-2 border-white">
                                        @if($like->pet->profile_image_url)
                                            <img src="{{ $like->pet->profile_image_url }}" alt="{{ $like->pet->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-gray-600 text-xs font-bold">{{ mb_substr($like->pet->name,0,2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- ペット情報 -->
                            <div class="pt-8 px-4 pb-4">
                                <div class="font-semibold text-lg">{{ $like->pet->name }} ({{ __([ 'dog' => '犬', 'cat' => '猫', 'rabbit' => 'うさぎ', 'other' => 'その他'][$like->pet->species] ?? $like->pet->species) }}{{ $like->pet->breed ? '・' . $like->pet->breed : '' }})</div>
                                
                                <div class="text-sm text-gray-600 mt-1">
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
                                    @if($like->pet->rescue_date)
                                        ・お迎え日: {{ \Illuminate\Support\Carbon::parse($like->pet->rescue_date)->format('Y/m/d') }}
                                    @endif
                                </div>
                                
                                @if($like->pet->shelter)
                                <div class="text-sm text-gray-600 mt-1">保護施設: {{ $like->pet->shelter->name }}</div>
                                @endif
                                
                                <div class="text-sm text-gray-600 mt-1">いいね: {{ $like->pet->likes->count() }} | 投稿: {{ $like->pet->posts->count() }}</div>
                                <div class="text-sm text-gray-500 mt-1">いいね日: {{ $like->created_at->format('Y/m/d') }}</div>
                                
                                <div class="mt-auto flex gap-2">
                                    <a href="{{ route('pets.show', $like->pet->id) }}" class="px-3 py-2 rounded border text-gray-700 text-sm hover:bg-gray-50">プロフィールを見る</a>
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
                                            class="px-3 py-2 rounded border text-gray-700 text-sm hover:bg-gray-50">
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
                <div class="text-gray-500 text-center py-8">まだいいねがありません。他のペットプロフィールにいいねをしてみましょう！</div>
            @endif
        </div>
        </div>
    </div>
</x-app-layout>
