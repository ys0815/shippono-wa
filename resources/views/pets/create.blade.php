<x-app-layout>
    <div class="min-h-screen bg-main-bg">
        <!-- ヘッダー -->
        <div class="bg-white/90 backdrop-blur border-b border-amber-100 shadow-sm sticky top-16 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <a href="{{ route('mypage') }}" class="mr-4">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h1 class="text-lg font-semibold text-amber-900">
                            @if(isset($pet))
                                ペットプロフィール編集
                            @else
                                ペットプロフィール登録
                            @endif
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- メインコンテンツ -->
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- 温かみのあるメッセージ -->
            <div class="rounded-lg p-6 mb-8 bg-gradient-to-r from-amber-100 to-orange-200 border border-amber-300">
                <div class="flex items-center mb-4">
                    <svg class="w-10 h-10 mr-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                        <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <h2 class="text-lg font-semibold text-amber-800">大切な家族のプロフィールを作成</h2>
                </div>
                <p class="text-base leading-relaxed mb-3 text-amber-700">
                    かけがえのない家族の一員の情報を登録して、みんなでシェアしましょう。<br>
                    その子の個性や魅力を、写真と一緒に記録してみませんか？
                </p>
                <div class="flex items-center text-sm text-amber-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span>どんな小さな子も、きっと誰かの心に響く存在です。</span>
                </div>
            </div>
            <form method="post" action="{{ isset($pet) ? route('mypage.pets.update', $pet->id) : route('mypage.pets.store') }}" enctype="multipart/form-data" class="space-y-10" x-data="ShelterPicker.create({
                    kind: 'facility',
                    area: '{{ old('shelter_area', '') }}',
                    shelterId: '{{ old('shelter_id', '') }}',
                    isInitializing: true,
                    init() {
                        console.log('Alpine.js init started');
                        
                        // 既存のペットデータがある場合は設定
                        @if(isset($pet))
                            // 既存データから値を設定
                            this.kind = '{{ old('shelter_kind', $pet->shelter->kind ?? 'facility') }}';
                            this.area = '{{ old('shelter_area', $pet->shelter->area ?? '') }}';
                            this.shelterId = '{{ old('shelter_id', $pet->shelter_id ?? '') }}';
                            console.log('Pet data - kind:', this.kind, 'area:', this.area, 'shelterId:', this.shelterId);
                            
                            // 非同期でリストを取得
                            this.$nextTick(() => {
                                this.fetchList().then(() => {
                                    // リスト取得後にshelterIdを再設定
                                    this.shelterId = '{{ old('shelter_id', $pet->shelter_id ?? '') }}';
                                    console.log('After fetchList - shelterId:', this.shelterId);
                                });
                            });
                        @else
                            this.kind = '{{ old('shelter_kind', 'facility') }}';
                            this.area = '{{ old('shelter_area', '') }}';
                            this.shelterId = '{{ old('shelter_id', '') }}';
                            this.fetchList();
                        @endif
                        
                        // 年齢の初期化
                        this.$nextTick(() => {
                            // 既存の年齢データがある場合は、隠しフィールドに設定
                            @if(isset($pet) && ($pet->age_years !== null || $pet->age_months !== null))
                                const years = {{ $pet->age_years ?? 0 }};
                                const months = {{ $pet->age_months ?? 0 }};
                                const totalMonths = (years * 12) + months;
                                const hiddenInput = document.getElementById('estimated_age');
                                if (hiddenInput) {
                                    hiddenInput.value = totalMonths;
                                }
                                console.log('Set existing age - years:', years, 'months:', months, 'total:', totalMonths);
                            @else
                                this.updateTotalMonths();
                            @endif
                        });
                    }
                })" x-init="init()">
                @csrf
                @if(isset($pet))
                    @method('PUT')
                @endif

                <!-- エラーメッセージ -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-medium text-red-800">入力内容にエラーがあります</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 基本情報 -->
                <div>
                    <h3 class="text-base font-medium text-main-text mb-4">基本情報</h3>

                    <div>
                        <label for="name" class="block text-base font-medium text-main-text mb-2">名前</label>
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                🐾 その子の名前を教えてください。呼び方も含めて、親しみやすい名前で大丈夫です。
                            </p>
                        </div>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $pet->name ?? '') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                               placeholder="ペットの名前を入力">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="species" class="block text-base font-medium text-main-text mb-2 mt-6" >ペットの種類</label>
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                🐕 どのような動物でしょうか？犬、猫、うさぎなど、その子の種類を選んでください。
                            </p>
                        </div>
                        <select id="species" name="species" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <option value="">種類を選択</option>
                            <option value="dog" {{ old('species', $pet->species ?? '') == 'dog' ? 'selected' : '' }}>犬</option>
                            <option value="cat" {{ old('species', $pet->species ?? '') == 'cat' ? 'selected' : '' }}>猫</option>
                            <option value="rabbit" {{ old('species', $pet->species ?? '') == 'rabbit' ? 'selected' : '' }}>うさぎ</option>
                            <option value="other" {{ old('species', $pet->species ?? '') == 'other' ? 'selected' : '' }}>その他</option>
                        </select>
                    </div>

                    <div>
                        <label for="breed" class="block text-base font-medium text-main-text mb-2 mt-6">品種</label>
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                🎨 品種がわかる場合は教えてください。ミックスや雑種でも、その子らしさが一番大切です。
                            </p>
                        </div>
                        <input type="text" 
                               name="breed" 
                               id="breed" 
                               value="{{ old('breed', $pet->breed ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                               placeholder="例：ミックス、三毛猫、ハムスター">
                        @error('breed')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-base font-medium text-main-text mb-2 mt-6">性別</label>
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                ♂♀ 性別がわかる場合は教えてください。わからない場合は「不明」で大丈夫です。
                            </p>
                        </div>
                        <select id="gender" name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <option value="">性別を選択</option>
                            <option value="male" {{ old('gender', $pet->gender ?? '') == 'male' ? 'selected' : '' }}>オス</option>
                            <option value="female" {{ old('gender', $pet->gender ?? '') == 'female' ? 'selected' : '' }}>メス</option>
                            <option value="unknown" {{ old('gender', $pet->gender ?? '') == 'unknown' ? 'selected' : '' }}>不明</option>
                        </select>
                    </div>

                    <div>
                        <label for="birth_date" class="block text-base font-medium text-main-text mb-2 mt-6">誕生日</label>
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                🎂 誕生日がわかる場合は教えてください。わからない場合は空欄のままで大丈夫です。
                            </p>
                        </div>
                        <input type="date" 
                               name="birth_date" 
                               id="birth_date" 
                               value="{{ old('birth_date', $pet->birth_date ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div>
                        <label for="estimated_age" class="block text-base font-medium text-main-text mb-2 mt-6">推定年齢</label>
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                📅 年齢がわかる場合は教えてください。わからない場合は空欄のままで大丈夫です。
                            </p>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input id="age_years" name="age_years" type="number" min="0" max="40" step="1" placeholder="0" class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" value="{{ old('age_years', $pet->age_years ?? '') }}" @input="updateTotalMonths()" />
                                <span class="text-main-text text-sm">歳</span>
                                <input id="age_months" name="age_months" type="number" min="0" max="11" step="1" placeholder="0" class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" value="{{ old('age_months', $pet->age_months ?? '') }}" @input="updateTotalMonths()" />
                                <span class="text-main-text text-sm">ヶ月</span>
                            </div>
                            <p class="text-xs text-sub-text">例: 1歳3ヶ月の場合は「1歳」「3ヶ月」、6ヶ月の場合は「0歳」「6ヶ月」</p>
                            <!-- 隠しフィールドで総月数を計算して保存 -->
                            <input type="hidden" id="estimated_age" name="estimated_age" value="{{ old('estimated_age', isset($pet) ? ((($pet->age_years ?? 0) * 12) + ($pet->age_months ?? 0)) : '') }}" />
                        </div>
                    </div>

                </div>

                <!-- お迎え情報 -->
                <div>
                    <h3 class="text-base font-medium text-main-text mb-4">お迎え情報</h3>

                    <!-- 1. 種別選択（セレクト） -->
                    <div>
                        <label for="shelter_kind" class="block text-base font-medium text-main-text mb-2 mt-6">保護施設の種別</label>
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                🏠 お迎えした場所の種別を教えてください。わからない場合は「不明」で大丈夫です。
                            </p>
                        </div>
                        <select id="shelter_kind" name="shelter_kind" x-model="kind" @change="handleKindChange()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <option value="">種別を選択</option>
                            <option value="facility" {{ old('shelter_kind', $pet->shelter->kind ?? '') == 'facility' ? 'selected' : '' }}>保護団体・施設</option>
                            <option value="site" {{ old('shelter_kind', $pet->shelter->kind ?? '') == 'site' ? 'selected' : '' }}>里親サイト</option>
                            <option value="unknown" {{ old('shelter_kind', $pet->shelter->kind ?? '') == 'unknown' ? 'selected' : '' }}>不明</option>
                        </select>
                    </div>

                    <!-- 2. 所在地カテゴリ選択（セレクト） -->
                    <div>
                        <label for="shelter_area" class="block text-base font-medium text-main-text mb-2 mt-6">保護施設の所在地</label>
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                📍 お迎えした場所の地域を教えてください。わからない場合は空欄のままで大丈夫です。
                            </p>
                        </div>
                        <select id="shelter_area" name="shelter_area" x-model="area" @change="handleAreaChange()" :disabled="kind==='unknown'" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <option value="">所在地を選択</option>
                            <template x-for="a in filteredAreas" :key="a">
                                <option :value="a" x-text="labels[a]" :selected="a === '{{ old('shelter_area', $pet->shelter->area ?? '') }}'"></option>
                            </template>
                        </select>
                    </div>

                    <!-- 3. 施設名選択（セレクト） -->
                    <div>
                        <label for="shelter_id" class="block text-base font-medium text-main-text mb-2 mt-6">保護施設名</label>
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                🏢 お迎えした団体・施設・サイト名を教えてください。わからない場合は空欄のままで大丈夫です。
                            </p>
                        </div>
                        <select id="shelter_id" name="shelter_id" x-model="shelterId" :disabled="kind==='unknown' || list.length===0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <option value="">団体・施設・サイト名を選択</option>
                            <option value="" x-show="loading">読み込み中...</option>
                            <template x-for="s in list" :key="s.id">
                                <option :value="s.id" x-text="s.name" :selected="s.id == '{{ old('shelter_id', $pet->shelter_id ?? '') }}'"></option>
                            </template>
                        </select>
                        <p class="text-xs text-sub-text mt-1" x-show="kind==='unknown'">※ 不明を選んだ場合は未選択のままで構いません。</p>
                    </div>

                    <div>
                        <label for="rescue_date" class="block text-base font-medium text-main-text mb-2 mt-6">お迎え記念日</label>
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                💝 お迎えした日を教えてください。特別な記念日として大切に記録しましょう。
                            </p>
                        </div>
                        <input type="date" 
                               name="rescue_date" 
                               id="rescue_date" 
                               value="{{ old('rescue_date', $pet->rescue_date ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                </div>

                <!-- プロフィール画像 -->
                <div>
                    <h3 class="text-base font-medium text-main-text mb-4">プロフィール画像</h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="profile_image" class="block text-base font-medium text-main-text mb-2">アイコン画像</label>
                            <div class="p-3 mb-3" style="background-color: #fefce8;">
                                <p class="text-sm" style="color: #a16207;">
                                    📸 その子の顔がよく見える写真を選んでください。正方形の画像がおすすめです。
                                </p>
                            </div>
                            @if(isset($pet) && $pet->profile_image_url)
                                <div class="mb-2">
                                    <img src="{{ $pet->profile_image_url }}" alt="現在のアイコン画像" class="w-20 h-20 object-cover rounded">
                                    <p class="text-xs text-sub-text">現在の画像</p>
                                </div>
                            @endif
                            <input id="profile_image" name="profile_image" type="file" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" onchange="previewProfileImage(this)" />
                            <p class="text-xs text-sub-text mt-1">正方形推奨</p>
                            
                            <!-- プロフィール画像プレビュー -->
                            <div id="profile-image-preview" class="mt-4 hidden">
                                <div class="relative inline-block">
                                    <img id="profile-preview-img" src="" alt="プロフィール画像プレビュー" class="w-32 h-32 object-cover rounded-lg">
                                    <button type="button" onclick="removeProfileImage()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600 transition-colors">×</button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label for="header_image" class="block text-base font-medium text-main-text mb-2">背景画像</label>
                            <div class="p-3 mb-3" style="background-color: #fefce8;">
                                <p class="text-sm" style="color: #a16207;">
                                    🖼️ その子の魅力が伝わる写真を選んでください。横長の画像がおすすめです。
                                </p>
                            </div>
                            @if(isset($pet) && $pet->header_image_url)
                                <div class="mb-2">
                                    <img src="{{ $pet->header_image_url }}" alt="現在の背景画像" class="w-20 h-12 object-cover rounded">
                                    <p class="text-xs text-sub-text">現在の画像</p>
                                </div>
                            @endif
                            <input id="header_image" name="header_image" type="file" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" onchange="previewHeaderImage(this)" />
                            <p class="text-xs text-sub-text mt-1">横長推奨</p>
                            
                            <!-- 背景画像プレビュー -->
                            <div id="header-image-preview" class="mt-4 hidden">
                                <div class="relative">
                                    <img id="header-preview-img" src="" alt="背景画像プレビュー" class="w-full h-32 object-cover rounded-lg">
                                    <button type="button" onclick="removeHeaderImage()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600 transition-colors">×</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- プロフィール説明 -->
                <div>
                    <h3 class="text-base font-medium text-main-text mb-4">プロフィール説明</h3>
                    <div>
                        <label for="profile_description" class="block text-base font-medium text-main-text mb-2">自由記述</label>
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                💝 その子の性格や好きなもの、エピソードなどを自由に書いてください。きっと誰かの心に響きます。
                            </p>
                        </div>
                        <textarea id="profile_description" 
                                  name="profile_description" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" 
                                  placeholder="この子の性格や好きなものなどを自由に書いてください">{{ old('profile_description', $pet->profile_description ?? '') }}</textarea>
                    </div>
                </div>

                <!-- ボタン -->
                <div class="bg-main-bg rounded-lg p-4 mb-6">
                    <div class="text-center mb-4">
                        <p class="text-sm text-main-text">
                            💝 大切な家族の一員のプロフィールを完成させましょう。
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('mypage') }}" class="btn btn-outline flex-1 justify-center">
                            キャンセル
                        </a>
                        <button type="submit" class="btn btn-brand flex-1 justify-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            @if(isset($pet))
                                更新する
                            @else
                                登録する
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // 年齢計算のヘルパー関数
        function calculateTotalMonths() {
            const years = parseInt(document.getElementById('age_years')?.value || 0);
            const months = parseInt(document.getElementById('age_months')?.value || 0);
            return (years * 12) + months;
        }
        
        function updateTotalMonths() {
            const totalMonths = calculateTotalMonths();
            const hiddenInput = document.getElementById('estimated_age');
            if (hiddenInput) {
                hiddenInput.value = totalMonths;
            }
        }
        
        // プロフィール画像プレビュー
        function previewProfileImage(input) {
            const preview = document.getElementById('profile-image-preview');
            const previewImg = document.getElementById('profile-preview-img');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }
        
        function removeProfileImage() {
            const input = document.getElementById('profile_image');
            const preview = document.getElementById('profile-image-preview');
            const previewImg = document.getElementById('profile-preview-img');
            
            input.value = '';
            previewImg.src = '';
            preview.classList.add('hidden');
        }
        
        // 背景画像プレビュー
        function previewHeaderImage(input) {
            const preview = document.getElementById('header-image-preview');
            const previewImg = document.getElementById('header-preview-img');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }
        
        function removeHeaderImage() {
            const input = document.getElementById('header_image');
            const preview = document.getElementById('header-image-preview');
            const previewImg = document.getElementById('header-preview-img');
            
            input.value = '';
            previewImg.src = '';
            preview.classList.add('hidden');
        }
        
        // 年齢入力フィールドのイベントリスナー
        document.addEventListener('DOMContentLoaded', function() {
            const yearsInput = document.getElementById('age_years');
            const monthsInput = document.getElementById('age_months');
            
            if (yearsInput) {
                yearsInput.addEventListener('input', updateTotalMonths);
            }
            if (monthsInput) {
                monthsInput.addEventListener('input', updateTotalMonths);
            }
        });
    </script>
</x-app-layout>


