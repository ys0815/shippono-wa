<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>パスワードリセット | #しっぽのわ</title>
    
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
                <h2 class="text-xl font-bold text-gray-800 mb-2">パスワードリセット</h2>
                <p class="text-gray-600">メールアドレスを入力してください</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-amber-100">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Description -->
                <div class="mb-6 p-4 bg-amber-50 rounded-lg border border-amber-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-amber-700">
                                パスワードを忘れた方へ：<br>
                                登録されているメールアドレスを入力してください。パスワードリセット用のリンクをお送りします。
                            </p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                                      autocomplete="email" 
                                      placeholder="メールアドレスを入力してください" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all duration-200">
                            {{ __('パスワードリセット用メールを送信') }}
                        </button>
                    </div>

                    <!-- Back to Login Link -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            パスワードを思い出した方は
                            <a href="{{ route('login') }}" class="font-medium text-amber-600 hover:text-amber-700 transition-colors">
                                ログインページに戻る
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
