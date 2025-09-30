<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight">メールアドレス変更</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg space-y-6">
                <div>
                    <x-input-label :value="__('現在のメールアドレス')" />
                    <div class="mt-1 p-4 border rounded text-gray-800">
                        {{ auth()->user()->email }}
                        @if(auth()->user()->email_verified_at)
                            <div class="text-xs text-gray-500 mt-1">登録日: {{ auth()->user()->email_verified_at->format('Y年n月j日') }}</div>
                        @endif
                    </div>
                </div>

                <form method="post" action="{{ route('verification.send') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="new_email" :value="__('新しいメールアドレス')" />
                        <x-text-input id="new_email" name="new_email" type="email" class="mt-1 block w-full" placeholder="new-email@example.com" required />
                    </div>

                    <div>
                        <x-input-label for="new_email_confirmation" :value="__('新しいメールアドレス（確認）')" />
                        <x-text-input id="new_email_confirmation" name="new_email_confirmation" type="email" class="mt-1 block w-full" placeholder="new-email@example.com" required />
                    </div>

                    <div class="pt-4 border-t">
                        <x-input-label for="password_confirm" :value="__('本人確認')" />
                        <x-text-input id="password_confirm" name="password" type="password" class="mt-1 block w-full" placeholder="現在のパスワードを入力" required />
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 p-3 rounded text-sm text-yellow-800">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>変更後、新しいメールアドレスに確認メールが送信されます</li>
                            <li>メール内のリンクをクリックして変更を完了してください</li>
                            <li>確認完了まで現在のメールアドレスが有効です</li>
                        </ul>
                    </div>

                    <x-primary-button class="w-full justify-center">確認メールを送信</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
