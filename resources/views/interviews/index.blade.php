<x-guest-layout>
    <x-slot name="title">里親インタビュー | #しっぽのわ</x-slot>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- ヒーロー/見出し -->
        <section class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">里親インタビュー</h1>
            <p class="text-gray-600">出会いから今までの物語を、やさしい言葉で。</p>
        </section>

        <!-- 検索 -->
        <section class="mb-8">
            <form method="GET" class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="キーワードで探す" class="flex-1 px-4 py-3 border border-gray-300 rounded-md focus:ring-amber-500 focus:border-amber-500" />
                <button class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-md hover:from-amber-600 hover:to-orange-600">検索</button>
            </form>
        </section>

        <!-- リスト（フラット表示） -->
        <section class="space-y-8">
            @forelse($interviews as $post)
                <article class="bg-white border border-gray-200 rounded-lg p-5 sm:p-6">
                    <div class="flex items-start gap-4">
                        <!-- アイコン（正円） -->
                        <a href="{{ route('interviews.show', $post) }}" class="shrink-0">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden border-4 border-white shadow bg-amber-100">
                                @if($post->pet && $post->pet->profile_image_url)
                                    <img src="{{ $post->pet->profile_image_url }}" alt="{{ $post->pet->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center">
                                        <span class="text-amber-600 text-lg font-bold">{{ mb_substr($post->pet->name ?? 'Pet', 0, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                        </a>

                        <div class="flex-1">
                            <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-1">
                                <a href="{{ route('interviews.show', $post) }}" class="hover:text-amber-600">{{ $post->title ?? ($post->pet->name.'の里親インタビュー') }}</a>
                            </h2>
                            <p class="text-gray-600 text-sm sm:text-base leading-relaxed mb-3 line-clamp-3">{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}</p>
                            <div class="flex items-center gap-3 text-sm text-gray-500">
                                <span class="font-medium text-gray-700">{{ $post->pet->name ?? '名無し' }}</span>
                                @if($post->pet)
                                    <span class="{{ $post->pet->gender === 'male' ? 'text-blue-400' : ($post->pet->gender === 'female' ? 'text-pink-400' : 'text-gray-400') }}">
                                        {{ ['male'=>'♂','female'=>'♀','unknown'=>'?'][$post->pet->gender] ?? '?' }}
                                    </span>
                                @endif
                                <span>飼い主さん：{{ $post->pet->user->display_name ?? $post->pet->user->name }}</span>
                                <span class="ml-auto">{{ $post->created_at->setTimezone('Asia/Tokyo')->format('Y.m.d') }}</span>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <p class="text-gray-500">まだインタビューがありません。</p>
            @endforelse
        </section>

        <div class="mt-10">
            {{ $interviews->links() }}
        </div>
    </main>
</x-guest-layout>
