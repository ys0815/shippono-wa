@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- ヘッダー -->
        <div class="bg-white/90 backdrop-blur border-b border-amber-100 shadow-sm sticky top-16 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <a href="{{ route('mypage.posts', ['type' => 'interview']) }}" class="mr-4">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h1 class="text-xl font-semibold text-gray-900">里親インタビューを投稿</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- メインコンテンツ -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- 温かみのあるメッセージ -->
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-lg p-6 mb-8">
                <div class="flex items-center mb-4">
                    <svg class="w-7 h-7 text-amber-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                    </svg>
                    <h2 class="text-xl font-semibold text-amber-800">あなたの体験が希望を届けます</h2>
                </div>
                <p class="text-amber-700 text-base leading-relaxed mb-3">
                    里親としての体験をシェアすることで、新しい家族を待つペットたちに希望を届けることができます。
                    どんな小さな幸せでも、あなたの言葉が誰かの心に響きます。
                </p>
                <div class="flex items-center text-sm text-amber-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span>安心して投稿してくださいね。みんなで温かいコミュニティを作りましょう。</span>
                </div>
            </div>

            <form method="POST" action="{{ route('mypage.posts.interview.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- 対象ペット選択 -->
                <div>
                    <label for="pet_id" class="block text-base font-medium text-gray-700 mb-2">対象ペット *</label>
                    <select id="pet_id" name="pet_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">選択してください</option>
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
                            <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            1ペットにつきインタビュー記事は1投稿までです。
                        </p>
                    @endif
                </div>

                <!-- タイトル -->
                <div>
                    <label for="title" class="block text-base font-medium text-gray-700 mb-2">タイトル *</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           maxlength="30"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="mt-1 text-sm text-gray-500">
                        <span id="title-count">0</span>/30文字
                    </div>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- メイン画像 -->
                <div>
                    <label for="main_image" class="block text-base font-medium text-gray-700 mb-2">メイン画像 *</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <div class="space-y-4">
                            <div>
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">ペットの写真をアップロード</p>
                            </div>
                            <input type="file" 
                                   id="main_image" 
                                   name="main_image" 
                                   accept="image/*"
                                   required
                                   class="hidden"
                                   onchange="previewImage(this)">
                            <button type="button" 
                                    onclick="document.getElementById('main_image').click()"
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-200">
                                画像を選択
                            </button>
                        </div>
                    </div>
                    
                    <!-- プレビュー表示エリア -->
                    <div id="image-preview" class="mt-4 border-2 border-dashed border-gray-300 rounded-lg p-4 hidden">
                        <p class="text-sm text-gray-600 mb-2">プレビュー表示エリア</p>
                        <img id="preview-img" src="" alt="プレビュー" class="max-w-full h-48 object-cover rounded-lg">
                    </div>
                    
                    @error('main_image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- インタビュー質問 -->
                <div class="space-y-6">
                    <!-- 質問1 -->
                    <div>
                        <label for="question1" class="block text-base font-medium text-gray-700 mb-2">
                            1. 新しい家族との出会い *
                        </label>
                       
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 mb-3">
                            <p class="text-sm text-blue-700">
                                💝 どんな小さな瞬間でも大丈夫です。その時の気持ちを素直に書いてください。
                            </p>
                        </div>
                        <textarea id="question1" 
                                  name="question1" 
                                  rows="4" 
                                  required
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('question1') }}</textarea>
                        <div class="mt-1 text-sm text-gray-500">
                            <span id="question1-count">0</span>/1000文字
                        </div>
                        @error('question1')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 質問2 -->
                    <div>
                        <label for="question2" class="block text-base font-medium text-gray-700 mb-2">
                            2. 迎える前の不安と準備 *
                        </label>
                      
                        <div class="bg-green-50 border-l-4 border-green-400 p-3 mb-3">
                            <p class="text-sm text-green-700">
                                🌱 不安な気持ちも含めて、等身大の体験を教えてください。きっと同じ気持ちの方がいます。
                            </p>
                        </div>
                        <textarea id="question2" 
                                  name="question2" 
                                  rows="4" 
                                  required
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('question2') }}</textarea>
                        <div class="mt-1 text-sm text-gray-500">
                            <span id="question2-count">0</span>/1000文字
                        </div>
                        @error('question2')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 質問3 -->
                    <div>
                        <label for="question3" class="block text-base font-medium text-gray-700 mb-2">
                            3. 迎えた後の変化と喜び *
                        </label>
    
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-3">
                            <p class="text-sm text-yellow-700">
                                ✨ 日常の小さな幸せや、心が温かくなった瞬間を教えてください。
                            </p>
                        </div>
                        <textarea id="question3" 
                                  name="question3" 
                                  rows="4" 
                                  required
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('question3') }}</textarea>
                        <div class="mt-1 text-sm text-gray-500">
                            <span id="question3-count">0</span>/1000文字
                        </div>
                        @error('question3')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 質問4 -->
                    <div>
                        <label for="question4" class="block text-base font-medium text-gray-700 mb-2">
                            4. 未来の里親へのメッセージ *
                        </label>
                      
                        <div class="bg-pink-50 border-l-4 border-pink-400 p-3 mb-3">
                            <p class="text-sm text-pink-700">
                                💌 あなたの言葉が、新しい家族を待つ誰かの心に届きます。
                            </p>
                        </div>
                        <textarea id="question4" 
                                  name="question4" 
                                  rows="4" 
                                  required
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('question4') }}</textarea>
                        <div class="mt-1 text-sm text-gray-500">
                            <span id="question4-count">0</span>/1000文字
                        </div>
                        @error('question4')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 質問5 -->
                    <div>
                        <label for="question5" class="block text-base font-medium text-gray-700 mb-2">
                            5. 最後に一言 *
                        </label>
                      
                        <div class="bg-purple-50 border-l-4 border-purple-400 p-3 mb-3">
                            <p class="text-sm text-purple-700">
                                🌟 あなたの物語の最後に、心に残る言葉を添えてください。きっと誰かの心に響きます。
                            </p>
                        </div>
                        <textarea id="question5" 
                                  name="question5" 
                                  rows="4" 
                                  required
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('question5') }}</textarea>
                        <div class="mt-1 text-sm text-gray-500">
                            <span id="question5-count">0</span>/1000文字
                        </div>
                        @error('question5')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- 公開設定 -->
                <div>
                    <label class="block text-base font-medium text-gray-700 mb-2">公開設定</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="published" {{ old('status', 'published') === 'published' ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">公開</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="draft" {{ old('status') === 'draft' ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">下書き保存</span>
                        </label>
                    </div>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ボタン -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="text-center mb-4">
                        <p class="text-sm text-gray-600">
                            💝 あなたの体験をシェアして、新しい家族を待つペットたちに希望を届けませんか？
                        </p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('mypage.posts', ['type' => 'interview']) }}" 
                           class="flex-1 text-gray-800 py-3 px-4 rounded-lg transition duration-200 font-medium text-center" 
                           style="background-color: #f3f4f6; hover:background-color: #e5e7eb;">
                            キャンセル
                        </a>
                        <button type="submit" 
                                class="flex-1 text-white py-3 px-4 rounded-lg transition duration-200 font-medium flex items-center justify-center" 
                                style="background-color: #d97706; hover:background-color: #b45309;">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
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
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
