<x-guest-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- パンくずリスト -->
            <nav class="mb-6">
                <ol class="flex items-center space-x-2 text-sm text-gray-600">
                    <li><a href="{{ route('home') }}" class="hover:text-amber-600">ホーム</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-gray-900 font-medium">家族をお迎えする前に読むこと</li>
                </ol>
            </nav>

            <!-- メインタイトル -->
            <header class="relative text-center mb-12 overflow-hidden rounded-2xl" style="height: 300px;">
                <!-- 背景画像 -->
                <div class="absolute inset-0">
                    <img src="{{ asset('images/hero-01.jpeg') }}" 
                         alt="保護動物と家族の幸せ" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40"></div>
                </div>
                
                <!-- コンテンツ -->
                <div class="relative z-10 h-full flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        <h1 class="text-3xl md:text-4xl font-bold mb-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.7);">
                            家族をお迎えする前に読むこと
                        </h1>
                        <div class="w-16 h-1 bg-amber-400 mx-auto"></div>
                        <p class="mt-4 text-lg opacity-90" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">
                            命を迎えるという選択に、確かな準備と優しさを
                        </p>
                    </div>
                </div>
            </header>

            <!-- 導入文 -->
            <section class="mb-12">
                <div class="bg-white rounded-lg shadow-sm p-6 md:p-8">
                    <p class="text-gray-700 leading-relaxed text-lg">
                        近年は保護犬や保護猫にスポットが当たりやすくなり、当サイトのような里親募集サイトからペットを迎えようと考える方も増えてきました。<br>
                        しかし、ペットを幸せにするには飼育前に心得ておかねばならないことがいくつかあります。
                    </p>
                </div>
            </section>

            <!-- 数字が語る日本の現状 -->
            <section class="mb-12">
                <div class="bg-white rounded-lg shadow-sm p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">数字が語る日本の現状</h2>
                    
                    <div class="mb-6">
                        <p class="text-3xl font-bold text-red-600 mb-2">43,216匹</p>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            かつて、日本では多くの犬や猫が殺処分されていました。平成29年度には、その数は43,216匹にものぼります。この悲しい事態の背景には、「年をとって世話がかかる」「思っていたより懐かない」といった人間の身勝手な理由で捨てられる命が数多くありました。彼らは本当に、死ななければならない存在だったのでしょうか。
                        </p>
                    </div>

                    <div class="bg-green-50 rounded-lg p-6 mb-6">
                        <p class="text-2xl font-bold text-green-700 mb-2">9,017件</p>
                        <p class="text-gray-700 leading-relaxed">
                            しかし、この現状は少しずつ改善されつつあります。環境省のデータによると、令和5年度の犬・猫の殺処分数は9,017件まで減少しています。これは、保護活動の広がりや、私たち一人ひとりの意識が変化してきたことの証です。
                        </p>
                    </div>

                    <p class="text-gray-700 leading-relaxed">
                        この流れをさらに加速させ、悲しい命をなくすためには、これから飼い主となるあなたが、責任ある行動をとることが不可欠です。
                    </p>
                </div>
            </section>

            <!-- ペットを飼いたいあなたへ贈る5つの心得 -->
            <section class="mb-12">
                <div class="bg-white rounded-lg shadow-sm p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8">ペットを飼いたいあなたへ贈る5つの心得</h2>

                    <div class="space-y-8">
                        <!-- 心得1 -->
                        <div class="border-l-4 border-amber-500 pl-6">
                            <div class="flex flex-col md:flex-row gap-6 items-start">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 mb-3">1. 何があっても最後まで共に生きる</h3>
                                    <p class="text-gray-700 leading-relaxed mb-4">
                                        ペットを飼育する上で一番大切なのは、何があっても最後まで一緒に生きること。飼育前には必ず、最期まで本当に面倒がみられるか考えなければいけません。
                                    </p>
                                    <p class="text-gray-700 leading-relaxed">
                                        私たちのライフスタイルは結婚や出産、転職などによって大きく変わります。そのため、現在、ペットを飼える環境であっても、数年後にはどうなっているか分かりかねる部分もあるでしょう。例えば、あなたは大丈夫であっても配偶者や同居人が飼育に賛成してくれなかったり、産まれた子どもが動物に対してアレルギーを持っていたりすることも……。
                                    </p>
                                    <p class="text-gray-700 leading-relaxed mt-4">
                                        こうした時に悲しい選択を避けるにも、"もしものときの対策法"を思い描いた上でペットを迎えるようにしましょう。
                                    </p>
                                </div>
                                <div class="w-full md:w-72 flex-shrink-0">
                                    <div class="relative overflow-visible">
                                        <img src="{{ asset('images/hero-02.jpeg') }}" 
                                             alt="家族とペットの絆" 
                                             class="relative z-10 w-full aspect-[4/3] object-cover rounded-md shadow">
                                        <div class="absolute -bottom-2 left-0 right-0 z-0 h-4 md:h-5" style="background: radial-gradient(circle, rgb(251 191 36) 0.8px, transparent 0.8px); background-size: 4px 4px;"></div>
                                        <div class="absolute top-0 -right-2 bottom-0 z-0 w-4 md:w-5" style="background: radial-gradient(circle, rgb(251 191 36) 0.8px, transparent 0.8px); background-size: 4px 4px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 心得2 -->
                        <div class="border-l-4 border-amber-500 pl-6">
                            <div class="flex flex-col md:flex-row gap-6 items-start">
                                <div class="w-full md:w-72 flex-shrink-0 order-2 md:order-1">
                                    <div class="relative overflow-visible">
                                        <img src="{{ asset('images/hero-09.jpeg') }}" 
                                             alt="家族の理解と協力" 
                                             class="relative z-10 w-full aspect-[4/3] object-cover rounded-md shadow">
                                        <div class="absolute -bottom-2 left-0 right-0 z-0 h-4 md:h-5" style="background: radial-gradient(circle, rgb(251 191 36) 0.8px, transparent 0.8px); background-size: 4px 4px;"></div>
                                        <div class="absolute top-0 -right-2 bottom-0 z-0 w-4 md:w-5" style="background: radial-gradient(circle, rgb(251 191 36) 0.8px, transparent 0.8px); background-size: 4px 4px;"></div>
                                    </div>
                                </div>
                                <div class="flex-1 order-1 md:order-2">
                                    <h3 class="text-xl font-bold text-gray-900 mb-3">2. 家族や周りの人の理解を得よう</h3>
                                    <p class="text-gray-700 leading-relaxed mb-4">
                                        ペットを育てるには、家族や周囲の人の理解を得ることも大切。もしも、あなたが結婚をしている場合は必ずパートナーに事前相談し、家庭の現状や子どもの意志も確認した上で迎えましょう。
                                    </p>
                                    <p class="text-gray-700 leading-relaxed">
                                        独身やひとり暮らしをしている方は、いざというときに頼れる人を見つけておく必要があります。身内が難しい場合は、家が近く信頼できる友人でも良いでしょう。周囲に頼れる人を作っておけば、万が一、あなたの身に何かがあったときや家を留守にしなければいけないときでも、ペットに悲しい思いをさせずに済みます。
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- 心得3 -->
                        <div class="border-l-4 border-amber-500 pl-6">
                            <div class="flex flex-col md:flex-row gap-6 items-start">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 mb-3">3. 金銭面もチェック</h3>
                                    <p class="text-gray-700 leading-relaxed mb-4">
                                        ペットを育てていくには、お金がかかります。フードやペットシーツ、猫砂などにかかる定期的な出費は一見、少額なように思えますが、長い目で見れば大きな負担となることもあります。
                                    </p>
                                    <p class="text-gray-700 leading-relaxed">
                                        人間と違って、ペットの医療費は保険が効かないため、病気のときは治療費が高額になってしまうことも……。特に、幼少期やシニア期は体調を崩しやすいので、想像していたよりもお金がかかってしまうこともあります。そのため、金銭的に不安な方は飼育前に貯金を貯めたり、ペット保険を検討してみたりして、必要な時に適切な治療を受けさせてあげられるようにしていきましょう。
                                    </p>
                                </div>
                                <div class="w-full md:w-72 flex-shrink-0">
                                    <div class="relative overflow-visible">
                                        <img src="{{ asset('images/hero-08.jpeg') }}" 
                                             alt="ペットの健康管理" 
                                             class="relative z-10 w-full aspect-[4/3] object-cover rounded-md shadow">
                                        <div class="absolute -bottom-2 left-0 right-0 z-0 h-4 md:h-5" style="background: radial-gradient(circle, rgb(251 191 36) 0.8px, transparent 0.8px); background-size: 4px 4px;"></div>
                                        <div class="absolute top-0 -right-2 bottom-0 z-0 w-4 md:w-5" style="background: radial-gradient(circle, rgb(251 191 36) 0.8px, transparent 0.8px); background-size: 4px 4px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 心得4 -->
                        <div class="border-l-4 border-amber-500 pl-6">
                            <div class="flex flex-col md:flex-row gap-6 items-start">
                                <div class="w-full md:w-72 flex-shrink-0 order-2 md:order-1">
                                    <div class="relative overflow-visible">
                                        <img src="{{ asset('images/hero-05.jpeg') }}" 
                                             alt="責任ある飼い主" 
                                             class="relative z-10 w-full aspect-[4/3] object-cover rounded-md shadow">
                                        <div class="absolute -bottom-2 left-0 right-0 z-0 h-4 md:h-5" style="background: radial-gradient(circle, rgb(251 191 36) 0.8px, transparent 0.8px); background-size: 4px 4px;"></div>
                                        <div class="absolute top-0 -right-2 bottom-0 z-0 w-4 md:w-5" style="background: radial-gradient(circle, rgb(251 191 36) 0.8px, transparent 0.8px); background-size: 4px 4px;"></div>
                                    </div>
                                </div>
                                <div class="flex-1 order-1 md:order-2">
                                    <h3 class="text-xl font-bold text-gray-900 mb-3">4. 二度と悲しい思いをさせない</h3>
                                    <p class="text-gray-700 leading-relaxed mb-4">
                                        悲しいことに日本は動物後進国であり、動物の命を守る法律にはまだまだ穴があると当社では考えております。人間の身勝手な理由で捨てられているペットも少なくありません。現に、当サイトには悲しい思いをし、動物愛護団体や保護活動家、その他ボランティアさんに保護されたペットもたくさんいます。
                                    </p>
                                    <p class="text-gray-700 leading-relaxed">
                                        だからこそ、ペットをおうちに迎えるときは「二度と悲しい思いをさせない」という気持ちを強く持たなければなりません。「人間の都合でペットの命を振り回してはいけない」という思いを胸に、責任を持って飼育していきましょう。
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- 心得5 -->
                        <div class="border-l-4 border-amber-500 pl-6">
                            <div class="flex flex-col md:flex-row gap-6 items-start">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 mb-3">5. 飼育するペットの習性を理解する</h3>
                                    <p class="text-gray-700 leading-relaxed mb-4">
                                        犬と猫では、しつけ方や性格、生活習慣が大きく異なります。ペットを迎える前に、その動物の習性や特性をしっかり学ぶことで、「思い通りにいかない」という悩みを減らし、より良い関係を築くことができます。
                                    </p>
                                    <p class="text-gray-700 leading-relaxed">
                                        私たちに個性があるように、動物にもそれぞれの個性があります。その個性を尊重し、彼らが快適に過ごせるように配慮していきましょう。
                                    </p>
                                </div>
                                <div class="w-full md:w-72 flex-shrink-0">
                                    <div class="relative overflow-visible">
                                        <img src="{{ asset('images/hero-06.jpeg') }}" 
                                             alt="ペットの習性理解" 
                                             class="relative z-10 w-full aspect-[4/3] object-cover rounded-md shadow">
                                        <div class="absolute -bottom-2 left-0 right-0 z-0 h-4 md:h-5" style="background: radial-gradient(circle, rgb(251 191 36) 0.8px, transparent 0.8px); background-size: 4px 4px;"></div>
                                        <div class="absolute top-0 -right-2 bottom-0 z-0 w-4 md:w-5" style="background: radial-gradient(circle, rgb(251 191 36) 0.8px, transparent 0.8px); background-size: 4px 4px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ペットは大きな幸せを与えてくれる存在 -->
            <section class="mb-12">
                <div class="bg-white rounded-lg shadow-sm p-6 md:p-8">
                    <div class="flex flex-col md:flex-row gap-8 items-center">
                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">ペットは大きな幸せを与えてくれる存在</h2>
                            <p class="text-gray-700 leading-relaxed mb-4">
                                ペットを飼育していると、時には大変だと感じて悩むこともあるでしょう。しかし、彼らはそれ以上に幸せを運んできてくれます。
                            </p>
                            <p class="text-gray-700 leading-relaxed mb-4">
                                人間相手では得られない癒しをくれたり、日常の中に笑顔の種をまいてくれたりと、ペットと過ごす日々には喜びや楽しみのほうが多く溢れています。日々のスキンシップを通し、共に成長できるのもペットを飼うことの醍醐味だといえるでしょう。
                            </p>
                            <p class="text-gray-700 leading-relaxed">
                                そんな彼らに対して私たちは、"誠実な飼い主"として向き合っていく必要があると考えております。
                            </p>
                        </div>
                        <div class="w-full md:w-80 flex-shrink-0">
                            <img src="{{ asset('images/hero-03.jpeg') }}" 
                                 alt="ペットとの幸せな時間" 
                                 class="w-full h-48 md:h-64 object-cover rounded-lg shadow-md">
                        </div>
                    </div>
                </div>
            </section>

            <!-- 呼びかけ CTA -->
            <section class="py-12">
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl p-8 md:p-12 text-center text-white">
                    <h2 class="text-3xl md:text-4xl font-bold mb-4 text-white">あなたの「覚悟」と「優しさ」が、次の命を守ります</h2>
                    <p class="text-lg md:text-xl opacity-95 mb-6">最期の瞬間まで、家族として——その約束が、悲しい命をひとつ減らします。</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" class="bg-white text-amber-600 px-8 py-4 rounded-full font-bold text-lg hover:bg-amber-50 transition-colors duration-300 shadow-lg">参加してはじめる</a>
                        <a href="{{ route('home') }}" class="border-2 border-white text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white hover:text-amber-600 transition-colors duration-300">幸せな日常を見る</a>
                    </div>
                </div>
            </section>

    </div>
</x-guest-layout>