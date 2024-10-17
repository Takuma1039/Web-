<x-app-layout>
    <div class="container mx-auto max-w-screen-xl">
        <h1 class="text-4xl font-extrabold text-gray-800 text-center mb-8">マイページ</h1>
    
        <!-- お気に入りスポットセクション -->
        <div class="mb-8">
            <div class="flex items-center mt-4 mb-2">
                <h1 class="text-xl md:text-2xl font-semibold ml-2 px-2">お気に入りスポット</h1>
                <x-view-more-button route="favoritespot" />
            </div>
            @if($likedSpots->isEmpty())
                <p class="text-center text-gray-500">まだお気に入り登録したスポットはありません。</p>
            @else
                <div id="swipe-container" class="overflow-x-scroll scrollbar-hide mb-4 relative px-2 sm:px-4">
                    <div id="swipe-content" class="flex snap-x snap-mandatory gap-1 w-max">
                        <!-- 各カードのループ -->
                        @foreach ($likedSpots as $likedSpot)
                            <div class="w-full sm:w-48 md:w-64 lg:w-72">
                                <x-spot-card :spot="$likedSpot->spot" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
        <!-- モーダルコンポーネント -->
        <x-modal-window />

        <!-- 作成した旅行計画セクション -->
        <div class="mb-8">
            <div class="flex items-center mt-4 mb-2">
                <h1 class="text-xl md:text-2xl font-semibold ml-2 px-2">作成した旅行計画</h1>
                <x-view-more-button route="plans.index" />
            </div>
            @if($plans->isEmpty())
                <p>まだ作成した旅行計画はありません。</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-2">
                    @foreach ($plans as $plan)
                        <a href="{{ route('plans.show', $plan->id) }}" class="p-6 bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
                            <!-- タイトル -->
                            <h2 class="text-xl font-extrabold mb-3">{{ $plan->title }}</h2>
                            <!-- 旅行日 -->
                            <p class="text-sm text-gray-500 mb-3">
                                <span class="font-semibold">旅行日:</span> 
                                <span>{{ $plan->start_date->format('Y年m月d日') }} {{ $plan->start_time->format('H時i分') }}</span>
                            </p>

                            <!-- 目的地リスト -->
                            <ul class="list-disc pl-5 space-y-1 text-gray-600 text-sm">
                                @foreach ($plan->destinations as $destination)
                                    <li class="font-medium">{{ $destination->name }}</li>
                                @endforeach
                            </ul>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- いいねした旅行計画一覧セクション -->
        <div class="mb-8">
            <div class="flex items-center mt-4 mb-2">
                <h1 class="text-xl md:text-2xl font-semibold ml-2 px-2">いいねした旅行計画</h1>
                <x-view-more-button route="planposts.likesplan" />
            </div>
            @if($likedSpots->isEmpty())
                <p>まだいいねした旅行計画はありません。</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-2">
                    @foreach ($planposts as $planpost)
                        <a href="{{ route('plans.show', $planpost->planpost->plan->id) }}" class="p-6 max-w-sm bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1 hover:scale-105">
                            <h2 class="text-xl font-extrabold mr-2">{{ $planpost->title }}</h2>
                            <p class="text-gray-500 text-sm mb-2">旅行日: 
                                <span class="font-semibold">{{ $planpost->start_date->format('Y年m月d日') }}</span> 
                                <span>{{ $planpost->start_time->format('H時i分') }}</span>
                            </p>
                            <div class="group relative flex h-48 items-end overflow-hidden rounded-xl shadow-lg transition-transform duration-300 transform">
                                <img src="{{ $planpost->planpost->planimages->first()->image_path }}" loading="lazy" alt="Image" class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:opacity-90" />
                                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-50"></div>
                            </div>
                            <p class="text-gray-700 mt-4 mb-2">目的地:
                                <ul class="list-disc pl-5 text-gray-600 text-sm">
                                    @foreach ($planpost->planpost->plan->destinations as $destination)
                                        <li class="font-medium">{{ $destination->name }}</li>
                                    @endforeach
                                </ul>
                            </p>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>


