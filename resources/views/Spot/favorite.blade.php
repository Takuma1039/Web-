<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-extrabold text-gray-800 text-center mb-8">お気に入りスポット</h1>
        
        <div class="mb-6 p-4 bg-white border-l-4 border-teal-400 rounded-lg shadow-md">
            <p class="text-lg font-semibold text-gray-800">
                スポット一覧からお気に入りスポットを探そう！
            </p>
            <a href="{{ route('spots.index') }}" class="inline-block mt-2 px-4 py-2 bg-teal-400 text-white rounded-full shadow transition duration-300 ease-in-out hover:bg-sky-600 hover:scale-105">
                スポット一覧
            </a>
        </div>

        @if($likedSpots->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($likedSpots as $spot)
                    <div class="spot-card bg-white rounded-lg shadow-lg overflow-hidden transition-transform duration-200 transform sm:hover:scale-105">
                        <a href="/spots/{{ $spot->spot->id }}">
                            <img src="{{ $spot->spot->spotimages->first()->image_path ?? asset('images/default-image.jpg') }}" 
                                alt="{{ $spot->spot->name ?? 'デフォルトのスポット画像' }}" class="w-full h-48 object-cover">
                        </a>
                        <div class="flex items-center justify-between p-2">
                            <h2 class="text-lg font-bold text-gray-800">{{ $spot->spot->name }}</h2>
                            <i class="fa-solid fa-star like-btn {{ $spot->spot->isLikedByAuthUser() ? 'liked' : '' }}" 
                                id="{{ $spot->spot->id }}" style="font-size: 1.25rem;"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <p class="no-spots-message hidden">まだお気に入り登録したスポットはありません。</p>
    </div>
    <div class='mt-8'>
            <x-pagination :paginator="$likedSpots" />
    </div>
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            setupLikeButtons();
            checkForEmptySpots(); // 初期状態でスポットの数をチェック
        });

        function setupLikeButtons() {
            const likeBtns = document.querySelectorAll(".like-btn");
            likeBtns.forEach(btn => {
                btn.addEventListener("click", handleLikeButtonClick);
            });
        }

        async function handleLikeButtonClick(e) {
            const clickedEl = e.target;
            const spotId = clickedEl.id;
            clickedEl.disabled = true;
            clickedEl.classList.toggle("liked");

            if (!clickedEl.classList.contains("liked") && !confirm("本当にお気に入りを解除しますか？(解除するとお気に入りスポットの一覧から削除されます。)")) {
                clickedEl.classList.toggle("liked"); // 元に戻す
                clickedEl.disabled = false; // ボタンを再度有効化
                return; // 処理を中止
            }

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
                handleResponse(data, clickedEl);
            } catch (error) {
                console.error("処理が失敗しました。再試行してください。");
            } finally {
                clickedEl.disabled = false; // ボタンを再度有効化
            }
        }

        function handleResponse(data, clickedEl) {
            const isLiked = clickedEl.classList.contains("liked");
            if (!isLiked) {
                const spotCard = clickedEl.closest('.spot-card');
                spotCard.remove(); // スポットカードを削除
                checkForEmptySpots(); // 残りのスポットをチェック
            } else {
              return;
            }
        }

        function checkForEmptySpots() {
            const remainingSpots = document.querySelectorAll('.spot-card');
            //console.log(`残っているスポットの数: ${remainingSpots.length}`);

            const noSpotsMessage = document.querySelector('.no-spots-message');
            if (remainingSpots.length === 0) {
                noSpotsMessage.classList.remove('hidden'); // hiddenクラスを削除して表示
                noSpotsMessage.style.display = 'block'; // 明示的に表示
            } else {
                noSpotsMessage.classList.add('hidden'); // スポットがある場合は非表示
            }
        }
    </script>
</x-app-layout>












