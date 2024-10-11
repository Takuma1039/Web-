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
        <p>まだお気に入り登録したスポットはありません。</p>
      @else
        <div id="swipe-container" class="overflow-x-scroll scrollbar-hide mb-4 relative px-0.5" style="overflow-y: hidden;">
          <div id="swipe-content" class="flex snap-x snap-mandatory gap-4" style="width: max-content;">
            <!-- 各カードのループ -->
            @foreach ($likedSpots as $likedSpot)
              <x-spot-card :spot="$likedSpot->spot" />
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
        @if($likedSpots->isEmpty())
          <p>まだ作成した旅行計画はありません。</p>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($plans as $plan)
          <div class="p-6 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-bold mb-4">{{ $plan->title }}</h2>
            <p class="text-gray-700 mb-2">旅行日: {{ $plan->start_date->format('Y年m月d日') }} {{ $plan->start_time->format('H時i分') }}</p>
            <ul class="list-disc pl-5">
              @foreach ($plan->destinations as $destination)
                <li>{{ $destination->name }}</li>
               @endforeach
            </ul>
            <div class="mt-4 flex justify-between">
              <a href="{{ route('plans.show', $plan->id) }}" class="text-blue-600 hover:underline">
                詳細を見る
              </a>
              <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline">削除</button>
              </form>
            </div>
          </div>
        @endforeach
        </div>
        @endif
      </div>

    <!-- いいねした旅行計画一覧セクション -->
    <div class="mb-8">
        <div class="flex items-center mt-4 mb-2">
          <h1 class="text-xl md:text-2xl font-semibold mr-2">いいねした旅行計画</h1>
          <x-view-more-button route="plans.index" />
        </div>
        @if($likedSpots->isEmpty())
          <p>まだいいねした旅行計画はありません。</p>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($plans as $plan)
          <div class="p-6 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition duration-300">
            <h2 class="text-xl font-bold mb-4">{{ $plan->title }}</h2>
            <p class="text-gray-700 mb-2">旅行日: {{ $plan->start_date->format('Y年m月d日') }} {{ $plan->start_time->format('H時i分') }}</p>
            <ul class="list-disc pl-5">
              @foreach ($plan->destinations as $destination)
                <li>{{ $destination->name }}</li>
               @endforeach
            </ul>
            <div class="mt-4 flex justify-between">
              <a href="{{ route('plans.show', $plan->id) }}" class="text-blue-600 hover:underline">
                詳細を見る
              </a>
              <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline">削除</button>
              </form>
            </div>
          </div>
        @endforeach
        </div>
        @endif
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


