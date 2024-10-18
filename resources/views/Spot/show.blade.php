<x-app-layout>
    <div class="bg-white dark:bg-gray-900 min-h-screen py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white flex items-center">
                            {{ $spot->name }}
                            @auth
                                <div class="ml-4">
                                    <!--お気に入りボタンコンポーネント追加-->
                                    <x-like-button :spot="$spot" />
                                </div>
                            @endauth
                        </h2>
                    </div>
          
                    <!--GoogleMap表示コンポーネント追加-->
                    <x-map-modal :api-key="$api_key" :latitude="$spot->lat" :longitude="$spot->long" :spot-name="$spot->name" />
                </div>
                <div class="p-4">
                    <div class="flex items-center">
                        <span class="font-bold text-lg">総合評価</span>
                        <!-- 総合評価評価コンポーネント追加 -->
                        <div class="flex items-center ml-2">
                            <x-rating :average-rating="$averageRating" />
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
                <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
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

                <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
                    <strong class="text-lg flex items-center">
                        <i class="fa-solid fa-snowflake mr-2"></i>おすすめシーズン:
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

                <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
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

                <div class="mb-4 p-4 bg-white rounded-lg shadow-md">
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
                <!-- モーダルコンポーネント -->
                <x-modal-window />
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
                        @auth
                            <!-- 口コミ投稿のボタン -->
                            <button id="reviewBtn" class="inline-block rounded-lg border border-blue-600 bg-blue-600 text-white hover:bg-blue-700 hover:border-blue-700 transition px-4 py-2 shadow-md">
                                口コミを投稿する
                            </button>
                        @endauth
                    </div>

                    <!-- 口コミ投稿のポップアップ -->
                    <div id="reviewModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
                        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full transition-transform transform scale-95 sm:hover:scale-100">
                            <h3 class="text-xl font-bold mb-4 text-center text-blue-600">口コミを投稿</h3>
                            <!-- 口コミ投稿フォーム -->
                            <x-review-form :spotId="$spot->id" />
                        </div>
                    </div>

                    <!--口コミの表示-->
                    @if($reviews->isEmpty())
                        <p class="text-gray-500">まだ口コミはありません。</p>
                    @else
                        @foreach($reviews as $review)
                            <div class="review bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg mb-4 transition-transform transform">
                                <div class="flex flex-col sm:flex-row justify-between mb-2">
                                    <div class="flex items-center">
                                        <!-- 星評価部分 -->
                                        @for ($i = 0; $i < 5; $i++)
                                            @php
                                                $currentValue = $i + 1;
                                                $reviewValue = floatval($review->review);
                                                $fullStar = $reviewValue >= $currentValue;
                                                $partialStar = !$fullStar && $reviewValue > $currentValue - 1 && $reviewValue < $currentValue;
                                                $fillPercentage = $partialStar ? ($reviewValue - ($currentValue - 1)) * 100 : 0;
                                            @endphp

                                            <div class="relative inline-block">
                                                <i class="fa-solid fa-star text-gray-300 text-xl"></i>
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
                                        <span class="ml-2 text-gray-700 font-semibold">投稿者: {{ $review->is_anonymous ? '匿名' : $review->user->name }}</span>
                                    </div>

                                    @auth
                                        @if (Auth::id() === $review->user_id)
                                            <div class="flex space-x-2 mt-2 sm:mt-0">
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
                                        <button class="like-button text-gray-500 hover:text-pink-500 flex items-center transition duration-200 ease-in-out" data-review-id="{{ $review->id }}">
                                            @if ($review->likes->where('user_id', auth()->id())->count())
                                                <i class="fas fa-thumbs-up"></i>
                                            @else
                                                <i class="far fa-thumbs-up"></i>
                                            @endif
                                        </button>
                                        <span class="ml-2 text-gray-600" id="like-count-{{ $review->id }}">{{ $review->likes ? $review->likes->count() : 0 }} 件のいいね</span>
                                        <span id="like-message-{{ $review->id }}" class="ml-2 text-pink-600 hidden text-sm font-semibold bg-pink-100 px-2 py-1 rounded-md transition duration-300"></span> <!-- メッセージ表示用 -->
                                    </div>
                                @endauth

                                <p class="text-gray-700 font-semibold text-lg mt-1">{{ $review->title }}</p>
                                <p class="text-gray-700 mt-2">{{ $review->comment }}</p>

                                <!-- 画像ギャラリー -->
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-4"> <!-- レスポンシブグリッド -->
                                    @foreach($reviewImages[$review->id] as $review_img)
                                        <a class="group relative flex h-32 items-end overflow-hidden rounded-lg shadow-lg transition-transform transform hover:scale-105" onclick="openModal('{{ $review_img->image_path }}')">
                                            <img src="{{ $review_img->image_path }}" loading="lazy" alt="Image" class="absolute inset-0 h-full w-full object-cover transition duration-200 group-hover:opacity-90" />
                                            <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-40"></div>
                                            <span class="relative ml-4 mb-3 inline-block text-sm text-white">{{ $review_img->name }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
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
        //口コミいいね機能
        document.addEventListener('DOMContentLoaded', function () {
            const likeButtons = document.querySelectorAll('.like-button');

            likeButtons.forEach(button => {
                let isProcessing = false;

                button.addEventListener('click', async function () {
                    if (isProcessing) return;
                    isProcessing = true;

                    const reviewId = this.getAttribute('data-review-id');
                    const likeCountElement = document.getElementById(`like-count-${reviewId}`);
                    const messageElement = document.getElementById(`like-message-${reviewId}`);

                    this.disabled = true;
                    this.classList.add('opacity-50');

                    try {
                        const response = await fetch(`/reviews/like`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ review_id: reviewId })
                        });

                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }

                        const data = await response.json();

                        // いいね数を更新
                        likeCountElement.textContent = `${data.likeCount} 件のいいね`;

                        // いいね状態に応じてメッセージを表示
                        if (data.liked) {
                            messageElement.textContent = "いいね！";
                            this.innerHTML = '<i class="fas fa-thumbs-up"></i>';
                            this.classList.add('text-blue-500');
                        } else {
                            messageElement.textContent = "いいねを取り消しました！";
                            this.innerHTML = '<i class="far fa-thumbs-up"></i>';
                            this.classList.remove('text-blue-500');
                        }

                        // メッセージを表示
                        messageElement.classList.remove('hidden');

                        // 数秒後にメッセージを非表示にする
                        setTimeout(() => {
                            messageElement.classList.add('hidden');
                        }, 1000);
                    } catch (error) {
                        console.error('Error:', error);
                        alert('処理中にエラーが発生しました。もう一度お試しください。');
                    } finally {
                        this.disabled = false;
                        this.classList.remove('opacity-50');
                        isProcessing = false;
                    }
                });
            });
        });
    </script>
</x-app-layout>