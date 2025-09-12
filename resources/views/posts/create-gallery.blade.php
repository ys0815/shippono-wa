<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- ヘッダー -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center">
                        <a href="{{ route('mypage.posts', ['type' => 'gallery']) }}" class="mr-4">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h1 class="text-xl font-semibold text-gray-900">今日の幸せを投稿</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- メインコンテンツ -->
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <form action="{{ route('mypage.posts.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- エラーメッセージ -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                <!-- ペット選択 -->
                <div>
                    <label for="pet_id" class="block text-sm font-medium text-gray-700 mb-2">ペットを選択</label>
                    <select name="pet_id" id="pet_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">ペットを選択してください</option>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->id }}" {{ old('pet_id') == $pet->id ? 'selected' : '' }}>
                                {{ $pet->name }} ({{ $pet->species }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- タイトル -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">タイトル</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title') }}"
                           maxlength="30"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="今日の幸せを30文字以内で入力">
                    <div class="mt-1 text-sm text-gray-500">
                        <span id="title-count">0</span>/30文字
                    </div>
                </div>

                <!-- 本文 -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">本文</label>
                    <textarea name="content" 
                              id="content" 
                              rows="6"
                              maxlength="300"
                              required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="今日の幸せな出来事を300文字以内で入力">{{ old('content') }}</textarea>
                    <div class="mt-1 text-sm text-gray-500">
                        <span id="content-count">0</span>/300文字
                    </div>
                </div>

                <!-- メディアアップロード（画像・動画） -->
                <div>
                    <label for="media" class="block text-sm font-medium text-gray-700 mb-2">写真・動画</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <input type="file" 
                               name="media[]" 
                               id="media" 
                               multiple
                               accept="image/*,video/*"
                               required
                               class="hidden"
                               onchange="previewMedia(this)">
                        <label for="media" class="cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">写真・動画を選択（最大2ファイル）</p>
                            <p class="text-xs text-gray-500">画像：JPEG, PNG, JPG, GIF（各2MB以下）</p>
                            <p class="text-xs text-gray-500">動画：MP4, MOV, AVI（各10MB以下）</p>
                        </label>
                    </div>
                    
                    <!-- メディアプレビュー -->
                    <div id="media-preview" class="mt-4 grid grid-cols-2 gap-4 hidden">
                        <!-- プレビューメディアがここに表示される -->
                    </div>
                </div>

                <!-- 公開設定 -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">公開設定</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="status" 
                                   value="published" 
                                   {{ old('status', 'published') === 'published' ? 'checked' : '' }}
                                   class="mr-2">
                            <span class="text-sm text-gray-700">公開する</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="status" 
                                   value="draft" 
                                   {{ old('status') === 'draft' ? 'checked' : '' }}
                                   class="mr-2">
                            <span class="text-sm text-gray-700">下書きとして保存</span>
                        </label>
                    </div>
                </div>

                <!-- ボタン -->
                <div class="flex space-x-4">
                    <button type="submit" 
                            class="flex-1 bg-gray-800 text-white py-3 px-4 rounded-lg hover:bg-gray-700 transition duration-200 font-medium">
                        投稿する
                    </button>
                    <a href="{{ route('mypage.posts', ['type' => 'gallery']) }}" 
                       class="flex-1 bg-gray-200 text-gray-800 py-3 px-4 rounded-lg hover:bg-gray-300 transition duration-200 font-medium text-center">
                        キャンセル
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // 文字数カウント
        document.getElementById('title').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('title-count').textContent = count;
        });

        document.getElementById('content').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('content-count').textContent = count;
        });

        // メディアプレビュー（画像・動画）
        function previewMedia(input) {
            const preview = document.getElementById('media-preview');
            preview.innerHTML = '';
            
            if (input.files && input.files.length > 0) {
                preview.classList.remove('hidden');
                
                Array.from(input.files).slice(0, 2).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative';
                        
                        // ファイルタイプを判定
                        const isVideo = file.type.startsWith('video/');
                        
                        if (isVideo) {
                            div.innerHTML = `
                                <video src="${e.target.result}" 
                                       class="w-full h-32 object-cover rounded-lg" 
                                       controls>
                                    お使いのブラウザは動画をサポートしていません。
                                </video>
                                <button type="button" onclick="removeMedia(this)" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm">×</button>
                            `;
                        } else {
                            div.innerHTML = `
                                <img src="${e.target.result}" alt="プレビュー${index + 1}" class="w-full h-32 object-cover rounded-lg">
                                <button type="button" onclick="removeMedia(this)" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm">×</button>
                            `;
                        }
                        
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                preview.classList.add('hidden');
            }
        }

        function removeMedia(button) {
            button.parentElement.remove();
            const preview = document.getElementById('media-preview');
            if (preview.children.length === 0) {
                preview.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
