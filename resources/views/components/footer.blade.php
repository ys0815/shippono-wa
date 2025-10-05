<!-- Footer Component -->
<footer class="bg-amber-50 border-t border-amber-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- メインフッターコンテンツ -->
        <div class="py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                <!-- コーポレートロゴと説明 -->
                <div class="lg:col-span-1">
                    <div class="flex items-center mb-4">
                        <img src="{{ asset('images/icon.png') }}" 
                             alt="# しっぽのわ" 
                             class="h-10 w-10 mr-3">
                        <h3 class="text-xl font-bold text-amber-900"># しっぽのわ</h3>
                    </div>
                    <p class="text-amber-800 text-sm leading-relaxed mb-4">
                        保護動物と家族の幸せな毎日を<br>
                        みんなで分かち合う場所
                    </p>
                    <p class="text-amber-700 text-xs">
                        温かいストーリーを通じて、<br>
                        優しさの循環を生み出します。
                    </p>
                </div>

                <!-- サイトマップ - サービス -->
                <div>
                    <h4 class="text-lg font-semibold text-amber-900 mb-4">サービス</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('about') }}" 
                               class="text-amber-800 hover:text-amber-900 transition-colors duration-200 text-sm">
                                #しっぽのわとは？
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('before_adoption') }}" 
                               class="text-amber-800 hover:text-amber-900 transition-colors duration-200 text-sm">
                                家族をお迎えする前に
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pets.search', 'dog') }}" 
                               class="text-amber-800 hover:text-amber-900 transition-colors duration-200 text-sm">
                                犬の家族を見る
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pets.search', 'cat') }}" 
                               class="text-amber-800 hover:text-amber-900 transition-colors duration-200 text-sm">
                                猫の家族を見る
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pets.search', 'rabbit') }}" 
                               class="text-amber-800 hover:text-amber-900 transition-colors duration-200 text-sm">
                                うさぎの家族を見る
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('pets.search', 'other') }}" 
                               class="text-amber-800 hover:text-amber-900 transition-colors duration-200 text-sm">
                                その他の家族を見る
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- サイトマップ - サポート -->
                <div>
                    <h4 class="text-lg font-semibold text-amber-900 mb-4">サポート</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" 
                               class="text-amber-800 hover:text-amber-900 transition-colors duration-200 text-sm">
                                ヘルプ・サポート
                            </a>
                        </li>
                        <li>
                            <a href="#" 
                               class="text-amber-800 hover:text-amber-900 transition-colors duration-200 text-sm">
                                お問い合わせ
                            </a>
                        </li>
                        <li>
                            <a href="#" 
                               class="text-amber-800 hover:text-amber-900 transition-colors duration-200 text-sm">
                                利用規約
                            </a>
                        </li>
                        <li>
                            <a href="#" 
                               class="text-amber-800 hover:text-amber-900 transition-colors duration-200 text-sm">
                                プライバシーポリシー
                            </a>
                        </li>
                        <li>
                            <a href="#" 
                               class="text-amber-800 hover:text-amber-900 transition-colors duration-200 text-sm">
                                特定商取引法に基づく表記
                            </a>
                        </li>
                        <li>
                            <a href="#" 
                               class="text-amber-800 hover:text-amber-900 transition-colors duration-200 text-sm">
                                サイトマップ
                            </a>
                        </li>
                        <li>
                            <a href="#" 
                               class="text-amber-800 hover:text-amber-900 transition-colors duration-200 text-sm">
                                よくある質問
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- SNSリンクとお知らせ -->
                <div>
                    <h4 class="text-lg font-semibold text-amber-900 mb-4">フォローする</h4>
                    <div class="flex space-x-4 mb-6">
                        <!-- X (旧Twitter) -->
                        <a href="#" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="w-10 h-10 bg-white border border-amber-300 hover:bg-amber-100 hover:border-amber-400 rounded-full flex items-center justify-center transition-colors duration-200 group shadow-sm">
                            <svg class="w-5 h-5 text-amber-700 group-hover:text-amber-800" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        
                        <!-- Instagram -->
                        <a href="#" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="w-10 h-10 bg-white border border-amber-300 hover:bg-amber-100 hover:border-amber-400 rounded-full flex items-center justify-center transition-colors duration-200 group shadow-sm">
                            <svg class="w-5 h-5 text-amber-700 group-hover:text-amber-800" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        
                        <!-- Facebook -->
                        <a href="#" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="w-10 h-10 bg-white border border-amber-300 hover:bg-amber-100 hover:border-amber-400 rounded-full flex items-center justify-center transition-colors duration-200 group shadow-sm">
                            <svg class="w-5 h-5 text-amber-700 group-hover:text-amber-800" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    </div>
                    
                    <!-- お知らせエリア -->
                    <div class="bg-white border border-amber-200 rounded-lg p-4 shadow-sm">
                        <h5 class="text-sm font-semibold text-amber-900 mb-2">お知らせ</h5>
                        <p class="text-xs text-amber-800 leading-relaxed">
                            最新の保護動物情報や<br>
                            イベント情報をお届けします
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ボーダーライン -->
        <div class="border-t border-amber-200"></div>

        <!-- コピーライト -->
        <div class="py-6">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                <div class="text-sm text-amber-700">
                    © {{ date('Y') }} # しっぽのわ. All rights reserved.
                </div>
                <div class="text-xs text-amber-600">
                    保護動物と家族の幸せを分かち合う場所
                </div>
            </div>
        </div>
    </div>
</footer>
