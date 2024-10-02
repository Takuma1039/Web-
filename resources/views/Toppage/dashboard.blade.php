<x-app-layout>
    <div class="mx-auto max-w-screen-2xl bg-gray-100">
        <div class="overflow-hidden">
            <div class="flex-1 overflow-auto p-4">
                <!-- 検索バー -->
                <div class="flex justify-center mb-6">
                    <input id="searchInput" type="text" placeholder="探したいキーワード" class="border border-gray-300 rounded-md p-2 w-1/2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                        検索
                    </button>
                </div>

                <!-- モーダル -->
                <div id="searchModal" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-xl w-full">
                        <h2 class="text-lg font-semibold mb-4 text-blue-500">Search for a Location</h2>
                        <form action="/search" method="GET">
                            <input type="text" name="query" class="w-full border border-gray-300 p-2 rounded-md mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="探したいキーワード">
                            
                            <h3 class="mb-2 font-semibold">スポットカテゴリー:</h3>
                            <div class="flex flex-wrap mb-4">
                                @foreach($spotcategories as $spotcategory)
                                    <label class="flex items-center mr-4 mb-2">
                                        <input type="checkbox" name="spot[spot_category_ids][]" value="{{ $spotcategory->id }}" class="mr-2">
                                        {{ $spotcategory->name }}
                                    </label>
                                @endforeach
                            </div>

                            <h3 class="mb-2 font-semibold">地域:</h3>
                            <div class="flex flex-wrap mb-4">
                                @foreach($locals as $local)
                                    <label class="flex items-center mr-4 mb-2">
                                        <input type="checkbox" name="spot[local_ids][]" value="{{ $local->id }}" class="mr-2">
                                        {{ $local->name }}
                                    </label>
                                @endforeach
                            </div>

                            <h3 class="mb-2 font-semibold">季節:</h3>
                            <div class="flex flex-wrap mb-4">
                                @foreach($seasons as $season)
                                    <label class="flex items-center mr-4 mb-2">
                                        <input type="checkbox" name="spot[season_ids][]" value="{{ $season->id }}" class="mr-2">
                                        {{ $season->name }}
                                    </label>
                                @endforeach
                            </div>

                            <h3 class="mb-2 font-semibold">月:</h3>
                            <div class="flex flex-wrap mb-4">
                                @foreach($months as $month)
                                    <label class="flex items-center mr-4 mb-2">
                                        <input type="checkbox" name="spot[month_ids][]" value="{{ $month->id }}" class="mr-2">
                                        {{ $month->name }}
                                    </label>
                                @endforeach
                            </div>

                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">検索</button>
                        </form>
                        <button id="closeModal" class="mt-4 text-blue-500 hover:underline">閉じる</button>
                    </div>
                </div>

                <script>
                    const searchInput = document.getElementById("searchInput");
                    const searchModal = document.getElementById("searchModal");
                    const closeModal = document.getElementById("closeModal");

                    searchInput.addEventListener("focus", () => {
                        searchModal.classList.remove("hidden");
                    });

                    closeModal.addEventListener("click", () => {
                        searchModal.classList.add("hidden");
                    });

                    searchModal.addEventListener("click", (event) => {
                        if (event.target === searchModal) {
                            searchModal.classList.add("hidden");
                        }
                    });
                </script>

                <div id="default-carousel" class="relative w-full" data-carousel="slide">
                    <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <img src="https://www.nta.co.jp/media/tripa/static_contents/nta-tripa/articles/images/000/000/418/medium/8342c3cf-fbb8-402a-9bd8-37fd7f1b8c33.jpg?1550646782" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 rounded-lg" alt="...">
                        </div>
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <img src="https://img.freepik.com/free-photo/fuji-mountain-and-kawaguchiko-lake-in-morning-autumn-seasons-fuji-mountain-at-yamanachi-in-japan_335224-102.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 rounded-lg" alt="...">
                        </div>
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <img src="https://img.freepik.com/free-photo/purple-nature-landscape-with-vegetation_23-2150859581.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 rounded-lg" alt="...">
                        </div>
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <img src="https://designwork-s.net/blog/wp-content/uploads/scientifantastic1.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 rounded-lg" alt="...">
                        </div>
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <img src="https://www.a-kimama.com/wp-content/uploads/2016/06/20160628-1.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 rounded-lg" alt="...">
                        </div>
                    </div>
                    
                    <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                        <button type="button" class="w-3 h-3 rounded-full bg-blue-500" aria-current="true" aria-label="スライド 1" data-carousel-slide-to="0"></button>
                        <button type="button" class="w-3 h-3 rounded-full bg-gray-300" aria-current="false" aria-label="スライド 2" data-carousel-slide-to="1"></button>
                        <button type="button" class="w-3 h-3 rounded-full bg-gray-300" aria-current="false" aria-label="スライド 3" data-carousel-slide-to="2"></button>
                        <button type="button" class="w-3 h-3 rounded-full bg-gray-300" aria-current="false" aria-label="スライド 4" data-carousel-slide-to="3"></button>
                        <button type="button" class="w-3 h-3 rounded-full bg-gray-300" aria-current="false" aria-label="スライド 5" data-carousel-slide-to="4"></button>
                    </div>
                    
                    <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                            <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                            </svg>
                            <span class="sr-only">前へ</span>
                        </span>
                    </button>
                    <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 group-focus:ring-4 group-focus:ring-white group-focus:outline-none">
                            <svg class="w-4 h-4 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l4 4-4 4"/>
                            </svg>
                            <span class="sr-only">次へ</span>
                        </span>
                    </button>
                </div>
	            
          <!--ranking--> 
<div class="flex items-center mt-4 mb-2">
    <h1 class="text-xl md:text-2xl font-semibold mr-2">人気スポットランキング</h1>
    <a href="{{ route('major.ranking') }}" class="flex items-center text-white bg-gradient-to-r from-blue-500 to-indigo-600 border-none py-1 px-3 rounded-full shadow-md transform transition-all duration-200 hover:scale-105 hover:shadow-lg text-sm">
        <span>View More</span>
        <svg class="w-3 h-3 md:w-4 md:h-4 ml-2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
        </svg>
    </a>
</div>

<div id="swipe-container" class="overflow-x-scroll scrollbar-hide mb-4 relative px-0.5" style="overflow-y: hidden;">
    <div id="swipe-content" class="flex snap-x snap-mandatory gap-4" style="width: max-content;">
        <!-- 各カードのループ -->
        @foreach ($spots as $spot)
            <div class="flex-none sm:w-48 md:w-64 h-auto snap-center">
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden relative h-full flex flex-col justify-between">
                    <div>
                        <!-- 画像の表示 -->
                        <a onclick="openModal('{{ $spot->spotimages->first()->image_path ?? asset('images/default-image.jpg') }}')" aria-label="スポットの詳細ページへ移動">
                            <img src="{{ $spot->spotimages->first()->image_path ?? asset('images/default-image.jpg') }}" alt="" class="w-full h-40 object-cover" loading="lazy">
                        </a>
                        <div class="p-4">
                            <h3 class="text-lg leading-6 font-bold text-gray-900">
                                <a href="/spots/{{ $spot->id }}" class="transition duration-300 ease-in-out transform hover:text-indigo-600">{{ $spot->name }}</a>
                            </h3>
                            <p class="text-gray-600 mt-2 text-sm">{{ $spot->truncated_body }}</p>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mt-2">
                            <!-- 評価の表示（詳細は後述） -->
                            @for ($i = 0; $i < 5; $i++)
                                @php
                                    $currentValue = $i + 1;
                                    $fullStar = $averageRating >= $currentValue;
                                    $partialStar = !$fullStar && $averageRating > $currentValue - 1 && $averageRating < $currentValue;
                                    $fillPercentage = $partialStar ? ($averageRating - ($currentValue - 1)) * 100 : 0;
                                @endphp
                                <div class="relative inline-block">
                                    <i class="fas fa-star text-gray-400 text-2xl"></i>
                                    @if ($fullStar)
                                        <i class="fas fa-star text-yellow-500 text-2xl absolute top-0 left-0"></i>
                                    @elseif ($partialStar)
                                        <div class="absolute top-0 left-0 h-full overflow-hidden" style="width: {{ $fillPercentage }}%;">
                                            <i class="fas fa-star text-yellow-500 text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                            @endfor
                            <span class="ml-2 text-gray-600 text-sm"><span class="text-gray-500 italic font-extrabold">{{ $totalReviews }} reviews</span></span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- モーダルウィンドウ -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
    <div class="relative bg-white rounded-lg shadow-lg overflow-hidden w-full max-w-3xl">
        <button onclick="closedModal()" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="拡大画像" class="w-full h-auto object-contain">
    </div>
</div>

<script>
    // モーダルを開く関数
    function openModal(imageSrc) {
        // 画像のソースをモーダル内の画像に設定
        document.getElementById('modalImage').src = imageSrc;
        // モーダルを表示
        document.getElementById('imageModal').classList.remove('hidden');
    }

    // モーダルを閉じる関数
    function closedModal() {
        // モーダルを非表示
        document.getElementById('imageModal').classList.add('hidden');
    }
    
    // モーダル外側をクリックして閉じる
    document.getElementById('imageModal').addEventListener('click', function(event) {
      if (event.target === this) {
        closedModal();
      }
    });
</script>
<script>
    // スワイプ操作の実装
    const swipeContainer = document.getElementById('swipe-container');
    let isDown = false;
    let startX;
    let scrollLeft;

    swipeContainer.addEventListener('mousedown', (e) => {
        isDown = true;
        swipeContainer.classList.add('active');
        startX = e.pageX - swipeContainer.offsetLeft;
        scrollLeft = swipeContainer.scrollLeft;
    });

    swipeContainer.addEventListener('mouseleave', () => {
        isDown = false;
        swipeContainer.classList.remove('active');
    });

    swipeContainer.addEventListener('mouseup', () => {
        isDown = false;
        swipeContainer.classList.remove('active');
    });

    swipeContainer.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - swipeContainer.offsetLeft;
        const walk = (x - startX) * 1; // スクロール量を調整
        swipeContainer.scrollLeft = scrollLeft - walk;
    });
</script>


</x-app-layout>

