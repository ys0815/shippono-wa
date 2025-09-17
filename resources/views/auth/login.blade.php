<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ログイン | #しっぽのわ</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased" style="font-family: 'Noto Sans JP', sans-serif;">
    <div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-32 w-32 flex items-center justify-center rounded-full bg-gradient-to-br from-amber-100 to-orange-100 shadow-lg mb-6">
                    <img src="{{ asset('images/icon.png') }}" alt="# しっぽのわ" class="h-20 w-20">
                </div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">ログイン</h2>
                <p class="text-gray-600">#しっぽのわにようこそ</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-amber-100">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('メールアドレス')" class="text-sm font-medium text-gray-700 mb-2" />
                        <x-text-input id="email" 
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors" 
                                      type="email" 
                                      name="email" 
                                      :value="old('email')" 
                                      required 
                                      autofocus 
                                      autocomplete="username" 
                                      placeholder="メールアドレスを入力してください" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('パスワード')" class="text-sm font-medium text-gray-700 mb-2" />
                        <x-text-input id="password" 
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                      type="password"
                                      name="password"
                                      required 
                                      autocomplete="current-password" 
                                      placeholder="パスワードを入力してください" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   class="rounded border-gray-300 text-amber-600 shadow-sm focus:ring-amber-500" 
                                   name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('ログイン状態を保持する') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-amber-600 hover:text-amber-700 font-medium transition-colors" 
                               href="{{ route('password.request') }}">
                                {{ __('パスワードを忘れた方') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all duration-200">
                            {{ __('ログイン') }}
                        </button>
                    </div>

                    <!-- Register Link -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            アカウントをお持ちでない方は
                            <a href="{{ route('register') }}" class="font-medium text-amber-600 hover:text-amber-700 transition-colors">
                                新規登録
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center">
                <a href="/" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    ← トップページに戻る
                </a>
            </div>
        </div>
    </div>
</body>
</html>
