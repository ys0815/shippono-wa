<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('mypage.pets') }}" class="mr-3 text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">ペットプロフィール管理</h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- 現在の家族リンク -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="font-medium text-gray-900 mb-4">現在の家族リンク</h3>
                
                @if($existingLinks->isNotEmpty())
                    @foreach($existingLinks as $link)
                        <div class="bg-white border rounded-lg p-4 mb-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-sm font-bold overflow-hidden">
                                            @if($link->pet1->profile_image_url)
                                                <img src="{{ $link->pet1->profile_image_url }}" alt="{{ $link->pet1->name }}" class="w-full h-full object-cover">
                                            @else
                                                {{ mb_substr($link->pet1->name, 0, 2) }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1">{{ $link->pet1->name }}</div>
                                    </div>
                                    <div class="text-blue-500 text-2xl">⇔</div>
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-sm font-bold overflow-hidden">
                                            @if($link->pet2->profile_image_url)
                                                <img src="{{ $link->pet2->profile_image_url }}" alt="{{ $link->pet2->name }}" class="w-full h-full object-cover">
                                            @else
                                                {{ mb_substr($link->pet2->name, 0, 2) }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-600 mt-1">{{ $link->pet2->name }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm text-gray-600">設定日: {{ $link->created_at->format('Y/m/d') }}</div>
                                    <button type="button" class="text-red-600 text-sm hover:text-red-800 mt-1">解除</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>現在設定されている家族リンクはありません</p>
                        <p class="text-sm mt-1">※家族リンクがない場合は、下記から新しいリンクを作成してください</p>
                    </div>
                @endif
            </div>

            <!-- 新しい家族リンクを作成 -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="font-medium text-gray-900 mb-4">新しい家族リンクを作成</h3>
                <p class="text-sm text-gray-600 mb-4">一緒に暮らしている動物を選択してください (複数選択可能)</p>
                
                @if($pets->count() >= 2)
                    <form method="post" action="{{ route('mypage.pets.links.store') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 gap-3">
                            @foreach($pets as $pet)
                                <label class="relative cursor-pointer">
                                    <input type="checkbox" name="selected_pets[]" value="{{ $pet->id }}" 
                                           class="absolute top-3 right-3 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-sm font-bold overflow-hidden">
                                                @if($pet->profile_image_url)
                                                    <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ mb_substr($pet->name, 0, 2) }}
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-900">{{ $pet->name }}</div>
                                                <div class="text-sm text-gray-600">
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
                        
                        <button type="submit" class="w-full bg-gray-600 text-white py-3 rounded-lg font-medium hover:bg-gray-700 transition duration-200">
                            家族リンクを作成
                        </button>
                    </form>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>家族リンクを作成するには、2匹以上のペットを登録してください</p>
                        <div class="mt-4">
                            <a href="{{ route('mypage.pets.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm rounded-lg hover:bg-gray-700 transition duration-200">
                                新しいペットを登録する
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- 家族リンクについて -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <h4 class="font-medium text-gray-900 mb-3">家族リンクについて</h4>
                <ul class="text-sm text-gray-700 space-y-1">
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
