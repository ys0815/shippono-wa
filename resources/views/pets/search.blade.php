<x-guest-layout>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
    <div x-data="{ sidebar:false, search:false }" class="min-h-screen bg-gray-50">

        <!-- Sidebar -->
        <div x-cloak x-show="sidebar" @keydown.escape.window="sidebar=false">
            <div class="fixed inset-0 bg-black/50 z-[1100]" @click="sidebar=false"></div>
            <aside class="fixed top-0 left-0 z-[1200] w-72 md:w-80 max-w-[85vw] h-full bg-white shadow-lg overflow-y-auto"
                   x-transition:enter="transition ease-in-out duration-300 transform"
                   x-transition:enter-start="-translate-x-full"
                   x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition ease-in-out duration-300 transform"
                   x-transition:leave-start="translate-x-0"
                   x-transition:leave-end="-translate-x-full">
                
                <!-- Header -->
                <div class="p-4 border-b bg-amber-50 border-amber-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/icon.png') }}" alt="# しっぽのわ" class="w-12 h-12">
                        <div>
                            <div class="text-lg font-bold text-gray-800"># しっぽのわ</div>
                            <div class="text-sm text-gray-600">保護動物と家族の幸せを共有</div>
                        </div>
                    </div>
                    <button @click="sidebar=false" aria-label="メニューを閉じる" 
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-amber-700 hover:bg-amber-100 focus:outline-none transition">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <!-- Menu blocks -->
                <nav class="p-4 space-y-6" aria-label="サイドバー">
                    @guest
                    <!-- ゲスト用アカウント項目 -->
                    <div>
                        <div class="text-xs font-semibold text-amber-700 mb-2">アカウント</div>
                        <div class="flex gap-2">
                            <a href="{{ route('register') }}" class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded text-gray-700 bg-white hover:bg-gray-50 text-center">新規登録</a>
                            <a href="{{ route('login') }}" class="flex-1 px-4 py-2 text-sm border border-gray-300 rounded text-gray-700 bg-white hover:bg-gray-50 text-center">ログイン</a>
                        </div>
                    </div>
                    @endguest

                    @auth
                    <!-- ログイン時ユーザー情報 -->
                    <div>
                        <div class="text-xs font-semibold text-amber-700 mb-2">ユーザー</div>
                        <div class="p-3 bg-amber-50 rounded-lg">
                            <div class="text-sm font-medium text-gray-800">{{ Auth::user()->display_name ?? Auth::user()->name }}</div>
                            <div class="text-xs text-gray-600">{{ Auth::user()->email }}</div>
                        </div>
                    </div>

                    <!-- マイページ -->
                    <div>
                        <div class="text-xs font-semibold text-amber-700 mb-2">メイン</div>
                        <ul class="space-y-1">
                            <li><a @click="sidebar=false" href="{{ route('mypage') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">マイページ</a></li>
                            <li><a @click="sidebar=false" href="{{ route('mypage.posts') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">投稿管理</a></li>
                            <li><a @click="sidebar=false" href="{{ route('mypage.pets') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">ペットプロフィール管理</a></li>
                            <li><a @click="sidebar=false" href="{{ route('mypage.likes') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">いいね一覧</a></li>
                        </ul>
                    </div>

                    <!-- 作成 -->
                    <div>
                        <div class="text-xs font-semibold text-amber-700 mb-2">作成</div>
                        <ul class="space-y-1">
                            <li><a @click="sidebar=false" href="{{ route('mypage.posts.gallery.create') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">今日の幸せを投稿</a></li>
                            <li><a @click="sidebar=false" href="{{ route('mypage.posts.interview.create') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">里親インタビューを投稿</a></li>
                            <li><a @click="sidebar=false" href="{{ route('mypage.pets.create') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">ペットを登録</a></li>
                        </ul>
                    </div>
                    @endauth

                    <!-- サイト情報 -->
                    <div>
                        <div class="text-xs font-semibold text-amber-700 mb-2">サイト</div>
                        <ul class="space-y-1">
                            <li><a @click="sidebar=false" href="/" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">#しっぽのわとは？</a></li>
                            <li><a @click="sidebar=false" href="{{ route('pets.search', 'dog') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">犬の家族を見る</a></li>
                            <li><a @click="sidebar=false" href="{{ route('pets.search', 'cat') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">猫の家族を見る</a></li>
                            <li><a @click="sidebar=false" href="{{ route('pets.search', 'rabbit') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">うさぎの家族を見る</a></li>
                            <li><a @click="sidebar=false" href="{{ route('pets.search', 'other') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">その他の家族を見る</a></li>
                            <li><a @click="sidebar=false; window.location.href='/#shelters'" href="/#shelters" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">保護団体リンク集</a></li>
                        </ul>
                    </div>

                    @auth
                    <!-- 設定 -->
                    <div>
                        <div class="text-xs font-semibold text-amber-700 mb-2">設定</div>
                        <ul class="space-y-1">
                            <li><a @click="sidebar=false" href="{{ route('profile.edit') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">プロフィール編集</a></li>
                            <li><a @click="sidebar=false" href="{{ route('mypage.profile.email') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">メールアドレス変更</a></li>
                            <li><a @click="sidebar=false" href="{{ route('mypage.profile.password') }}" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">パスワード変更</a></li>
                        </ul>
                    </div>

                    <!-- ログアウト -->
                    <div>
                        <div class="text-xs font-semibold text-amber-700 mb-2">その他</div>
                        <ul class="space-y-1">
                            <li><a @click="sidebar=false" href="#" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">ヘルプ・サポート</a></li>
                            <li><a @click="sidebar=false" href="#" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">お問い合わせ</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="flex items-center p-2 rounded text-gray-700 hover:bg-amber-50 hover:text-amber-800 transition-colors">ログアウト</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth

                    <!-- ソーシャルメディア -->
                    <div>
                        <div class="text-xs font-semibold text-amber-700 mb-2">フォロー</div>
                        <div class="flex gap-3">
                            <!-- X (旧Twitter) -->
                            <a href="#" target="_blank" rel="noopener noreferrer" 
                               class="w-8 h-8 bg-black text-white rounded-full flex items-center justify-center hover:bg-gray-800 hover:scale-110 transition-all duration-200 shadow-lg">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>
                            
                            <!-- Instagram -->
                            <a href="#" target="_blank" rel="noopener noreferrer" 
                               class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-full flex items-center justify-center hover:from-purple-600 hover:to-pink-600 hover:scale-110 transition-all duration-200 shadow-lg">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            
                            <!-- Facebook -->
                            <a href="#" target="_blank" rel="noopener noreferrer" 
                               class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 hover:scale-110 transition-all duration-200 shadow-lg">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </nav>
            </aside>
        </div>

        <!-- Search modal -->
        <div x-cloak x-show="search" @keydown.escape.window="search=false">
            <div class="fixed inset-0 bg-black/50 z-[950]" @click="search=false"></div>
            <div class="fixed top-16 right-4 left-4 sm:left-auto sm:w-[28rem] bg-white z-[960] rounded-lg shadow-xl p-4"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
                <h3 class="text-sm font-semibold text-gray-800 mb-3">絞り込み検索</h3>
                <form class="space-y-3">
                    <div>
                        <div class="text-xs text-gray-700 mb-1">動物の種類</div>
                        <div class="flex flex-wrap gap-3 text-sm">
                            <label class="flex items-center gap-1"><input type="checkbox"> 犬</label>
                            <label class="flex items-center gap-1"><input type="checkbox"> 猫</label>
                            <label class="flex items-center gap-1"><input type="checkbox"> うさぎ</label>
                            <label class="flex items-center gap-1"><input type="checkbox"> その他</label>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-700 mb-1">性別</label>
                            <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                                <option>すべて</option>
                                <option>オス</option>
                                <option>メス</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-700 mb-1">保護施設</label>
                            <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                                <option>すべて</option>
                                <option>施設A</option>
                                <option>施設B</option>
                                <option>施設C</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-amber-500 text-white text-sm rounded-md hover:bg-amber-600 transition">検索</button>
                        <button type="button" @click="search=false" class="px-4 py-2 text-sm border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">閉じる</button>
                    </div>
                </form>
            </div>
        </div>

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

            <!-- ソート機能 -->
            <div class="mb-8 bg-white rounded-lg border border-amber-100 p-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        検索結果一覧 <span class="font-semibold text-amber-600">{{ number_format($totalCount) }}</span> 件
                    </div>
                    <div class="flex items-center gap-4">
                        <label class="text-sm text-gray-700">並び順:</label>
                        <select id="sort-order" class="px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400">
                            <option value="newest" {{ $sort === 'newest' ? 'selected' : '' }}>新着順</option>
                            <option value="updated" {{ $sort === 'updated' ? 'selected' : '' }}>更新順</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>古い順</option>
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
        let currentSort = '{{ $sort }}';
        let allPets = [];
        let totalCount = {{ $totalCount }};
        let speciesName = '{{ $speciesName }}';

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
                sort: currentSort
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
