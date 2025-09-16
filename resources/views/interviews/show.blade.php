<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $post->title ?? '里親インタビュー' }} | #しっぽのわ</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50" style="font-family: 'Noto Sans JP', sans-serif;">
<div x-data="{ sidebar:false, search:false }" class="min-h-screen bg-gray-50">
    @include('partials.header')

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- メイン画像 -->
        <section class="mb-8">
            <div class="relative h-64 sm:h-80 lg:h-96 bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 rounded-lg overflow-hidden">
                @php
                    $mainImage = null;
                    // インタビュー投稿時のメディアから画像を取得
                    if ($post->media && $post->media->count() > 0) {
                        $imageMedia = $post->media->where('type', 'image')->first();
                        if ($imageMedia) {
                            $mainImage = $imageMedia->url;
                            if (strpos($mainImage, 'http') !== 0) {
                                $mainImage = '/storage/' . $mainImage;
                            }
                        }
                    }
                @endphp
                
                @if($mainImage)
                    <img src="{{ $mainImage }}" alt="メイン画像" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-24 h-24 sm:w-32 sm:h-32 mx-auto mb-4 rounded-full overflow-hidden border-4 border-white shadow-lg bg-amber-100">
                                @if($post->pet && $post->pet->profile_image_url)
                                    <img src="{{ $post->pet->profile_image_url }}" alt="{{ $post->pet->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                        <span class="text-amber-600 text-2xl sm:text-3xl font-bold">{{ mb_substr($post->pet->name ?? 'Pet', 0, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <!-- インタビュー情報ヘッダー -->
        <section class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8 mb-8">
            <div class="text-center mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">{{ $post->title ?? ($post->pet->name.'の里親インタビュー') }}</h1>
                <div class="flex items-center justify-center gap-3 text-gray-600 mb-4">
                    <span class="font-medium text-gray-700">{{ $post->pet->name ?? '名無し' }}</span>
                    @if($post->pet)
                        <span class="{{ $post->pet->gender === 'male' ? 'text-blue-400' : ($post->pet->gender === 'female' ? 'text-pink-400' : 'text-gray-400') }}">
                            {{ ['male'=>'♂','female'=>'♀','unknown'=>'?'][$post->pet->gender] ?? '?' }}
                        </span>
                        <span>飼い主さん：{{ $post->pet->user->display_name ?? $post->pet->user->name }}</span>
                    @endif
                </div>
                <div class="text-sm text-gray-500">
                    投稿日時：{{ $post->created_at->format('Y年n月j日 H:i') }}
                </div>
            </div>
        </section>

        <!-- インタビューコンテンツ -->
        <section class="space-y-8">
            @if($post->interviewContent)
                <!-- ① 新しい家族との出会い -->
                @if($post->interviewContent->question1)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">① 新しい家族との出会い</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        <div class="prose prose-base max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($post->interviewContent->question1)) !!}
                        </div>
                    </div>
                @endif

                <!-- ② 迎える前の不安と準備 -->
                @if($post->interviewContent->question2)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">② 迎える前の不安と準備</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        <div class="prose prose-base max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($post->interviewContent->question2)) !!}
                        </div>
                    </div>
                @endif

                <!-- ③ 迎えた後の変化と喜び -->
                @if($post->interviewContent->question3)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">③ 迎えた後の変化と喜び</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        <div class="prose prose-base max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($post->interviewContent->question3)) !!}
                        </div>
                    </div>
                @endif

                <!-- ④ 未来の里親へのメッセージ -->
                @if($post->interviewContent->question4)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">④ 未来の里親へのメッセージ</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        <div class="prose prose-base max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($post->interviewContent->question4)) !!}
                        </div>
                    </div>
                @endif

                <!-- ⑤ 最後に一言 -->
                @if($post->interviewContent->question5)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8">
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-6 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">⑤ 最後に一言</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        <div class="prose prose-base max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($post->interviewContent->question5)) !!}
                        </div>
                    </div>
                @endif
            @else
                <!-- フォールバック：通常のコンテンツ表示 -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8">
                    <div class="prose prose-base max-w-none text-gray-700 leading-relaxed">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                </div>
            @endif

            <!-- 戻るボタン -->
            <div class="text-center pt-8">
                <a href="{{ route('interviews.index') }}" class="inline-block px-8 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-full hover:from-amber-600 hover:to-orange-600 transition font-medium text-lg shadow-lg">
                    一覧に戻る
                </a>
            </div>
        </section>
    </main>
</div>
</body>
</html>


