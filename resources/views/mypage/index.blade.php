<x-app-layout>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-4xl font-extrabold text-gray-800 text-center mb-8">マイページ</h1>
    
    <!-- お気に入りスポットセクション -->
    <div class="mb-8">
        <div class="flex items-center mt-4 mb-2">
          <h1 class="text-xl md:text-2xl font-semibold mr-2">お気に入りスポット</h1>
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
        <h1 class="text-xl md:text-2xl font-semibold mr-2">作成した旅行計画</h1>
        <x-view-more-button route="plans.index" />
    </div>
    @if($plans->isEmpty())
        <p>まだ作成した旅行計画はありません。</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($plans as $plan)
                <div class="p-6 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition duration-300">
                    <a href="{{ route('plans.show', $plan->id) }}"><h2 class="text-xl font-bold mr-2 transition duration-300 ease-in-out transform hover:text-indigo-600">{{ $plan->title }}</h2></a>
                    <p class="text-gray-700 mb-2">旅行日: {{ $plan->start_date->format('Y年m月d日') }} {{ $plan->start_time->format('H時i分') }}</p>
                    <ul class="list-disc pl-5">
                        @foreach ($plan->destinations as $destination)
                            <li>{{ $destination->name }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- いいねした旅行計画一覧セクション -->
<div class="mb-8">
    <div class="flex items-center mt-4 mb-2">
        <h1 class="text-xl md:text-2xl font-semibold mr-2">いいねした旅行計画</h1>
        <x-view-more-button route="planposts.likesplan" />
    </div>
    @if($likedSpots->isEmpty())
        <p>まだいいねした旅行計画はありません。</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($planposts as $planpost)
                <div class="p-6 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition duration-300">
                    <a href="{{ route('plans.show', $planpost->planpost->plan->id) }}"><h2 class="text-xl font-bold mr-2 transition duration-300 ease-in-out transform hover:text-indigo-600">{{ $planpost->title }}</h2></a>
                    <p class="text-gray-700 mb-2">旅行日: {{ $planpost->start_date->format('Y年m月d日') }} {{ $planpost->start_time->format('H時i分') }}</p>
                    @foreach($planpost->planpost->planimages as $plan_img)
                        <a class="group relative flex h-48 items-end overflow-hidden rounded-lg shadow-lg transition-transform duration-300 transform hover:scale-105" onclick="openModal('{{ $plan_img->image_path }}')">
                            <img src="{{ $plan_img->image_path }}" loading="lazy" alt="Image" class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:opacity-90" />
                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-40"></div>
                        </a>
                    @endforeach
                    <x-modal-window />
                    <p class="text-gray-700 mb-2">目的地:
                        <ul class="list-disc pl-5">
                            @foreach ($planpost->planpost->plan->destinations as $destination)
                                <li>{{ $destination->name }}</li>
                            @endforeach
                        </ul>
                    </p>
                </div>
            @endforeach
        </div>
    @endif
</div>
</x-app-layout>


