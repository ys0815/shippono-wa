<x-app-layout>

    <div class="min-h-screen bg-main-bg">
        <!-- ヘッダー（固定） -->
        <div class="bg-white/90 backdrop-blur border-b border-amber-100 shadow-sm sticky top-16 z-[900]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <a href="{{ route('mypage') }}" class="mr-4">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h1 class="text-lg font-semibold text-amber-900">メールアドレス変更</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- メインコンテンツ -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">
                <div>
                    <x-input-label :value="__('現在のメールアドレス')" />
                    <div class="mt-1 p-4 border rounded text-main-text">
                        {{ auth()->user()->email }}
                        @if(auth()->user()->email_verified_at)
                            <div class="text-xs text-sub-text mt-1">登録日: {{ auth()->user()->email_verified_at->format('Y年n月j日') }}</div>
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

                    <div class="bg-amber-50 border border-amber-200 p-3 rounded text-sm text-amber-800">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>変更後、新しいメールアドレスに確認メールが送信されます</li>
                            <li>メール内のリンクをクリックして変更を完了してください</li>
                            <li>確認完了まで現在のメールアドレスが有効です</li>
                        </ul>
                    </div>

                    <button type="submit" class="btn btn-brand w-full py-3">
                        確認メールを送信
                    </button>
                </form>
        </div>
    </div>
</x-app-layout>
