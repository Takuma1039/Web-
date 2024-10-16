<x-app-layout>
    <div class="container mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-800 text-center mb-8">いいねした旅行計画一覧</h1>
        
        <div class="flex flex-col md:flex-row-reverse items-center md:gap-2">
            <!-- みんなの旅行計画 -->
            <div class="text-right mb-4">
                <a href="{{ route('planposts.index') }}" class="bg-teal-600 text-white border-2 border-teal-600 rounded-full px-4 py-1 font-bold uppercase tracking-wide hover:bg-white hover:text-teal-800 transition-all duration-300 flex items-center justify-center">
                    みんなの旅行計画から探す
                </a>
            </div>
        
            <!-- 旅行計画の新規投稿ボタン -->
            <div class="text-right mb-4">
                <a href="{{ route('plans.post') }}" class="bg-fuchsia-500 text-white border-2 border-fuchsia-500 rounded-full px-4 py-1 font-bold uppercase tracking-wide hover:bg-white hover:text-fuchsia-600 transition-all duration-300 flex items-center justify-center">
                    旅行計画の投稿
                </a>
            </div>
        </div>

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

        <!-- いいねした旅行計画一覧 -->
        @if ($planposts->count() > 0)
            <div class="p-1 space-y-6 md:space-y-8">
                @foreach ($planposts as $planpost)
                    <div class="flex flex-col md:flex-row justify-between border border-gray-300 rounded-lg p-4 md:p-6 shadow-sm">
                        <div class="w-full md:w-3/5 mb-4 md:mb-0">
                            <div class="flex items-center justify-between mb-3 md:mb-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('plans.show', $planpost->planpost->plan->id) }}">
                                        <h2 class="text-lg md:text-xl font-bold transition duration-300 ease-in-out transform hover:text-indigo-600">{{ $planpost->title }}</h2>
                                    </a>
                                    <i class="fa-solid fa-heart like-btn {{ $planpost->planpost->isLikedByAuthUser() ? 'liked' : '' }}" 
                                        id="{{ $planpost->planpost->id }}" style="font-size: 1.25rem;"></i>
                                </div>
                                <div class="text-right text-sm">
                                    <a href="{{ route('plans.post') }}" class="bg-blue-600 text-white border-2 border-blue-600 rounded-full px-2 py-1 font-bold uppercase tracking-wide hover:bg-white hover:text-blue-700 transition-all duration-300">
                                        自分用に編集
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center justify-between mb-3 md:mb-4">
                                <span class="text-gray-700 font-semibold text-sm md:text-md">投稿者: 
                                    @if(Auth::id() === $planpost->planpost->user_id)
                                        {{ $planpost->planpost->user->name }}
                                    @else
                                        {{ $planpost->planpost->is_anonymous ? '匿名' : $planpost->planpost->user->name }}
                                    @endif
                                </span>
                                <span class="inline-block bg-teal-100 text-teal-800 text-xs md:text-sm font-medium mr-1 px-2.5 py-0.5 rounded">カテゴリー: 
                                    {{ $planpost->planpost->local->name }} 
                                    {{ $planpost->planpost->season->name }}
                                    {{ $planpost->planpost->month->name }}
                                    {{ $planpost->planpost->plantype->name }}
                                </span>
                            </div>
                            <div class="border border-gray-300 bg-gray-50 rounded-md p-4 mt-2 md:mt-4">
                                <p class="text-base md:text-md text-gray-600">{{ $planpost->comment }}</p>
                            </div>
                        </div>

                        <div class="w-full md:w-2/5 flex flex-col gap-4 relative md:ml-6">
                            @foreach($planpost->planpost->planimages as $plan_img)
                                <a class="group relative flex-grow overflow-hidden rounded-lg shadow-lg transition-transform duration-300 transform hover:scale-105" onclick="openModal('{{ $plan_img->image_path }}')">
                                    <img src="{{ asset($plan_img->image_path) }}" loading="lazy" alt="Image" class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:opacity-90" />
                                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-40"></div>
                                </a>
                            @endforeach
                        </div>
                        <x-modal-window />
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-600 mt-8">現在、いいねした旅行計画はありません。</p>
        @endif
    </div>
    <script type="module">
      document.addEventListener('DOMContentLoaded', () => {
          setupLikeButtons();
          checkForEmptyPlans(); // 初期状態で旅行計画の数をチェック
      });

      function setupLikeButtons() {
          const likeBtns = document.querySelectorAll(".like-btn");
          likeBtns.forEach(btn => {
              btn.addEventListener("click", handleLikeButtonClick);
          });
      }

      async function handleLikeButtonClick(e) {
          const clickedEl = e.target;
          const planpostId = clickedEl.id;
          clickedEl.disabled = true;
          clickedEl.classList.toggle("liked");

          if (!clickedEl.classList.contains("liked") && !confirm("本当にいいねを解除しますか？(解除するといいねした旅行計画の一覧から削除されます。)")) {
              clickedEl.classList.toggle("liked"); // 元に戻す
              clickedEl.disabled = false; // ボタンを再度有効化
              return; // 処理を中止
          }

          try {
              const res = await fetch("/planpost/like", {
                  method: "POST",
                  headers: {
                      "Content-Type": "application/json",
                      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                  },
                  body: JSON.stringify({ planpost_id: planpostId }),
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
              const likesplan = clickedEl.closest('.likesplan');
              likesplan.remove(); // スポットカードを削除
              checkForEmptyPlans(); // 残りの旅行計画をチェック
          } else {
              showLikeMessage(clickedEl.id, "いいね");
          }
      }

      function checkForEmptySpots() {
          const remainingPlans = document.querySelectorAll('.likesplan');
          console.log(`残っている旅行計画の数: ${remainingPlans.length}`);
          
          remainingPlans.forEach((planpost, index) => {
              console.log(`旅行計画 ${index + 1}:`, planpost);
          });

          const noPlansMessage = document.querySelector('.no-plans-message');
          if (remainingPlans.length === 0) {
              noPlansMessage.classList.remove('hidden'); // hiddenクラスを削除して表示
              noPlansMessage.style.display = 'block'; // 明示的に表示
          } else {
              noPlansMessage.classList.add('hidden'); // 旅行計画がある場合は非表示
          }
      }

      function showLikeMessage(PlanpostId, message) {
          const likeMessage = document.getElementById(`likeMessage-${PlanpostId}`);
          if (likeMessage) {
              likeMessage.textContent = message;
              likeMessage.classList.remove("hidden");

              setTimeout(() => {
                  likeMessage.classList.add("hidden");
              }, 800);
          }
      }
  </script>
</x-app-layout>
