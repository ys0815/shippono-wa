<x-guest-layout>
    <div class="min-h-screen bg-gray-50">
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
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $pet->name }}
                                <span class="text-xl font-normal {{ $pet->gender === 'male' ? 'text-blue-500' : ($pet->gender === 'female' ? 'text-pink-500' : 'text-gray-500') }}">
                                    {{ __(['male' => '♂', 'female' => '♀', 'unknown' => '?'][$pet->gender] ?? '?') }}
                                </span>
                                @if($pet->age_years !== null || $pet->age_months !== null)
                                    <span class="text-sm text-gray-500 ml-1">
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
                <h2 class="text-xl font-bold text-gray-800 mb-4 relative inline-block">
                    <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">基本情報</span>
                    <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                </h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm text-gray-700">
                    <div>
                        <dt class="text-gray-500">動物種</dt>
                        <dd class="mt-1 font-medium">{{ __('dog' === $pet->species ? '犬' : ('cat' === $pet->species ? '猫' : ('rabbit' === $pet->species ? 'うさぎ' : 'その他'))) }}</dd>
                    </div>
                    @if($pet->breed)
                        <div>
                            <dt class="text-gray-500">品種</dt>
                            <dd class="mt-1 font-medium">{{ $pet->breed }}</dd>
                        </div>
                    @endif
                    @if($pet->birth_date)
                        <div>
                            <dt class="text-gray-500">誕生日</dt>
                            <dd class="mt-1 font-medium">{{ \Carbon\Carbon::parse($pet->birth_date)->format('Y年n月j日') }}</dd>
                        </div>
                    @endif
                    @if($pet->rescue_date)
                        <div>
                            <dt class="text-gray-500">お迎え記念日</dt>
                            <dd class="mt-1 font-medium">{{ \Carbon\Carbon::parse($pet->rescue_date)->format('Y年n月j日') }}</dd>
                        </div>
                    @endif
                </dl>

                @if($pet->profile_description)
                    <div class="mt-6">
                        <dt class="text-gray-500 mb-1">プロフィール</dt>
                        <dd class="prose prose-sm max-w-none text-gray-700 leading-relaxed">{{ $pet->profile_description }}</dd>
                    </div>
                @endif
            </section>

            <!-- 保護団体情報＆ボタン -->
            <section class="mt-8 bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 relative inline-block">
                    <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">保護団体</span>
                    <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                </h2>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-gray-700">
                        <div class="font-semibold">{{ $pet->shelter->name ?? '情報なし' }}</div>
                    </div>
                    <div class="flex gap-2">
                        @if($pet->shelter && $pet->shelter->website_url)
                            <a href="{{ $pet->shelter->website_url }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 text-sm rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition font-medium">保護団体サイトへ</a>
                        @else
                            <button disabled class="px-4 py-2 text-sm rounded-full border-2 border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">保護団体サイトへ</button>
                        @endif
                        @php $hasInterview = $pet->posts()->where('type','interview')->exists(); @endphp
                        @if($hasInterview)
                            <a href="{{ route('pets.show', $pet->id) }}#interview" class="px-4 py-2 text-sm rounded-full border-2 border-pink-400 text-pink-700 bg-white hover:bg-pink-50 hover:border-pink-500 transition font-medium">しっぽのわを読む</a>
                        @else
                            <button disabled class="px-4 py-2 text-sm rounded-full border-2 border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">しっぽのわを読む</button>
                        @endif
                    </div>
                </div>
            </section>

            <!-- 関連コンテンツ（インタビュー） -->
            <section id="interview" class="mt-8 bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 relative inline-block">
                    <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">関連コンテンツ</span>
                    <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                </h2>
                @if($hasInterview)
                    <a href="#" class="inline-flex items-center gap-2 px-4 py-2 rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition font-medium">里親インタビューを読む</a>
                @else
                    <p class="text-gray-500 text-sm">里親インタビューはまだ投稿されていません。</p>
                @endif
            </section>

            <!-- シェア機能 -->
            <section class="mt-8 bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 relative inline-block">
                    <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">シェア用URL発行</span>
                    <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                </h2>
                
                @auth
                    @if($pet->user_id === Auth::id())
                        <div class="space-y-4">
                            <!-- シェア用URL生成ボタン -->
                            <form action="{{ route('pets.generate-share-link', $pet) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-full hover:from-amber-600 hover:to-orange-600 transition font-medium">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                    </svg>
                                    シェア用URL発行
                                </button>
                            </form>

                            <!-- 生成されたURL表示 -->
                            @if(session('share_url'))
                                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">シェア用URL:</label>
                                    <div class="flex gap-2">
                                        <input type="text" value="{{ session('share_url') }}" readonly class="flex-1 px-3 py-2 border border-gray-300 rounded-md text-sm" id="share-url">
                                        <button onclick="copyToClipboard('share-url')" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition text-sm">
                                            コピー
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- QRコード生成ボタン -->
                            @if(session('share_url'))
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600 mb-3">QRコード:</p>
                                    <a href="{{ route('pets.qr-code', $pet) }}" target="_blank" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-purple-500 text-white rounded-full hover:bg-purple-600 transition text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                        </svg>
                                        QRコード生成
                                    </a>
                                </div>
                            @endif

                            <!-- SNS共有ボタン -->
                            @if(session('share_url'))
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600 mb-3">SNSで共有:</p>
                                    <div class="flex gap-3">
                                        <!-- Twitter -->
                                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($pet->name . 'のプロフィールをチェック！') }}&url={{ urlencode(session('share_url')) }}" 
                                           target="_blank" rel="noopener noreferrer"
                                           class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition text-sm">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                            </svg>
                                            Twitter
                                        </a>

                                        <!-- Facebook -->
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(session('share_url')) }}" 
                                           target="_blank" rel="noopener noreferrer"
                                           class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition text-sm">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                            Facebook
                                        </a>

                                        <!-- LINE -->
                                        <a href="https://social-plugins.line.me/lineit/share?url={{ urlencode(session('share_url')) }}" 
                                           target="_blank" rel="noopener noreferrer"
                                           class="flex items-center gap-2 px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition text-sm">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.349 0 .63.285.63.63 0 .346-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .63.285.63.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.281.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                                            </svg>
                                            LINE
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">このペットのシェア用URLを発行するには、ペットの所有者である必要があります。</p>
                    @endif
                @else
                    <p class="text-gray-500 text-sm">
                        <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-800">ログイン</a>してシェア用URLを発行できます。
                    </p>
                @endauth
            </section>
        </main>
    </div>

    <script>
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            element.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand('copy');
            
            // コピー完了のフィードバック
            const button = element.nextElementSibling;
            const originalText = button.textContent;
            button.textContent = 'コピー完了!';
            button.classList.add('bg-green-600', 'hover:bg-green-700');
            button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
            
            setTimeout(() => {
                button.textContent = originalText;
                button.classList.remove('bg-green-600', 'hover:bg-green-700');
                button.classList.add('bg-gray-600', 'hover:bg-gray-700');
            }, 2000);
        }
    </script>
</x-guest-layout>
