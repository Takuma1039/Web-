<x-app-layout>
  <div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-8">
      <!-- タイトル -->
      <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">スポット一覧</h1>
      
      <!-- スポット一覧 -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($spots as $spot)
          <div class="bg-white p-4 rounded-lg shadow-md transition-transform transform">
            <div class="relative">
              <!-- 画像表示 -->
              @if ($spot->spotimages->isNotEmpty())
                <img src="{{ $spot->spotimages->first()->image_path }}" 
                     alt="{{ $spot->name }}" class="w-full h-48 object-cover rounded-lg">
              @else
                <img src="/images/no_image_available.png" alt="No image" 
                     class="w-full h-48 object-cover rounded-lg">
              @endif

              <!-- スポット名 -->
              <h2 class="text-xl font-semibold text-gray-800 mt-2">
                <a href="{{ route('spots.show', $spot->id) }}" class="hover:underline">
                  {{ $spot->name }}
                </a>
              </h2>
            </div>

            <!-- スポット情報 -->
            <p class="text-gray-600 mt-2">{{ $spot->truncated_body }}</p>

            <!-- いいね数とボタン -->
            <div class="flex justify-between items-center mt-4 relative">
              <span class="text-gray-600" id="likeCount-{{ $spot->id }}">{{ $spot->likes_count }} いいね</span>
              
              @auth
                  <div class="flex ml-2">
                    <i class="fa-solid fa-heart like-btn {{ $spot->isLikedByAuthUser() ? 'liked' : '' }}" id="like-btn-{{ $spot->id }}" style="font-size: 1.25rem;"></i>
                    <!-- いいねメッセージ表示 -->
                    <div id="likeMessage-{{ $spot->id }}" class="hidden absolute left-1/2 transform -translate-x-1/2 -translate-y-full mb-2 text-sm text-white bg-gradient-to-r from-green-400 to-green-600 border border-green-700 rounded-lg px-4 py-2 shadow-xl transition-all duration-300 ease-in-out opacity-0" style="pointer-events: none;">
                      お気に入り登録しました
                    </div>
                  </div>
                @endauth
            </div>
          </div>
        @empty
          <p class="text-center text-gray-500">現在、スポット情報はありません。</p>
        @endforelse
      </div>

      <!-- ページネーション -->
      <div class="mt-8">
        {{ $spots->links() }}
      </div>
    </div>
  </div>
  
  <script>
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
        likeMessage.style.opacity = 1; // 初期状態を設定
        likeMessage.style.pointerEvents = "auto"; // メッセージのポインタイベントを有効化

        setTimeout(() => {
          likeMessage.style.opacity = 0; // メッセージをフェードアウト
          likeMessage.style.pointerEvents = "none"; // メッセージのポインタイベントを無効化
          likeMessage.classList.add("hidden"); // hiddenクラスを追加
        }, 800);
      } catch (error) {
        alert("処理が失敗しました。画面を再読み込みし、通信環境の良い場所で再度お試しください。");
      } finally {
        clickedEl.disabled = false; // ボタンを再度有効化
      }
    }
  </script>
</x-app-layout>
