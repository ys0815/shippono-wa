<x-app-layout>

    <div class="min-h-screen bg-main-bg">
        <!-- ヘッダー（固定） -->
        <div class="bg-white/90 backdrop-blur border-b border-amber-100 shadow-sm sticky top-16 z-[900]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="{{ route('mypage.pets') }}" class="mr-4">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h1 class="text-lg font-semibold text-amber-900">家族リンク設定</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- メインコンテンツ -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- 成功メッセージ -->
            @if (session('status') === 'family-links-created')
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">家族リンクを作成しました！</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('status') === 'family-links-deleted')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-800">家族リンクを解除しました。</p>
                        </div>
                    </div>
                </div>
            @endif
            <!-- 現在の家族リンク -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <h3 class="text-base font-semibold text-main-text mb-3">現在の家族リンク</h3>
                
                @if($familyGroups->isNotEmpty())
                    @foreach($familyGroups as $group)
                        <div class="bg-main-bg border border-gray-200 rounded-lg p-4 mb-3">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <!-- ペット表示エリア -->
                                <div class="flex-1 mb-3 sm:mb-0">
                                    @if(count($group['pets']) <= 3)
                                        <!-- 3匹以下の場合は横並び -->
                                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                                            @foreach($group['pets'] as $index => $pet)
                                                <div class="flex flex-col items-center">
                                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-sm font-bold overflow-hidden">
                                                        @if($pet->profile_image_url)
                                                            <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" class="w-full h-full object-cover">
                                                        @else
                                                            {{ mb_substr($pet->name, 0, 2) }}
                                                        @endif
                                                    </div>
                                                    <div class="text-xs text-main-text mt-1 text-center">{{ $pet->name }}</div>
                                                </div>
                                                @if($index < count($group['pets']) - 1)
                                                    <div class="text-blue-500 text-lg flex-shrink-0">⇔</div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <!-- 4匹以上の場合はグリッドレイアウト -->
                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                                            @foreach($group['pets'] as $index => $pet)
                                                <div class="flex flex-col items-center">
                                                    <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-sm font-bold overflow-hidden">
                                                        @if($pet->profile_image_url)
                                                            <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" class="w-full h-full object-cover">
                                                        @else
                                                            {{ mb_substr($pet->name, 0, 2) }}
                                                        @endif
                                                    </div>
                                                    <div class="text-xs text-main-text mt-1 text-center">{{ $pet->name }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="text-center mt-2">
                                            <span class="text-sm text-blue-600 font-medium">家族グループ</span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- 設定日と解除ボタン -->
                                <div class="text-center sm:text-right">
                                    <div class="text-sm text-main-text mb-2">設定日: {{ $group['created_at']->format('Y/m/d') }}</div>
                                    <button type="button" 
                                            onclick="window.dispatchEvent(new CustomEvent('open-confirm', {
                                                detail: { 
                                                    id: 'confirmation-modal',
                                                    title: '家族リンクを解除しますか？',
                                                    message: 'この動物プロフィールの家族リンクを解除しますか？',
                                                    confirmText: '解除する',
                                                    cancelText: 'キャンセル',
                                                    confirmClass: 'bg-red-600 hover:bg-red-700 text-white',
                                                    icon: '🔗',
                                                    formId: 'unlink-form'
                                                }
                                            }))"
                                            class="text-red-600 text-sm hover:text-red-800 px-3 py-1 border border-red-300 rounded hover:bg-red-50 transition duration-200">
                                        解除
                                    </button>
                                    <form id="unlink-form" 
                                          method="post" 
                                          action="{{ route('mypage.pets.links.destroy') }}" 
                                          class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-sub-text">
                        <p>現在設定されている家族リンクはありません</p>
                        <p class="text-sm mt-1">※家族リンクがない場合は、下記から新しいリンクを作成してください</p>
                    </div>
                @endif
            </div>

            <!-- 新しい家族リンクを作成 -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <h3 class="text-base font-semibold text-main-text mb-3">新しい家族リンクを作成</h3>
                <p class="text-sm text-main-text mb-4">一緒に暮らしている動物を選択してください (複数選択可能)</p>
                
                @if($pets->count() >= 2)
                    <form method="post" action="{{ route('mypage.pets.links.store') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($pets as $pet)
                                <label class="relative cursor-pointer">
                                    <input type="checkbox" name="selected_pets[]" value="{{ $pet->id }}" 
                                           class="absolute top-3 right-3 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <div class="border rounded-lg p-4 hover:bg-main-bg transition duration-200">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-sm font-bold overflow-hidden flex-shrink-0">
                                                @if($pet->profile_image_url)
                                                    <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ mb_substr($pet->name, 0, 2) }}
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="font-medium text-main-text truncate">{{ $pet->name }}</div>
                                                <div class="text-sm text-main-text truncate">
                                                    {{ __(['dog' => '犬', 'cat' => '猫', 'rabbit' => 'うさぎ', 'other' => 'その他'][$pet->species] ?? $pet->species) }}
                                                    @if($pet->breed)
                                                        {{ $pet->breed }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        
                        <button type="submit" class="btn btn-brand w-full py-3">
                            家族リンクを作成
                        </button>
                    </form>
                @else
                    <div class="text-center py-8 text-sub-text">
                        <p>家族リンクを作成するには、2匹以上のペットを登録してください</p>
                        <div class="mt-4">
                            <a href="{{ route('mypage.pets.create') }}" class="btn btn-brand px-6 py-3">
                                新しいペットを登録する
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- 家族リンクについて -->
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                <h4 class="text-base font-semibold text-main-text mb-3">家族リンクについて</h4>
                <ul class="text-sm text-main-text space-y-1">
                    <li>• 一緒に暮らしている動物同士をリンクできます</li>
                    <li>• 複数の動物を同時に選択して、グループとしてリンク可能</li>
                    <li>• リンクされた動物は、プロフィールページで「家族」として表示されます</li>
                    <li>• 動物数に制限はありません (3匹以上でも可能)</li>
                    <li>• リンクはいつでも解除できます</li>
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
