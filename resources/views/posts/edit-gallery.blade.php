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
                        <a href="{{ route('mypage.posts', ['type' => 'gallery']) }}" class="mr-4">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h1 class="text-lg font-semibold text-amber-900">今日の幸せ投稿を編集</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- メインコンテンツ -->
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- 温かみのあるメッセージ -->
            <div class="rounded-lg p-6 mb-8 bg-gradient-to-r from-amber-100 to-orange-200 border border-amber-300">
                <div class="flex items-center mb-4">
                    <svg class="w-10 h-10 mr-3 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                    </svg>
                    <h2 class="text-lg font-semibold text-amber-800">幸せな投稿をより良くしましょう</h2>
                </div>
                <p class="text-base leading-relaxed mb-3 text-amber-700">
                    ペットとの日常にある小さな幸せを、写真や動画と一緒に残してみませんか？<br>
                    そのひとときの温かさは、きっと同じ気持ちを分かち合う誰かに届きます。
                </p>
                <div class="flex items-center text-sm text-amber-600">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <span>どんなささやかな瞬間も大切です。あなたの日常が、誰かの心をそっと和ませます。</span>
                </div>
            </div>

            <form action="{{ route('mypage.posts.update', $post) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf
                @method('PUT')
                
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
                    <label for="pet_id" class="block text-base font-medium text-main-text mb-2">ペットを選択</label>
                    <div class="p-3 mb-3 bg-yellow-50">
                        <p class="text-sm text-yellow-800">
                            🐾 今日はどの子の幸せを分かち合いますか？かけがえのない家族を選んでくださいね。
                        </p>
                    </div>
                    <select name="pet_id" id="pet_id" required 
                            class="w-full px-3 py-2 border border-sub-border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        <option value="">ペットを選択してください</option>
                        @foreach($pets as $pet)
                            <option value="{{ $pet->id }}" {{ old('pet_id', $post->pet_id) == $pet->id ? 'selected' : '' }}>
                                {{ $pet->name }} ({{ $pet->species }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- タイトル -->
                <div>
                    <label for="title" class="block text-base font-medium text-main-text mb-2">タイトル</label>
                    <div class="p-3 mb-3 bg-yellow-50">
                        <p class="text-sm text-yellow-800">
                            ✨ その瞬間の気持ちを素直にひとこと。短くても、想いはきっと伝わります。
                        </p>
                    </div>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $post->title) }}"
                           maxlength="30"
                           required
                           class="w-full px-3 py-2 border border-sub-border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                           placeholder="今日の幸せを30文字以内で入力">
                    <div class="mt-1 text-sm text-sub-text">
                        <span id="title-count">{{ strlen(old('title', $post->title)) }}</span>/30文字
                    </div>
                </div>

                <!-- 本文 -->
                <div>
                    <label for="content" class="block text-base font-medium text-main-text mb-2">本文</label>
                    <div class="p-3 mb-3 bg-yellow-50">
                        <p class="text-sm text-yellow-800">
                            💝 その時に感じた喜びや愛しさを、自由に書いてください。小さな出来事が、大きな温もりとなって広がります。
                        </p>
                    </div>
                    <textarea name="content" 
                              id="content" 
                              rows="6"
                              maxlength="140"
                              required
                              class="w-full px-3 py-2 border border-sub-border rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                              placeholder="今日の幸せな出来事を140文字以内で入力">{{ old('content', $post->content) }}</textarea>
                    <div class="mt-1 text-sm text-sub-text">
                        <span id="content-count">{{ strlen(old('content', $post->content)) }}</span>/140文字
                    </div>
                </div>

                <!-- 現在のメディア -->
                @if($post->media->count() > 0)
                    <div>
                        <label class="block text-base font-medium text-main-text mb-2">現在のメディア</label>
                        <div class="grid grid-cols-2 gap-4">
                            @foreach($post->media as $media)
                                <div class="relative">
                                    @if($media->type === 'image')
                                        <img src="{{ Storage::url($media->url) }}" 
                                             alt="現在の画像" 
                                             class="w-full h-32 object-cover rounded-lg">
                                    @elseif($media->type === 'video')
                                        <video src="{{ Storage::url($media->url) }}" 
                                               class="w-full h-32 object-cover rounded-lg"
                                               controls muted preload="metadata" playsinline
                                               style="opacity: 0;"
                                               onloadeddata="this.style.opacity='1';"
                                               oncanplay="this.style.opacity='1';"
                                               onloadstart="this.style.opacity='0.5';">
                                            お使いのブラウザは動画をサポートしていません。
                                        </video>
                                    @endif
                                    <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                        <span class="text-white text-sm">現在の{{ $media->type === 'image' ? '画像' : '動画' }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-sm text-sub-text">新しいメディアを選択すると、現在のメディアは置き換えられます</p>
                    </div>
                @endif

                <!-- メディアアップロード（画像・動画） -->
                <div>
                    <label for="media" class="block text-base font-medium text-main-text mb-2">新しい写真・動画</label>
                    <div class="p-3 mb-3 bg-yellow-50">
                        <p class="text-sm text-yellow-800">
                            📸 写真や動画で幸せを形にしましょう。見る人も一緒に、その空気を感じられます。
                        </p>
                    </div>
                    <div class="border-2 border-dashed border-sub-border rounded-lg p-6 text-center">
                        <input type="file" 
                               name="media[]" 
                               id="media" 
                               multiple
                               accept="image/*,video/*"
                               class="hidden"
                               onchange="previewMedia(this)">
                        <label for="media" class="cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-main-text">新しい写真・動画を選択（最大2ファイル）</p>
                            <p class="text-xs text-sub-text">画像：JPEG, PNG, JPG, GIF（各2MB以下）</p>
                            <p class="text-xs text-sub-text">動画：MP4, MOV, AVI（各10MB以下）</p>
                        </label>
                    </div>
                    
                    <!-- メディアプレビュー -->
                    <div id="media-preview" class="mt-4 grid grid-cols-2 gap-4 hidden">
                        <!-- プレビューメディアがここに表示される -->
                    </div>
                </div>

                <!-- 公開設定 -->
                <div>
                    <label class="block text-base font-medium text-main-text mb-2">公開設定</label>
                    <div class="p-3 mb-3 bg-yellow-50">
                        <p class="text-sm text-yellow-800">
                            🌟 公開すれば仲間と幸せを分け合えます。もちろん下書きにして、あなたのペースで大切に残すこともできます。
                        </p>
                    </div>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="status" 
                                   value="published" 
                                   {{ old('status', $post->status) === 'published' ? 'checked' : '' }}
                                   class="mr-2">
                            <span class="text-sm text-main-text">公開する</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   name="status" 
                                   value="draft" 
                                   {{ old('status', $post->status) === 'draft' ? 'checked' : '' }}
                                   class="mr-2">
                            <span class="text-sm text-main-text">下書きとして保存</span>
                        </label>
                    </div>
                </div>

                <!-- ボタン -->
                <div class="bg-main-bg rounded-lg p-4 mb-6">
                    <div class="text-center mb-4">
                        <p class="text-sm text-main-text">
                            💝 あなたの「幸せな瞬間」が、「#しっぽのわ」をもっとやさしく彩ります。
                        </p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('mypage.posts', ['type' => 'gallery']) }}" class="btn btn-outline flex-1 justify-center">
                            キャンセル
                        </a>
                        <button type="submit" class="btn btn-brand flex-1 justify-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" focusable="false">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                            </svg>
                            更新する
                        </button>
                    </div>
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

        // メディアプレビュー（画像・動画）: URL.createObjectURLで即時表示し、revokeで解放
        function previewMedia(input) {
            const preview = document.getElementById('media-preview');
            // 既存のObjectURLを解放
            Array.from(preview.querySelectorAll('[data-object-url]')).forEach(el => {
                const url = el.getAttribute('data-object-url');
                if (url) URL.revokeObjectURL(url);
            });
            preview.innerHTML = '';

            if (input.files && input.files.length > 0) {
                preview.classList.remove('hidden');

                Array.from(input.files).slice(0, 2).forEach((file, index) => {
                    const objectUrl = URL.createObjectURL(file);
                    const div = document.createElement('div');
                    div.className = 'relative';

                    const isVideo = file.type.startsWith('video/');

                    if (isVideo) {
                        div.innerHTML = `
                            <video class="w-full h-32 object-cover rounded-lg" 
                                   controls muted preload="metadata" playsinline
                                   style="opacity: 1;"
                                   data-object-url="${objectUrl}">
                                <source src="${objectUrl}" type="video/mp4">
                                お使いのブラウザは動画をサポートしていません。
                            </video>
                            <button type="button" onclick="removeMedia(this)" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm">×</button>
                        `;
                        
                        // 動画プレビュー用の先頭フレーム生成
                        const video = div.querySelector('video');
                        if (video) {
                            video.addEventListener('loadedmetadata', function() {
                                try {
                                    video.currentTime = 0.1;
                                } catch(e) {}
                            });
                            
                            video.addEventListener('seeked', function() {
                                try {
                                    const canvas = document.createElement('canvas');
                                    canvas.width = video.videoWidth || 480;
                                    canvas.height = video.videoHeight || 270;
                                    const ctx = canvas.getContext('2d');
                                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                                    const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                                    if (dataUrl) {
                                        video.setAttribute('poster', dataUrl);
                                    }
                                } catch(e) {}
                            }, { once: true });
                        }
                    } else {
                        div.innerHTML = `
                            <img src="${objectUrl}" alt="プレビュー${index + 1}" class="w-full h-32 object-cover rounded-lg" data-object-url="${objectUrl}">
                            <button type="button" onclick="removeMedia(this)" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm">×</button>
                        `;
                    }

                    preview.appendChild(div);
                });

                preview.querySelectorAll('video').forEach(v => {
                    v.style.opacity = '0';
                    v.addEventListener('loadeddata', () => { v.style.opacity = '1'; }, { once: true });
                });
            } else {
                preview.classList.add('hidden');
            }
        }

        function removeMedia(button) {
            const wrapper = button.parentElement;
            const media = wrapper.querySelector('[data-object-url]');
            if (media) {
                const url = media.getAttribute('data-object-url');
                if (url) URL.revokeObjectURL(url);
            }
            wrapper.remove();
            const preview = document.getElementById('media-preview');
            if (preview.children.length === 0) {
                preview.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
