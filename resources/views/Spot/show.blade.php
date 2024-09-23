<x-app-layout>
  <div class="bg-white dark:bg-gray-800 h-screen h-full py-6 sm:py-8 lg:py-12">
    <div class="mx-auto max-w-screen-2xl px-4 md:px-8">
        <div class="mb-4 flex items-center justify-between gap-8 sm:mb-8 md:mb-12">
            <div class="items-center gap-12">
              <h2 class="text-2xl font-bold text-gray-800 lg:text-3xl dark:text-white">
                {{ $spot->name }}
              </h2>
              @if (!is_null($spot->spotcategory))
                <a href="/spotcategories/{{ $spot->spotcategory->id }}">{{ $spot->spotcategory->name }}</a>
              @else
                <span>カテゴリーがありません</span>
              @endif
              <p><a href="/locals/{{ $spot->local->id }}">{{ $spot->local->name }}</a></p>
              <a href="/seasons/{{ $spot->season->id }}">{{ $spot->season->name }}</a>
              <a href="/months/{{ $spot->month->id }}">{{ $spot->month->name }}</a>
                {{-- @authはログイン済ユーザーのみに閲覧できるものを中に定義--}}
                  @auth
                    {{-- 自身がこのスポットにいいねしたのか判定 --}}
                      @if($spot->isLikedByAuthUser())
                        {{-- こちらがいいね済の際に表示される方で、likedクラスが付与してあることで星に色がつきます --}}
                        <div class="flexbox">
                          <i class="fa-solid fa-star like-btn liked" id={{$spot->id}}></i>
                        </div>
                      @else
                        <div class="flexbox">
                          <i class="fa-solid fa-star like-btn" id={{$spot->id}}></i>
                        </div>
                      @endif
                  @endauth

                  @guest
                    <p>loginしていません</p>
                  @endguest
                  
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
                  
              <p class="hidden max-w-2xl text-gray-500 dark:text-gray-300 md:block">
                {{ $spot->body }}
              </p>
            </div>

            <a href="#"
                class="inline-block rounded-lg border bg-white dark:bg-gray-700 dark:border-none px-4 py-2 text-center text-sm font-semibold text-gray-500 dark:text-gray-200 outline-none ring-indigo-300 transition duration-100 hover:bg-gray-100 focus-visible:ring active:bg-gray-200 md:px-8 md:py-3 md:text-base">
                More
            </a>
        </div>


        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:gap-6 xl:gap-8">
            <!-- image - start -->
          @foreach($spot_image as $key => $spot_img)
            @if($key == 0)
            <a href="#"
                class="group relative flex h-48 items-end overflow-hidden rounded-lg bg-gray-100 shadow-lg md:h-80">
                <img src="{{ $spot_img->image_path }}" loading="lazy" alt="Photo by Minh Pham" class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />

                <div
                    class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-50">
                </div>

                <span class="relative ml-4 mb-3 inline-block text-sm text-white md:ml-5 md:text-lg">Image1</span>
            </a>
            <!-- image - end -->
            @elseif($key == 1)
            <!-- image - start -->
            <a href="#"
                class="group relative flex h-48 items-end overflow-hidden rounded-lg bg-gray-100 shadow-lg md:col-span-2 md:h-80">
                <img src="{{ $spot_img->image_path }}" loading="lazy" alt="Photo by Magicle" class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />

                <div
                    class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-50">
                </div>

                <span class="relative ml-4 mb-3 inline-block text-sm text-white md:ml-5 md:text-lg">Image2</span>
            </a>
            <!-- image - end -->
            @elseif($key == 2)
            <!-- image - start -->
            <a href="#"
                class="group relative flex h-48 items-end overflow-hidden rounded-lg bg-gray-100 shadow-lg md:col-span-2 md:h-80">
                <img src="{{ $spot_img->image_path }}" loading="lazy" alt="Photo by Martin Sanchez" class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />

                <div
                    class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-50">
                </div>

                <span class="relative ml-4 mb-3 inline-block text-sm text-white md:ml-5 md:text-lg">Image3</span>
            </a>
            <!-- image - end -->
            @elseif($key == 3)
            <!-- image - start -->
            <a href="#"
                class="group relative flex h-48 items-end overflow-hidden rounded-lg bg-gray-100 shadow-lg md:h-80">
                <img src="{{ $spot_img->image_path }}" loading="lazy" alt="Photo by Lorenzo Herrera" class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:scale-110" />

                <div
                    class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-50">
                </div>

                <span class="relative ml-4 mb-3 inline-block text-sm text-white md:ml-5 md:text-lg">Image4</span>
            </a>
            <!-- image - end -->
            @endif
          @endforeach
        </div>
        
        <div class="content">
          <div class="">
            <dl>
	          <dt class="relative p-2 rounded-lg bg-neutral-300 text-center w-36 my-2"><strong>開園時間</strong></dt>
	          <dd class="w-3/4 mb-2">時期によって異なります。 <a href="http://hitachikaihin.jp/guide/schedule.html" target="_blank">開園時間</a>を確認してください</dd>
           </dl>

           <dl>
	         <dt class="relative p-2 rounded-lg bg-neutral-300 text-center w-36 mb-2"><strong>休園日</strong></dt>
	         <dd class="w-3/4 mb-2">火曜(祝日の場合は翌日)、12/31、1/1、2月第1火～金曜</dd>
          </dl>

          <dl>
	        <dt class="relative p-2 rounded-lg bg-neutral-300 text-center w-36 mb-2"><strong>入園料金</strong></dt>
	        <dd class="w-3/4 mb-2">大人（高校生以上）450円、シルバー（65歳以上）210円、中学生以下無料</dd>
          </dl>

          <dl>
	        <dt class="relative p-2 rounded-lg bg-neutral-300 text-center w-36 mb-2"><strong>アクセス</strong></dt>
	        <dd class="w-3/4 mb-2">【電車】JR常磐線「勝田」駅より路線バス「海浜公園西口（約15分）」下車、または「海浜公園南口（約20分）」下車すぐ<br>
	            【車】常陸那珂有料道路「ひたち海浜公園」ICすぐ / 常磐自動車道「日立南太田」ICより約15km</dd>
          </dl>

          <dl>
	        <dt class="relative p-2 rounded-lg bg-neutral-300 text-center w-36 mb-2"><strong>公式サイト</strong></dt>
	        <dd class="w-3/4 mb-2"><a href="http://hitachikaihin.jp/" target="_blank">国営ひたち海浜公園</a></dd>
          </dl>
        </div>
        
            <div class="content_post">
                <h3>[住所]</h3>
                <p>{{ $spot->address }}</p>
                <h3>[緯度・経度]</h3>
                <p>緯度 : {{ $spot->lat }}</p>
                <p>経度 : {{ $spot->long }}</p>
            </div>
        </div>
        <div class="footer">
            <a href="/dashboard">戻る</a>
        </div>
        <p class='user'>ログインユーザー:{{ Auth::user()->name }}</p>
        <div class="edit">
          <a href="/spots/{{ $spot->id }}/edit">edit</a>
        </div>
</x-app-layout>
</html>