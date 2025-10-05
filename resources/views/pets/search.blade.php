<x-guest-layout>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- ページヘッダー -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-main-text mb-3 relative inline-block">
                <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent font-sans">
                    {{ $speciesName }}の家族
                </span>
                <div class="w-20 h-1 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full mx-auto mt-2"></div>
            </h1>
            <p class="text-sm text-main-text">新しい家族のもとで、今日も元気に暮らしています！</p>
        </div>

        <!-- 検索条件表示 -->
        @if(isset($searchParams) && array_filter($searchParams))
        <div class="mb-6 bg-amber-50 rounded-lg border border-amber-200 p-4">
            <h3 class="text-sm font-semibold text-main-text mb-3">現在の検索条件</h3>
            <div class="flex flex-wrap gap-2">
                @if(isset($searchParams['species']) && $searchParams['species'])
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                        動物の種類: {{ $speciesNames[$searchParams['species']] ?? $searchParams['species'] }}
                    </span>
                @endif
                @if(isset($searchParams['gender']) && $searchParams['gender'])
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                        性別: {{ $genderNames[$searchParams['gender']] ?? $searchParams['gender'] }}
                    </span>
                @endif
                @if(isset($searchParams['shelter_kind']) && $searchParams['shelter_kind'])
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                        保護施設の種別: {{ $shelterKindNames[$searchParams['shelter_kind']] ?? $searchParams['shelter_kind'] }}
                    </span>
                @endif
                @if(isset($searchParams['shelter_area']) && $searchParams['shelter_area'])
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                        保護施設の所在地: {{ $areaNames[$searchParams['shelter_area']] ?? $searchParams['shelter_area'] }}
                    </span>
                @endif
                @if(isset($searchParams['shelter_id']) && $searchParams['shelter_id'])
                    @php
                        $shelter = \App\Models\Shelter::find($searchParams['shelter_id']);
                    @endphp
                    @if($shelter)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            保護施設名: {{ $shelter->name }}
                        </span>
                    @endif
                @endif
            </div>
        </div>
        @endif

        <!-- ソート機能 -->
        <div class="mb-8 bg-white rounded-lg border border-amber-100 p-4">
            <div class="flex items-center justify-between gap-4">
                <div class="text-sm text-main-text">
                    検索結果一覧 <span class="font-semibold text-amber-600">{{ number_format($totalCount) }}</span> 件
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-main-text whitespace-nowrap">並び順:</label>
                    <select id="sort-order" class="px-3 py-2 text-sm border border-sub-border rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 min-w-[120px]">
                        <option value="newest" {{ $filters['sort'] === 'newest' ? 'selected' : '' }}>新着順</option>
                        <option value="updated" {{ $filters['sort'] === 'updated' ? 'selected' : '' }}>更新順</option>
                        <option value="oldest" {{ $filters['sort'] === 'oldest' ? 'selected' : '' }}>古い順</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- ペット一覧 -->
        <div id="pets-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- JavaScriptで動的に生成されるペットカード -->
        </div>

        

        <!-- ローディングインジケーター -->
        <div id="loading-indicator" class="text-center py-6 hidden">
            <div class="inline-flex items-center px-5 py-3 text-base text-main-text">
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-main-text" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                読み込み中...
            </div>
        </div>

        <!-- これ以上ないメッセージ -->
        <div id="no-more-pets" class="text-center py-6 text-sub-text text-base hidden">
            これ以上ペットはいません
        </div>

        <!-- スクロールヒント -->
        <div id="scroll-hint" class="text-center py-4 hidden">
            <div class="inline-flex items-center px-4 py-2 bg-amber-50 border border-amber-300 rounded-lg text-amber-800 text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
                スクロールして、もっと見る
            </div>
        </div>
    </main>

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

        // ペットデータをリセットして再読み込み
        function resetAndReloadPets() {
            currentPage = 1;
            allPets = [];
            hasMorePets = true;
            document.getElementById('pets-container').innerHTML = '';
            document.getElementById('no-more-pets').classList.add('hidden');
            loadPets();
        }

        // ペットデータを読み込み
        function loadPets() {
            if (isLoading || !hasMorePets) return;
            
            isLoading = true;
            document.getElementById('loading-indicator').classList.remove('hidden');
            
            const params = new URLSearchParams({
                page: currentPage,
                sort: currentSort,
                ...searchParams
            });
            
            fetch(`/pets/search/{{ $species }}?${params}`, { headers: { 'Accept': 'application/json' } })
                .then(async response => {
                    const contentType = response.headers.get('content-type') || '';
                    if (!response.ok || !contentType.includes('application/json')) {
                        throw new Error(`Unexpected response (${response.status})`);
                    }
                    return response.json();
                })
                .then(data => {
                    // 件数の更新（可能ならサーバ値を採用）
                    if (typeof data.totalCount === 'number') {
                        totalCount = data.totalCount;
                    } else if (typeof data.total === 'number') {
                        totalCount = data.total;
                    }

                    if (data.pets && data.pets.length > 0) {
                        data.pets.forEach(pet => {
                            addPetToContainer(pet);
                        });
                        currentPage++;
                        
                        // ページサイズの推定（サーバが返す値があれば優先）
                        const pageSize = typeof data.per_page === 'number' ? data.per_page : (typeof data.pageSize === 'number' ? data.pageSize : 8);
                        if (data.pets.length < pageSize || (typeof totalCount === 'number' && allPets.length >= totalCount)) {
                            hasMorePets = false;
                            document.getElementById('no-more-pets').classList.remove('hidden');
                        }
                    } else {
                        hasMorePets = false;
                        if (allPets.length === 0) {
                            document.getElementById('no-more-pets').classList.remove('hidden');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error loading pets:', error);
                    // 非表示にならないよう最低限の復旧
                    hasMorePets = false;
                    if (allPets.length === 0) {
                        document.getElementById('no-more-pets').classList.remove('hidden');
                    }
                })
                .finally(() => {
                    isLoading = false;
                    document.getElementById('loading-indicator').classList.add('hidden');
                    updateScrollHint();
                });
        }

        // ペットカードをコンテナに追加
        function addPetToContainer(pet) {
            // 重複チェック
            if (allPets.some(existingPet => existingPet.id === pet.id)) {
                return;
            }
            
            // デバッグ用：ペットの完全なデータ構造をログ出力
            console.log(`Pet ${pet.name} full data:`, pet);
            console.log(`Pet ${pet.name} interview_post:`, pet.interview_post);
            
            const container = document.getElementById('pets-container');
            const petElement = document.createElement('div');
            petElement.className = 'text-center group bg-white rounded-xl shadow-sm border border-main-border p-6 hover:shadow-md transition-shadow duration-300';
            
            const genderIcon = pet.gender === 'male' ? '♂' : (pet.gender === 'female' ? '♀' : '?');
            const genderColor = pet.gender === 'male' ? 'text-blue-500' : (pet.gender === 'female' ? 'text-pink-500' : 'text-sub-text');
            
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
                <!-- ペット画像 -->
                <div class="relative mb-4">
                    <a href="/pets/${pet.id}" class="block w-32 h-32 mx-auto rounded-full overflow-hidden border-2 border-amber-400 shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                        ${pet.profile_image_url ? 
                            `<img src="${pet.profile_image_url}" alt="${pet.name}" loading="lazy" decoding="async" class="w-full h-full object-cover">` :
                            `<div class="w-full h-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                <span class="text-amber-600 text-xl font-bold">${pet.name.substring(0, 2)}</span>
                            </div>`
                        }
                    </a>
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
                    <div class="text-xl font-bold text-main-text">
                        ${pet.name} 
                        <span class="text-lg font-normal ${genderColor}">
                            ${genderIcon}
                        </span>
                        ${ageDisplay ? `<span class="text-sm text-sub-text ml-1">${ageDisplay}</span>` : ''}
                    </div>
                    
                    <!-- プロフィール説明 -->
                    ${pet.profile_description ? `
                        <div class="text-sm text-main-text leading-relaxed max-w-xs mx-auto">
                            ${pet.profile_description.length > 60 ? pet.profile_description.substring(0, 60) + '...' : pet.profile_description}
                        </div>
                    ` : ''}
                </div>
                
                <!-- ボタンエリア -->
                <div class="flex gap-2 mt-6 justify-center">
                    ${pet.shelter && pet.shelter.website_url ? 
                        `<a href="${pet.shelter.website_url}" target="_blank" rel="noopener noreferrer" 
                           class="px-4 py-2 text-sm rounded-full border-2 border-amber-400 text-amber-700 bg-white hover:bg-amber-50 hover:border-amber-500 transition-all duration-200 font-medium shadow-sm text-center">
                            保護団体サイトへ
                        </a>` :
                        `<button disabled class="px-4 py-2 text-sm rounded-full border-2 border-main-border text-gray-400 bg-main-bg cursor-not-allowed text-center">
                            保護団体サイトへ
                        </button>`
                    }
                    
                    ${(pet.interview_post && pet.interview_post.id && pet.interview_post.id !== null) ? 
                        `<a href="/interviews/${pet.interview_post.id}" 
                           class="px-4 py-2 text-sm rounded-full border-2 border-pink-400 text-pink-700 bg-white hover:bg-pink-50 hover:border-pink-500 transition-all duration-200 font-medium shadow-sm text-center">
                             お迎え体験を読む
                        </a>` :
                        `<button disabled class="px-4 py-2 text-sm rounded-full border-2 border-main-border text-gray-400 bg-main-bg cursor-not-allowed text-center">
                            お迎え体験を読む
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

        // スクロールヒントの更新
        function updateScrollHint() {
            const scrollHint = document.getElementById('scroll-hint');
            // 表示できる内容が残っている場合のみスクロールヒントを表示
            if (hasMorePets && allPets.length > 0 && allPets.length < totalCount) {
                scrollHint.classList.remove('hidden');
            } else {
                scrollHint.classList.add('hidden');
            }
        }

        // 初期データを読み込み
        document.addEventListener('DOMContentLoaded', function() {
            const initialPets = @json($pets ?? []);
            
            // デバッグ用：初期データの構造を確認
            console.log('Initial pets data:', initialPets);
            
            // 初期データを表示
            initialPets.forEach(pet => {
                addPetToContainer(pet);
            });
            
            // 次のページから読み込み開始
            currentPage = 2;
            
            // 初期表示時のスクロールヒント更新
            // 初期表示件数が総件数より少ない場合のみスクロールヒントを表示
            updateScrollHint();
        });

        
    </script>
</x-guest-layout>