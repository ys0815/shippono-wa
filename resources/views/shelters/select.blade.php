<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">お迎え元の選択</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div x-data="shelterPicker()" x-init="init()" class="bg-white p-6 shadow sm:rounded-lg space-y-6">
                <!-- 1. 種別選択 -->
                <div>
                    <div class="text-sm text-gray-600 mb-2">1. 種別を選択</div>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="kind='facility'" :class="btn(kind==='facility')">保護団体・施設</button>
                        <button type="button" @click="kind='site'" :class="btn(kind==='site')">里親サイト</button>
                    </div>
                </div>

                <!-- 2. 所在地カテゴリ選択 -->
                <div>
                    <div class="text-sm text-gray-600 mb-2">2. 所在地カテゴリを選択</div>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="a in areas" :key="a">
                            <button type="button" @click="area=a" :class="btn(area===a)" x-text="labels[a]"></button>
                        </template>
                    </div>
                </div>

                <!-- 3. リスト表示 -->
                <div>
                    <div class="text-sm text-gray-600 mb-2">3. 該当リスト</div>
                    <template x-if="loading">
                        <div class="text-gray-500">読み込み中...</div>
                    </template>
                    <template x-if="!loading && list.length===0">
                        <div class="text-gray-500">該当がありません</div>
                    </template>
                    <ul class="divide-y">
                        <template x-for="s in list" :key="s.id">
                            <li class="py-3">
                                <div class="font-medium" x-text="s.name"></div>
                                <a class="text-sm text-blue-600" :href="s.website_url" target="_blank">公式サイト</a>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        function shelterPicker(){
            return {
                kind: 'facility',
                area: 'kanto',
                areas: ['hokkaido_tohoku','kanto','chubu_tokai','kinki','chugoku_shikoku','kyushu_okinawa','national'],
                labels: {
                    hokkaido_tohoku: '北海道・東北',
                    kanto: '関東',
                    chubu_tokai: '中部・東海',
                    kinki: '近畿',
                    chugoku_shikoku: '中国・四国',
                    kyushu_okinawa: '九州・沖縄',
                    national: '全国',
                },
                list: [],
                loading: false,
                btn(active){
                    return active ? 'px-3 py-2 rounded bg-gray-900 text-white' : 'px-3 py-2 rounded border text-gray-700';
                },
                init(){ this.fetchList(); },
                async fetchList(){
                    this.loading = true;
                    try {
                        const url = `/api/shelters?kind=${this.kind}&area=${this.area}`;
                        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                        this.list = await res.json();
                    } finally {
                        this.loading = false;
                    }
                },
                $watch: {
                    kind(){ this.fetchList(); },
                    area(){ this.fetchList(); },
                },
            }
        }
    </script>
</x-app-layout>


