/**
 * ShelterPicker - 保護団体選択の共通Alpine.jsコンポーネント
 * 用途: 検索モーダル、ペット登録フォーム、その他の保護団体選択機能
 */
window.ShelterPicker = {
    // 共通データ
    data: {
        areas: [
            "hokkaido_tohoku",
            "kanto",
            "chubu_tokai",
            "kinki",
            "chugoku_shikoku",
            "kyushu_okinawa",
            "national",
        ],
        labels: {
            hokkaido_tohoku: "北海道・東北",
            kanto: "関東",
            chubu_tokai: "中部・東海",
            kinki: "近畿",
            chugoku_shikoku: "中国・四国",
            kyushu_okinawa: "九州・沖縄",
            national: "全国",
        },
    },

    /**
     * ShelterPickerインスタンスを作成
     * @param {Object} options - オプション設定
     * @param {string} options.kind - 初期種別 ('facility', 'site', 'unknown', '')
     * @param {string} options.area - 初期地域
     * @param {string} options.shelterId - 初期選択された保護団体ID
     * @param {Function} options.init - カスタム初期化関数
     * @param {Function} options.handleKindChange - 種別変更時のカスタム処理
     * @param {Function} options.handleAreaChange - 地域変更時のカスタム処理
     * @returns {Object} Alpine.jsデータオブジェクト
     */
    create(options = {}) {
        return {
            // 基本プロパティ
            kind: options.kind || "",
            area: options.area || "",
            areas: this.data.areas,
            labels: this.data.labels,
            list: [],
            loading: false,
            shelterId: options.shelterId || "",

            // カスタムプロパティ（オプション）
            isInitializing: options.isInitializing || false,

            /**
             * 初期化処理
             */
            init() {
                console.log("ShelterPicker init started");

                // カスタム初期化関数があれば実行
                if (options.init && typeof options.init === "function") {
                    options.init.call(this);
                }
            },

            /**
             * 種別変更時の処理
             */
            handleKindChange() {
                this.shelterId = "";

                // サイトの場合は全国に固定
                if (this.kind === "site") {
                    this.area = "national";
                } else if (!this.area) {
                    this.area = "";
                }

                // カスタム処理があれば実行
                if (
                    options.handleKindChange &&
                    typeof options.handleKindChange === "function"
                ) {
                    options.handleKindChange.call(this);
                } else {
                    this.fetchList();
                }
            },

            /**
             * 地域変更時の処理
             */
            handleAreaChange() {
                this.shelterId = "";

                // カスタム処理があれば実行
                if (
                    options.handleAreaChange &&
                    typeof options.handleAreaChange === "function"
                ) {
                    options.handleAreaChange.call(this);
                } else {
                    this.fetchList();
                }
            },

            /**
             * フィルタされた地域リストを取得
             */
            get filteredAreas() {
                return this.kind === "site" ? ["national"] : this.areas;
            },

            /**
             * 保護団体リストを取得
             */
            async fetchList() {
                if (this.kind === "unknown") {
                    this.list = [];
                    return;
                }
                if (!this.area) {
                    this.list = [];
                    return;
                }

                this.loading = true;
                try {
                    const url = `/api/shelters?kind=${this.kind}&area=${this.area}`;
                    console.log("Fetching shelters:", url);

                    const res = await fetch(url, {
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            Accept: "application/json",
                        },
                    });

                    const all = await res.json();
                    console.log("API response:", all);

                    // クライアント側でも kind/area で厳密に絞り込み
                    this.list = all.filter(
                        (s) => s.kind === this.kind && s.area === this.area
                    );
                    console.log("Filtered list:", this.list);
                } catch (error) {
                    console.error("Error fetching shelters:", error);
                    this.list = [];
                } finally {
                    this.loading = false;
                }
            },

            /**
             * ボタンのクラスを取得（選択画面用）
             */
            btn(active) {
                return active
                    ? "px-3 py-2 rounded bg-gray-900 text-white"
                    : "px-3 py-2 rounded border text-gray-700";
            },
        };
    },
};
