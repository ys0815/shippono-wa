@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ url()->previous() }}" class="mr-3 text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $pet->name }}のプロフィール</h2>
        </div>
    </x-slot>

    <div class="py-4 px-4 max-w-2xl mx-auto bg-gray-50 min-h-screen">
        <!-- ペットプロフィールカード -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
            <!-- プロフィール画像 -->
            <div class="relative h-48 bg-gray-100">
                @if($pet->header_image_url)
                    <img src="{{ $pet->header_image_url }}" 
                         alt="{{ $pet->name }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400 text-lg">プロフィール画像</span>
                    </div>
                @endif
                
                <!-- ペットアイコン -->
                <div class="absolute -bottom-8 left-4">
                    <div class="w-16 h-16 bg-white rounded-full border-4 border-white shadow-lg flex items-center justify-center">
                        @if($pet->profile_image_url)
                            <img src="{{ $pet->profile_image_url }}" 
                                 alt="{{ $pet->name }}" 
                                 class="w-full h-full object-cover rounded-full">
                        @else
                            <span class="text-gray-500 text-lg font-medium">
                                {{ mb_substr($pet->name, 0, 2) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- ペット情報 -->
            <div class="pt-10 px-4 pb-4">
                <div class="mb-4">
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $pet->name }}</h1>
                    @if($pet->breed)
                        <p class="text-lg text-gray-600">({{ $pet->breed }})</p>
                    @endif
                </div>
                
                <!-- 基本情報 -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">性別</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ __(['dog' => 'オス', 'cat' => 'メス', 'rabbit' => 'オス', 'other' => 'オス'][$pet->species] ?? 'オス') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">年齢</p>
                        <p class="text-sm font-medium text-gray-900">
                            @if($pet->age_years && $pet->age_months)
                                {{ $pet->age_years }}歳{{ $pet->age_months }}ヶ月
                            @elseif($pet->age_years)
                                {{ $pet->age_years }}歳
                            @else
                                {{ $pet->age_months }}ヶ月
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">種類</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ __(['dog' => '犬', 'cat' => '猫', 'rabbit' => 'うさぎ', 'other' => 'その他'][$pet->species] ?? $pet->species) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">飼い主</p>
                        <p class="text-sm font-medium text-gray-900">{{ $pet->user->display_name ?? $pet->user->name }}さん</p>
                    </div>
                </div>

                <!-- プロフィール説明 -->
                @if($pet->profile_description)
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-1">プロフィール</p>
                        <p class="text-sm text-gray-900">{{ $pet->profile_description }}</p>
                    </div>
                @endif

                <!-- お迎え情報 -->
                @if($pet->shelter)
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-1">お迎え情報</p>
                        <p class="text-sm text-gray-900">{{ $pet->shelter->name }}</p>
                    </div>
                @endif

                <!-- いいねボタン -->
                @auth
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex items-center space-x-2">
                            @if($isLiked)
                                <form action="{{ route('likes.destroy', $pet->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="flex items-center space-x-1 text-red-600 hover:text-red-700 transition duration-200">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                            <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                        </svg>
                                        <span class="text-sm font-medium">いいね済み</span>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('likes.store') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="pet_id" value="{{ $pet->id }}">
                                    <button type="submit" 
                                            class="flex items-center space-x-1 text-gray-600 hover:text-red-600 transition duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                        </svg>
                                        <span class="text-sm font-medium">いいね</span>
                                    </button>
                                </form>
                            @endif
                            <span class="text-sm text-gray-500">{{ $likeCount }}件</span>
                        </div>
                    </div>
                @else
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500 text-center">
                            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">ログイン</a>していいねしてみましょう！
                        </p>
                    </div>
                @endauth
            </div>
        </div>

        <!-- 最近の投稿 -->
        @if($pet->posts->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $pet->name }}の最近の投稿</h3>
                <div class="space-y-4">
                    @foreach($pet->posts as $post)
                        <div class="border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-gray-900">{{ $post->title }}</h4>
                                <span class="text-xs text-gray-500">{{ $post->created_at->format('Y/m/d') }}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($post->content, 100) }}</p>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($post->type === 'gallery') bg-blue-100 text-blue-800
                                    @elseif($post->type === 'interview') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($post->type === 'gallery') 今日の幸せ
                                    @elseif($post->type === 'interview') 里親インタビュー
                                    @else {{ $post->type }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
