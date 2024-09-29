<x-app-layout>
  <div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">マイページ</h1>
    
    <!-- お気に入りスポットセクション -->
    <div class="mb-8">
      <div class="flex items-center mt-4">
        <h2 class="text-xl md:text-2xl font-semibold mr-2">お気に入りのスポット</h2>
        <a href="{{ route('favoritespot') }}" class="flex items-center text-white bg-gradient-to-r from-blue-500 to-indigo-600 border-none py-1 px-3 rounded-full shadow-md transform transition-all duration-200 hover:scale-105 hover:shadow-lg text-sm">
          <span>View More</span>
          <svg class="w-3 h-3 md:w-4 md:h-4 ml-2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
            <path d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
          </svg>
        </a>
      </div>

      @if($likedSpots->isEmpty())
        <p>まだお気に入り登録したスポットはありません。</p>
      @else
        <div x-data="swipeCards()" class="mb-4 overflow-x-scroll scrollbar-hide relative">
          <div class="flex snap-x snap-mandatory gap-4" style="width: max-content;">
            <template x-for="spot in likedSpots" :key="spot.id">
              <div class="flex-none sm:w-48 md:w-64 h-auto snap-center">
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-lg flex flex-col">
                  <a :href="spot.link">
                    <img :src="spot.image" alt="" class="w-full h-32 object-cover"> <!-- 高さを小さく -->
                  </a>
                  <div class="p-2 flex-grow">
                    <h3 class="text-md leading-5 font-bold text-gray-900" x-text="spot.title"></h3> <!-- フォントサイズを小さく -->
                    <p class="text-gray-600 mt-1 text-xs" x-text="spot.description"></p> <!-- フォントサイズを小さく -->
                  </div>
                  <div class="p-2">
                    <a :href="spot.link" class="text-blue-500 hover:underline">詳細を見る</a>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>
      @endif
    </div>

    <!-- 作成した旅行計画セクション -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold mb-2">作成した旅行計画一覧</h2>
      <div x-data="swipeCards()" class="mb-4 overflow-x-scroll scrollbar-hide relative">
        <div class="flex snap-x snap-mandatory gap-4" style="width: max-content;">
          <template x-for="plan in travelPlans" :key="plan.id">
            <div class="flex-none sm:w-48 md:w-64 h-auto snap-center">
              <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-lg flex flex-col">
                <a :href="plan.link">
                  <img :src="plan.image" alt="" class="w-full h-32 object-cover"> <!-- 高さを小さく -->
                </a>
                <div class="p-2 flex-grow">
                  <h3 class="text-md leading-5 font-bold text-gray-900" x-text="plan.title"></h3> <!-- フォントサイズを小さく -->
                  <p class="text-gray-600 mt-1 text-xs" x-text="plan.description"></p> <!-- フォントサイズを小さく -->
                </div>
                <div class="p-2">
                  <a :href="plan.link" class="text-blue-500 hover:underline">詳細を見る</a>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>

    <!-- いいねした旅行計画一覧セクション -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold mb-2">いいねした旅行計画一覧</h2>
      <div x-data="swipeCards()" class="mb-4 overflow-x-scroll scrollbar-hide relative">
        <div class="flex snap-x snap-mandatory gap-4" style="width: max-content;">
          <template x-for="likedPlan in likedTravelPlans" :key="likedPlan.id">
            <div class="flex-none sm:w-48 md:w-64 h-auto snap-center">
              <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-lg flex flex-col">
                <a :href="likedPlan.link">
                  <img :src="likedPlan.image" alt="" class="w-full h-32 object-cover"> <!-- 高さを小さく -->
                </a>
                <div class="p-2 flex-grow">
                  <h3 class="text-md leading-5 font-bold text-gray-900" x-text="likedPlan.title"></h3> <!-- フォントサイズを小さく -->
                  <p class="text-gray-600 mt-1 text-xs" x-text="likedPlan.description"></p> <!-- フォントサイズを小さく -->
                </div>
                <div class="p-2">
                  <a :href="likedPlan.link" class="text-blue-500 hover:underline">詳細を見る</a>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>

  </div>

  <div class="footer mt-6">
    <a href="javascript:history.back();" class="text-blue-500 hover:underline">戻る</a>
  </div>

  <script>
    function swipeCards() {
      return {
        likedSpots: [
          @foreach ($likedSpots as $spot)
          {
            id: {{ $spot->id }},
            image: '{{ $spot->spotimages->first()->image_path ?? asset('images/default-image.jpg') }}',
            title: '{{ $spot->name }}',
            description: '{{ Str::limit($spot->body, 100) }}',
            link: '/spots/{{ $spot->id }}',
          },
          @endforeach
        ],
        travelPlans: [
          @foreach ($travelPlans as $plan)
          {
            id: {{ $plan->id }},
            image: '{{ $plan->image_path ?? asset('images/default-image.jpg') }}',
            title: '{{ $plan->name }}',
            description: '{{ Str::limit($plan->description, 100) }}',
            link: '/travel-plans/{{ $plan->id }}',
          },
          @endforeach
        ],
        likedTravelPlans: [
          @foreach ($likedTravelPlans as $likedPlan)
          {
            id: {{ $likedPlan->id }},
            image: '{{ $likedPlan->image_path ?? asset('images/default-image.jpg') }}',
            title: '{{ $likedPlan->name }}',
            description: '{{ Str::limit($likedPlan->description, 100) }}',
            link: '/travel-plans/{{ $likedPlan->id }}',
          },
          @endforeach
        ],
      };
    }
  </script>
</x-app-layout>


