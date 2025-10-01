<x-guest-layout>
    <!-- 画像セクション（画面いっぱい） -->
    <section class="w-full relative overflow-hidden" style="position: relative !important;">
        <img src="{{ asset('images/hero-01.jpeg') }}" 
             alt="保護動物と家族の幸せ" 
             class="w-full h-64 sm:h-80 md:h-96 lg:h-[400px] xl:h-[400px] 2xl:h-[400px] object-cover about-hero-image" 
             style="height: 256px !important; max-height: 400px; object-position: center;">
        <!-- 画像下部のグラデーションオーバーレイ（重なり順を明示） -->
        <div class="absolute inset-0 pointer-events-none" style="z-index: 10 !important; background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.25) 40%, rgba(0,0,0,0) 75%) !important;"></div>
        <!-- 画像下部のテキスト（グラデーションの上に重ねる） -->
        <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 pb-8 sm:pb-10 pointer-events-none" style="z-index: 20 !important; position: absolute !important; top: auto !important; bottom: 0 !important; left: 0 !important; right: 0 !important; width: 100% !important;">
            <div class="max-w-4xl mx-auto text-center">
                <p class="text-white text-lg sm:text-xl md:text-2xl lg:text-3xl xl:text-3xl 2xl:text-4xl font-medium leading-relaxed drop-shadow-lg select-none">
                    家族を迎える前に知っておきたいこと
                </p>
            </div>
        </div>
    </section>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- メインコンテンツ -->
        <main class="pt-16 pb-16 space-y-20 md:space-y-24 lg:space-y-28 xl:space-y-32">

            <!-- サイト紹介 -->
            <section class="text-center">
                <div class="max-w-4xl mx-auto">
                    <div class="inline-flex items-center space-x-2 bg-amber-100 text-amber-700 px-4 py-2 rounded-full text-sm font-medium mb-6">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>家族をお迎えする前に知っておきたいこと</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl lg:text-5xl xl:text-5xl font-bold text-gray-900 mb-6">
                        命を迎えるその瞬間に
                    </h1>
                    <p class="text-base md:text-xl lg:text-xl text-gray-600 leading-relaxed mb-8">
                        近年、保護犬や保護猫に関心が集まり、里親募集サイトや保護シェルターを通じて新しい家族を迎える方が増えてきました。けれども、ペットを幸せにするためには、迎える前に考えておきたい大切なことがあります。
                    </p>
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl p-8 max-w-2xl mx-auto shadow-sm">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                                <span class="text-2xl">💝</span>
                            </div>
                        </div>
                        <p class="text-gray-700 leading-relaxed mb-3">
                            ここでは、安心して一歩を踏み出すためのヒントをお届けします。
                        </p>
                        <p class="text-amber-700 text-sm font-medium">
                            必要なのは準備とやさしさです。
                        </p>
                    </div>
                </div>
            </section>

            <!-- 数字が語る日本の現状 -->
            <section>
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">数字が語る日本の現状</h2>
                    <div class="relative inline-block">
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-28 h-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    </div>
                </div>
                
                <!-- 改善された数字を前面に -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-200 p-8 mb-8 shadow-lg">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-6">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-4xl md:text-5xl font-bold text-green-700 mb-4">9,017件</p>
                        <p class="text-xl md:text-2xl font-semibold text-green-800 mb-4">令和5年度の殺処分数</p>
                        <p class="text-gray-700 leading-relaxed text-base md:text-xl max-w-4xl mx-auto">
                            近年は改善が進み、令和5年度には9,017件まで減少しています。保護活動の広がりや、私たち一人ひとりの意識の変化が確かな成果を生んでいます。
                        </p>
                    </div>
                </div>

                <!-- 過去の数字を背景として表示 -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                    <div class="text-center mb-6">
                        <h3 class="text-xl md:text-2xl font-semibold text-gray-800 mb-4">過去との比較</h3>
                        <div class="flex flex-col md:flex-row items-center justify-center gap-8">
                            <div class="text-center">
                                <p class="text-2xl md:text-3xl font-bold text-red-600 mb-2">43,216匹</p>
                                <p class="text-sm md:text-base text-gray-600">平成29年度</p>
                            </div>
                            <div class="hidden md:block text-2xl text-gray-400">→</div>
                            <div class="text-center">
                                <p class="text-2xl md:text-3xl font-bold text-green-600 mb-2">9,017件</p>
                                <p class="text-sm md:text-base text-gray-600">令和5年度</p>
                            </div>
                        </div>
                        <div class="mt-4 text-sm text-gray-500">
                            <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full mr-2">79%減少</span>
                            <span class="text-gray-600">過去の数字と比較して大幅な改善を実現</span>
                        </div>
                    </div>

                    <div class="text-center mb-8">
                        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">あなたの「優しい選択」が、大きな力になります</h3>
                        <p class="text-gray-700 leading-relaxed text-base md:text-xl">
                            この流れをさらに前へ進めるために——これから家族を迎えるあなたの選択が、次の命を守る力になります。
                        </p>
                    </div>
                </div>
            </section>

            <!-- ペットを飼いたいあなたへ贈る5つの心得 -->
            <section>
                <div class="text-center mb-12">
                    <p class="text-amber-700 text-sm md:text-base tracking-wide mb-2">これから家族を迎えるみなさまへ</p>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">ペットを飼いたいあなたへ贈る5つの心得</h2>
                    <div class="relative inline-block">
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-28 h-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    </div>
                </div>

                <div class="space-y-8">
                    <!-- 心得1 -->
                    <article class="group bg-white border border-amber-100 rounded-2xl shadow-sm overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-5">
                            <figure class="md:col-span-2">
                                <img src="{{ asset('images/hero-02.jpeg') }}" alt="家族とペットの絆" class="w-full h-56 md:h-full object-cover">
                            </figure>
                            <div class="md:col-span-3 p-6 md:p-8">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="inline-flex items-center justify-center w-9 h-9 aspect-square rounded-full border-2 border-amber-400 text-amber-700 font-bold">1</span>
                                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900">何があっても最後まで共に生きる</h3>
                                </div>
                                <div class="space-y-4 text-gray-700 leading-relaxed text-base md:text-xl">
                                    <p>ペットを迎えるときに最も大切なのは、最期まで一緒に生きるという覚悟です。</p>
                                    <p>結婚や出産、転職など、私たちの暮らしは変化していきます。その中で、もしもの時に備えて考えておくことが、悲しい選択を避ける第一歩になります。</p>
                                    <p>アレルギーの有無や家族の理解など、事前に想像しておくことで「もしもの時の対策」を描くことができます。その備えが、共に生きるためのやさしい力になります。</p>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- 心得2 -->
                    <article class="group bg-white border border-amber-100 rounded-2xl shadow-sm overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-5">
                            <div class="md:col-span-3 p-6 md:p-8 order-2 md:order-1">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="inline-flex items-center justify-center w-9 h-9 aspect-square rounded-full border-2 border-amber-400 text-amber-700 font-bold">2</span>
                                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900">家族や周りの人の理解を得よう</h3>
                                </div>
                                <div class="space-y-4 text-gray-700 leading-relaxed text-base md:text-xl">
                                    <p>ペットを育てるには、家族や周囲の理解が欠かせません。パートナーや子どもにきちんと相談し、同じ気持ちで迎えられるようにしましょう。</p>
                                    <p>ひとり暮らしの場合は、信頼できる友人や親族など、いざというときに助けてくれる人を見つけておくことが大切です。</p>
                                    <p>周囲に頼れる人を作っておけば、万が一、あなたの身に何かがあったときや家を留守にしなければいけないときでも、ペットに悲しい思いをさせずに済みます。</p>
                                </div>
                            </div>
                            <figure class="md:col-span-2 order-1 md:order-2">
                                <img src="{{ asset('images/hero-09.jpeg') }}" alt="家族の理解と協力" class="w-full h-56 md:h-full object-cover">
                            </figure>
                        </div>
                    </article>

                    <!-- 心得3 -->
                    <article class="group bg-white border border-amber-100 rounded-2xl shadow-sm overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-5">
                            <figure class="md:col-span-2">
                                <img src="{{ asset('images/hero-08.jpeg') }}" alt="ペットの健康管理" class="w-full h-56 md:h-full object-cover">
                            </figure>
                            <div class="md:col-span-3 p-6 md:p-8">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="inline-flex items-center justify-center w-9 h-9 aspect-square rounded-full border-2 border-amber-400 text-amber-700 font-bold">3</span>
                                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900">金銭面もチェック</h3>
                                </div>
                                <div class="space-y-4 text-gray-700 leading-relaxed text-base md:text-xl">
                                    <p>ペットと暮らしていくには、思っている以上にお金がかかります。フードやペットシーツ、猫砂など、日々の生活に必要なものは積み重ねると大きな負担になることもあります。</p>
                                    <p>さらに、人間とは違ってペットの医療費には保険が効かないため、病気やけがのときには治療費が高額になる場合もあります。特に子どもの時期やシニア期は体調を崩しやすく、「想像していたよりも費用がかかる」と感じる方も少なくありません。</p>
                                    <p>だからこそ、金銭面に不安がある方は、飼い始める前に少しずつ貯金をしたり、ペット保険に加入することを検討したりするのがおすすめです。</p>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- 心得4 -->
                    <article class="group bg-white border border-amber-100 rounded-2xl shadow-sm overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-5">
                            <div class="md:col-span-3 p-6 md:p-8 order-2 md:order-1">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="inline-flex items-center justify-center w-9 h-9 aspect-square rounded-full border-2 border-amber-400 text-amber-700 font-bold">4</span>
                                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900">二度と悲しい思いをさせない</h3>
                                </div>
                                <div class="space-y-4 text-gray-700 leading-relaxed text-base md:text-xl">
                                    <p>悲しいことに、日本はまだ動物福祉の面で後進国と言われており、動物の命を守る法律には多くの課題が残されています。その結果、人間の身勝手な理由で捨てられてしまうペットも少なくありません。</p>
                                    <p>だからこそ、ペットをお迎えするときには「二度と悲しい思いをさせない」という強い気持ちを持つことが大切です。</p>
                                </div>
                            </div>
                            <figure class="md:col-span-2 order-1 md:order-2">
                                <img src="{{ asset('images/hero-05.jpeg') }}" alt="責任ある飼い主" class="w-full h-56 md:h-full object-cover">
                            </figure>
                        </div>
                    </article>

                    <!-- 心得5 -->
                    <article class="group bg-white border border-amber-100 rounded-2xl shadow-sm overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-5">
                            <figure class="md:col-span-2">
                                <img src="{{ asset('images/hero-06.jpeg') }}" alt="ペットの習性理解" class="w-full h-56 md:h-full object-cover">
                            </figure>
                            <div class="md:col-span-3 p-6 md:p-8">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="inline-flex items-center justify-center w-9 h-9 aspect-square rounded-full border-2 border-amber-400 text-amber-700 font-bold">5</span>
                                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900">飼育するペットの習性を理解する</h3>
                                </div>
                                <div class="space-y-4 text-gray-700 leading-relaxed text-base md:text-xl">
                                    <p>ペットによって、しつけ方や性格、生活のリズムが大きく異なります。迎える前に、その動物の習性や特性をしっかり学んでおくことで、より良い関係を築けるようになります。</p>
                                    <p>違いを尊重しながら、安心して快適に過ごせる環境を整えてあげましょう。</p>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            <!-- ペットは大きな幸せを与えてくれる存在 -->
            <section>
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">ペットは大きな幸せを与えてくれる存在</h2>
                    <div class="relative inline-block">
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-28 h-2 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full"></div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl shadow-lg border border-amber-200 p-8">
                    <div class="flex flex-col md:flex-row gap-8 items-center">
                        <div class="flex-1">
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 bg-amber-500 text-white rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl md:text-3xl font-bold text-gray-900">ペットとの幸せな時間</h3>
                            </div>
                            <p class="text-gray-700 leading-relaxed mb-4 text-base md:text-xl">
                                ペットと暮らしていると、時には「大変だな」と悩んでしまうこともあるかもしれません。けれど、その何倍も大きな幸せを、彼らは私たちに届けてくれます。
                            </p>
                            <p class="text-gray-700 leading-relaxed mb-4 text-base md:text-xl">
                                人間相手では得られない癒しを与えてくれたり、ふとした瞬間に笑顔のきっかけをくれたり……。ペットとの日々は、喜びや楽しみにあふれています。日々のスキンシップを通じて、お互いに成長していけるのも、ペットと暮らす醍醐味のひとつです。
                            </p>
                            <p class="text-gray-700 leading-relaxed text-base md:text-xl">
                                だからこそ、私たちは"誠実な飼い主"として、最後まで責任と愛情を持って向き合っていくことが大切だと考えています。
                            </p>
                        </div>
                        <div class="w-full md:w-80 flex-shrink-0">
                            <div class="relative overflow-visible">
                                <img src="{{ asset('images/hero-03.jpeg') }}" 
                                     alt="ペットとの幸せな時間" 
                                     class="relative z-10 w-full h-48 md:h-64 object-cover rounded-lg shadow-lg">
                                <div class="absolute -bottom-2 left-0 right-0 z-0 h-4 md:h-5" style="background: radial-gradient(circle, rgb(251 191 36) 0.8px, transparent 0.8px); background-size: 4px 4px;"></div>
                                <div class="absolute top-0 -right-2 bottom-0 z-0 w-4 md:w-5" style="background: radial-gradient(circle, rgb(251 191 36) 0.8px, transparent 0.8px); background-size: 4px 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 呼びかけ CTA -->
            <section class="py-12 md:py-16 lg:py-20">
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-8 md:p-12 lg:p-16 text-center text-white">
                    <h2 class="text-3xl md:text-4xl lg:text-5xl xl:text-5xl font-bold mb-4 md:mb-6 text-white">あなたの「覚悟」と「優しさ」が、次の命を守ります</h2>
                    <p class="text-base md:text-xl lg:text-xl opacity-95 mb-6 md:mb-8">最期の瞬間まで、家族として——その約束が、悲しい命をひとつ減らします。</p>
                    <div class="flex flex-col sm:flex-row gap-4 md:gap-6 lg:gap-8 justify-center items-center">
                        <a href="{{ route('register') }}" class="bg-white text-amber-600 px-8 py-4 md:px-12 md:py-5 lg:px-16 lg:py-6 rounded-full font-bold text-base md:text-xl lg:text-xl hover:bg-amber-50 transition-colors duration-300 shadow-lg w-full sm:w-auto sm:min-w-[200px] md:min-w-[240px] lg:min-w-[280px] text-center whitespace-nowrap">参加してはじめる</a>
                        <a href="{{ route('home') }}" class="border-2 border-white text-white px-8 py-4 md:px-12 md:py-5 lg:px-16 lg:py-6 rounded-full font-bold text-base md:text-xl lg:text-xl hover:bg-white hover:text-amber-600 transition-colors duration-300 w-full sm:w-auto sm:min-w-[200px] md:min-w-[240px] lg:min-w-[280px] text-center whitespace-nowrap">幸せな日常を見る</a>
                    </div>
                </div>
            </section>

        </main>
    </div>
</x-guest-layout>