<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg text-gray-800 leading-tight">ペット詳細ガイド</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg space-y-6">
                <p class="text-sm text-gray-700">ここに送付いただくワイヤーフレームの内容を配置します。セクション構成・文言は後で差し替え可能です。</p>

                <div class="pt-4 border-t">
                    <a href="{{ route('mypage.pets.create') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-900 text-white rounded">+ 新しいペットを登録</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


