<div class="mb-4">
    @auth
        <div class="flex items-center mb-2">
            <button class="like-button text-gray-500 hover:text-blue-500 flex items-center transition duration-200 ease-in-out" data-review-id="{{ $review->id }}">
                @if ($review->likes->where('user_id', auth()->id())->count())
                    <i class="fas fa-thumbs-up"></i>
                @else
                    <i class="far fa-thumbs-up"></i>
                @endif
            </button>
            <span class="ml-2 text-gray-600" id="like-count-{{ $review->id }}">{{ $review->likes ? $review->likes->count() : 0 }} 件のいいね</span>
            <span id="like-message-{{ $review->id }}" class="ml-2 text-green-600 hidden text-sm font-semibold bg-green-100 px-2 py-1 rounded-md transition duration-300"></span> <!-- メッセージ表示用 -->
        </div>
    @endauth
</div>

<script>
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
                        messageElement.textContent = "いいねしました！";
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


