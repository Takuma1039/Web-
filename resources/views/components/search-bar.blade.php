<!-- 検索バー -->
<div class="flex justify-center mb-6" id="closeModal">
    <input id="searchInput" type="text" placeholder="探したいキーワード" class="border border-gray-300 rounded-md p-2 w-1/2 focus:outline-none focus:ring-2 focus:ring-blue-500">
    <button class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
        検索
    </button>
</div>

<!-- モーダル -->
<div id="searchModal" class="fixed inset-0 hidden z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
    <div class="bg-white p-4 rounded-lg shadow-lg max-w-xs w-full sm:max-w-sm md:max-w-md lg:max-w-lg">
        <h2 class="text-lg font-semibold mb-2 text-blue-500">Search for a Spot</h2>
        <form action="/search" method="GET">
            <input type="text" name="query" class="w-full border border-gray-300 p-2 rounded-md mb-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="探したいキーワード">
            
            <h3 class="mb-1 font-semibold text-sm">スポットカテゴリー:</h3>
            <div class="flex flex-wrap mb-2">
                @foreach($spotcategories as $spotcategory)
                    <label class="flex items-center mr-2 mb-1 text-sm">
                        <input type="checkbox" name="spot[spot_category_ids][]" value="{{ $spotcategory->id }}" class="mr-1">
                        {{ $spotcategory->name }}
                    </label>
                @endforeach
            </div>

            <h3 class="mb-1 font-semibold text-sm">地域:</h3>
            <div class="flex flex-wrap mb-2">
                @foreach($locals as $local)
                    <label class="flex items-center mr-2 mb-1 text-sm">
                        <input type="checkbox" name="spot[local_ids][]" value="{{ $local->id }}" class="mr-1">
                        {{ $local->name }}
                    </label>
                @endforeach
            </div>

            <h3 class="mb-1 font-semibold text-sm">季節:</h3>
            <div class="flex flex-wrap mb-2">
                @foreach($seasons as $season)
                    <label class="flex items-center mr-2 mb-1 text-sm">
                        <input type="checkbox" name="spot[season_ids][]" value="{{ $season->id }}" class="mr-1">
                        {{ $season->name }}
                    </label>
                @endforeach
            </div>

            <h3 class="mb-1 font-semibold text-sm">月:</h3>
            <div class="flex flex-wrap mb-2">
                @foreach($months as $month)
                    <label class="flex items-center mr-2 mb-1 text-sm">
                        <input type="checkbox" name="spot[month_ids][]" value="{{ $month->id }}" class="mr-1">
                        {{ $month->name }}
                    </label>
                @endforeach
            </div>

            <button type="submit" class="bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600 transition duration-200 w-full">検索</button>
        </form>
    </div>
</div>

<script>
    const searchInput = document.getElementById("searchInput");
    const searchModal = document.getElementById("searchModal");
    const closedModal = document.getElementById("closeModal");

    searchInput.addEventListener("focus", () => {
        searchModal.classList.remove("hidden");
    });
    
    searchInput.addEventListener("keypress", (event) => {
        if (event.key === "Enter") {
            event.preventDefault();  // デフォルトのフォーム送信を防ぐ
            searchModal.classList.remove("hidden");
        }
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
