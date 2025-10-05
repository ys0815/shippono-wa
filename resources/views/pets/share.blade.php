<x-guest-layout>
    @section('head')
        <!-- OGP メタタグ -->
        <meta property="og:title" content="{{ $shareLink->title }}">
        <meta property="og:description" content="{{ $shareLink->description }}">
        <meta property="og:image" content="{{ $pet->profile_image_url ? asset($pet->profile_image_url) : asset('images/icon.png') }}">
        <meta property="og:url" content="{{ $shareLink->getShareUrl() }}">
        <meta property="og:type" content="profile">
        <meta property="og:site_name" content="#しっぽのわ">
        
        <!-- X Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $shareLink->title }}">
        <meta name="twitter:description" content="{{ $shareLink->description }}">
        <meta name="twitter:image" content="{{ $pet->profile_image_url ? asset($pet->profile_image_url) : asset('images/icon.png') }}">
        
        <!-- その他のメタタグ -->
        <meta name="description" content="{{ $shareLink->description }}">
        <title>{{ $shareLink->title }} - #しっぽのわ</title>
    @endsection
    <div class="min-h-screen bg-main-bg">
        <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- ヘッダーエリア（背景/アイコン/名前） -->
            <section class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="relative h-40 sm:h-56 bg-gradient-to-r from-amber-100 to-orange-100">
                    @if($pet->header_image_url)
                        <img src="{{ $pet->header_image_url }}" alt="header" class="absolute inset-0 w-full h-full object-cover opacity-90">
                    @endif
                </div>
                <div class="px-6 pb-6 -mt-12">
                    <div class="flex items-center gap-4">
                        <div class="w-24 h-24 rounded-full ring-4 ring-white overflow-hidden bg-white -mt-6">
                            @if($pet->profile_image_url)
                                <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('images/icon.png') }}" alt="icon" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-main-text">{{ $pet->name }}
                                <span class="text-xl font-normal {{ $pet->gender === 'male' ? 'text-blue-500' : ($pet->gender === 'female' ? 'text-pink-500' : 'text-sub-text') }}">
                                    {{ __(['male' => '♂', 'female' => '♀', 'unknown' => '?'][$pet->gender] ?? '?') }}
                                </span>
                                @if($pet->age_years !== null || $pet->age_months !== null)
                                    <span class="text-sm text-sub-text ml-1">
                                        @if($pet->age_years > 0 && $pet->age_months > 0)
                                            (推定{{ $pet->age_years }}歳{{ $pet->age_months }}ヶ月)
                                        @elseif($pet->age_years > 0)
                                            (推定{{ $pet->age_years }}歳)
                                        @elseif($pet->age_months > 0)
                                            (推定{{ $pet->age_months }}ヶ月)
                                        @endif
                                    </span>
                                @endif
                            </h1>
                            @if($pet->breed)
                                <div class="text-amber-600 font-medium">{{ $pet->breed }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <!-- 基本情報 -->
            <section class="mt-8 bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-main-text mb-4 relative inline-block">
                    <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">基本情報</span>
                    <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                </h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm text-main-text">
                    <div>
                        <dt class="text-sub-text">動物種</dt>
                        <dd class="mt-1 font-medium">{{ __('dog' === $pet->species ? '犬' : ('cat' === $pet->species ? '猫' : ('rabbit' === $pet->species ? 'うさぎ' : 'その他'))) }}</dd>
                    </div>
                    @if($pet->breed)
                        <div>
                            <dt class="text-sub-text">品種</dt>
                            <dd class="mt-1 font-medium">{{ $pet->breed }}</dd>
                        </div>
                    @endif
                    @if($pet->birth_date)
                        <div>
                            <dt class="text-sub-text">誕生日</dt>
                            <dd class="mt-1 font-medium">{{ \Carbon\Carbon::parse($pet->birth_date)->format('Y年n月j日') }}</dd>
                        </div>
                    @endif
                    @if($pet->rescue_date)
                        <div>
                            <dt class="text-sub-text">お迎え記念日</dt>
                            <dd class="mt-1 font-medium">{{ \Carbon\Carbon::parse($pet->rescue_date)->format('Y年n月j日') }}</dd>
                        </div>
                    @endif
                </dl>

                @if($pet->profile_description)
                    <div class="mt-6">
                        <dt class="text-sub-text mb-1">プロフィール</dt>
                        <dd class="prose prose-sm max-w-none text-main-text leading-relaxed">{{ $pet->profile_description }}</dd>
                    </div>
                @endif
            </section>

            <!-- 保護団体情報＆ボタン -->
            <section class="mt-8 bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-main-text mb-4 relative inline-block">
                    <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">保護団体</span>
                    <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                </h2>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-main-text">
                        <div class="font-semibold">{{ $pet->shelter->name ?? '情報なし' }}</div>
                    </div>
                    <div class="flex gap-2">
                        @if($pet->shelter && $pet->shelter->website_url)
                            <a href="{{ $pet->shelter->website_url }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 text-sm rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition font-medium">保護団体サイトへ</a>
                        @else
                            <button disabled class="px-4 py-2 text-sm rounded-full border-2 border-gray-200 text-gray-400 bg-main-bg cursor-not-allowed">保護団体サイトへ</button>
                        @endif
                        @php $hasInterview = $pet->posts()->where('type','interview')->exists(); @endphp
                        @if($hasInterview)
                            <a href="{{ route('pets.show', $pet->id) }}#interview" class="px-4 py-2 text-sm rounded-full border-2 border-pink-400 text-pink-700 bg-white hover:bg-pink-50 hover:border-pink-500 transition font-medium">しっぽのわを読む</a>
                        @else
                            <button disabled class="px-4 py-2 text-sm rounded-full border-2 border-gray-200 text-gray-400 bg-main-bg cursor-not-allowed">しっぽのわを読む</button>
                        @endif
                    </div>
                </div>
            </section>

            <!-- 関連コンテンツ（インタビュー） -->
            <section id="interview" class="mt-8 bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-main-text mb-4 relative inline-block">
                    <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">関連コンテンツ</span>
                    <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                </h2>
                @if($hasInterview)
                    <a href="#" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition font-medium">里親インタビューを読む</a>
                @else
                    <p class="text-sub-text text-sm">里親インタビューはまだ投稿されていません。</p>
                @endif
            </section>

            <!-- シェア情報 -->
            <section class="mt-8 bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-main-text mb-4 relative inline-block">
                    <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">シェア情報</span>
                    <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                </h2>
                <div class="text-sm text-main-text">
                    <p>このページは {{ $shareLink->view_count }} 回閲覧されました。</p>
                    @if($shareLink->expires_at)
                        <p>有効期限: {{ $shareLink->expires_at->format('Y年n月j日') }}</p>
                    @endif
                </div>
            </section>
        </main>
    </div>
</x-guest-layout>
