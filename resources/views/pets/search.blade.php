<x-guest-layout>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>

    <main class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- ページヘッダー -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-bold text-gray-800 mb-3 relative inline-block">
                    <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent font-sans">
                        {{ $speciesName }}の家族
                    </span>
                    <div class="absolute -bottom-1.5 left-1/2 transform -translate-x-1/2 w-24 h-1.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                </h1>
                <p class="text-sm text-gray-600">かわいい{{ $speciesName }}の家族たちがあなたを待っています</p>
            </div>

            <!-- 検索条件表示 -->
            @if(isset($searchParams) && array_filter($searchParams))
            <div class="mb-6 bg-amber-50 rounded-lg border border-amber-200 p-4">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">現在の検索条件</h3>
                <div class="flex flex-wrap gap-2">
                    @if(isset($searchParams['species']) && $searchParams['species'])
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            動物の種類: {{ $speciesNames[$searchParams['species']] ?? $searchParams['species'] }}
                        </span>
                    @endif
                    @if(isset($searchParams['gender']) && $searchParams['gender'])
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            性別: {{ $searchParams['gender'] === 'male' ? 'オス' : ($searchParams['gender'] === 'female' ? 'メス' : '不明') }}
                        </span>
                    @endif
                    @if(isset($searchParams['shelter_kind']) && $searchParams['shelter_kind'])
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            施設種別: {{ $searchParams['shelter_kind'] === 'facility' ? '保護団体・施設' : ($searchParams['shelter_kind'] === 'site' ? '里親サイト' : '不明') }}
                        </span>
                    @endif
                    @if(isset($searchParams['shelter_area']) && $searchParams['shelter_area'])
                        @php
                            $areaLabels = [
                                'hokkaido_tohoku' => '北海道・東北',
                                'kanto' => '関東',
                                'chubu_tokai' => '中部・東海',
                                'kinki' => '近畿',
                                'chugoku_shikoku' => '中国・四国',
                                'kyushu_okinawa' => '九州・沖縄',
                                'national' => '全国'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            所在地: {{ $areaLabels[$searchParams['shelter_area']] ?? $searchParams['shelter_area'] }}
                        </span>
                    @endif
                    @if(isset($searchParams['shelter_id']) && $searchParams['shelter_id'])
                        @php
                            $shelter = \App\Models\Shelter::find($searchParams['shelter_id']);
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                            施設名: {{ $shelter->name ?? '不明' }}
                        </span>
                    @endif
                </div>
                <div class="mt-3">
                    <a href="{{ route('pets.search', 'all') }}" class="text-sm text-amber-600 hover:text-amber-700 underline">
                        検索条件をクリア
                    </a>
                </div>
            </div>
            @endif

            <!-- ソート機能 -->
            <div class="mb-8 bg-white rounded-lg border border-amber-100 p-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        検索結果一覧 <span class="font-semibold text-amber-600">{{ number_format($totalCount) }}</span> 件
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="text-sm text-gray-700">並び順:</label>
                        <select id="sort-order" class="px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                            <option value="newest" {{ $filters['sort'] === 'newest' ? 'selected' : '' }}>新着順</option>
                            <option value="updated" {{ $filters['sort'] === 'updated' ? 'selected' : '' }}>更新順</option>
                            <option value="oldest" {{ $filters['sort'] === 'oldest' ? 'selected' : '' }}>古い順</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ペット一覧 -->
            <div id="pets-container" class="flex flex-wrap gap-8 justify-center items-start">
                <!-- JavaScriptで動的に生成されるペットカード -->
            </div>

            <!-- ローディングインジケーター -->
            <div id="loading-indicator" class="text-center py-6 hidden">
                <div class="inline-flex items-center px-5 py-3 text-base text-gray-600">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    読み込み中...
                </div>
            </div>

            <!-- これ以上ないメッセージ -->
            <div id="no-more-pets" class="text-center py-6 text-gray-500 text-base hidden">
                これ以上ペットはいません
            </div>

            <!-- スクロールヒント -->
            <div id="scroll-hint" class="text-center py-4 hidden">
                <div class="inline-flex items-center px-4 py-2 bg-amber-50 border border-amber-200 rounded-lg text-amber-700 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    スクロールして、もっと見る
                </div>
            </div>
        </main>
    </div>

    <script>
        // 無限スクロール機能
        let currentPage = 1;
        let isLoading = false;
        let hasMorePets = true;
        let currentSort = '{{ $filters['sort'] }}';
        let allPets = [];
        let totalCount = {{ $totalCount }};
        let speciesName = '{{ $speciesName }}';
        
        // 検索パラメータ
        let searchParams = @json($searchParams ?? []);

        // ソート機能のイベントリスナー
        document.getElementById('sort-order').addEventListener('change', function() {
            currentSort = this.value;
            resetAndReloadPets();
        });

        function resetAndReloadPets() {
            currentPage = 1;
            hasMorePets = true;
            allPets = [];
            document.getElementById('pets-container').innerHTML = '';
            document.getElementById('no-more-pets').classList.add('hidden');
            document.getElementById('scroll-hint').classList.add('hidden');
            document.getElementById('loading-indicator').classList.add('hidden');
            loadPets();
        }

        function updateScrollHint() {
            const scrollHint = document.getElementById('scroll-hint');
            const noMorePets = document.getElementById('no-more-pets');
            
            if (totalCount >= 5 && hasMorePets && allPets.length < totalCount) {
                scrollHint.classList.remove('hidden');
                noMorePets.classList.add('hidden');
            } else {
                scrollHint.classList.add('hidden');
            }
        }

        function loadPets() {
            if (isLoading || !hasMorePets) return;
            
            isLoading = true;
            document.getElementById('loading-indicator').classList.remove('hidden');
            
            const params = new URLSearchParams({
                page: currentPage,
                sort: currentSort,
                ...searchParams
            });
            
            fetch(`/api/pets/search/{{ $species }}?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (data.pets.length === 0) {
                        hasMorePets = false;
                        document.getElementById('no-more-pets').classList.remove('hidden');
                    } else {
                        data.pets.forEach(pet => {
                            addPetToContainer(pet);
                        });
                        currentPage++;
                        hasMorePets = data.hasMore;
                    }
                    
                    updateScrollHint();
                })
                .catch(error => {
                    console.error('Error loading pets:', error);
                })
                .finally(() => {
                    isLoading = false;
                    document.getElementById('loading-indicator').classList.add('hidden');
                });
        }

        function addPetToContainer(pet) {
            // 重複チェック
            if (allPets.some(existingPet => existingPet.id === pet.id)) {
                return;
            }
            
            const container = document.getElementById('pets-container');
            const petElement = document.createElement('div');
            petElement.className = 'text-center group w-80 flex-shrink-0';
            
            const genderIcon = pet.gender === 'male' ? '♂' : (pet.gender === 'female' ? '♀' : '?');
            const genderColor = pet.gender === 'male' ? 'text-blue-500' : (pet.gender === 'female' ? 'text-pink-500' : 'text-gray-500');
            
            let ageDisplay = '';
            if (pet.age_years && pet.age_months && pet.age_years > 0 && pet.age_months > 0) {
                ageDisplay = `(推定${pet.age_years}歳${pet.age_months}ヶ月)`;
            } else if (pet.age_years && pet.age_years > 0) {
                ageDisplay = `(推定${pet.age_years}歳)`;
            } else if (pet.age_months && pet.age_months > 0) {
                ageDisplay = `(推定${pet.age_months}ヶ月)`;
            } else if (pet.estimated_age) {
                ageDisplay = `(推定${pet.estimated_age}ヶ月)`;
            }
            
            petElement.innerHTML = `
                <!-- ペット画像（正円・大きく表示） -->
                <div class="relative mb-4">
                    <a href="/pets/${pet.id}" class="block w-40 h-40 mx-auto rounded-full overflow-hidden border-4 border-white shadow-xl group-hover:shadow-2xl transition-shadow duration-300">
                        ${pet.profile_image_url ? 
                            `<img src="${pet.profile_image_url}" alt="${pet.name}" class="w-full h-full object-cover">` :
                            `<div class="w-full h-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                <span class="text-amber-600 text-2xl font-bold">${pet.name.substring(0, 2)}</span>
                            </div>`
                        }
                    </a>
                    <!-- 装飾的な背景 -->
                    <div class="absolute -inset-4 bg-gradient-to-br from-amber-200 to-orange-200 rounded-full opacity-20 group-hover:opacity-30 transition-opacity duration-300 -z-10"></div>
                </div>
                
                <!-- ペット情報 -->
                <div class="space-y-2">
                    <!-- 品種 -->
                    ${pet.breed ? `
                        <div class="text-sm text-amber-600 font-medium">
                            ${pet.breed}
                        </div>
                    ` : ''}
                    
                    <!-- 名前・性別・推定年齢 -->
                    <div class="text-xl font-bold text-gray-800">
                        ${pet.name} 
                        <span class="text-lg font-normal ${genderColor}">
                            ${genderIcon}
                        </span>
                        ${ageDisplay ? `<span class="text-sm text-gray-500 ml-1">${ageDisplay}</span>` : ''}
                    </div>
                    
                    <!-- プロフィール説明 -->
                    ${pet.profile_description ? `
                        <div class="text-sm text-gray-600 leading-relaxed max-w-xs mx-auto">
                            ${pet.profile_description.length > 60 ? pet.profile_description.substring(0, 60) + '...' : pet.profile_description}
                        </div>
                    ` : ''}
                </div>
                
                <!-- ボタンエリア -->
                <div class="flex gap-2 mt-6 justify-center">
                    ${pet.shelter && pet.shelter.website_url ? 
                        `<a href="${pet.shelter.website_url}" target="_blank" rel="noopener noreferrer" 
                           class="px-4 py-2 text-sm rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition-all duration-200 font-medium shadow-sm">
                            保護団体サイトへ
                        </a>` :
                        `<button disabled class="px-4 py-2 text-sm rounded-full border-2 border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">
                            保護団体サイトへ
                        </button>`
                    }
                    
                    ${pet.interview_post ? 
                        `<a href="/interviews/${pet.interview_post.id}" 
                           class="px-4 py-2 text-sm rounded-full border-2 border-pink-400 text-pink-700 bg-white hover:bg-pink-50 hover:border-pink-500 transition-all duration-200 font-medium shadow-sm">
                            しっぽのわを読む
                        </a>` :
                        `<button disabled class="px-4 py-2 text-sm rounded-full border-2 border-gray-200 text-gray-400 bg-gray-50 cursor-not-allowed">
                            しっぽのわを読む
                        </button>`
                    }
                </div>
            `;
            
            container.appendChild(petElement);
            allPets.push(pet);
        }

        // スクロールイベントリスナー
        window.addEventListener('scroll', function() {
            if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
                if (!isLoading && hasMorePets) {
                    loadPets();
                }
            }
        });

        // ページ読み込み時に初期データを読み込み
        document.addEventListener('DOMContentLoaded', function() {
            // 初期表示用のペットデータを設定
            const initialPets = {!! json_encode($pets->map(function($pet) {
                // インタビューポストを取得
                $interviewPost = $pet->posts()
                    ->where('type', 'interview')
                    ->where('status', 'published')
                    ->latest()
                    ->first();

                return [
                    'id' => $pet->id,
                    'name' => $pet->name,
                    'species' => $pet->species,
                    'gender' => $pet->gender,
                    'breed' => $pet->breed,
                    'age_years' => $pet->age_years,
                    'age_months' => $pet->age_months,
                    'estimated_age' => $pet->estimated_age,
                    'profile_description' => $pet->profile_description,
                    'profile_image_url' => $pet->profile_image_url,
                    'user' => [
                        'name' => $pet->user->display_name ?? $pet->user->name,
                    ],
                    'shelter' => $pet->shelter ? [
                        'name' => $pet->shelter->name,
                        'area' => $pet->shelter->area,
                        'website_url' => $pet->shelter->website_url,
                    ] : null,
                    'interview_post' => $interviewPost ? [
                        'id' => $interviewPost->id,
                        'title' => $interviewPost->title,
                    ] : null,
                    'created_at' => $pet->created_at->setTimezone('Asia/Tokyo')->format('Y年n月j日'),
                    'updated_at' => $pet->updated_at->setTimezone('Asia/Tokyo')->format('Y年n月j日'),
                ];
            }), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) !!};
            
            // 初期データを表示
            initialPets.forEach(pet => {
                addPetToContainer(pet);
            });
            
            // 次のページから読み込み開始
            currentPage = 2;
            
            // 初期表示時のスクロールヒント更新
            updateScrollHint();
        });
    </script>
</x-guest-layout>
