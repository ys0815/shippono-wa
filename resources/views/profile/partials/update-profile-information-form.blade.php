<section>
    <header class="mb-6">
        <h2 class="text-base font-semibold text-gray-900">基本情報</h2>
    </header>

    @if ($errors->any())
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition
            class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-800"
        >
            <div class="font-medium mb-1">プロフィールを更新できませんでした。</div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('status') === 'profile-updated')
        <div
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 2500)"
            class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-sm text-green-800"
        >
            プロフィールを更新しました。
        </div>
    @endif

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <!-- メール・パスワードは別ページへ（ボタンのみ） -->
        <div>
            <x-input-label for="email" :value="__('メールアドレス')" />
            <div class="flex gap-3">
                <x-text-input id="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" disabled />
                <a href="{{ route('mypage.profile.email') }}" class="px-3 py-2 mt-1 rounded border text-gray-700">変更</a>
            </div>
        </div>

        <div>
            <x-input-label for="password" :value="__('パスワード')" />
            <div class="flex gap-3">
                <x-text-input id="password" type="password" class="mt-1 block w-full" value="********" disabled />
                <a href="{{ route('mypage.profile.password') }}" class="px-3 py-2 mt-1 rounded border text-gray-700">変更</a>
            </div>
        </div>

        <div>
            <x-input-label for="display_name" :value="__('ハンドルネーム')" />
            <x-text-input id="display_name" name="display_name" type="text" class="mt-1 block w-full" :value="old('display_name', $user->display_name)" required />
            <p class="mt-1 text-xs text-gray-500">※投稿やプロフィールで表示される名前です</p>
            <x-input-error class="mt-2" :messages="$errors->get('display_name')" />
        </div>

        <header class="pt-4 border-t">
            <h3 class="text-base font-semibold text-gray-900 mt-4">SNSアカウント設定</h3>
        </header>

        <div>
            <x-input-label for="sns_x" :value="__('X（旧Twitter）')" />
            <div class="flex items-center gap-2">
                <span class="text-gray-500">@</span>
                <x-text-input id="sns_x" name="sns_x" type="text" class="mt-1 block w-full" :value="old('sns_x', $user->sns_x)" placeholder="example._pet" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('sns_x')" />
        </div>

        <div>
            <x-input-label for="sns_instagram" :value="__('Instagram')" />
            <div class="flex items-center gap-2">
                <span class="text-gray-500">@</span>
                <x-text-input id="sns_instagram" name="sns_instagram" type="text" class="mt-1 block w-full" :value="old('sns_instagram', $user->sns_instagram)" placeholder="example.with.pets" />
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('sns_instagram')" />
        </div>

        <div>
            <x-input-label for="sns_facebook" :value="__('Facebook')" />
            <x-text-input id="sns_facebook" name="sns_facebook" type="text" class="mt-1 block w-full" :value="old('sns_facebook', $user->sns_facebook)" placeholder="https://facebook.com/yourpage" />
            <x-input-error class="mt-2" :messages="$errors->get('sns_facebook')" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full justify-center">プロフィールを更新する</x-primary-button>
        </div>
    </form>
</section>
