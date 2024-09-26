<x-app-layout>
    <div class="mx-auto max-w-screen-2xl bg-gray-100">
      <div class="overflow-hidden">
        <!-- Content Body -->
        <div class=" flex-1 overflow-auto p-4">
          <!-- 検索バー -->
          <div class="flex justify-center mb-6">
            <input id="searchInput" type="text" placeholder="探したいキーワード" class="border border-gray-300 rounded-md p-2 w-1/2">
            <button class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
              検索
            </button>
          </div>

          <!-- モーダル -->
          <div id="searchModal" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-xl w-full"> <!-- 横幅を拡大 -->
              <h2 class="text-lg font-semibold mb-4">Search for a Location</h2>
              <form action="/search" method="GET">
                <!-- クエリ入力 -->
                <input type="text" name="query" class="w-full border border-gray-300 p-2 rounded-md mb-4" placeholder="探したいキーワード">

                <!-- カテゴリー選択 -->
                <h3 class="mb-2">Spot Categories:</h3>
                <div class="flex flex-wrap mb-4">
                  @foreach($spotcategories as $spotcategory)
                    <label class="flex items-center mr-4 mb-2">
                      <input type="checkbox" name="spot[spot_category_ids][]" value="{{ $spotcategory->id }}" class="mr-2">
                        {{ $spotcategory->name }}
                      </label>
                  @endforeach
                </div>

                <!-- Local -->
                <h3 class="mb-2">Local:</h3>
                <div class="flex flex-wrap mb-4">
                  @foreach($locals as $local)
                    <label class="flex items-center mr-4 mb-2">
                      <input type="checkbox" name="spot[local_ids][]" value="{{ $local->id }}" class="mr-2">
                        {{ $local->name }}
                    </label>
                  @endforeach
                </div>

                <!-- Season -->
                <h3 class="mb-2">Season:</h3>
                <div class="flex flex-wrap mb-4">
                  @foreach($seasons as $season)
                    <label class="flex items-center mr-4 mb-2">
                      <input type="checkbox" name="spot[season_ids][]" value="{{ $season->id }}" class="mr-2">
                        {{ $season->name }}
                    </label>
                  @endforeach
                </div>

                <!-- Month -->
                <h3 class="mb-2">Month:</h3>
                <div class="flex flex-wrap mb-4">
                  @foreach($months as $month)
                    <label class="flex items-center mr-4 mb-2">
                      <input type="checkbox" name="spot[month_ids][]" value="{{ $month->id }}" class="mr-2">
                        {{ $month->name }}
                    </label>
                  @endforeach
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Search</button>
              </form>
              <button id="closeModal" class="mt-4 text-blue-500">Close</button>
            </div>
          </div>

          <script>
            const searchInput = document.getElementById("searchInput");
            const searchModal = document.getElementById("searchModal");
            const closeModal = document.getElementById("closeModal");

            // 検索ボックスがクリックされたときモーダルを表示
            searchInput.addEventListener("focus", () => {
              searchModal.classList.remove("hidden");
            });

            // モーダルを閉じるボタン
            closeModal.addEventListener("click", () => {
              searchModal.classList.add("hidden");
            });
  
            // モーダル外をクリックしたときも閉じる
            searchModal.addEventListener("click", (event) => {
              if (event.target === searchModal) {
                searchModal.classList.add("hidden");
              }
            });
          </script>

              <!--<h1 class="text-2xl font-semibold">Welcome to our website</h1>-->
              <!--<p>... Content goes here ...</p>-->
              
          <div id="default-carousel" class="relative w-full" data-carousel="slide">
            <!-- Carousel wrapper -->
            <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
              <!-- Item 1 -->
              <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="https://www.nta.co.jp/media/tripa/static_contents/nta-tripa/articles/images/000/000/418/medium/8342c3cf-fbb8-402a-9bd8-37fd7f1b8c33.jpg?1550646782" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
              </div>
              <!-- Item 2 -->
              <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="https://img.freepik.com/free-photo/fuji-mountain-and-kawaguchiko-lake-in-morning-autumn-seasons-fuji-mountain-at-yamanachi-in-japan_335224-102.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
              </div>
              <!-- Item 3 -->
              <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="https://img.freepik.com/free-photo/purple-nature-landscape-with-vegetation_23-2150859581.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
              </div>
              <!-- Item 4 -->
              <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="https://designwork-s.net/blog/wp-content/uploads/scientifantastic1.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
              </div>
              <!-- Item 5 -->
              <div class="hidden duration-700 ease-in-out" data-carousel-item>
                <img src="https://www.a-kimama.com/wp-content/uploads/2016/06/20160628-1.jpg" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
              </div>
            </div>
            
            <!-- Slider indicators -->
            <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
              <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
              <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
              <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
              <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 4" data-carousel-slide-to="3"></button>
              <button type="button" class="w-3 h-3 rounded-full" aria-current="false" aria-label="Slide 5" data-carousel-slide-to="4"></button>
            </div>
            <!-- Slider controls -->
            <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
              <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                </svg>
                <span class="sr-only">Previous</span>
              </span>
            </button>
            <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
              <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <span class="sr-only">Next</span>
              </span>
            </button>
          </div>
	            
          <!--ranking--> 
          <div class="flex items-center mt-4">
            <h1 class="text-xl md:text-2xl font-semibold mr-2">人気スポットランキング</h1>
            <a href="{{ route('major.ranking') }}" class="flex items-center text-white bg-gradient-to-r from-blue-500 to-indigo-600 border-none py-1 px-3 rounded-full shadow-md transform transition-all duration-200 hover:scale-105 hover:shadow-lg text-sm">
              <span>View More</span>
              <svg class="w-3 h-3 md:w-4 md:h-4 ml-2" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                <path d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
              </svg>
            </a>
          </div>
        </div>
        
        <div x-data="swipeCards()" x-init="
                    let isDown = false; 
                    let startX; 
                    let scrollLeft;
			              $el.addEventListener('mousedown', (e) => {
			                isDown = true;
			                startX = e.pageX - $el.offsetLeft;
			                scrollLeft = $el.scrollLeft;
			              });
			              $el.addEventListener('mouseleave', () => {
			                isDown = false;
			              });
			              $el.addEventListener('mouseup', () => {
			                isDown = false;
			              });
			              $el.addEventListener('mousemove', (e) => {
			                if (!isDown) return;
			                e.preventDefault();
			                const x = e.pageX - $el.offsetLeft;
			                const walk = (x - startX) * 1;
			                $el.scrollLeft = scrollLeft - walk;
		                });
			              " class="overflow-x-scroll scrollbar-hide mb-4 relative px-0.5" style="overflow-y: hidden;">
	        <div class="flex snap-x snap-mandatory gap-4" style="width: max-content;">
            <template x-for="card in cards" :key="card.id">
              <div class="flex-none sm:w-48 md:w-64 h-auto snap-center">
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden relative h-full flex flex-col justify-between">
                  <div>
                    <!-- 画像の表示 -->
                    <img :src="card.image" alt="" class="w-full h-40 object-cover">

                    <!-- 日付ボタンの追加 -->
                    <a :href="card.link" class="absolute top-3 right-3 z-10" aria-label="カードの詳細リンク">
                      <div class="text-sm bg-indigo-600 px-4 text-white rounded-full h-16 w-16 flex flex-col items-center justify-center hover:bg-white hover:text-indigo-600 transition duration-500 ease-in-out">
                        <small class="font-bold" x-text="card.date.month"></small>
                      </div>
                    </a>

                    <div class="p-4">
                      <h3 class="text-lg leading-6 font-bold text-gray-900" x-text="card.title"></h3>
                      <p class="text-gray-600 mt-2 text-sm" x-text="card.description"></p>
                    </div>
                  </div>

                  <!-- 評価の表示 -->
                  <div class="p-4">
                    <div class="flex items-center mt-2">
                      <template x-for="n in 5" :key="n">
                        <i :class="n <= Math.floor(Number(card.rating)) ? 'fas fa-star text-teal-600' : 'far fa-star text-gray-400'"></i>
                      </template>
                      <span class="ml-2 text-gray-600 text-sm" x-text="card.reviewCount + ' reviews'"></span>
                    </div>
                  </div>
                </div>
              </div>
            </template>
	        </div>
        </div>
      </div>
    </div>
    <script>
	     function swipeCards() {
			   return {
			     cards: [
			       @foreach ($spots as $spot)
			       {
			         id: {{ $spot->id }},
               image: '{{ $spot->spotimages->first()->image_path }}', // 適切な画像URLに置き換えてください
               title: '{{ $spot->name }}',
               description: '{{ $spot->truncated_body }}',
               rating: Math.random() * 5, // デモ用にランダムな評価を生成（適宜修正）
               reviewCount: {{ rand(10, 100) }}, // デモ用にランダムなレビュー数を生成
               link: '/spots/{{ $spot->id }}',
               date: { day: {{ $spot->created_at->day }}, month: '{{ $spot->created_at->format('F') }}' }
			       },
			       @endforeach
			     ],
			     maxDescriptionLength: 30, // 最大文字数を指定
           getTruncatedDescription(description) {
            return description.length > this.maxDescriptionLength
                ? description.substring(0, this.maxDescriptionLength) + '...' // 切り捨てと省略記号
                : description;
           }
			   };
			 }
　　</script>
</x-app-layout>
