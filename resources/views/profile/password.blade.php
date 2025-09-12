<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">パスワード変更</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                @if ($errors->updatePassword->any())
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-800"
                    >
                        <div class="font-medium mb-1">パスワードを更新できませんでした。</div>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->updatePassword->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('status') === 'password-updated')
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2500)"
                        class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-sm text-green-800"
                    >
                        パスワードを更新しました。
                    </div>
                @endif

                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <x-input-label for="current_password" :value="__('現在のパスワード')" />
                        <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                        <x-input-error :messages="$errors->updatePassword->get('current_password') ?? []" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('新しいパスワード')" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <p class="text-xs text-gray-500 mt-1">8文字以上、大小英字・数字・記号の組み合わせ推奨</p>
                        <x-input-error :messages="$errors->updatePassword->get('password') ?? []" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('新しいパスワード（確認）')" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation') ?? []" class="mt-2" />
                    </div>

                    <div class="bg-blue-50 border border-blue-200 p-3 rounded text-sm text-blue-800">
                        <div class="font-medium mb-1">パスワードのコツ</div>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>8文字以上に設定してください</li>
                            <li>大小英字・数字・記号を組み合わせましょう</li>
                            <li>生年月日や名前は避けましょう</li>
                        </ul>
                    </div>

                    <x-primary-button class="w-full justify-center">パスワードを変更する</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
