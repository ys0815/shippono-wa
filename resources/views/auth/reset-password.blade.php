<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>新しいパスワード設定 | #しっぽのわ</title>
    
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
                <h2 class="text-xl font-bold text-main-text mb-2">新しいパスワード設定</h2>
                <p class="text-main-text">新しいパスワードを入力してください</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-amber-100">
                <!-- Description -->
                <div class="mb-6 p-4 bg-amber-50 rounded-lg border border-amber-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-amber-700">
                                新しいパスワードを設定してください。<br>
                                セキュリティのため、8文字以上の英数字を含むパスワードをお勧めします。
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('メールアドレス')" class="text-sm font-medium text-main-text mb-2" />
                        <x-text-input id="email" 
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors" 
                                      type="email" 
                                      name="email" 
                                      :value="old('email', $request->email)" 
                                      required 
                                      autofocus 
                                      autocomplete="username" 
                                      placeholder="メールアドレスを入力してください" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('新しいパスワード')" class="text-sm font-medium text-main-text mb-2" />
                        <x-text-input id="password" 
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                      type="password"
                                      name="password"
                                      required 
                                      autocomplete="new-password" 
                                      placeholder="新しいパスワードを入力してください" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        <p class="mt-1 text-sm text-sub-text">8文字以上の英数字を含むパスワードを設定してください</p>
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
                            {{ __('パスワードを更新') }}
                        </button>
                    </div>

                    <!-- Back to Login Link -->
                    <div class="text-center">
                        <p class="text-sm text-main-text">
                            ログインは
                            <a href="{{ route('login') }}" class="font-medium text-amber-600 hover:text-amber-700 transition-colors">
                                こちら
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
