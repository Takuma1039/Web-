<x-app-layout>
    <div class="container mx-auto py-8">
        <h1 class="text-4xl font-bold text-gray-800 text-center mb-8">みんなの旅行計画</h1>

        <!-- 検索バー -->
        <div class="flex justify-center mb-8">
            <input type="text" placeholder="探したいキーワード" class="border border-gray-300 p-3 w-1/2 rounded-l-md focus:ring focus:ring-indigo-200">
            <button class="bg-indigo-500 text-white p-3 rounded-r-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l5-5m0 0l-5-5m5 5H4" />
                </svg>
            </button>
        </div>

        <!-- 旅行計画リスト -->
        @if($planposts->isEmpty())
            <p class="text-center text-gray-500 text-xl">まだ旅行計画は投稿されていません。</p>
        @else
            <div class="space-y-8">
                @foreach ($planposts as $planpost)
                    <div class="flex justify-between border border-gray-300 rounded-lg p-6 shadow-sm">
                        <div class="w-3/5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('plans.show', $planpost->plan->id) }}"><h2 class="text-xl font-bold mr-2 transition duration-300 ease-in-out transform hover:text-indigo-600">{{ $planpost->title }}</h2></a>
                                    @auth
                                        <x-planpost-like :planpost="$planpost" />
                                    @endauth
                                </div>
                                @if(Auth::id() === $planpost->user_id)
                                    <form action="{{ route('planposts.destroy', $planpost->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline font-semibold transition duration-200 text-lg">削除</button>
                                    </form>
                                @endif
                            </div>
                            <div class="flex ites-center justify-between">
                                <span class="text-gray-700 font-semibold text-md">投稿者: {{ $planpost->is_anonymous ? '匿名' : $planpost->user->name }}</span>
                                <span class="inline-block bg-teal-100 text-teal-800 text-sm font-medium mr-1 px-2.5 py-0.5 rounded">カテゴリー: 
                                    {{ $planpost->local->name }} 
                                    {{ $planpost->season->name }}
                                    {{ $planpost->month->name }}
                                    {{ $planpost->plantype->name }}
                                </span>
                            </div>
                            <div class="border border-gray-300 bg-gray-50 rounded-md p-5 mt-4">
                                <p class="text-base text-gray-600">{{ $planpost->comment }}</p>
                            </div>
                        </div>

                        <div class="w-2/5 ml-6">
                            @foreach($planpost->planimages as $plan_img)
                                <a class="group relative flex h-48 items-end overflow-hidden rounded-lg shadow-lg transition-transform duration-300 transform hover:scale-105" onclick="openModal('{{ $plan_img->image_path }}')">
                                    <img src="{{ $plan_img->image_path }}" loading="lazy" alt="Image" class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:opacity-90" />
                                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-40"></div>
                                </a>
                            @endforeach
                            <x-modal-window />
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- ページネーション -->
        <div class='mt-8'>
            <x-pagination :paginator="$planposts" />
      </div>
    </div>
</x-app-layout>
