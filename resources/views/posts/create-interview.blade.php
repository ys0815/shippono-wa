@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <div class="min-h-screen bg-main-bg">
        <!-- ヘッダー -->
        <div class="bg-white/90 backdrop-blur border-b border-amber-100 shadow-sm sticky top-16 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <a href="{{ route('mypage.posts', ['type' => 'interview']) }}" class="mr-4">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h1 class="text-lg font-semibold text-amber-900">里親インタビューを投稿</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- メインコンテンツ -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- 温かみのあるメッセージ -->
            <div class="rounded-lg p-6 mb-8 bg-gradient-to-r from-amber-100 to-orange-200 border border-amber-300">
                <div class="flex items-center mb-4">
                    <svg class="w-10 h-10 mr-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                    </svg>
                    <h2 class="text-lg font-semibold text-amber-800">あなたの体験が希望を届けます</h2>
                </div>
                <p class="text-base leading-relaxed mb-3 text-amber-700">
                    里親としての体験をシェアすることで、新しい家族を待つ誰かの背中を優しく押します。
                    里親になることを検討している方や興味をお持ちの方に、温かな希望と勇気を届けませんか？
                </p>
                <div class="flex items-center text-sm text-amber-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span>安心して投稿してくださいね。あなたの体験が、きっと誰かの支えになります。</span>
                </div>
            </div>

            <form method="POST" action="{{ route('mypage.posts.interview.store') }}" enctype="multipart/form-data" class="space-y-10">
                @csrf
                
                <!-- 対象ペット選択 -->
                <div>
                    <label for="pet_id" class="block text-base font-medium text-main-text mb-2">ペットを選択</label>
                    <div class="p-3 mb-3 bg-yellow-50">
                        <p class="text-sm text-yellow-800">
                            🐾 どのペットの里親インタビューをシェアしますか？かけがえのない家族を選んでくださいね。
                        </p>
                    </div>
                    
                    <select id="pet_id" name="pet_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">ペットを選択してください</option>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->id }}" 
                                    {{ old('pet_id') == $pet->id ? 'selected' : '' }}
                                    {{ in_array($pet->id, $postedPetIds) ? 'disabled' : '' }}>
                                {{ $pet->name }}
                                @if(in_array($pet->id, $postedPetIds))
                                    ※このペットは既に投稿済みです
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('pet_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if(count($postedPetIds) > 0)
                        <p class="mt-2 text-sm text-amber-600">
                    <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            1ペットにつきインタビュー記事は1投稿までです。
                        </p>
                    @endif
                </div>

                <!-- タイトル -->
                <div>
                    <label for="title" class="block text-base font-medium text-main-text mb-2">タイトル</label>
                    <div class="p-3 mb-3 bg-yellow-50">
                        <p class="text-sm text-yellow-800">
                            ✨ その瞬間の気持ちを一言で表現してください。シンプルで温かいタイトルが素敵です。
                        </p>
                    </div>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           maxlength="30"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    <div class="mt-1 text-sm text-sub-text">
                        <span id="title-count">0</span>/30文字
                    </div>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- メイン画像 -->
                <div>
                    <label for="main_image" class="block text-base font-medium text-main-text mb-2">メイン画像</label>
                    <div class="p-3 mb-3" style="background-color: #fefce8;">
                        <p class="text-sm" style="color: #a16207;">
                            📸 その瞬間を切り取った写真をアップロードしてください。思い出がより鮮明に伝わります。
                        </p>
                    </div>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <input type="file" 
                               id="main_image" 
                               name="main_image" 
                               accept="image/*"
                               required
                               class="hidden"
                               onchange="previewImage(this)">
                        <label for="main_image" class="cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-main-text">メイン画像を選択</p>
                            <p class="text-xs text-sub-text">画像：JPEG, PNG, JPG, GIF（10MB以下）</p>
                        </label>
                    </div>
                    
                    <!-- プレビュー表示エリア -->
                    <div id="image-preview" class="mt-4 grid grid-cols-1 gap-4 hidden">
                        <!-- プレビュー画像がここに表示される -->
                    </div>
                    
                    @error('main_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- インタビュー質問 -->
                <div class="space-y-6">
                    <!-- 質問1 -->
                    <div>
                        <label for="question1" class="block text-base font-medium text-main-text mb-2">
                            1. 新しい家族との出会い
                        </label>
                       
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                💝 どんな小さな瞬間でも大丈夫です。その時の気持ちを素直に書いてください。
                            </p>
                        </div>
                        <textarea id="question1" 
                                  name="question1" 
                                  rows="4" 
                                  required
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">{{ old('question1') }}</textarea>
                        <div class="mt-1 text-sm text-sub-text">
                            <span id="question1-count">0</span>/1000文字
                        </div>
                        @error('question1')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 質問2 -->
                    <div>
                        <label for="question2" class="block text-base font-medium text-main-text mb-2">
                            2. 迎える前の不安と準備
                        </label>
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                🌱 不安な気持ちも含めて、等身大の体験を教えてください。きっと同じ気持ちの方がいます。
                            </p>
                        </div>
                        <textarea id="question2" 
                                  name="question2" 
                                  rows="4" 
                                  required
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">{{ old('question2') }}</textarea>
                        <div class="mt-1 text-sm text-sub-text">
                            <span id="question2-count">0</span>/1000文字
                        </div>
                        @error('question2')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 質問3 -->
                    <div>
                        <label for="question3" class="block text-base font-medium text-main-text mb-2">
                            3. 迎えた後の変化と喜び
                        </label>
    
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                ✨ 日常の小さな幸せや、心が温かくなった瞬間を教えてください。
                            </p>
                        </div>
                        <textarea id="question3" 
                                  name="question3" 
                                  rows="4" 
                                  required
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">{{ old('question3') }}</textarea>
                        <div class="mt-1 text-sm text-sub-text">
                            <span id="question3-count">0</span>/1000文字
                        </div>
                        @error('question3')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 質問4 -->
                    <div>
                        <label for="question4" class="block text-base font-medium text-main-text mb-2">
                            4. 未来の里親へのメッセージ
                        </label>
                      
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                💌 あなたの言葉が、新しい家族を待つ誰かの心に届きます。
                            </p>
                        </div>
                        <textarea id="question4" 
                                  name="question4" 
                                  rows="4" 
                                  required
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">{{ old('question4') }}</textarea>
                        <div class="mt-1 text-sm text-sub-text">
                            <span id="question4-count">0</span>/1000文字
                        </div>
                        @error('question4')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 質問5 -->
                    <div>
                        <label for="question5" class="block text-base font-medium text-main-text mb-2">
                            5. 最後に一言
                        </label>
                        <div class="p-3 mb-3" style="background-color: #fefce8;">
                            <p class="text-sm" style="color: #a16207;">
                                🌟 あなたの物語の最後に、心に残る言葉を添えてください。きっと誰かの心に響きます。
                            </p>
                        </div>
                        <textarea id="question5" 
                                  name="question5" 
                                  rows="4" 
                                  required
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">{{ old('question5') }}</textarea>
                        <div class="mt-1 text-sm text-sub-text">
                            <span id="question5-count">0</span>/1000文字
                        </div>
                        @error('question5')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- 公開設定 -->
                <div>
                    <label class="block text-base font-medium text-main-text mb-2">公開設定</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="published" {{ old('status', 'published') === 'published' ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-main-text">公開</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="draft" {{ old('status') === 'draft' ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-main-text">下書き保存</span>
                        </label>
                    </div>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ボタン -->
                <div class="bg-main-bg rounded-lg p-4 mb-6">
                    <div class="text-center mb-4">
                        <p class="text-sm text-main-text">
                            💝 あなたの体験をシェアして、新しい家族を待つペットたちに希望を届けませんか？
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('mypage.posts', ['type' => 'interview']) }}" class="btn btn-outline flex-1 justify-center">
                            キャンセル
                        </a>
                        <button type="submit" class="btn btn-brand flex-1 justify-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>
                            投稿する
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // タイトル文字数カウント
        document.getElementById('title').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('title-count').textContent = count;
        });

        // 質問文字数カウント
        ['question1', 'question2', 'question3', 'question4', 'question5'].forEach(function(questionId) {
            document.getElementById(questionId).addEventListener('input', function() {
                const count = this.value.length;
                document.getElementById(questionId + '-count').textContent = count;
            });
        });

        // 画像プレビュー機能
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = '';
            
            if (input.files && input.files[0]) {
                preview.classList.remove('hidden');
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'プレビュー';
                    img.className = 'w-full h-48 object-cover rounded-lg';
                    img.loading = 'lazy';
                    img.decoding = 'async';
                    
                    div.appendChild(img);
                    preview.appendChild(div);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
