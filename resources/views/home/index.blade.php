<x-guest-layout>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
    <div x-data="{ 
        sidebar:false, 
        search:false,
        init() {
            // ページ読み込み時にハッシュフラグメントをチェック
            this.$nextTick(() => {
                if (window.location.hash === '#shelters') {
                    setTimeout(() => {
                        const element = document.getElementById('shelters');
                        if (element) {
                            element.scrollIntoView({ 
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }, 100);
                }
            });
        }
    }" class="min-h-screen bg-gray-50">


        <!-- Hero Section - 全面画像版 -->
        <section class="w-full relative overflow-hidden" style="position: relative !important;">
            <img src="{{ asset('images/' . $heroImage) }}" 
                 alt="保護動物と家族の幸せ" 
                 class="w-full h-64 sm:h-80 md:h-96 lg:h-[400px] xl:h-[400px] 2xl:h-[400px] object-cover about-hero-image" 
                 style="height: 256px !important; max-height: 400px; object-position: center;">
            <!-- 画像下部のグラデーションオーバーレイ（重なり順を明示） -->
            <div class="absolute inset-0 pointer-events-none" style="z-index: 10 !important; background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.25) 40%, rgba(0,0,0,0) 75%) !important;"></div>
            <!-- 画像下部のテキスト（グラデーションの上に重ねる） -->
            <div class="absolute bottom-0 left-0 right-0 p-8 sm:p-10 pb-10 sm:pb-12" style="z-index: 20 !important; position: absolute !important; top: auto !important; bottom: 0 !important; left: 0 !important; right: 0 !important; width: 100% !important;">
                <div class="max-w-5xl mx-auto text-center">
                    <p class="text-white text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-4xl 2xl:text-5xl font-medium leading-relaxed drop-shadow-lg select-none mb-8">
                        保護動物と家族の幸せな毎日をみんなで分かち合う場所
                    </p>
                    <div class="flex flex-row flex-wrap gap-5 justify-center">
                        @guest
                            <a href="{{ route('login') }}" 
                               class="px-8 py-3 bg-gray-800/80 backdrop-blur-sm border border-gray-600/50 rounded-full text-white hover:bg-gray-700/80 transition-all duration-300 font-semibold text-base">
                               ログイン
                            </a>
                            <a href="{{ route('register') }}" 
                               class="px-8 py-3 bg-amber-500 hover:bg-amber-600 rounded-full text-white transition-all duration-300 font-semibold text-base shadow-lg hover:shadow-xl">
                               新規登録
                            </a>
                        @else
                            <a href="{{ route('mypage') }}" 
                               class="px-8 py-3 bg-gray-800/80 backdrop-blur-sm border border-gray-600/50 rounded-full text-white hover:bg-gray-700/80 transition-all duration-300 font-semibold text-base">
                               マイページ
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="px-8 py-3 bg-amber-500 hover:bg-amber-600 rounded-full text-white transition-all duration-300 font-semibold text-base shadow-lg hover:shadow-xl">
                                    ログアウト
                                </button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </section>

        <!-- 新着の保護動物一覧 -->
        <section class="w-full bg-white border-t border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-800 mb-2">
                        <p class="text-base text-gray-600 mb-2">かわいい家族たちの生活を見てみよう</p>
                        <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent font-sans">
                            家族になった子たち新着
                        </span>
                    </h3>
                    <div class="relative inline-block">
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-28 h-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    </div>
          
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 xl:gap-10">
                    @foreach($recentPets as $pet)
                        <div class="text-center group">
                            <!-- ペット画像（正円・大きく表示） -->
                            <div class="relative mb-4">
                                <a href="{{ route('pets.show', $pet->id) }}" class="block w-40 h-40 mx-auto rounded-full overflow-hidden border-4 border-white shadow-xl group-hover:shadow-2xl transition-shadow duration-300">
                                    @if($pet->profile_image_url)
                                        <img src="{{ $pet->profile_image_url }}" alt="{{ $pet->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                            <span class="text-amber-600 text-2xl font-bold">{{ mb_substr($pet->name, 0, 2) }}</span>
                                        </div>
                                    @endif
                                </a>
                                <!-- 装飾的な背景 -->
                                <div class="absolute -inset-4 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                            </div>
                            
                            <!-- ペット情報 -->
                            <div class="space-y-3">
                                <!-- 品種 -->
                                @if($pet->breed)
                                    <div class="text-base text-amber-600 font-medium">
                                        {{ $pet->breed }}
                                    </div>
                                @endif
                                
                                <!-- 名前・性別・推定年齢 -->
                                <div class="text-2xl font-bold text-gray-800">
                                    {{ $pet->name }} 
                                    <span class="text-xl font-normal {{ $pet->gender === 'male' ? 'text-blue-500' : ($pet->gender === 'female' ? 'text-pink-500' : 'text-gray-500') }}">
                                        {{ __(['male' => '♂', 'female' => '♀', 'unknown' => '?'][$pet->gender] ?? '?') }}
                                    </span>
                                    @if($pet->age_years !== null || $pet->age_months !== null)
                                        <span class="text-base text-gray-500 ml-1">
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
                                
                                <!-- プロフィール説明 -->
                                @if($pet->profile_description)
                                    <div class="text-base text-gray-600 leading-relaxed max-w-xs mx-auto">
                                        {{ Str::limit($pet->profile_description, 60) }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- ボタンエリア -->
                            <div class="flex gap-2 mt-6 justify-center">
                                @if($pet->shelter && $pet->shelter->website_url)
                                    <a href="{{ $pet->shelter->website_url }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="px-4 py-2 text-sm rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition-all duration-200 font-medium shadow-sm">
                                        保護団体サイトへ
                                    </a>
                                @else
                                    <button disabled class="px-4 py-2 text-sm rounded-full border-2 border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">
                                        保護団体サイトへ
                                    </button>
                                @endif
                                
                                @php
                                    $interviewPost = $pet->posts()->where('type','interview')->where('status','published')->latest()->first();
                                @endphp

                                @if($interviewPost)
                                    <a href="{{ route('interviews.show', $interviewPost) }}" 
                                       class="px-4 py-2 text-sm rounded-full border-2 border-pink-400 text-pink-700 bg-white hover:bg-pink-50 hover:border-pink-500 transition-all duration-200 font-medium shadow-sm">
                                        しっぽのわを読む
                                    </a>
                                @else
                                    <button disabled class="px-4 py-2 text-sm rounded-full border-2 border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">
                                        しっぽのわを読む
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-24">
            <!-- サービスコンセプト -->
            <section class="relative bg-gray-50 border-gray-200 py-16">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-800 mb-2">
                        <p class="text-base text-gray-600 mb-2">私たちが大切にしている想い</p>
                        <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent font-sans">
                            サービスコンセプト
                        </span>
                    </h3>
                    <div class="relative inline-block">
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-28 h-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    </div>

                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-10">
                    <div class="text-center group">
                        <div class="relative mb-8">
                            <div class="w-24 h-24 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 text-4xl group-hover:scale-110 transition-transform duration-300">
                                ❤
                            </div>
                            <div class="absolute -inset-3 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-800 mb-4">幸せの可視化</h4>
                        <p class="text-base text-gray-600 leading-relaxed">保護動物と家族の温かいストーリーを、写真と文章で心に届けます。</p>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-8">
                            <div class="w-24 h-24 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 text-4xl group-hover:scale-110 transition-transform duration-300">
                                🤝
                            </div>
                            <div class="absolute -inset-3 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-800 mb-4">共感の輪</h4>
                        <p class="text-base text-gray-600 leading-relaxed">一人ひとりの想いに触れて、優しさが広がる支援の循環を生み出します。</p>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-8">
                            <div class="w-24 h-24 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 text-4xl group-hover:scale-110 transition-transform duration-300">
                                🔗
                            </div>
                            <div class="absolute -inset-3 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-800 mb-4">命をつなぐ</h4>
                        <p class="text-base text-gray-600 leading-relaxed">施設や里親さんとの出会いを、温かく分かりやすくお手伝いします。</p>
                    </div>
                </div>
            </section>

        </main>

        <!-- 統計情報 -->
        <section class="w-full bg-white border-t border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-gray-800 mb-2">
                        <p class="text-base text-gray-600 mb-2">みんなの活動を数字で見てみよう</p>
                        <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent font-sans">
                            統計情報
                        </span>
                    </h3>
                    <div class="relative inline-block">
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-28 h-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    </div>
                  
                    @if(isset($stats['updated_at']))
                        <p class="text-sm text-gray-500 mt-2">最終更新: {{ \Carbon\Carbon::parse($stats['updated_at'])->format('Y年n月j日 H:i') }}</p>
                    @endif
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 xl:gap-8">
                    <div class="text-center group">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 group-hover:scale-110 transition-transform duration-300">
                                📝
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['posts_gallery']) }}</div>
                        <div class="text-sm text-gray-600">投稿数</div>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 group-hover:scale-110 transition-transform duration-300">
                                💬
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['posts_interview']) }}</div>
                        <div class="text-sm text-gray-600">インタビュー数</div>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 group-hover:scale-110 transition-transform duration-300">
                                🐾
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['pets']) }}</div>
                        <div class="text-sm text-gray-600">登録動物数</div>
                    </div>
                    <div class="text-center group">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 group-hover:scale-110 transition-transform duration-300">
                                🏢
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['shelters']) }}</div>
                        <div class="text-sm text-gray-600">掲載団体数</div>
                    </div>
                    <div class="text-center group col-span-2 sm:col-span-1">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 text-amber-600 group-hover:scale-110 transition-transform duration-300">
                                ❤️
                            </div>
                            <div class="absolute -inset-1 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                        </div>
                        <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($stats['likes']) }}</div>
                        <div class="text-sm text-gray-600">いいね数</div>
                    </div>
                </div>
            </div>
        </section>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-24">
            <!-- 保護団体リンク集 -->
            <section id="shelters" class="relative bg-gray-50 border-gray-200 py-12" x-data="shelterFilter()">
                <div class="text-center mb-10">
                    <h3 class="text-3xl font-bold text-gray-800 mb-2">
                        <p class="text-sm text-gray-600 mb-2">協力いただいている保護団体の皆様</p>
                        <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent font-sans">
                            保護団体リンク集
                        </span>
                    </h3>
                    <div class="relative inline-block">
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-28 h-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    </div>
            
                </div>
                
                <!-- 地域選択フィルター -->
                <div class="mb-8">
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <label for="region-filter" class="text-sm font-medium text-gray-700 whitespace-nowrap">地域別表示</label>
                        <select id="region-filter" 
                                x-model="selectedRegion" 
                                @change="filterShelters()"
                                class="px-4 py-2 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors duration-200 w-full sm:w-auto sm:min-w-[200px]">
                            <option value="all">全国</option>
                            <option value="hokkaido-tohoku">北海道・東北</option>
                            <option value="kanto">関東</option>
                            <option value="chubu-tokai">中部・東海</option>
                            <option value="kinki">近畿</option>
                            <option value="chugoku-shikoku">中国・四国</option>
                            <option value="kyushu-okinawa">九州・沖縄</option>
                        </select>
                    </div>
                    
                    <!-- フィルター結果表示 -->
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600" x-show="selectedRegion !== 'all'">
                            <span x-text="getRegionName(selectedRegion)"></span>の保護団体を表示中
                            <span x-text="'（' + filteredShelters.length + '件中' + Math.min(currentPage * itemsPerPage, filteredShelters.length) + '件表示）'"></span>
                        </p>
                        <p class="text-sm text-gray-600" x-show="selectedRegion === 'all'">
                            里親募集サイトを表示中
                            <span x-text="'（' + filteredShelters.length + '件中' + Math.min(currentPage * itemsPerPage, filteredShelters.length) + '件表示）'"></span>
                        </p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($shelters as $index => $shelter)
                        <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-lg transition-shadow duration-300 group shelter-card"
                             x-show="isVisible('{{ $shelter->prefecture ? $shelter->prefecture->name : '' }}', '{{ $shelter->kind }}') && isInCurrentPage({{ $index }})"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-95"
                             data-prefecture="{{ $shelter->prefecture ? $shelter->prefecture->name : '' }}"
                             data-kind="{{ $shelter->kind }}"
                             data-index="{{ $index }}">
                            <!-- カード全体をflexboxで縦方向に配置 -->
                            <div class="flex flex-col h-full">
                                <!-- 上部コンテンツ -->
                                <div class="flex items-start gap-4 flex-1">
                                    <!-- 団体ロゴ・アイコン -->
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center text-amber-600 text-xl group-hover:scale-110 transition-transform duration-300">
                                            🏢
                                        </div>
                                    </div>
                                    
                                    <!-- 団体情報 -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-amber-700 transition-colors">
                                            {{ $shelter->name }}
                                        </h4>
                                        
                                        <!-- 地域・種類 -->
                                        <div class="flex flex-wrap gap-2">
                                            @if($shelter->prefecture)
                                                <span class="px-2 py-1 text-xs bg-amber-100 text-amber-700 rounded-full">
                                                    {{ $shelter->prefecture->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- 下部の公式サイトボタン -->
                                <div class="mt-4 flex justify-end">
                                    @if($shelter->website_url)
                                        <a href="{{ $shelter->website_url }}" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-200 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            公式サイト
                                        </a>
                                    @else
                                        <span class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                            公式サイト
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-2xl">
                                🏢
                            </div>
                            <p class="text-gray-500">登録されている保護団体はありません</p>
                        </div>
                    @endforelse
                    
                    <!-- フィルター結果が空の場合 -->
                    <div class="col-span-full text-center py-12" x-show="selectedRegion !== 'all' && filteredShelters.length === 0">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-400 text-2xl">
                            🔍
                        </div>
                        <p class="text-gray-500" x-text="getRegionName(selectedRegion) + 'には保護団体が登録されていません'"></p>
                    </div>
                </div>
                
                <!-- もっと見るボタン -->
                <div class="text-center mt-8" x-show="shouldShowLoadMore()">
                    <button @click="loadMore()" 
                            class="px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-200 font-medium">
                        もっと見る
                    </button>
                </div>
            </section>
        </main>
        
        <!-- Footer: moved to global layouts -->
    </div>

    <script>
        // スムーズスクロール機能
        function scrollToShelters() {
            const sheltersSection = document.getElementById('shelters');
            if (sheltersSection) {
                // ヘッダーの高さを考慮してオフセットを調整
                const headerHeight = 56; // h-14 = 56px
                const offsetTop = sheltersSection.offsetTop - headerHeight - 20; // 20pxの余白
                
                // スムーズスクロール実行
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        }

        function shelterFilter() {
            return {
                selectedRegion: 'all',
                itemsPerPage: 5,
                currentPage: 1,
                filteredShelters: [],
                
                // 地域マッピング
                regionMapping: {
                    'hokkaido-tohoku': ['北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県'],
                    'kanto': ['茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県'],
                    'chubu-tokai': ['新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県', '岐阜県', '静岡県', '愛知県'],
                    'kinki': ['三重県', '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県'],
                    'chugoku-shikoku': ['鳥取県', '島根県', '岡山県', '広島県', '山口県', '徳島県', '香川県', '愛媛県', '高知県'],
                    'kyushu-okinawa': ['福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県']
                },
                
                init() {
                    // 初期化時に全件表示
                    this.updateFilteredShelters();
                },
                
                filterShelters() {
                    // フィルタリング時にページをリセット
                    this.currentPage = 1;
                    this.updateFilteredShelters();
                },
                
                updateFilteredShelters() {
                    const allCards = document.querySelectorAll('.shelter-card');
                    this.filteredShelters = Array.from(allCards).filter(card => {
                        const prefecture = card.getAttribute('data-prefecture');
                        const kind = card.getAttribute('data-kind');
                        return this.isVisible(prefecture, kind);
                    });
                },
                
                isVisible(prefectureName, shelterKind) {
                    if (this.selectedRegion === 'all') {
                        // 全国選択時は里親募集サイト（kind=site）のみ表示
                        return shelterKind === 'site';
                    }
                    
                    if (!prefectureName || prefectureName === '') {
                        return false;
                    }
                    
                    const targetPrefectures = this.regionMapping[this.selectedRegion] || [];
                    return targetPrefectures.includes(prefectureName);
                },
                
                getRegionName(regionKey) {
                    const regionNames = {
                        'all': '全国',
                        'hokkaido-tohoku': '北海道・東北',
                        'kanto': '関東',
                        'chubu-tokai': '中部・東海',
                        'kinki': '近畿',
                        'chugoku-shikoku': '中国・四国',
                        'kyushu-okinawa': '九州・沖縄'
                    };
                    return regionNames[regionKey] || '全国';
                },
                
                hasVisibleShelters() {
                    const cards = document.querySelectorAll('.shelter-card');
                    return Array.from(cards).some(card => !card.hasAttribute('style') || !card.style.display.includes('none'));
                },
                
                isInCurrentPage(index) {
                    // フィルタリング後の表示順序で判定
                    const visibleCards = this.getVisibleCards();
                    const visibleIndex = visibleCards.findIndex(card => 
                        parseInt(card.getAttribute('data-index')) === index
                    );
                    
                    if (visibleIndex === -1) return false;
                    
                    const maxIndex = this.currentPage * this.itemsPerPage - 1;
                    return visibleIndex <= maxIndex;
                },
                
                loadMore() {
                    this.currentPage++;
                },
                
                shouldShowLoadMore() {
                    const totalVisible = this.filteredShelters.length;
                    const currentlyShowing = this.currentPage * this.itemsPerPage;
                    
                    return totalVisible > currentlyShowing;
                },
                
                getVisibleCards() {
                    return this.filteredShelters;
                }
            }
        }
    </script>
</x-guest-layout>


