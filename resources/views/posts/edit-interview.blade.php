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
                        <h1 class="text-xl font-semibold text-gray-900">投稿を編集</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- メインコンテンツ -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <form method="POST" action="{{ route('mypage.posts.update', $post) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- 対象ペット選択 -->
                <div>
                    <label for="pet_id" class="block text-sm font-medium text-gray-700 mb-2">対象ペット *</label>
                    <select id="pet_id" name="pet_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">選択してください</option>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->id }}" {{ old('pet_id', $post->pet_id) == $pet->id ? 'selected' : '' }}>
                                {{ $pet->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('pet_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- タイトル -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">タイトル *</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title', $post->title) }}"
                           maxlength="30"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="mt-1 text-sm text-gray-500">
                        <span id="title-count">{{ strlen(old('title', $post->title)) }}</span>/30文字
                    </div>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- メイン画像 -->
                <div>
                    <label for="main_image" class="block text-sm font-medium text-gray-700 mb-2">メイン画像 *</label>
                    
                    <!-- 現在の画像表示 -->
                    @if($post->media->count() > 0)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">現在の画像:</p>
                            <img src="{{ Storage::url($post->media->first()->url) }}" 
                                 alt="現在の画像" 
                                 class="w-32 h-32 object-cover rounded-lg">
                        </div>
                    @endif
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <div class="space-y-4">
                            <div>
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">新しい画像をアップロード（任意）</p>
                            </div>
                            <input type="file" 
                                   id="main_image" 
                                   name="main_image" 
                                   accept="image/*"
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
                        <label for="question1" class="block text-sm font-medium text-gray-700 mb-2">
                            1. 新しい家族との出会い *
                        </label>
                        <p class="text-sm text-gray-600 mb-2">どのようにして出会ったか、その時の気持ちなどを教えてください</p>
                        <textarea id="question1" 
                                  name="question1" 
                                  rows="4" 
                                  required
                                  minlength="200"
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('question1', $post->content) }}</textarea>
                        <div class="mt-1 text-sm text-gray-500">
                            <span id="question1-count">{{ strlen(old('question1', $post->content)) }}</span>/1000文字
                        </div>
                        @error('question1')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 質問2-5 -->
                    <div>
                        <label for="question2" class="block text-sm font-medium text-gray-700 mb-2">
                            2. 迎える前の不安と準備 *
                        </label>
                        <p class="text-sm text-gray-600 mb-2">迎える前に感じた不安や、どのような準備をしたかを教えてください</p>
                        <textarea id="question2" 
                                  name="question2" 
                                  rows="4" 
                                  required
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('question2', $post->interviewContent?->question2 ?? '') }}</textarea>
                        <div class="mt-1 text-sm text-gray-500">
                            <span id="question2-count">{{ strlen(old('question2', $post->interviewContent?->question2 ?? '')) }}</span>/1000文字
                        </div>
                        @error('question2')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="question3" class="block text-sm font-medium text-gray-700 mb-2">
                            3. 迎えた後の変化と喜び *
                        </label>
                        <p class="text-sm text-gray-600 mb-2">ペットを迎えてからの生活の変化や喜びを教えてください</p>
                        <textarea id="question3" 
                                  name="question3" 
                                  rows="4" 
                                  required
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('question3', $post->interviewContent?->question3 ?? '') }}</textarea>
                        <div class="mt-1 text-sm text-gray-500">
                            <span id="question3-count">{{ strlen(old('question3', $post->interviewContent?->question3 ?? '')) }}</span>/1000文字
                        </div>
                        @error('question3')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="question4" class="block text-sm font-medium text-gray-700 mb-2">
                            4. 未来の里親へのメッセージ *
                        </label>
                        <p class="text-sm text-gray-600 mb-2">これから里親を考えている方へのメッセージをお願いします</p>
                        <textarea id="question4" 
                                  name="question4" 
                                  rows="4" 
                                  required
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('question4', $post->interviewContent?->question4 ?? '') }}</textarea>
                        <div class="mt-1 text-sm text-gray-500">
                            <span id="question4-count">{{ strlen(old('question4', $post->interviewContent?->question4 ?? '')) }}</span>/1000文字
                        </div>
                        @error('question4')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="question5" class="block text-sm font-medium text-gray-700 mb-2">
                            5. 最後に一言 *
                        </label>
                        <p class="text-sm text-gray-600 mb-2">最後に伝えたいことがあればお聞かせください</p>
                        <textarea id="question5" 
                                  name="question5" 
                                  rows="4" 
                                  required
                                  maxlength="1000"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('question5', $post->interviewContent?->question5 ?? '') }}</textarea>
                        <div class="mt-1 text-sm text-gray-500">
                            <span id="question5-count">{{ strlen(old('question5', $post->interviewContent?->question5 ?? '')) }}</span>/1000文字
                        </div>
                        @error('question5')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- 公開設定 -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">公開設定</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="status" value="published" {{ old('status', $post->status) === 'published' ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">公開</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="status" value="draft" {{ old('status', $post->status) === 'draft' ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-700">下書き保存</span>
                        </label>
                    </div>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 送信ボタン -->
                <div class="flex space-x-4">
                    <button type="submit" 
                            class="px-6 py-3 text-white rounded-lg transition duration-200" 
                            style="background-color: #d97706; hover:background-color: #b45309;">
                        更新する
                    </button>
                    <a href="{{ route('mypage.posts', ['type' => 'interview']) }}" 
                       class="px-6 py-3 text-gray-800 rounded-lg transition duration-200" 
                       style="background-color: #f3f4f6; hover:background-color: #e5e7eb;">
                        キャンセル
                    </a>
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
