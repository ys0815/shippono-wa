<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $shelter->name }} | #しっぽのわ</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-main-text antialiased bg-main-bg" style="font-family: 'Noto Sans JP', sans-serif;">
<div x-data="{ sidebar:false, search:false }" class="min-h-screen bg-main-bg">
    <!-- 共通ヘッダー -->
    @include('partials.header')

    <!-- メインコンテンツ -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- 保護団体情報 -->
        <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
                <!-- 保護団体アイコン -->
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center border-4 border-white shadow-lg">
                        <svg class="w-12 h-12 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                
                <!-- 保護団体情報 -->
                <div class="flex-1">
                    <h1 class="text-3xl sm:text-4xl font-bold text-main-text mb-4">{{ $shelter->name }}</h1>
                    
                    <div class="space-y-3">
                        <!-- 種別 -->
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-medium text-main-text">種別：</span>
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-sm font-medium">
                                {{ $shelter->kind === 'facility' ? '施設' : ($shelter->kind === 'site' ? 'サイト' : '不明') }}
                            </span>
                        </div>
                        
                        <!-- 所在地 -->
                        @if($shelter->prefecture)
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-medium text-main-text">所在地：</span>
                                <span class="text-main-text">{{ $shelter->prefecture->name }}</span>
                            </div>
                        @endif
                        
                        <!-- ウェブサイト -->
                        @if($shelter->website_url)
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-medium text-main-text">ウェブサイト：</span>
                                <a href="{{ $shelter->website_url }}" target="_blank" class="text-amber-600 hover:text-amber-700 hover:underline">
                                    {{ $shelter->website_url }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 関連ペット -->
        @if($pets->count() > 0)
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-main-text mb-6">この保護団体からお迎えされたペット</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($pets as $pet)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                            <a href="{{ route('pets.show', $pet) }}" class="block">
                                @if($pet->profile_image_url)
                                    <div class="h-48 overflow-hidden">
                                        <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="h-48 bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                        <span class="text-4xl text-amber-600 font-bold">{{ mb_substr($pet->name, 0, 2) }}</span>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h3 class="font-semibold text-main-text mb-2">{{ $pet->name }}</h3>
                                    <div class="flex items-center gap-2 text-sm text-main-text">
                                        <span>{{ $pet->species }}</span>
                                        @if($pet->gender)
                                            <span class="{{ $pet->gender === 'male' ? 'text-blue-500' : ($pet->gender === 'female' ? 'text-pink-500' : 'text-sub-text') }}">
                                                {{ ['male'=>'♂','female'=>'♀','unknown'=>'?'][$pet->gender] ?? '?' }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-sub-text mt-2">
                                        飼い主さん：{{ $pet->user->display_name ?? $pet->user->name }}
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-sub-text mb-4">
                    <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-main-text mb-2">まだペットの登録がありません</h3>
                <p class="text-sub-text">この保護団体からお迎えされたペットの情報が登録されると、ここに表示されます。</p>
            </div>
        @endif
    </main>
</div>
</body>
</html>
