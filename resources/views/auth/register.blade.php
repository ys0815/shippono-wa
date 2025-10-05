<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>新規登録 | #しっぽのわ</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-main-text antialiased" style="font-family: 'Noto Sans JP', sans-serif;">
    <div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-32 w-32 flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 shadow-lg mb-6">
                    <img src="{{ asset('images/icon.png') }}" alt="# しっぽのわ" class="h-20 w-20">
                </div>
                <h2 class="text-xl font-bold text-main-text mb-2">新規登録</h2>
                <p class="text-main-text">#しっぽのわに参加しましょう</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-amber-100">
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- ハンドルネーム -->
                    <div>
                        <x-input-label for="display_name" :value="__('ハンドルネーム')" class="text-sm font-medium text-main-text mb-2" />
                        <x-text-input id="display_name" 
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors" 
                                      type="text" 
                                      name="display_name" 
                                      :value="old('display_name')" 
                                      required 
                                      autofocus 
                                      autocomplete="display_name" 
                                      placeholder="ハンドルネームを入力してください" />
                        <x-input-error :messages="$errors->get('display_name')" class="mt-2" />
                        <p class="mt-1 text-sm text-sub-text">{{ __('公開されるハンドルネームです') }}</p>
                    </div>

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('メールアドレス')" class="text-sm font-medium text-main-text mb-2" />
                        <x-text-input id="email" 
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors" 
                                      type="email" 
                                      name="email" 
                                      :value="old('email')" 
                                      required 
                                      autocomplete="username" 
                                      placeholder="メールアドレスを入力してください" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('パスワード')" class="text-sm font-medium text-main-text mb-2" />
                        <x-text-input id="password" 
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                      type="password"
                                      name="password"
                                      required 
                                      autocomplete="new-password" 
                                      placeholder="パスワードを入力してください" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('パスワード確認')" class="text-sm font-medium text-main-text mb-2" />
                        <x-text-input id="password_confirmation" 
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                      type="password"
                                      name="password_confirmation" 
                                      required 
                                      autocomplete="new-password" 
                                      placeholder="パスワードを再入力してください" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all duration-200">
                            {{ __('新規登録') }}
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center">
                        <p class="text-sm text-main-text">
                            すでにアカウントをお持ちの方は
                            <a href="{{ route('login') }}" class="font-medium text-amber-600 hover:text-amber-700 transition-colors">
                                ログイン
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center">
                <a href="/" class="text-sm text-sub-text hover:text-main-text transition-colors">
                    ← トップページに戻る
                </a>
            </div>
        </div>
    </div>
</body>
</html>
