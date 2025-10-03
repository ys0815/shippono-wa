<x-guest-layout>
    <main class="w-full max-w-6xl mx-auto">
            <!-- 統合プロフィールヘッダー -->
            <section class="bg-white border border-gray-200 p-5 sm:p-8">
                <!-- 背景画像エリア -->
                <div class="relative h-36 sm:h-40 bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 mb-8 -mx-5 sm:-mx-8 -mt-5 sm:-mt-8">
                    @if($pet->header_image_url)
                        <img src="{{ $pet->header_image_url }}" alt="header" loading="lazy" decoding="async" class="absolute inset-0 w-full h-full object-cover">
                        <!-- オーバーレイ -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent"></div>
                    @endif
                    
                    <!-- アイコンを背景バナー内に配置 -->
                    <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 w-40 h-40 sm:w-44 sm:h-44 rounded-full overflow-hidden border-4 border-white shadow-lg bg-amber-100 cursor-pointer hover:shadow-xl transition-shadow duration-300" 
                         onclick="openPetImageModal()">
                        @if($pet->profile_image_url)
                            <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" loading="lazy" decoding="async" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                <span class="text-amber-600 text-4xl sm:text-5xl font-bold">{{ mb_substr($pet->name, 0, 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- プロフィール情報 -->
                <div class="pt-8">
                    <!-- ペット名と性別 -->
                    <div class="flex items-center justify-center sm:justify-start gap-4 mb-6">
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 leading-tight">
                            {{ $pet->name }} 
                            <span class="text-2xl sm:text-3xl lg:text-4xl font-normal {{ $pet->gender === 'male' ? 'text-blue-500' : ($pet->gender === 'female' ? 'text-pink-500' : 'text-gray-500') }}">
                                {{ __(['male' => '♂', 'female' => '♀', 'unknown' => '?'][$pet->gender] ?? '?') }}
                            </span>
                            @if($pet->age_years !== null || $pet->age_months !== null)
                                <span class="text-sm sm:text-base lg:text-lg text-gray-500 ml-1">
                                    @if($pet->age_years > 0 && $pet->age_months > 0)
                                        (推定{{ $pet->age_years }}歳{{ $pet->age_months }}ヶ月)
                                    @elseif($pet->age_years > 0)
                                        (推定{{ $pet->age_years }}歳)
                                    @elseif($pet->age_months > 0)
                                        (推定{{ $pet->age_months }}ヶ月)
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- 飼い主情報・SNS・いいね統合エリア -->
                    <div class="mb-8">
                        <!-- 飼い主さん名とSNSリンク -->
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                            <!-- 飼い主さん名 -->
                            <div class="text-center sm:text-left">
                                <p class="text-gray-600 text-lg leading-relaxed">
                                    <span class="font-medium text-amber-600">飼い主さん：</span>
                                    <span class="font-semibold text-gray-800">{{ $pet->user->display_name ?? $pet->user->name }}さん</span>
                                </p>
                            </div>
                            
                            <!-- SNSリンク -->
                            @if($pet->user->sns_x || $pet->user->sns_instagram || $pet->user->sns_facebook)
                                <div class="flex justify-center sm:justify-end gap-3">
                                    @if($pet->user->sns_x)
                                        <a href="{{ $pet->user->sns_x }}" target="_blank" rel="noopener noreferrer" 
                                           class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800 hover:scale-110 transition-all duration-200 shadow-lg group">
                                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($pet->user->sns_instagram)
                                        <a href="{{ $pet->user->sns_instagram }}" target="_blank" rel="noopener noreferrer" 
                                           class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full flex items-center justify-center hover:from-purple-600 hover:to-pink-600 hover:scale-110 transition-all duration-200 shadow-lg group">
                                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    @if($pet->user->sns_facebook)
                                        <a href="{{ $pet->user->sns_facebook }}" target="_blank" rel="noopener noreferrer" 
                                           class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 hover:scale-110 transition-all duration-200 shadow-lg group">
                                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <!-- いいねボタンとシェアボタン -->
                        <div class="flex justify-center sm:justify-start gap-4">
                            <!-- いいねボタン -->
                            @auth
                                @if($isLiked)
                                    <form action="{{ route('likes.destroy', $pet->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="group flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-red-400 to-pink-500 text-white rounded-full hover:from-red-500 hover:to-pink-600 hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl">
                                            <svg class="w-5 h-5 fill-current group-hover:scale-110 transition-transform duration-200" viewBox="0 0 20 20">
                                                <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                            </svg>
                                            <span class="font-semibold text-base">{{ $likeCount }}</span>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('likes.store') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="pet_id" value="{{ $pet->id }}">
                                        <button type="submit" class="group flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-amber-100 to-orange-100 text-amber-600 rounded-full hover:from-amber-200 hover:to-orange-200 hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl border border-amber-200">
                                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                            </svg>
                                            <span class="font-semibold text-base">{{ $likeCount }}</span>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <div class="flex items-center gap-3 px-6 py-3 bg-gray-100 text-gray-400 rounded-full shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                    </svg>
                                    <span class="font-semibold text-base">{{ $likeCount }}</span>
                                </div>
                            @endauth

                            <!-- シェアボタン -->
                            <button onclick="openShareModal()" class="px-6 py-3 text-sm rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition-all duration-200 font-medium shadow-sm text-center">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                </svg>
                                シェア
                            </button>
                        </div>
                    </div>

                    <!-- 区切り線 -->
                    <div class="border-t border-gray-200 mb-8"></div>

                    <!-- 基本情報 -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">基本情報</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        
                        <div class="grid grid-cols-1 gap-x-8 gap-y-4 text-base text-gray-700">
                            @if($pet->breed)
                                <div>
                                    <dt class="text-amber-600">品種</dt>
                                    <dd class="mt-1 font-medium">{{ $pet->breed }}</dd>
                                </div>
                            @endif
                            @if($pet->birth_date)
                                <div>
                                    <dt class="text-amber-600">誕生日</dt>
                                    <dd class="mt-1 font-medium">{{ \Carbon\Carbon::parse($pet->birth_date)->format('Y年n月j日') }}</dd>
                                </div>
                            @endif
                            @if($pet->rescue_date)
                                <div>
                                    <dt class="text-amber-600">お迎え記念日</dt>
                                    <dd class="mt-1 font-medium">{{ \Carbon\Carbon::parse($pet->rescue_date)->format('Y年n月j日') }}</dd>
                                </div>
                            @endif
                            @if($pet->area)
                                <div>
                                    <dt class="text-amber-600">地域</dt>
                                    <dd class="mt-1 font-medium">{{ $pet->area }}</dd>
                                </div>
                            @endif
                            @if($pet->profile_description)
                                <div>
                                    <dt class="text-amber-600">プロフィール</dt>
                                    <dd class="mt-1 font-medium">{{ $pet->profile_description }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 区切り線 -->
                    <div class="border-t border-gray-200 mb-8"></div>

                    <!-- お迎え先団体情報 -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 relative inline-block">
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">お迎え先の保護団体</span>
                            <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                        </h2>
                        
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                            <div class="text-gray-700">
                                <div class="font-semibold text-lg">{{ $pet->shelter->name ?? '情報なし' }}</div>
                            </div>
                            <div class="flex gap-3">
                                @if($pet->shelter && $pet->shelter->website_url)
                                    <a href="{{ $pet->shelter->website_url }}" target="_blank" rel="noopener noreferrer" class="px-5 py-3 text-base rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition font-medium">保護団体サイトへ</a>
                                @else
                                    <button disabled class="px-5 py-3 text-base rounded-full border-2 border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">保護団体サイトへ</button>
                                @endif
                                @php $interviewPost = $pet->posts()->where('type','interview')->where('status','published')->latest()->first(); @endphp
                                @if($interviewPost)
                                    <a href="{{ route('interviews.show', $interviewPost) }}" class="px-5 py-3 text-base rounded-full border-2 border-pink-400 text-pink-700 bg-white hover:bg-pink-50 hover:border-pink-500 transition-all duration-200 font-medium shadow-sm">しっぽのわを読む</a>
                                @else
                                    <button disabled class="px-5 py-3 text-base rounded-full border-2 border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">しっぽのわを読む</button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 区切り線 -->
                    <div class="border-t border-gray-200 mb-8"></div>
<!-- 里親インタビュー専用セクション -->
<section class="relative overflow-hidden bg-gradient-to-br from-pink-50 via-rose-50 to-pink-100 border border-pink-200 rounded-3xl p-8 mb-8 shadow-lg">
    <!-- 装飾的な背景要素 -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-pink-200 rounded-full -translate-y-16 translate-x-16 opacity-20"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 bg-rose-200 rounded-full translate-y-12 -translate-x-12 opacity-30"></div>
    
    <div class="relative z-10">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <!-- 左側：コンテンツ -->
            <div class="flex items-start lg:items-center gap-4 flex-1">
                <!-- アイコン -->
                <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-500 rounded-2xl flex items-center justify-center shadow-lg flex-shrink-0">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                
                <!-- テキストコンテンツ -->
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold text-gray-800 mb-3 leading-tight">
                        <span class="bg-gradient-to-r from-pink-600 to-rose-600 bg-clip-text text-transparent">
                            里親インタビューを読む
                        </span>
                    </h2>
                    
                    <!-- メインメッセージ -->
                    <div class="mb-4">
                        <p class="text-lg font-medium text-gray-700 mb-2">
                            『{{ $pet->name }}』と家族になった物語をのぞいてみませんか？
                        </p>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            里親さんの体験談から、出会いのあたたかさを感じてください。
                        </p>
                    </div>
                    
                    <!-- ステータス表示（未入力の場合のみ） -->
                    @if(!$interviewPost)
                        <div class="flex items-center text-sm text-gray-500 bg-gray-50 px-3 py-2 rounded-lg">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            まだインタビュー記事はありません
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- 右側：ボタン -->
            <div class="flex-shrink-0 flex justify-end lg:justify-start">
                @if($interviewPost)
                    <a href="{{ route('interviews.show', $interviewPost) }}" 
                       class="group inline-flex items-center px-8 py-4 text-lg font-semibold rounded-2xl bg-gradient-to-r from-pink-500 to-rose-500 text-white hover:from-pink-600 hover:to-rose-600 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-3 group-hover:animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        物語を読む
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @else
                    <button disabled class="inline-flex items-center px-8 py-4 text-lg font-semibold rounded-2xl bg-gray-100 text-gray-400 cursor-not-allowed">
                        <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        物語を読む
                    </button>
                @endif
            </div>
        </div>
    </div>
</section>

                    <!-- 区切り線 -->
                    <div class="border-t border-gray-200 mb-8"></div>
                    <!-- 一緒に暮らす家族 -->
                    @if($familyPets->count() > 0)
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-800 mb-6 relative inline-block">
                                <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">一緒に暮らす家族</span>
                                <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                            </h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                                @foreach($familyPets as $familyPet)
                                    <a href="{{ route('pets.show', $familyPet->id) }}" class="group">
                                        <div class="bg-gray-50 rounded-lg p-5 hover:bg-gray-100 transition">
                                            <div class="w-20 h-20 mx-auto rounded-full overflow-hidden border-2 border-amber-200 mb-4">
                                                @if($familyPet->profile_image_url)
                                                    <img src="{{ $familyPet->profile_image_url }}" alt="{{ $familyPet->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                                        <span class="text-amber-600 text-lg font-bold">{{ mb_substr($familyPet->name, 0, 2) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-center">
                                                <h3 class="font-medium text-gray-800 group-hover:text-amber-600 transition text-base">{{ $familyPet->name }}</h3>
                                                <p class="text-sm text-gray-500 leading-relaxed">
                                                    {{ __('dog' === $familyPet->species ? '犬' : ('cat' === $familyPet->species ? '猫' : ('rabbit' === $familyPet->species ? 'うさぎ' : 'その他'))) }}
                                                    <span class="{{ $familyPet->gender === 'male' ? 'text-blue-400' : ($familyPet->gender === 'female' ? 'text-pink-400' : 'text-gray-400') }}">
                                                        {{ __(['male' => '♂', 'female' => '♀', 'unknown' => '?'][$familyPet->gender] ?? '?') }}
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- 区切り線 -->
                        <div class="border-t border-gray-200 mb-8"></div>
                    @endif

                    <!-- 投稿一覧 -->
                    <div>
                        <div class="mb-8">
                            <h2 class="text-xl font-bold text-gray-800 mb-6 relative inline-block">
                                <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">今日の幸せ、シェアしよう</span>
                                <span class="absolute -bottom-1 left-0 w-16 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></span>
                            </h2>
                        </div>
                        
                        <!-- フィルター・ソート機能 -->
                        <div class="mb-6 bg-white rounded-lg border border-amber-100 p-4">
                            <div class="flex flex-row gap-4 items-center">
                                <!-- 並び順 -->
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-700 mb-1">並び順</label>
                                    <select id="sort-order" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                                        <option value="newest" selected>新着順</option>
                                        <option value="popular">人気順</option>
                                        <option value="oldest">古い順</option>
                                    </select>
                                </div>
                                
                                <!-- 期間 -->
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-700 mb-1">期間</label>
                                    <select id="time-filter" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                                        <option value="all">すべて</option>
                                        <option value="today">今日</option>
                                        <option value="week">今週</option>
                                        <option value="month">今月</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div id="posts-container" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- 投稿はJavaScriptで動的に読み込まれます -->
                        </div>
                        <div id="loading-indicator" class="text-center py-6 hidden">
                            <div class="inline-flex items-center px-5 py-3 text-base text-gray-600">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                読み込み中...
                            </div>
                        </div>
                        <div id="no-more-posts" class="text-center py-6 text-gray-500 text-base hidden">
                            これ以上投稿はありません
                        </div>
                        <div id="scroll-hint" class="text-center py-4 hidden">
                            <div class="inline-flex items-center px-4 py-2 bg-amber-50 border border-amber-200 rounded-lg text-amber-700 text-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                                スクロールして、もっと見る
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

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


        // 無限スクロール機能
        let currentPage = 1;
        let isLoading = false;
        let hasMorePosts = true;
        let currentSort = 'newest';
        let currentTimeFilter = 'all';
        let allPosts = [];
        let totalPosts = 0;

        // 初期投稿読み込み（デフォルトで5件表示）
        document.addEventListener('DOMContentLoaded', function() {
            loadPosts();
        });

        // フィルター・ソート機能のイベントリスナー
        document.getElementById('sort-order').addEventListener('change', function() {
            currentSort = this.value;
            resetAndReloadPosts();
        });

        document.getElementById('time-filter').addEventListener('change', function() {
            currentTimeFilter = this.value;
            resetAndReloadPosts();
        });


        function resetAndReloadPosts() {
            console.log('Resetting and reloading posts');
            currentPage = 1;
            hasMorePosts = true;
            allPosts = [];
            totalPosts = 0;
            document.getElementById('posts-container').innerHTML = '';
            document.getElementById('no-more-posts').classList.add('hidden');
            document.getElementById('scroll-hint').classList.add('hidden');
            loadPosts();
        }

        function refreshPostsDisplay() {
            const container = document.getElementById('posts-container');
            container.innerHTML = '';
            allPosts.forEach(post => {
                addPostToContainer(post);
            });
        }

        function updateScrollHint() {
            const scrollHint = document.getElementById('scroll-hint');
            const noMorePosts = document.getElementById('no-more-posts');
            
            console.log('Updating scroll hint:', { totalPosts, hasMorePosts, allPostsLength: allPosts.length });
            
            // 投稿総数が5件以上で、まだ読み込める投稿がある場合のみ表示
            if (totalPosts >= 5 && hasMorePosts && allPosts.length < totalPosts) {
                scrollHint.classList.remove('hidden');
                noMorePosts.classList.add('hidden');
                console.log('Scroll hint shown');
            } else {
                scrollHint.classList.add('hidden');
                if (!hasMorePosts && allPosts.length > 0) {
                    noMorePosts.classList.remove('hidden');
                    console.log('No more posts shown');
                }
            }
        }

        function loadPosts() {
            if (isLoading || !hasMorePosts) {
                console.log('loadPosts skipped:', { isLoading, hasMorePosts });
                return;
            }
            
            console.log('Loading posts - Page:', currentPage, 'Sort:', currentSort, 'TimeFilter:', currentTimeFilter);
            
            isLoading = true;
            document.getElementById('loading-indicator').classList.remove('hidden');
            
            const params = new URLSearchParams({
                page: currentPage,
                sort: currentSort,
                time_filter: currentTimeFilter
            });
            
            fetch(`/api/pets/{{ $pet->id }}/posts?${params}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Posts loaded:', data);
                    
                    // 投稿総数を取得（初回のみ）
                    if (currentPage === 1) {
                        totalPosts = data.totalPosts || 0;
                        console.log('Total posts:', totalPosts);
                    }
                    
                    if (data.posts.length === 0) {
                        hasMorePosts = false;
                        document.getElementById('no-more-posts').classList.remove('hidden');
                        console.log('No more posts available');
                    } else {
                        data.posts.forEach(post => {
                            allPosts.push(post);
                            addPostToContainer(post);
                        });
                        currentPage++;
                        hasMorePosts = data.hasMore;
                        console.log('Posts added. Current page:', currentPage, 'Has more:', hasMorePosts, 'All posts count:', allPosts.length);
                    }
                    
                    // 説明文の表示制御
                    updateScrollHint();
                })
                .catch(error => {
                    console.error('Error loading posts:', error);
                })
                .finally(() => {
                    isLoading = false;
                    document.getElementById('loading-indicator').classList.add('hidden');
                });
        }

        function addPostToContainer(post) {
            const container = document.getElementById('posts-container');
            const postElement = document.createElement('div');
            postElement.className = 'bg-gray-50 rounded-lg p-6';
            
            let mediaHtml = '';
            if (post.media.length > 0) {
                // 複数メディアのスクロール切り替えシステム
                mediaHtml = `<div class="mb-4">`;
                
                if (post.media.length === 1) {
                    // 単一メディアの場合
                    const media = post.media[0];
                    let mediaUrl = media.url;
                    console.log('Media URL:', mediaUrl, 'Type:', media.type);
                    
                    if (media.type === 'image') {
                        mediaHtml += `<div class="w-full h-80 rounded-lg overflow-hidden mb-3">
                                        <img src="${mediaUrl}" alt="${post.title}" class="w-full h-full object-cover max-h-[300px]" 
                                             onerror="console.error('Image load error:', this.src); this.style.display='none';">
                                      </div>`;
                    } else if (media.type === 'video') {
                        mediaHtml += `<div class="w-full h-80 rounded-lg overflow-hidden mb-3 relative">
                                        <video src="${mediaUrl}" class="w-full h-full object-cover" muted 
                                               onerror="console.error('Video load error:', this.src); this.style.display='none';">
                                        </video>
                                        <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30">
                                            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                      </div>`;
                    }
                } else {
                    // 複数メディアの場合 - スクロール切り替えシステム（単一投稿と同じサイズ）
                    mediaHtml += `<div class="relative">
                                    <div class="w-full h-80 rounded-lg overflow-hidden mb-3 relative">
                                        <div id="media-carousel-${post.id}" class="flex transition-transform duration-300 ease-in-out">
                                            ${post.media.map((media, index) => {
                                                let mediaUrl = media.url;
                                                if (media.type === 'image') {
                                                    return `<div class="w-full h-80 flex-shrink-0">
                                                                <img src="${mediaUrl}" alt="${post.title}" class="w-full h-full object-cover max-h-[300px]" 
                                                                     onerror="console.error('Image load error:', this.src); this.style.display='none';">
                                                            </div>`;
                                                } else if (media.type === 'video') {
                                                    return `<div class="w-full h-80 flex-shrink-0 relative">
                                                                <video src="${mediaUrl}" class="w-full h-full object-cover" muted 
                                                                       onerror="console.error('Video load error:', this.src); this.style.display='none';">
                                                                </video>
                                                                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30">
                                                                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                                        <path d="M8 5v14l11-7z"/>
                                                                    </svg>
                                                                </div>
                                                            </div>`;
                                                }
                                            }).join('')}
                                        </div>
                                        
                                        <!-- ナビゲーションボタン -->
                                        <button onclick="previousMedia(${post.id}, ${post.media.length})" 
                                                class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-70 transition-all">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                                            </svg>
                                        </button>
                                        <button onclick="nextMedia(${post.id}, ${post.media.length})" 
                                                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white rounded-full p-2 hover:bg-opacity-70 transition-all">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8.59 16.59L10 18l6-6-6-6-1.41 1.41L13.17 12z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <!-- インジケーター -->
                                    <div class="flex justify-center space-x-2 mb-3">
                                        ${post.media.map((_, index) => 
                                            `<button onclick="goToMedia(${post.id}, ${index}, ${post.media.length})" 
                                                     class="w-2 h-2 rounded-full transition-all ${index === 0 ? 'bg-amber-500' : 'bg-gray-300'}"
                                                     id="indicator-${post.id}-${index}"></button>`
                                        ).join('')}
                                    </div>
                                </div>`;
                }
                
                mediaHtml += '</div>';
            }

            let actionButtons = '';
            @auth
                if (post.user_id === {{ Auth::id() }}) {
                    actionButtons = `
                        <div class="flex gap-2">
                            <a href="/mypage/posts/${post.id}/edit" 
                               class="px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                編集
                            </a>
                            <form action="/mypage/posts/${post.id}" method="POST" class="inline">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" 
                                        class="px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600 transition"
                                        onclick="return confirm('この投稿を削除してもよろしいですか？この操作は元に戻せません。')">
                                    削除
                                </button>
                            </form>
                            <form action="/mypage/posts/${post.id}/toggle-visibility" method="POST" class="inline">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" 
                                        class="px-3 py-1 text-xs bg-yellow-500 hover:bg-yellow-600 text-white rounded transition">
                                    非公開
                                </button>
                            </form>
                        </div>
                    `;
                }
            @endauth

            postElement.innerHTML = `
                <!-- ヘッダー情報（コンパクト） -->
                <div class="flex justify-between items-center mb-3">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-white px-2 py-1 rounded" style="background-color: #f59e0b;">
                            今日の幸せ
                        </span>
                        <span class="text-xs text-gray-500">${post.created_at || '日時不明'}</span>
                    </div>
                </div>
                
                <!-- メイン画像（最優先表示） -->
                ${mediaHtml}
                
                <!-- テキスト情報（画像の下にコンパクト配置） -->
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">${post.title}</h3>
                    <!-- 投稿本文は非表示（投稿詳細ページでのみ表示） -->
                </div>
                
                <!-- ボタンエリア（水平に下揃え） -->
                <div class="mt-3 flex justify-between items-end">
                    <!-- アクションボタン（左側） -->
                    <div>
                        ${actionButtons}
                    </div>
                    
                    <!-- 続きを見るボタン（右側） -->
                    <div>
                        <button onclick="viewPostDetail(${post.id})" 
                                class="px-4 py-2 text-sm bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-lg hover:from-amber-600 hover:to-orange-600 transition-all duration-200 shadow-sm">
                            続きを見る
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(postElement);
        }

        // スクロールイベントリスナー
        window.addEventListener('scroll', function() {
            // ページの最下部に近づいたら次の投稿を読み込み
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
                if (!isLoading && hasMorePosts) {
                    loadPosts();
                }
            }
        });

        // メディア切り替え関数
        function previousMedia(postId, totalMedia) {
            const carousel = document.getElementById(`media-carousel-${postId}`);
            const currentIndex = parseInt(carousel.dataset.currentIndex || 0);
            const newIndex = currentIndex > 0 ? currentIndex - 1 : totalMedia - 1;
            
            carousel.style.transform = `translateX(-${newIndex * 100}%)`;
            carousel.dataset.currentIndex = newIndex;
            updateIndicators(postId, newIndex, totalMedia);
        }

        function nextMedia(postId, totalMedia) {
            const carousel = document.getElementById(`media-carousel-${postId}`);
            const currentIndex = parseInt(carousel.dataset.currentIndex || 0);
            const newIndex = currentIndex < totalMedia - 1 ? currentIndex + 1 : 0;
            
            carousel.style.transform = `translateX(-${newIndex * 100}%)`;
            carousel.dataset.currentIndex = newIndex;
            updateIndicators(postId, newIndex, totalMedia);
        }

        function goToMedia(postId, index, totalMedia) {
            const carousel = document.getElementById(`media-carousel-${postId}`);
            carousel.style.transform = `translateX(-${index * 100}%)`;
            carousel.dataset.currentIndex = index;
            updateIndicators(postId, index, totalMedia);
        }

        function updateIndicators(postId, currentIndex, totalMedia) {
            for (let i = 0; i < totalMedia; i++) {
                const indicator = document.getElementById(`indicator-${postId}-${i}`);
                if (indicator) {
                    if (i === currentIndex) {
                        indicator.className = 'w-2 h-2 rounded-full transition-all bg-amber-500';
                    } else {
                        indicator.className = 'w-2 h-2 rounded-full transition-all bg-gray-300';
                    }
                }
            }
        }

        // 続きを見るボタンの関数
        function viewPostDetail(postId) {
            window.location.href = `/posts/${postId}`;
        }

        // シェアモーダル制御
        function openShareModal() {
            document.getElementById('shareModal').classList.remove('hidden');
        }

        function closeShareModal() {
            document.getElementById('shareModal').classList.add('hidden');
        }

        // シェア機能
        function shareToCopy() {
            const shareUrl = window.location.href;
            
            if (navigator.clipboard && window.isSecureContext) {
                // モダンブラウザでHTTPS環境の場合
                navigator.clipboard.writeText(shareUrl).then(() => {
                    alert('URLをクリップボードにコピーしました');
                    closeShareModal();
                }).catch(err => {
                    console.error('Failed to copy URL:', err);
                    // フォールバック
                    fallbackCopyTextToClipboard(shareUrl);
                });
            } else {
                // フォールバック
                fallbackCopyTextToClipboard(shareUrl);
            }
        }
        
        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            
            // テキストエリアを画面外に配置
            textArea.style.top = '0';
            textArea.style.left = '0';
            textArea.style.position = 'fixed';
            textArea.style.opacity = '0';
            
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    alert('URLをクリップボードにコピーしました');
                    closeShareModal();
                } else {
                    alert('コピーに失敗しました。手動でコピーしてください。\n\n' + text);
                }
            } catch (err) {
                console.error('Fallback: Failed to copy URL:', err);
                alert('コピーに失敗しました。手動でコピーしてください。\n\n' + text);
            }
            
            document.body.removeChild(textArea);
        }

        function shareToX() {
            const petName = '{{ $pet->name }}';
            const shareUrl = window.location.href;
            const text = `#しっぽのわ 「${petName}」のプロフィールをチェック！\n\n${shareUrl}`;
            const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}`;
            window.open(twitterUrl, '_blank', 'width=600,height=400');
            closeShareModal();
        }

        function shareToLine() {
            const shareUrl = window.location.href;
            const lineUrl = `https://social-plugins.line.me/lineit/share?url=${encodeURIComponent(shareUrl)}`;
            window.open(lineUrl, '_blank', 'width=600,height=400');
            closeShareModal();
        }

        function shareToFacebook() {
            const url = window.location.href;
            const text = '{{ $pet->name }} - #しっぽのわ';
            
            // Facebookアプリがインストールされているかチェック
            const isFacebookInstalled = /FBAN|FBAV/i.test(navigator.userAgent) || 
                (navigator.platform === 'iPhone' || navigator.platform === 'iPad') ||
                (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
            
            if (isFacebookInstalled) {
                // Facebookアプリがインストールされている場合、アプリを開く
                const facebookAppUrl = `fb://share?link=${encodeURIComponent(url)}&quote=${encodeURIComponent(text)}`;
                window.location.href = facebookAppUrl;
                
                // フォールバック: アプリが開かない場合はブラウザで開く
                setTimeout(() => {
                    const facebookWebUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}&quote=${encodeURIComponent(text)}`;
                    window.open(facebookWebUrl, '_blank', 'width=600,height=400');
                }, 1000);
            } else {
                // Facebookアプリがインストールされていない場合、ブラウザで開く
                const facebookWebUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}&quote=${encodeURIComponent(text)}`;
                window.open(facebookWebUrl, '_blank', 'width=600,height=400');
            }
            closeShareModal();
        }

        function shareToInstagram() {
            const url = window.location.href;
            const text = '{{ $pet->name }} - #しっぽのわ';
            
            // Web Share APIを使用してInstagramの通常のシェア機能に遷移
            if (navigator.share) {
                navigator.share({
                    title: text,
                    text: text,
                    url: url
                }).then(() => {
                    console.log('Instagramシェアが成功しました');
                }).catch(() => {
                    // フォールバック: リンクをコピー
                    copyToClipboard(text, url);
                });
            } else {
                // Web Share APIが利用できない場合、リンクをコピー
                copyToClipboard(text, url);
            }
            closeShareModal();
        }
        
        function copyToClipboard(text, url) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(`${text}\n${url}`).then(() => {
                    alert('リンクをコピーしました。Instagramアプリで「リンクをシェア」して投稿してください。');
                });
            } else {
                alert('以下のリンクをコピーしてInstagramアプリで「リンクをシェア」して投稿してください。\n\n' + text + '\n' + url);
            }
        }
    </script>

    <!-- シェアモーダル -->
    <div id="shareModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
                <div class="text-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-6">シェアしよう</h3>
            
                    <div class="flex flex-wrap justify-center gap-6 mb-8">
                        <!-- リンクコピー -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToCopy()">
                            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-2 hover:bg-gray-200 transition-colors">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600">リンクコピー</span>
                        </div>
                        
                        <!-- X (旧Twitter) -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToX()">
                            <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center mb-2 hover:bg-gray-800 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600">X</span>
                        </div>
                        
                        <!-- LINE -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToLine()">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mb-2 hover:bg-green-600 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.349 0 .63.285.63.63 0 .346-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .63.285.63.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.281.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600">LINE</span>
                        </div>
                        
                        <!-- Facebook -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToFacebook()">
                            <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center mb-2 hover:bg-blue-700 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600">Facebook</span>
                        </div>
                        
                        <!-- Instagram -->
                        <div class="flex flex-col items-center cursor-pointer" onclick="shareToInstagram()">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mb-2 hover:from-purple-600 hover:to-pink-600 transition-colors">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-gray-600">Instagram</span>
                        </div>
                    </div>
                    
                    <button onclick="closeShareModal()" class="w-full py-3 px-6 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        閉じる
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ペット画像表示用モーダル -->
    <div id="pet-image-modal" class="fixed inset-0 z-[9999] hidden bg-black bg-opacity-95 flex items-center justify-center p-2 sm:p-4 overflow-y-auto" onclick="closePetImageModal(event)">
        <div class="relative w-full h-full max-w-screen-2xl max-h-screen flex items-center justify-center" onclick="event.stopPropagation()">
            <!-- 閉じるボタン -->
            <button onclick="closePetImageModal()" class="absolute top-2 right-2 sm:top-4 sm:right-4 text-white text-3xl sm:text-4xl z-10 bg-black bg-opacity-50 rounded-full w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center hover:bg-opacity-70 transition-all">
                &times;
            </button>
            
            <!-- 画像コンテナ -->
            <div class="relative w-full h-full flex items-center justify-center overflow-hidden">
                @if($pet->profile_image_url)
                    <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" class="max-w-full max-h-full object-contain" style="max-height: 90vh;">
                @else
                    <div class="w-96 h-96 bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center rounded-full">
                        <span class="text-amber-600 text-8xl font-bold">{{ mb_substr($pet->name, 0, 2) }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // ペット画像モーダル関数
        function openPetImageModal() {
            const modal = document.getElementById('pet-image-modal');
            if (modal) {
                modal.classList.remove('hidden');
                // スクロールを無効化
                document.body.style.overflow = 'hidden';
            }
        }

        function closePetImageModal(event) {
            // モーダル背景をクリックした場合のみ閉じる
            if (event && event.target !== event.currentTarget) {
                return;
            }
            
            const modal = document.getElementById('pet-image-modal');
            if (modal) {
                modal.classList.add('hidden');
                // スクロールを有効化
                document.body.style.overflow = '';
            }
        }

        // キーボード操作（ESCキーで閉じる）
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closePetImageModal();
            }
        });
    </script>
</x-guest-layout>
