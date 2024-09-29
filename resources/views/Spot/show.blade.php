<x-app-layout>
  <div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">
              {{ $spot->name }}
            </h2>
            @auth
  <div class="flex items-center ml-2">
    <i class="fa-solid fa-star like-btn {{ $spot->isLikedByAuthUser() ? 'liked' : '' }}" id="{{ $spot->id }}" style="font-size: 1.5rem;"></i>
  </div>
@endauth

<!-- いいねメッセージ表示 -->
<div id="likeMessage" class="hidden text-sm text-white bg-green-500 px-2 py-1 rounded-md">
  お気に入り登録しました
</div>
</div>
<a href="#"
  class="inline-block rounded-lg border bg-white dark:bg-gray-700 dark:border-none px-4 py-2 text-center text-sm font-semibold text-gray-500 dark:text-gray-200 outline-none ring-indigo-300 transition duration-100 hover:bg-gray-100 focus-visible:ring active:bg-gray-200 md:px-8 md:py-3 md:text-base">
  Map
</a>

        </div>
<div class="mb-4">
          <p class="hidden text-gray-500 dark:text-gray-300 md:block">
            {{ $spot->body }}
          </p>
        </div>
        <!-- カテゴリー、シーズン、月、地域の表示 -->
        <div class="mb-4 p-4 bg-gray-100 rounded-lg shadow-md">
  <strong class="text-lg flex items-center">
    <i class="fa-solid fa-tags mr-2"></i>カテゴリー:
  </strong>
  <div class="flex space-x-2 mt-2">
    @if ($categories->isNotEmpty())
      @foreach ($categories as $category)
        <a href="/spotcategories/{{ $category->id }}" class="text-blue-500 hover:text-blue-700 transition duration-200">{{ $category->name }}</a>
      @endforeach
    @else
      <span class="text-gray-500">カテゴリーがありません</span>
    @endif
  </div>
</div>

        <div class="mb-4 p-4 bg-gray-100 rounded-lg shadow-md">
  <strong class="text-lg flex items-center">
    <i class="fa-solid fa-snowflake mr-2"></i>シーズン:
  </strong>
  @if ($seasons->isNotEmpty())
    <div class="flex space-x-2 mt-2">
      @foreach ($seasons as $season)
        <a href="/seasons/{{ $season->id }}" class="text-blue-500 hover:text-blue-700 transition duration-200">{{ $season->name }}</a>
      @endforeach
    </div>
  @else
    <span class="text-gray-500">シーズンがありません</span>
  @endif
</div>

<div class="mb-4 p-4 bg-gray-100 rounded-lg shadow-md">
  <strong class="text-lg flex items-center">
    <i class="fa-solid fa-calendar-alt mr-2"></i>月:
  </strong>
  @if ($months->isNotEmpty())
    <div class="flex space-x-2 mt-2">
      @foreach ($months as $month)
        <a href="/months/{{ $month->id }}" class="text-blue-500 hover:text-blue-700 transition duration-200">{{ $month->name }}</a>
      @endforeach
    </div>
  @else
    <span class="text-gray-500">月がありません</span>
  @endif
</div>

<div class="mb-4 p-4 bg-gray-100 rounded-lg shadow-md">
  <strong class="text-lg flex items-center">
    <i class="fa-solid fa-map-marker-alt mr-2"></i>地域:
  </strong>
  <a href="/locals/{{ $spot->local_id }}" class="text-blue-500 hover:text-blue-700 transition duration-200">{{ $spot->local->name }}</a>
</div>


        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:gap-6 xl:gap-8">
  @foreach($spotImages as $spot_img)
    <a href="#" class="group relative flex h-48 items-end overflow-hidden rounded-lg shadow-lg transition-transform duration-300 transform hover:scale-105">
      <img src="{{ $spot_img->image_path }}" loading="lazy" alt="Image" class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:opacity-90" />
      <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-40"></div>
      <span class="relative ml-4 mb-3 inline-block text-sm text-white">Image</span>
    </a>
  @endforeach
</div>


        <!-- 詳細情報のセクション -->
        <div class="content mt-4">
          <h3 class="text-xl font-bold">詳細情報</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <dl class="p-4 bg-neutral-200 rounded-lg">
              <dt class="font-semibold">住所</dt>
              <dd>{!! nl2br(e($spot->address)) !!}</dd>
            </dl>
            <dl class="p-4 bg-neutral-200 rounded-lg">
              <dt class="font-semibold">開園時間</dt>
              <dd>{!! nl2br(e($spot->opendate)) !!}</dd>
            </dl>
            <dl class="p-4 bg-neutral-200 rounded-lg">
              <dt class="font-semibold">休園日</dt>
              <dd>{!! nl2br(e($spot->closedate)) !!}</dd>
            </dl>
            <dl class="p-4 bg-neutral-200 rounded-lg">
              <dt class="font-semibold">入園料金</dt>
              <dd>{!! nl2br(e($spot->price)) !!}</dd>
            </dl>
            <dl class="p-4 bg-neutral-200 rounded-lg">
              <dt class="font-semibold">アクセス</dt>
              <dd>{!! nl2br(e($spot->access)) !!}</dd>
            </dl>
            <dl class="p-4 bg-neutral-200 rounded-lg">
              <dt class="font-semibold">公式サイト</dt>
              <dd><a href="{{ $spot->site }}" target="_blank" class="text-blue-500 hover:underline">{{ $spot->site }}</a></dd>
            </dl>
          </div>
        </div>

        <div class="mt-6 text-center">
          <a href="javascript:history.back();" class="text-indigo-600 hover:underline">戻る</a>
        </div>
      </div>

      @auth
        <div class="mt-4 text-right">
          <p>ログインユーザー: {{ Auth::user()->name }}</p>
          <a href="/spots/{{ $spot->id }}/edit" class="text-indigo-600 hover:underline">編集</a>
        </div>
      @endauth
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
    </div>
  </div>
</x-app-layout>