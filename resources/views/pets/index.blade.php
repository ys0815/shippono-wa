<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('mypage') }}" class="mr-3 text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">ペットプロフィール管理</h2>
        </div>
    </x-slot>

    <div class="pt-16 pb-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-base font-semibold text-gray-800 mb-3">登録済みペット</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($pets as $pet)
                        <div class="group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-amber-200 h-full flex flex-col transform hover:-translate-y-1">
                            <!-- プロフィール画像（背景画像） -->
                            <div class="relative h-40 bg-gradient-to-br from-amber-50 to-orange-100 flex items-center justify-center">
                                @if($pet->header_image_url)
                                    <div class="w-full h-full overflow-hidden">
                                        <img src="{{ $pet->header_image_url }}" alt="プロフィール画像" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    </div>
                                @else
                                    <div class="text-amber-300 text-sm flex items-center">
                                        <svg class="w-8 h-8 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                        プロフィール画像
                                    </div>
                                @endif
                                
                                <!-- アイコン画像（正円） -->
                                <div style="position: absolute; bottom: -16px; left: 50%; transform: translateX(-50%); z-index: 20;">
                                    <div style="width: 96px; height: 96px; border-radius: 50%; background-color: white; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 4px solid white; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
                                        @if($pet->profile_image_url)
                                            <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div style="width: 100%; height: 100%; background: linear-gradient(to bottom right, #fbbf24, #f97316); display: flex; align-items: center; justify-content: center;">
                                                <span style="color: white; font-size: 1.25rem; font-weight: bold;">{{ mb_substr($pet->name,0,2) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- いいね・投稿数バッジ -->
                                <div class="absolute top-3 right-3 flex space-x-2">
                                    <div class="bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-medium text-gray-700 flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $pet->likes->count() }}
                                    </div>
                                    <div class="bg-white/90 backdrop-blur-sm rounded-full px-2 py-1 text-xs font-medium text-gray-700 flex items-center">
                                        <svg class="w-3 h-3 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $pet->posts->count() }}
                                    </div>
                                </div>
                            </div>
                            
                    <!-- ペット情報 -->
                    <div class="pt-16 px-6 pb-6 flex-1 flex flex-col">
                                <div class="text-center mb-4">
                                    <h3 class="font-bold text-xl text-gray-800 mb-1">{{ $pet->name }}</h3>
                                    <div class="text-sm text-amber-600 font-medium">
                                        {{ __([ 'dog' => '犬', 'cat' => '猫', 'rabbit' => 'うさぎ', 'other' => 'その他'][$pet->species] ?? $pet->species) }}
                                        @if($pet->breed)
                                            <span class="text-gray-500">・{{ $pet->breed }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ __([ 'male' => 'オス', 'female' => 'メス', 'unknown' => '不明'][$pet->gender] ?? $pet->gender) }}
                                        @if($pet->age_years !== null || $pet->age_months !== null)
                                            @if($pet->age_years > 0 && $pet->age_months > 0)
                                                ・{{ $pet->age_years }}歳{{ $pet->age_months }}ヶ月
                                            @elseif($pet->age_years > 0)
                                                ・{{ $pet->age_years }}歳
                                            @elseif($pet->age_months > 0)
                                                ・{{ $pet->age_months }}ヶ月
                                            @endif
                                        @endif
                                    </div>
                                    
                                    @if($pet->rescue_date)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                        </svg>
                                        お迎え日: {{ \Illuminate\Support\Carbon::parse($pet->rescue_date)->format('Y/m/d') }}
                                    </div>
                                    @endif
                                    
                                    @if($pet->shelter)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $pet->shelter->name }}
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- アクションボタン -->
                                <div class="mt-auto space-y-2">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('pets.show', $pet->id) }}" 
                                           class="flex-1 bg-amber-50 text-amber-700 py-2 px-3 rounded-lg text-sm font-medium text-center hover:bg-amber-100 transition-colors duration-200 flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            見る
                                        </a>
                                        <a href="{{ route('mypage.pets.edit', ['pet_id' => $pet->id]) }}" 
                                           class="flex-1 bg-gray-100 text-gray-700 py-2 px-3 rounded-lg text-sm font-medium text-center hover:bg-gray-200 transition-colors duration-200 flex items-center justify-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            編集
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                            <div class="w-24 h-24 bg-gradient-to-br from-amber-100 to-orange-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-12 h-12 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">まだペットが登録されていません</h3>
                            <p class="text-gray-500 mb-6">大切な家族の一員を登録して、<br>みんなでシェアしましょう</p>
                            <a href="{{ route('mypage.pets.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-medium rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                新しいペットを登録
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- 家族リンク設定セクション -->
                <div class="mt-8">
                    <h3 class="text-base font-semibold text-gray-800 mb-3">家族リンク設定</h3>

                    <div class="text-sm text-gray-700 mb-4">
                        複数の動物を一緒に飼っている場合、家族として関連付けることができます。
                    </div>

                    @php($pair = $pets->take(2))
                    @if($pair->count() === 2)
                        <div class="flex items-center justify-between rounded-lg bg-blue-50 border border-blue-200 px-4 py-3 mb-4">
                            <div class="text-gray-800 font-medium">
                                {{ $pair[0]->name }} ← {{ $pair[1]->name }}
                            </div>
                            <a href="{{ route('mypage.pets.links') }}" class="px-3 py-1 text-xs rounded border border-gray-300 text-gray-700 hover:bg-gray-100">編集</a>
                        </div>
                    @else
                        <div class="flex items-center justify-between rounded-lg bg-gray-50 border border-gray-200 px-4 py-3 mb-4">
                            <div class="text-gray-500">
                                家族リンクは未設定です
                            </div>
                            <a href="{{ route('mypage.pets.links') }}" class="px-3 py-1 text-xs rounded border border-gray-300 text-gray-700 hover:bg-gray-100">編集</a>
                        </div>
                    @endif

                    <a href="{{ route('mypage.pets.links') }}" class="w-full inline-flex justify-center items-center px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 transition duration-200">
                        <span class="mr-1">+</span> 家族リンクを追加
                    </a>
                </div>

                <div class="mt-8">
                    <a href="{{ route('mypage.pets.create') }}" 
                       class="block w-full text-center bg-amber-600 text-white py-3 rounded-lg font-medium hover:bg-amber-700 transition duration-200">
                        新しいペットを登録
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


