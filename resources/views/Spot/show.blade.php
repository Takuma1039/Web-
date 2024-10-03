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
                <i class="fa-solid fa-heart like-btn {{ $spot->isLikedByAuthUser() ? 'liked' : '' }}" id="{{ $spot->id }}" style="font-size: 1.5rem;"></i>
              </div>
            @endauth

            <!-- いいねメッセージ表示 -->
            <div id="likeMessage" class="hidden text-sm text-white bg-green-500 px-2 py-1 rounded-md">
              お気に入り登録しました
            </div>
          </div>
          <!-- Mapボタン -->
          <a href="javascript:void(0);" class="inline-block rounded-lg border bg-white dark:bg-gray-700 dark:border-none px-4 py-2 text-center text-sm font-semibold text-gray-500 dark:text-gray-200 outline-none ring-indigo-300 transition duration-100 hover:bg-gray-100 focus-visible:ring active:bg-gray-200 md:px-8 md:py-3 md:text-base" onclick="openMapModal()">
            Map
          </a>

          <!-- Mapモーダルウィンドウ -->
          <div id="mapModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" onclick="closeMapModal()">
            <div class="relative top-1/2 transform -translate-y-1/2 mx-auto p-5 border w-full max-w-lg shadow-2xl rounded-lg bg-white z-60" onclick="event.stopPropagation();">
              <button class="absolute top-2 right-2 text-gray-500 hover:text-red-600 transition duration-150" onclick="closeMapModal()">
                &times;
              </button>
              <div id="map" style="width: 100%; height: 400px;"></div>
            </div>
          </div>

          <script>
            // Mapモーダル表示用のJavaScript関数
            function openMapModal() {
              document.getElementById('mapModal').classList.remove('hidden');

              // 既にGoogle Mapsがロードされているかどうかを確認
              if (!window.google || !window.google.maps) {
                // Google Maps APIを非同期でロード
                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key={{ $api_key }}&callback=initMap`;
                script.async = true;
                script.defer = true;
                document.body.appendChild(script);
              } else {
                // 既にロード済みの場合はマップを初期化
                initMap();
              }
            }

            // モーダル閉じる用のJavaScript関数
            function closeMapModal() {
              document.getElementById('mapModal').classList.add('hidden');
            }

            // Google Maps APIでマップを初期化する関数
            function initMap() {
              var spotLocation = { lat: {{ $spot->lat }}, lng: {{ $spot->long }} };

              var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 14,
                center: spotLocation
              });

              var marker = new google.maps.Marker({
                position: spotLocation,
                map: map,
                title: '{{ $spot->name }}'
              });
            }
          </script>
        </div>
        
        <div class="p-4">
          <div class="flex items-center">
            <span class="font-bold text-lg">総合評価</span>
            <div class="flex items-center ml-2">
              @for ($i = 0; $i < 5; $i++)
                @php
                  $currentValue = $i + 1; // 現在の星の値（1〜5）
                  $fullStar = $averageRating >= $currentValue; // 完全な星の表示
                  $partialStar = !$fullStar && $averageRating > $currentValue - 1 && $averageRating < $currentValue; // 部分的な星の表示
                  $fillPercentage = $partialStar ? ($averageRating - ($currentValue - 1)) * 100 : 0; // 部分的な星の塗り
                @endphp

                <div class="relative inline-block">
                  <!-- 空の星のベースを描画 -->
                  <i class="fas fa-star text-gray-300 text-xl"></i>

                  @if ($fullStar)
                    <!-- 完全な星の塗り -->
                    <i class="fas fa-star text-yellow-500 text-xl absolute top-0 left-0"></i>
                 @elseif ($partialStar)
                    <!-- 部分的な星の塗り -->
                    <div class="absolute top-0 left-0 h-full overflow-hidden" style="width: {{ $fillPercentage }}%;">
                      <i class="fas fa-star text-yellow-500 text-xl"></i>
                    </div>
                  @endif
                </div>
              @endfor
              <span class="text-gray-600 ml-2 font-semibold">{{ number_format($averageRating, 2) }}</span> <!-- 総合評価の値を表示 -->
            </div>
          </div>
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
              <span class="text-gray-500">スポットが属するカテゴリーがありません</span>
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
            <span class="text-gray-500">スポットが属するシーズンがありません</span>
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
            <span class="text-gray-500">スポットが属する月がありません</span>
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
            <a class="group relative flex h-48 items-end overflow-hidden rounded-lg shadow-lg transition-transform duration-300 transform hover:scale-105" onclick="openModal('{{ $spot_img->image_path }}')">
              <img src="{{ $spot_img->image_path }}" loading="lazy" alt="Image" class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:opacity-90" />
              <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-40"></div>
              <span class="relative ml-4 mb-3 inline-block text-sm text-white">Image</span>
            </a>
          @endforeach
        </div>
        <!-- モーダルウィンドウ -->
        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden" onclick="closeModal()">
          <div class="relative bg-white rounded-lg shadow-lg overflow-hidden w-full max-w-3xl" onclick="event.stopPropagation();">
            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
            <img id="modalImage" src="" alt="拡大画像" class="w-full h-auto object-contain">
          </div>
        </div>
        <!--モーダルウィンドウ用-->
        <script>
          function openModal(imagePath) {
            document.getElementById('modalImage').src = imagePath;
            document.getElementById('imageModal').classList.remove('hidden');
          }

          function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
          }
        </script>

        <!-- 詳細情報のセクション -->
        <div class="content mt-4">
          <h3 class="text-xl font-bold">詳細情報</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <dl class="p-4 bg-neutral-200 rounded-lg">
              <dt class="font-semibold">住所</dt>
              <dd>{!! nl2br(e($spot->address)) !!}</dd>
            </dl>
            <dl class="p-4 bg-neutral-200 rounded-lg">
              <dt class="font-semibold">時間</dt>
              <dd>{!! nl2br(e($spot->opendate)) !!}</dd>
            </dl>
            <dl class="p-4 bg-neutral-200 rounded-lg">
              <dt class="font-semibold">定休日</dt>
              <dd>{!! nl2br(e($spot->closedate)) !!}</dd>
            </dl>
            <dl class="p-4 bg-neutral-200 rounded-lg">
              <dt class="font-semibold">料金</dt>
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
        <!--口コミセクション-->
        <div class="reviews mt-8">
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">口コミ一覧</h3>
            @auth
            <!-- 口コミ投稿のボタン -->
            <button id="reviewBtn" class="inline-block rounded-lg border border-blue-600 bg-blue-600 text-white hover:bg-blue-700 hover:border-blue-700 transition px-4 py-2 shadow-md">
              口コミを投稿する
            </button>
            @endauth
          </div>

          <!-- 口コミ投稿のポップアップ -->
          <div id="reviewModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full transition-transform transform scale-95 hover:scale-100">
              <h3 class="text-xl font-bold mb-4 text-center text-blue-600">口コミを投稿</h3>

              <!-- エラーメッセージの表示 -->
              @if ($errors->any())
                <div class="mb-4 text-red-600">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <form action="{{ route('reviews.store', $spot->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                  <label for="title" class="block mb-2 text-gray-700 font-semibold">タイトル:</label>
                  <input type="text" id="title" name="title" required maxlength="50" 
                    class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 transition duration-150 ease-in-out">
                  @error('title')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                  @enderror
                </div>

                <div class="mb-4">
                  <label for="review" class="block mb-2 text-gray-700 font-semibold">評価:</label>
                  <input type="number" id="review" name="review" required step="0.1" min="1.0" max="5.0" 
                    class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 transition duration-150 ease-in-out" 
                    placeholder="1.0 - 5.0の範囲で入力">
                  @error('review')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                  @enderror
                </div>

                <div class="mb-4">
                  <label for="comment" class="block mb-2 text-gray-700 font-semibold">口コミ:</label>
                  <textarea id="comment" name="comment" rows="4" required 
                    class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 transition duration-150 ease-in-out"></textarea>
                  @error('comment')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                  @enderror
                </div>

                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700">新しい画像ファイル（複数可）:</label>
                  <input type="file" name="images[]" id="image-input" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" accept="image/*" onchange="previewImages()">
                  <div id="image-preview" class="grid grid-cols-2 gap-4 mt-2"></div>
                </div>
                <!--画像のプレビュー画面用-->
                <script>
                  function previewImages() {
                    const preview = document.getElementById('image-preview');
                    preview.innerHTML = ''; // プレビューをリセット

                    const files = document.getElementById('image-input').files;

                    if (files.length === 0) {
                      preview.innerHTML = '<p class="text-gray-500">画像が選択されていません。</p>';
                      return;
                    }

                    for (let i = 0; i < files.length; i++) {
                      const file = files[i];
                      const reader = new FileReader();

                      // 画像のプレビューと名前入力欄を作成
                      reader.onload = function(e) {
                        const imgContainer = document.createElement('div');
                        imgContainer.classList.add('flex', 'flex-col', 'items-center', 'mb-4');

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('h-32', 'w-full', 'object-cover', 'rounded-lg', 'shadow-lg');

                        // 新しく追加された画像に対する名前入力欄を生成
                        const nameInput = document.createElement('input');
                        nameInput.type = 'text';
                        nameInput.name = `new_image_names[]`; // 新しい画像の名前用
                        nameInput.placeholder = '画像の名前を入力';
                        nameInput.classList.add('mt-1', 'block', 'w-full', 'border', 'border-gray-300', 'rounded-md', 'shadow-sm', 'focus:ring', 'focus:ring-indigo-200');

                        imgContainer.appendChild(img);
                        imgContainer.appendChild(nameInput);
                        preview.appendChild(imgContainer);
                      }

                      reader.readAsDataURL(file);
                    }
                  }
                </script>
                <!-- 匿名選択用チェックボックス -->
                <div class="mb-4 flex items-center">
                  <input type="hidden" name="is_anonymous" value="0">
                  <input type="checkbox" id="is_anonymous" name="is_anonymous" value="1" class="mr-2">
                  <label for="is_anonymous" class="text-gray-700 font-semibold">匿名で投稿する</label>
                </div>

                <div class="flex justify-between">
                  <button type="submit" class="bg-blue-600 text-white rounded-lg px-4 py-2 hover:bg-blue-700 transition">送信</button>
                  <button type="button" id="closeModal" class="ml-2 bg-gray-300 rounded-lg px-4 py-2 hover:bg-gray-400 transition">キャンセル</button>
                </div>
              </form>
            </div>
          </div>

          <!--口コミの表示-->
          @if($reviews->isEmpty())
            <p class="text-gray-500">まだ口コミはありません。</p>
          @else
            @foreach($reviews as $review)
              <div class="review bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg mb-4 transition-transform transform">
                <div class="flex items-center justify-between mb-2">
                  <div class="flex items-center">
                    @for ($i = 0; $i < 5; $i++)
                      @php
                        $currentValue = $i + 1; // 現在の星の値（1〜5）
                        $reviewValue = floatval($review->review); // 評価をfloat型に変換

                        // 星の状態を判定
                        $fullStar = $reviewValue >= $currentValue; // 完全な星の表示
                        $partialStar = !$fullStar && $reviewValue > $currentValue - 1 && $reviewValue < $currentValue; // 部分的な星の表示
                        $fillPercentage = $partialStar ? ($reviewValue - ($currentValue - 1)) * 100 : 0; // 部分的な星の塗り
                      @endphp

                      <div class="relative inline-block">
                        <!-- 空の星のベースを描画 -->
                        <i class="fa-solid fa-star text-gray-300 text-xl"></i>
                        <!-- 星の色塗り判定 -->
                        @if ($fullStar)
                          <i class="fa-solid fa-star text-yellow-500 text-xl absolute top-0 left-0"></i>
                        @elseif ($partialStar)
                          <div class="absolute top-0 left-0 h-full overflow-hidden" style="width: {{ $fillPercentage }}%;">
                            <i class="fa-solid fa-star text-yellow-500 text-xl"></i>
                          </div>
                        @endif
                      </div>
                    @endfor

                    <span class="text-gray-600 ml-2 font-semibold">{{ number_format($reviewValue, 1) }}</span>
                    <span class="ml-2 text-gray-700 font-semibold">
                      投稿者: 
                      @if ($review->is_anonymous)
                        匿名
                      @else
                        {{ $review->user->name }}
                      @endif
                    </span>
                  </div>

                  @auth
                    @if (Auth::id() === $review->user_id)
                      <div class="flex space-x-2">
                        <a href="{{ route('reviews.edit', $review->id) }}" class="text-blue-600 hover:underline">編集</a>
                        <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="text-red-600 hover:underline">削除</button>
                        </form>
                      </div>
                    @endif
                  @endauth
                </div>
                <!--いいねボタン-->
                @auth
                  <div class="flex items-center mb-2">
                    <button class="like-button text-gray-500 hover:text-blue-500 flex items-center" data-review-id="{{ $review->id }}">
                      @if ($review->likes->where('user_id', auth()->id())->count())
                        <i class="fas fa-thumbs-up"></i> いいねを取り消す
                      @else
                        <i class="far fa-thumbs-up"></i> いいね
                      @endif
                    </button>
                    <span class="ml-2 text-gray-600" id="like-count-{{ $review->id }}">{{ $review->likes ? $review->likes->count() : 0 }} 件のいいね</span>
                  </div>
                @endauth
                <p class="text-gray-700 font-semibold text-lg mt-1 transition-colors duration-200 ease-in-out">
                  {{ $review->title }}
                </p>
                <p class="text-gray-700 mt-2">{{ $review->comment }}</p>

                <!-- 画像ギャラリー -->
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:gap-6 xl:gap-8 mt-4">
                  @foreach($reviewImages[$review->id] as $index => $review_img)
                    <a class="group relative flex h-32 items-end overflow-hidden rounded-lg shadow-lg transition-transform duration-300 transform hover:scale-105" onclick="openModal('{{ $review_img->image_path }}')">
                      <img src="{{ $review_img->image_path }}" loading="lazy" alt="Image" class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:opacity-90" />
                      <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-40"></div>
                      <span class="relative ml-4 mb-3 inline-block text-sm text-white">{{ $review_img->name }}</span>
                    </a>
                  @endforeach
                </div>
              </div>
            @endforeach
          @endif
        </div>

        <div class="mt-6 text-center">
          <a href="javascript:history.back();" class="text-indigo-600 hover:underline">戻る</a>
        </div>
      </div>

      @php
        $yourDeveloperId = 1; // 開発者のIDを指定
      @endphp
      @auth
        <div class="mt-4 text-right">
          <p>ログインユーザー: {{ Auth::user()->name }}</p>
          @if (auth()->user()->id === $yourDeveloperId)
          <a href="/spots/{{ $spot->id }}/edit" class="text-indigo-600 hover:underline">編集</a>
          @endif
        </div>
      @endauth
    </div>
  </div>
  <script>
    // スポットのお気に入り機能
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
      } finally {
          // リクエストが完了したらボタンを再度有効化する
          clickedEl.disabled = false;
        }
    });
    
    //口コミ投稿メッセージ表示用
    document.querySelector('form').onsubmit = function() {
      alert('口コミが投稿されました！');
    };

    // モーダルの表示/非表示切替
    document.getElementById('reviewBtn').addEventListener('click', function() {
      document.getElementById('reviewModal').classList.remove('hidden');
    });

    document.getElementById('closeModal').addEventListener('click', function() {
      document.getElementById('reviewModal').classList.add('hidden');
    });
    
    // 背景クリックでモーダルを閉じる
    document.getElementById('reviewModal').addEventListener('click', function(event) {
      if (event.target === this) {
        this.classList.add('hidden');
      }
    });
      
    //口コミいいね機能
    document.addEventListener('DOMContentLoaded', function () { //ページの読み込みが完了した後に実行
      const likeButtons = document.querySelectorAll('.like-button'); //.like-buttonクラスを持つすべての要素を取得し、likeButtonsという変数に格納

      likeButtons.forEach(button => {
        button.addEventListener('click', async function () { //すべての「いいね」ボタンに対して、クリックイベントリスナーを追加
          const reviewId = this.getAttribute('data-review-id'); //どの口コミに対して「いいね」をするかを決定
          const likeCountElement = document.getElementById(`like-count-${reviewId}`); //いいねの数を表示するための要素を、reviewIdに基づいて取得

          // ボタンを無効化して連打を防止
          this.disabled = true;

          try {
            const response = await fetch(`/reviews/like`, { //fetchを使って、サーバーに「いいね」操作のリクエストを送信
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
              },
                body: JSON.stringify({ review_id: reviewId }) //「いいね」をする対象のレビューIDをJSON形式で送信
              });

              // レスポンスのチェック
              if (!response.ok) {
                throw new Error('Network response was not ok');
              }

              const data = await response.json(); //レスポンスをJSON形式に変換してdataとして格納

              // いいね数を更新
              likeCountElement.textContent = `${data.likeCount} 件のいいね`; //サーバーから返されたデータから、最新の「いいね」数を取得し、likeCountElementに表示

              // いいね状態を視覚的に変更
              if (data.liked) {
                  this.innerHTML = '<i class="fas fa-thumbs-up"></i> いいねを取り消す';
                  this.classList.add('text-blue-500');
              } else {
                  this.innerHTML = '<i class="far fa-thumbs-up"></i> いいね';
                  this.classList.remove('text-blue-500');
              }
          } catch (error) {
              console.error('Error:', error);
              alert('処理中にエラーが発生しました。もう一度お試しください。');
          } finally {
              // リクエストが完了したらボタンを再度有効化
              this.disabled = false;
          }
        });
      });
    });
  </script>
</x-app-layout>