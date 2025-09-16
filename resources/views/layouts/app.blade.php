<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('images/icon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen pt-16">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white/90 backdrop-blur border-b border-amber-100 shadow-sm sticky top-16 z-30">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="relative z-10">
                {{ $slot }}
            </main>
        </div>

        <!-- 共通確認モーダル -->
        <x-confirmation-modal 
            id="confirmation-modal"
            title="確認"
            message="この操作を実行しますか？"
            confirm-text="実行"
            cancel-text="キャンセル"
            confirm-class="bg-red-600 hover:bg-red-700 text-white"
            icon="⚠️" />

        <!-- デバッグ用テストボタン（本番では削除） -->
        <div style="position: fixed; bottom: 20px; right: 20px; z-index: 10000;">
            <button onclick="window.dispatchEvent(new CustomEvent('open-confirm', {
                detail: { 
                    id: 'confirmation-modal',
                    title: 'テストモーダル', 
                    message: 'モーダル表示テストです', 
                    formId: null, 
                    confirmText: 'テスト実行',
                    cancelText: 'キャンセル',
                    confirmClass: 'bg-blue-600 hover:bg-blue-700 text-white',
                    icon: '🧪'
                }
            }))" 
            class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
                モーダルテスト
            </button>
        </div>
    </body>
</html>
