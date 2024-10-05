<x-app-layout>
  <div class="mx-auto max-w-screen-2xl bg-gray-100">
    <div class="overflow-hidden">
      <div class="flex-1 overflow-auto p-4">
        <!--検索バーコンポーネント-->
        <x-search-bar :spotcategories="$spotcategories" :locals="$locals" :seasons="$seasons" :months="$months" />

        <div id="default-carousel" class="relative w-full" data-carousel="slide">
          <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
            @foreach($slideImages as $index => $image)
              <div class="{{ $index === 0 ? '' : 'hidden' }} duration-700 ease-in-out" data-carousel-item>
                <img src="{{ $image }}" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 rounded-lg" alt="Spot Image {{ $index + 1 }}">
              </div>
            @endforeach
          </div>
    
          <!-- Navigation Buttons (dots) -->
          <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
            @foreach($slideImages as $index => $image)
              <button type="button" class="w-3 h-3 rounded-full {{ $index === 0 ? 'bg-blue-500' : 'bg-gray-300' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="スライド {{ $index + 1 }}" data-carousel-slide-to="{{ $index }}"></button>
            @endforeach
          </div>

          <!-- Previous button -->
          <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
              <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
              </svg>
              <span class="sr-only">前へ</span>
            </span>
          </button>

          <!-- Next button -->
          <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
              <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l4 4-4 4"/>
              </svg>
              <span class="sr-only">次へ</span>
            </span>
          </button>
        </div>

	            
        <!--お気に入りランキング--> 
        <div class="flex items-center mt-4 mb-2">
          <h1 class="text-xl md:text-2xl font-semibold mr-2">人気スポットランキング</h1>
          <x-view-more-button route="major.ranking" />
        </div>

        <div id="swipe-container" class="overflow-x-scroll scrollbar-hide mb-4 relative px-0.5" style="overflow-y: hidden;">
          <div id="swipe-content" class="flex snap-x snap-mandatory gap-4" style="width: max-content;">
            <!-- 各カードのループ -->
            @foreach ($majorspots as $spot)
              <x-spot-card :spot="$spot" />
            @endforeach
          </div>
        </div>
        
        <!-- モーダルコンポーネント -->
        <x-modal-window />

        <!--口コミスポットランキング-->
        <div class="flex items-center mt-4 mb-2">
          <h1 class="text-xl md:text-2xl font-semibold mr-2">口コミスポットランキング</h1>
          <x-view-more-button route="review.ranking" />
        </div>

        <div id="swipe-container" class="overflow-x-scroll scrollbar-hide mb-4 relative px-0.5" style="overflow-y: hidden;">
          <div id="swipe-content" class="flex snap-x snap-mandatory gap-4" style="width: max-content;">
            <!-- 各カードのループ -->
            @foreach ($reviewspots as $spot)
              <x-spot-card :spot="$spot" />
            @endforeach
          </div>
        </div>

        <!--おすすめスポットランキング-->
        <div class="flex items-center mt-4 mb-2">
          <h1 class="text-xl md:text-2xl font-semibold mr-2">今の時期におすすめなスポットランキング</h1>
            <x-view-more-button route="season.ranking" />
        </div>

        <div id="swipe-container" class="overflow-x-scroll scrollbar-hide mb-4 relative px-0.5" style="overflow-y: hidden;">
          <div id="swipe-content" class="flex snap-x snap-mandatory gap-4" style="width: max-content;">
            <!-- 各カードのループ -->
            @foreach ($seasonspots as $spot)
              <x-spot-card :spot="$spot" />
            @endforeach
          </div>
        </div>

        <!--スポット一覧-->
        <div class="flex items-center mt-4 mb-2">
          <h1 class="text-xl md:text-2xl font-semibold mr-2">スポット一覧</h1>
          <x-view-more-button route="spots.index" />
        </div>

        <div id="swipe-container" class="overflow-x-scroll scrollbar-hide mb-4 relative px-0.5" style="overflow-y: hidden;">
          <div id="swipe-content" class="flex snap-x snap-mandatory gap-4" style="width: max-content;">
            <!-- 各カードのループ -->
            @foreach ($spotlists as $spot)
              <x-spot-card :spot="$spot" />
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    // スワイプ操作の実装
const swipeContainer = document.getElementById('swipe-container');
let isDown = false;
let startX;
let scrollLeft;

// マウスイベント
const mouseDownHandler = (e) => {
    isDown = true;
    swipeContainer.classList.add('active');
    startX = e.pageX - swipeContainer.offsetLeft;
    scrollLeft = swipeContainer.scrollLeft;
};

const mouseLeaveHandler = () => {
    isDown = false;
    swipeContainer.classList.remove('active');
};

const mouseUpHandler = () => {
    isDown = false;
    swipeContainer.classList.remove('active');
};

const mouseMoveHandler = (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - swipeContainer.offsetLeft;
    const walk = (x - startX) * 1; // スクロール量を調整
    swipeContainer.scrollLeft = scrollLeft - walk;
};

// タッチイベント
const touchStartHandler = (e) => {
    isDown = true;
    swipeContainer.classList.add('active');
    startX = e.touches[0].pageX - swipeContainer.offsetLeft;
    scrollLeft = swipeContainer.scrollLeft;
};

const touchEndHandler = () => {
    isDown = false;
    swipeContainer.classList.remove('active');
};

const touchMoveHandler = (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.touches[0].pageX - swipeContainer.offsetLeft;
    const walk = (x - startX) * 1; // スクロール量を調整
    swipeContainer.scrollLeft = scrollLeft - walk;
};

// イベントリスナーの登録
swipeContainer.addEventListener('mousedown', mouseDownHandler);
swipeContainer.addEventListener('mouseleave', mouseLeaveHandler);
swipeContainer.addEventListener('mouseup', mouseUpHandler);
swipeContainer.addEventListener('mousemove', mouseMoveHandler);

// タッチイベントの登録
swipeContainer.addEventListener('touchstart', touchStartHandler);
swipeContainer.addEventListener('touchend', touchEndHandler);
swipeContainer.addEventListener('touchmove', touchMoveHandler);

  </script>
</x-app-layout>

