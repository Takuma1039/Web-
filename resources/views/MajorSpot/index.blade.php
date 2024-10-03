<x-app-layout>
  <div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-8">
      <!-- タイトル -->
      <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">
        人気の観光スポットランキング
      </h1>

      <!-- 検索バー -->
      <div class="flex justify-center mb-6">
        <input type="text" placeholder="探したいキーワード" 
               class="border border-gray-300 rounded-md p-2 w-1/2">
        <button class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
          検索
        </button>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="col-span-2">
          @php
            $currentRank = 1;  // 現在の順位
            $previousLikeCount = null;  // 前のいいね数を保存するための変数
            $skipCount = 0;  // 同じ順位が連続した場合にスキップするための変数
          @endphp

          @forelse($majorranking as $index => $spot)
            @php
              // 現在のスポットのいいね数と前のスポットのいいね数を比較
              if ($previousLikeCount === null || $previousLikeCount !== $spot->likes_count) {
                // 前のスポットといいね数が異なる場合は、順位を更新
                $currentRank += $skipCount;  // スキップしていた分を順位に加算
                $skipCount = 1;  // スキップ数をリセット
              } else {
                // 同じいいね数ならスキップ数を増やす
                $skipCount++;
              }
              $previousLikeCount = $spot->likes_count;  // 現在のいいね数を前のいいね数に更新
            @endphp
            <div class="p-4 bg-white rounded-lg shadow-md mb-4">
              <div class="flex items-center mb-2">
                <span class="text-xl font-semibold text-blue-500">第{{ $index + 1 }}位</span>
                <a href="/spots/{{ $spot->id }}" class="ml-4 text-xl font-bold text-indigo-600 hover:underline">
                  {{ $spot->name }}
                </a>
                @auth
                  <div class="flexbox ml-2">
                    <i class="fa-solid fa-heart like-btn {{ $spot->isLikedByAuthUser() ? 'liked' : '' }}" id="like-btn-{{ $spot->id }}" style="font-size: 1.25rem;"></i>
                  </div>
                @endauth
                <!-- いいねメッセージ表示 -->
                <div id="likeMessage-{{ $spot->id }}" class="hidden text-sm text-white bg-green-500 px-2 py-1 ml-2 rounded-md">
                  お気に入り登録しました
                </div>
              </div>
          
              <div class="flex mb-2">
                <div class="w-1/2 pr-2">
                  <p class="text-gray-600 mb-2">
                    {{ $spot->truncated_body }}
                  </p>
                  <span class="text-sm font-medium text-gray-600" id="likeCount-{{ $spot->id }}">{{ $spot->likes_count }} いいね</span>
                </div>
                
                <div class="w-1/2 pl-2">
                  <!-- 画像を表示 -->
                  @if ($spot->spotimages->isNotEmpty())
                    <img src="{{ $spot->spotimages->first()->image_path }}" alt="{{ $spot->name }}" class="w-full h-48 object-cover rounded-lg">
                  @else
                    <img src="/images/no_image_available.png" alt="No image" class="w-full h-48 object-cover rounded-lg">
                  @endif
                </div>
              </div>
            </div>
          @empty
            <p class="text-center text-gray-500">現在、スポット情報はありません。</p>
          @endforelse
        </div>
      </div>

      <!-- ページネーション -->
      <div class="flex justify-center mt-8">
        <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Previous</button>
        <button class="px-4 py-2 mx-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">1</button>
        <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">2</button>
        <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">3</button>
        <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Next</button>
      </div>
    </div>
  </div>
  
  <script type="module">
    document.addEventListener('DOMContentLoaded', () => {
      const likeBtns = document.querySelectorAll(".like-btn");
      likeBtns.forEach(btn => {
        btn.addEventListener("click", handleLikeButtonClick);
      });
    });

    async function handleLikeButtonClick(e) {
      const clickedEl = e.target;
      const spotId = clickedEl.id.split('-')[2]; // 'like-btn-{id}'からidを取得
      clickedEl.disabled = true; // ボタンを無効化

      clickedEl.classList.toggle("liked");
      const likeMessage = document.getElementById(`likeMessage-${spotId}`);
      const likeCountEl = document.getElementById(`likeCount-${spotId}`); // いいねカウントの要素を取得

      try {
        const res = await fetch("/spot/like", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
          },
          body: JSON.stringify({ spot_id: spotId }),
        });

        if (!res.ok) throw new Error("Network response was not ok");

        const data = await res.json();

        // いいねカウントとメッセージを更新
        likeCountEl.textContent = `${data.likes_count} いいね`; // likes_countを更新
        likeMessage.textContent = clickedEl.classList.contains("liked") ? "お気に入り登録しました" : "お気に入り解除しました"; // liked状態によってメッセージを切り替え
        likeMessage.classList.remove("hidden");

        setTimeout(() => {
          likeMessage.classList.add("hidden");
        }, 800);
      } catch (error) {
        alert("処理が失敗しました。画面を再読み込みし、通信環境の良い場所で再度お試しください。");
      } finally {
        clickedEl.disabled = false; // ボタンを再度有効化
      }
    }
  </script>
</x-app-layout>

