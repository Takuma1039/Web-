<x-app-layout>
  <div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-8">
      <!-- タイトル -->
      <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">
        今の時期に人気の観光スポットランキング
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
          @forelse($majorranking as $index => $spot)
            <div class="p-4 bg-white rounded-lg shadow-md mb-4">
              <div class="flex items-center mb-2">
                <span class="text-xl font-semibold text-blue-500">第{{ $index + 1 }}位</span>
                <a href="/spots/{{ $spot->id }}" class="ml-4 text-xl font-bold text-indigo-600 hover:underline">
                  {{ $spot->name }}
                </a>
                @auth
                  {{-- 自身がこのスポットにいいねしたのか判定 --}}
                    @if($spot->isLikedByAuthUser())
                      {{-- こちらがいいね済の際に表示される方で、likedクラスが付与してあることで星に色がつきます --}}
                      <div class="flexbox ml-2">
                        <i class="fa-solid fa-star like-btn liked" id={{$spot->id}} style="font-size: 1.25rem;"></i>
                      </div>
                    @else
                      <div class="flexbox ml-2">
                        <i class="fa-solid fa-star like-btn" id={{$spot->id}} style="font-size: 1.25rem;"></i>
                      </div>
                    @endif
                @endauth
                @guest
                  <p>loginしていません</p>
                @endguest
                <!-- いいねメッセージ表示 -->
                <div id="likeMessage" class="hidden text-sm text-white bg-green-500 px-2 py-1 rounded-md">
                  お気に入り登録しました
                </div>
              </div>
              <script type="module">
                const likeBtn = document.querySelector(".like-btn");
                likeBtn.addEventListener("click", async (e) => {
                  const clickedEl = e.target;
                  const spotId = clickedEl.id;
                  // リクエストが完了するまでボタンを無効化して、連打を防止する
                  clickedEl.disabled = true;
                  // ビジュアル的に「いいね」の状態を切り替える
                  clickedEl.classList.toggle("liked");
                      
                  try {
                    const res = await fetch("/spot/like", {
                      //リクエストメソッドはPOST
                      method: "POST",
                      headers: {
                        //Content-Typeでサーバーに送るデータの種類を伝える。今回はapplication/json
                        "Content-Type": "application/json", //サーバーに送るデータの種類を指定
                        //csrfトークンを付与
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"), // CSRFトークンを付与
                      },
                      body: JSON.stringify({ spot_id: spotId }), // データをJSON形式で送信
                    });
                    // レスポンスのチェック
                    if (!res.ok) {
                      throw new Error("Network response was not ok");
                    }
                    // レスポンスをJSON形式で取得
                    const data = await res.json();
                    // 「いいね」状態に応じてメッセージを変更
                    if (clickedEl.classList.contains("liked")) {
                      likeMessage.textContent = "お気に入り登録しました";
                    } else {
                      likeMessage.textContent = "お気に入り解除しました";
                    }

                    // メッセージを表示
                    likeMessage.classList.remove("hidden");

                    // 0.8秒後にメッセージ非表示
                    setTimeout(() => {
                      likeMessage.classList.add("hidden");
                    }, 800);
                    // エラーハンドリング
                  } catch (error) {
                      alert("処理が失敗しました。画面を再読み込みし、通信環境の良い場所で再度お試しください。");
                    }
                    finally {
                      // リクエストが完了したらボタンを再度有効化する
                      clickedEl.disabled = false;
                    }
                });
              </script>
              <div class="flex mb-2">
                <div class="w-1/2 pr-2">
                  <p class="text-gray-600 mb-2">
                    {{ $spot->truncated_body }} <!-- 切り捨てた本文を表示 -->
                  </p>
                  <span class="text-sm font-medium text-gray-600">{{ $spot->likes_count }} いいね</span>
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
</x-app-layout>
