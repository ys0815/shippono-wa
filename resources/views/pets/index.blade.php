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

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="font-medium text-gray-900 mb-4">登録済みペット</h3>

                <div class="space-y-6">
                    @forelse ($pets as $pet)
                        <div class="border rounded-lg overflow-hidden">
                            <!-- プロフィール画像（背景画像） -->
                            <div class="relative h-32 bg-gray-200 flex items-center justify-center">
                                @if($pet->header_image_url)
                                    <img src="{{ $pet->header_image_url }}" alt="プロフィール画像" class="w-full h-full object-cover">
                                @else
                                    <div class="text-gray-400 text-sm">プロフィール画像</div>
                                @endif
                                
                                <!-- アイコン画像（正円） -->
                                <div class="absolute -bottom-6 left-4">
                                    <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center overflow-hidden border-2 border-white">
                                        @if($pet->profile_image_url)
                                            <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-gray-600 text-xs font-bold">{{ mb_substr($pet->name,0,2) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- ペット情報 -->
                            <div class="pt-8 px-4 pb-4">
                                <div class="font-semibold text-lg">{{ $pet->name }} ({{ __([ 'dog' => '犬', 'cat' => '猫', 'rabbit' => 'うさぎ', 'other' => 'その他'][$pet->species] ?? $pet->species) }}{{ $pet->breed ? '・' . $pet->breed : '' }})</div>
                                
                                <div class="text-sm text-gray-600 mt-1">
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
                                    @if($pet->rescue_date)
                                        ・お迎え日: {{ \Illuminate\Support\Carbon::parse($pet->rescue_date)->format('Y/m/d') }}
                                    @endif
                                </div>
                                
                                @if($pet->shelter)
                                <div class="text-sm text-gray-600 mt-1">保護施設: {{ $pet->shelter->name }}</div>
                                @endif
                                
                                <div class="text-sm text-gray-600 mt-1">いいね: {{ $pet->likes->count() }} | 投稿: {{ $pet->posts->count() }}</div>
                                
                                <div class="mt-4 flex gap-2">
                                    <a href="{{ route('pets.show', $pet->id) }}" class="px-3 py-2 rounded border text-gray-700 text-sm hover:bg-gray-50">プロフィールを見る</a>
                                    <a href="{{ route('mypage.pets.edit', ['pet_id' => $pet->id]) }}" class="px-3 py-2 rounded border text-gray-700 text-sm hover:bg-gray-50">プロフィール編集</a>
                                    <a href="#" class="px-3 py-2 rounded border text-gray-700 text-sm hover:bg-gray-50">投稿を見る</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-500 text-center py-8">まだ登録がありません。新しいペットを登録してください。</div>
                    @endforelse
                </div>

                <!-- 家族リンク設定セクション -->
                <div class="mt-8">
                    <h3 class="font-medium text-gray-900 mb-3">家族リンク設定</h3>

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

                <div class="mt-6">
                    <a href="{{ route('mypage.pets.create') }}" class="block w-full text-center bg-gray-800 text-white py-3 rounded-lg font-medium hover:bg-gray-700 transition duration-200">＋新しいペットを登録</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


