<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if(isset($pet))
                ペットプロフィール編集
            @else
                ペットプロフィール管理
            @endif
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <!-- エラーメッセージ表示 -->
                @if ($errors->any())
                    <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                        <div class="font-medium mb-1">入力内容にエラーがあります：</div>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="post" action="{{ isset($pet) ? route('mypage.pets.update', $pet->id) : route('mypage.pets.store') }}" enctype="multipart/form-data" x-data="shelterPicker()" x-init="init()" class="space-y-6">
                    @csrf
                    @if(isset($pet))
                        @method('PUT')
                    @endif

                    <h3 class="font-medium text-gray-900">基本情報</h3>

                    <div>
                        <x-input-label for="name" :value="__('名前')" />
                        <x-text-input id="name" name="name" class="mt-1 block w-full" :value="old('name', $pet->name ?? '')" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="species" :value="__('ペットの種類')" />
                        <select id="species" name="species" class="mt-1 block w-full border-gray-300 rounded">
                            <option value="" x-show="list.length===0">種類を選択</option>
                            <option value="dog" {{ old('species', $pet->species ?? '') == 'dog' ? 'selected' : '' }}>犬</option>
                            <option value="cat" {{ old('species', $pet->species ?? '') == 'cat' ? 'selected' : '' }}>猫</option>
                            <option value="rabbit" {{ old('species', $pet->species ?? '') == 'rabbit' ? 'selected' : '' }}>うさぎ</option>
                            <option value="other" {{ old('species', $pet->species ?? '') == 'other' ? 'selected' : '' }}>その他</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="breed" :value="__('品種')" />
                        <x-text-input id="breed" name="breed" class="mt-1 block w-full" :value="old('breed', $pet->breed ?? '')" placeholder="例：ミックス、三毛猫、ハムスター" />
                        <x-input-error :messages="$errors->get('breed')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="gender" :value="__('性別')" />
                        <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 rounded">
                            <option value="" x-show="list.length===0">性別を選択</option>
                            <option value="male" {{ old('gender', $pet->gender ?? '') == 'male' ? 'selected' : '' }}>オス</option>
                            <option value="female" {{ old('gender', $pet->gender ?? '') == 'female' ? 'selected' : '' }}>メス</option>
                            <option value="unknown" {{ old('gender', $pet->gender ?? '') == 'unknown' ? 'selected' : '' }}>不明</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="birth_date" :value="__('誕生日')" />
                        <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date', $pet->birth_date ?? '')" />
                    </div>

                    <div>
                        <x-input-label for="estimated_age" :value="__('推定年齢')" />
                        <div class="mt-1 space-y-2">
                            <div class="flex items-center gap-2">
                                <input id="age_years" name="age_years" type="number" min="0" max="40" step="1" placeholder="0" class="block w-20 border-gray-300 rounded" :value="old('age_years', $pet->age_years ?? '')" @input="updateTotalMonths()" />
                                <span class="text-gray-600 text-sm">歳</span>
                                <input id="age_months" name="age_months" type="number" min="0" max="11" step="1" placeholder="0" class="block w-20 border-gray-300 rounded" :value="old('age_months', $pet->age_months ?? '')" @input="updateTotalMonths()" />
                                <span class="text-gray-600 text-sm">ヶ月</span>
                            </div>
                            <p class="text-xs text-gray-500">例: 1歳3ヶ月の場合は「1歳」「3ヶ月」、6ヶ月の場合は「0歳」「6ヶ月」</p>
                            <!-- 隠しフィールドで総月数を計算して保存 -->
                            <input type="hidden" id="estimated_age" name="estimated_age" :value="calculateTotalMonths()" />
                        </div>
                    </div>

                    <h3 class="font-medium text-gray-900 pt-2 border-t">お迎え情報</h3>

                    <!-- 1. 種別選択（セレクト） -->
                    <div>
                        <x-input-label for="shelter_kind" :value="__('保護施設の種別')" />
                        <select id="shelter_kind" name="shelter_kind" x-model="kind" @change="handleKindChange()" class="mt-1 block w-full border-gray-300 rounded">
                            <option value="" x-show="list.length===0">種別を選択</option>
                            <option value="facility">保護団体・施設</option>
                            <option value="site">里親サイト</option>
                            <option value="unknown">不明</option>
                        </select>
                    </div>

                    <!-- 2. 所在地カテゴリ選択（セレクト） -->
                    <div>
                        <x-input-label for="shelter_area" :value="__('保護施設の所在地')" />
                        <select id="shelter_area" name="shelter_area" x-model="area" @change="handleAreaChange()" :disabled="kind==='unknown'" class="mt-1 block w-full border-gray-300 rounded">
                            <option value="" x-show="list.length===0">所在地を選択</option>
                            <template x-for="a in filteredAreas" :key="a">
                                <option :value="a" x-text="labels[a]"></option>
                            </template>
                        </select>
                    </div>

                    <!-- 3. 施設名選択（セレクト） -->
                    <div>
                        <x-input-label for="shelter_id" :value="__('保護施設名')" />
                        <select id="shelter_id" name="shelter_id" x-model="shelterId" :disabled="kind==='unknown' || list.length===0" class="mt-1 block w-full border-gray-300 rounded">
                            <option value="">団体・施設・サイト名を選択</option>
                            <option value="" x-show="loading">読み込み中...</option>
                            <template x-for="s in list" :key="s.id">
                                <option :value="s.id" x-text="s.name"></option>
                            </template>
                        </select>
                        <p class="text-xs text-gray-500 mt-1" x-show="kind==='unknown'">※ 不明を選んだ場合は未選択のままで構いません。</p>
                    </div>

                    <div>
                        <x-input-label for="rescue_date" :value="__('お迎え記念日')" />
                        <x-text-input id="rescue_date" name="rescue_date" type="date" class="mt-1 block w-full" :value="old('rescue_date', $pet->rescue_date ?? '')" />
                    </div>

                    <h3 class="font-medium text-gray-900 pt-2 border-t">プロフィール画像</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-input-label for="profile_image" :value="__('アイコン画像')" />
                            @if(isset($pet) && $pet->profile_image_url)
                                <div class="mb-2">
                                    <img src="{{ $pet->profile_image_url }}" alt="現在のアイコン画像" class="w-20 h-20 object-cover rounded">
                                    <p class="text-xs text-gray-500">現在の画像</p>
                                </div>
                            @endif
                            <input id="profile_image" name="profile_image" type="file" accept="image/*" class="mt-1 block w-full border-gray-300 rounded" />
                            <p class="text-xs text-gray-500 mt-1">正方形推奨</p>
                        </div>
                        <div>
                            <x-input-label for="header_image" :value="__('背景画像')" />
                            @if(isset($pet) && $pet->header_image_url)
                                <div class="mb-2">
                                    <img src="{{ $pet->header_image_url }}" alt="現在の背景画像" class="w-20 h-12 object-cover rounded">
                                    <p class="text-xs text-gray-500">現在の画像</p>
                                </div>
                            @endif
                            <input id="header_image" name="header_image" type="file" accept="image/*" class="mt-1 block w-full border-gray-300 rounded" />
                            <p class="text-xs text-gray-500 mt-1">横長推奨</p>
                        </div>
                    </div>

                    <h3 class="font-medium text-gray-900 pt-2 border-t">プロフィール説明</h3>
                    <div>
                        <x-input-label for="profile_description" :value="__('自由記述')" />
                        <textarea id="profile_description" name="profile_description" rows="4" class="mt-1 block w-full border-gray-300 rounded" placeholder="この子の性格や好きなものなどを自由に書いてください">{{ old('profile_description', $pet->profile_description ?? '') }}</textarea>
                    </div>

                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center">
                            @if(isset($pet))
                                更新する
                            @else
                                登録する
                            @endif
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function shelterPicker(){
            return {
                kind: 'facility',
                area: '',
                areas: ['hokkaido_tohoku','kanto','chubu_tokai','kinki','chugoku_shikoku','kyushu_okinawa','national'],
                labels: { hokkaido_tohoku: '北海道・東北', kanto: '関東', chubu_tokai: '中部・東海', kinki: '近畿', chugoku_shikoku: '中国・四国', kyushu_okinawa: '九州・沖縄', national: '全国' },
                list: [], loading: false, shelterId: '', isInitializing: true,
                init(){ 
                    // 既存のペットデータがある場合は設定
                    @if(isset($pet))
                        @if($pet->shelter)
                            this.kind = '{{ $pet->shelter->kind }}';
                            this.area = '{{ $pet->shelter->area }}';
                            this.shelterId = '{{ $pet->shelter_id }}';
                            console.log('Pet has shelter - kind:', this.kind, 'area:', this.area, 'shelterId:', this.shelterId);
                        @else
                            this.kind = '{{ old('shelter_kind', 'facility') }}';
                            this.area = '{{ old('shelter_area', '') }}';
                            this.shelterId = '{{ old('shelter_id', '') }}';
                            console.log('Pet has no shelter - using defaults');
                        @endif
                        this.$nextTick(() => {
                            this.fetchList().then(() => {
                                // リスト取得後にshelterIdを再設定
                                this.shelterId = '{{ $pet->shelter_id ?? '' }}';
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
                        
                        // フォームの初期値を設定
                        @if(isset($pet))
                            const yearsInput = document.getElementById('age_years');
                            const monthsInput = document.getElementById('age_months');
                            if (yearsInput) {
                                yearsInput.value = {{ $pet->age_years ?? 0 }};
                                yearsInput.dispatchEvent(new Event('input'));
                            }
                            if (monthsInput) {
                                monthsInput.value = {{ $pet->age_months ?? 0 }};
                                monthsInput.dispatchEvent(new Event('input'));
                            }
                        @endif
                    });
                    
                    console.log('Initialized with kind:', this.kind, 'area:', this.area, 'shelterId:', this.shelterId);
                    console.log('Age years:', document.getElementById('age_years')?.value, 'Age months:', document.getElementById('age_months')?.value);
                    
                    // フォームの初期化を確実にするため、少し遅延して実行
                    setTimeout(() => {
                        @if(isset($pet))
                            const yearsInput = document.getElementById('age_years');
                            const monthsInput = document.getElementById('age_months');
                            if (yearsInput && yearsInput.value === '') {
                                yearsInput.value = {{ $pet->age_years ?? 0 }};
                                yearsInput.dispatchEvent(new Event('input'));
                            }
                            if (monthsInput && monthsInput.value === '') {
                                monthsInput.value = {{ $pet->age_months ?? 0 }};
                                monthsInput.dispatchEvent(new Event('input'));
                            }
                        @endif
                        
                        // 初期化完了
                        this.isInitializing = false;
                        console.log('Initialization completed');
                    }, 100);
                },
                handleKindChange() {
                    console.log('handleKindChange called - kind:', this.kind); // デバッグ用
                    // 初期化中でない場合のみshelterIdをクリア
                    if (!this.isInitializing) {
                        this.shelterId = '';
                    }
                    if (this.kind === 'site') {
                        this.area = 'national';
                        console.log('Set area to national for site'); // デバッグ用
                
                        this.$nextTick(() => {
                            console.log('About to fetch list for site'); // デバッグ用
                            this.fetchList();
                        });
                    } else if (!this.area) {
                        this.area = '';
                        this.fetchList();
                    } else {
                        this.fetchList();
                    }
                },
                handleAreaChange() {
                    // 初期化中でない場合のみshelterIdをクリア
                    if (!this.isInitializing) {
                        this.shelterId = '';
                    }
                    this.fetchList();
                },
                get filteredAreas(){ return this.kind==='site' ? ['national'] : this.areas; },
                setKind(k){ this.kind=k; if(this.kind==='site'){ this.area='national'; } this.fetchList(); },
                async fetchList(){
                    if(this.kind==='unknown'){ this.list=[]; return; }
                    if(!this.area){ this.list=[]; return; } // area が空の場合はリストを空にする
                    
                    console.log('fetchList called - kind:', this.kind, 'area:', this.area); // デバッグ用
                    this.loading = true;
                    try {
                        const url = `/api/shelters?kind=${this.kind}&area=${this.area}`;
                        console.log('Fetching:', url); // デバッグ用
                        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                        const all = await res.json();
                        console.log('API response:', all); 
                        this.list = all.filter(s => s.kind===this.kind && s.area===this.area);
                        console.log('Filtered list:', this.list); // デバッグ用
                        
                        // 既存のshelterIdがリストに存在するかチェック（初期化中の場合のみ）
                        if (this.isInitializing) {
                            const currentShelterId = '{{ $pet->shelter_id ?? '' }}';
                            if (currentShelterId && this.list.find(s => String(s.id) === String(currentShelterId))) {
                                this.shelterId = currentShelterId;
                                console.log('Set shelterId from existing data:', this.shelterId);
                            }
                        } else if (!this.list.find(s => String(s.id) === String(this.shelterId))) {
                            this.shelterId = '';
                        }
                    } finally { this.loading = false; }
                },
                calculateTotalMonths() {
                    const years = parseInt(document.getElementById('age_years')?.value || 0);
                    const months = parseInt(document.getElementById('age_months')?.value || 0);
                    return (years * 12) + months;
                },
                updateTotalMonths() {
                    const totalMonths = this.calculateTotalMonths();
                    const hiddenInput = document.getElementById('estimated_age');
                    if (hiddenInput) {
                        hiddenInput.value = totalMonths;
                    }
                },
                $watch: {
                    kind() {
                        console.log('Kind changed to:', this.kind);
                        this.shelterId = '';
                        if (this.kind === 'site') {
                            this.area = 'national';
                        }
                        this.fetchList();
                    },
                    area() {
                        console.log('Area changed to:', this.area);
                        this.shelterId = '';
                        this.fetchList();
                    }
                },
            }
        }
    </script>
</x-app-layout>


