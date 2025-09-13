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

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($likes->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($likes as $like)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <!-- ペット画像 -->
                            <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                                @if($like->pet->header_image_url)
                                    <img src="{{ $like->pet->header_image_url }}" 
                                         alt="{{ $like->pet->name }}" 
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center">
                                            @if($like->pet->profile_image_url)
                                                <img src="{{ $like->pet->profile_image_url }}" 
                                                     alt="{{ $like->pet->name }}" 
                                                     class="w-full h-full object-cover rounded-full">
                                            @else
                                                <span class="text-gray-500 text-2xl">
                                                    {{ mb_substr($like->pet->name, 0, 2) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- ペット情報 -->
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $like->pet->name }}</h3>
                                    <span class="text-sm text-gray-500">
                                        {{ $like->created_at->format('Y/m/d') }}
                                    </span>
                                </div>
                                
                                <div class="text-sm text-gray-600 mb-3">
                                    <p>{{ __(['dog' => '犬', 'cat' => '猫', 'rabbit' => 'うさぎ', 'other' => 'その他'][$like->pet->species] ?? $like->pet->species) }}</p>
                                    @if($like->pet->breed)
                                        <p>{{ $like->pet->breed }}</p>
                                    @endif
                                    @if($like->pet->age_years || $like->pet->age_months)
                                        <p>
                                            @if($like->pet->age_years && $like->pet->age_months)
                                                {{ $like->pet->age_years }}歳{{ $like->pet->age_months }}ヶ月
                                            @elseif($like->pet->age_years)
                                                {{ $like->pet->age_years }}歳
                                            @else
                                                {{ $like->pet->age_months }}ヶ月
                                            @endif
                                        </p>
                                    @endif
                                </div>
                                
                                <!-- いいね取り消しボタン -->
                                <div class="flex justify-end">
                                    <button type="button" 
                                            @click="$dispatch('open-modal', { id: 'unlike-modal', formId: 'unlike-form-{{ $like->pet->id }}' })"
                                            class="text-red-600 text-sm hover:text-red-800 px-3 py-1 border border-red-300 rounded hover:bg-red-50 transition duration-200">
                                        いいねを取り消す
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
                    <p class="text-gray-500">動物プロフィールにいいねをしてみましょう！</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
