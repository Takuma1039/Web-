<x-app-layout>
    <div class="container mx-auto max-w-screen-xl py-4 md:py-8">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-800 text-center mb-6 md:mb-8">みんなの旅行計画</h1>
        
        <!-- 成功メッセージ -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        <!-- 検索バーコンポーネント -->
        <x-plan-search-bar :plantypes="$plantypes" :locals="$locals" :seasons="$seasons" :months="$months" />

        <!-- 旅行計画リスト -->
        @if($planposts->isEmpty())
            <p class="text-center text-gray-500 text-lg md:text-xl">まだ旅行計画は投稿されていません。</p>
        @else
            <div class="p-1 space-y-6 md:space-y-8">
                @foreach ($planposts as $planpost)
                    <div class="flex flex-col md:flex-row justify-between border border-gray-300 rounded-lg p-4 md:p-6 shadow-sm">
                        <div class="w-full flex flex-col md:w-3/5 mb-4 md:mb-0">
                            <div class="flex items-center justify-between mb-3 md:mb-4">
                                <div class="flex flex-row items-center gap-2">
                                    <a href="{{ route('plans.show', $planpost->plan->id) }}">
                                        <h2 class="text-lg md:text-xl font-bold transition duration-300 ease-in-out transform hover:text-indigo-600">{{ $planpost->title }}</h2>
                                    </a>
                                    @auth
                                        @if(Auth::id() !== $planpost->user_id)
                                            <x-planpost-like :planpost="$planpost" />
                                        @endif
                                    @endauth
                                </div>
                                @if(Auth::id() === $planpost->user_id)
                                    <div class="flex">
                                        <a href="/planposts/{{ $planpost->id }}/edit" class="text-indigo-600 hover:underline font-semibold transition duration-200 text-base md:text-lg">編集</a>
                                        <form action="{{ route('planposts.destroy', $planpost->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline font-semibold transition duration-200 text-base md:text-lg">削除</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center justify-between mb-3 md:mb-4">
                                <span class="text-gray-700 font-semibold text-sm md:text-md">投稿者: 
                                    @if(Auth::id() === $planpost->user_id)
                                        {{ $planpost->user->name }}
                                    @else
                                        {{ $planpost->is_anonymous ? '匿名' : $planpost->user->name }}
                                    @endif
                                </span>
                                <span class="inline-block bg-teal-100 text-teal-800 text-xs md:text-sm font-medium mr-1 px-2.5 py-0.5 rounded">カテゴリー: 
                                    {{ $planpost->local->name }} 
                                    {{ $planpost->season->name }}
                                    {{ $planpost->month->name }}
                                    @foreach($planpost->plantypes as $plantype)
                                        {{ $plantype->name }}
                                    @endforeach
                                </span>
                            </div>
                            <div class="border border-gray-300 bg-gray-50 rounded-md p-4 mt-2 md:mt-4 flex-grow">
                                <p class="text-base md:text-md text-gray-600">{{ $planpost->comment }}</p>
                            </div>
                        </div>

                        <div class="w-full md:w-2/5 grid grid-cols-3 gap-4 sm:grid-cols-3 md:grid-cols-2 relative md:ml-6 flex-grow">
                            @foreach($planpost->planimages as $plan_img)
                                <a class="group relative overflow-hidden rounded-lg shadow-lg transition-transform duration-300 transform hover:scale-105" onclick="openModal('{{ $plan_img->image_path }}')">
                                    <img src="{{ asset($plan_img->image_path) }}" loading="lazy" alt="Image" class="h-full w-full object-contain object-cover object-center transition duration-200 group-hover:opacity-90" />
                                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-40"></div>
                                </a>
                            @endforeach
                        </div>
                        <x-modal-window />
                    </div>
                @endforeach
            </div>
        @endif

        <!-- ページネーション -->
        <div class='mt-6 md:mt-8'>
            <x-pagination :paginator="$planposts" />
        </div>
    </div>
</x-app-layout>
