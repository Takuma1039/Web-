@props(['planpost'])

<div class="relative flex items-center">
    <!-- いいねメッセージ表示 -->
    <div id="likeMessage-{{ $planpost->id }}" 
        class="hidden absolute left-1/2 transform -translate-x-1/2 -translate-y-full mb-2 text-sm font-semibold text-fuchsia-600 bg-fuchsia-100 px-2 py-1 rounded-full transition duration-300 opacity-0 z-50" 
        style="pointer-events: none; white-space: nowrap; z-index: 200;">
    </div>

    <!-- いいねボタン -->
    <i class="fa-solid fa-heart like-btn {{ $planpost->isLikedByAuthUser() ? 'liked' : '' }}" 
        id="like-btn-{{ $planpost->id }}" style="font-size: 1.25rem; z-index: 10;"></i>
  
    <!-- いいねボタンの処理 -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const likeBtn = document.getElementById('like-btn-{{ $planpost->id }}');
            const likeMessage = document.getElementById('likeMessage-{{ $planpost->id }}');
            const likeCount = document.getElementById('likeCount-{{ $planpost->id }}'); // いいね数の要素を取得

            likeBtn.addEventListener('click', async () => {
                likeBtn.disabled = true; // ボタンを無効化
                likeBtn.classList.toggle('liked'); // いいね状態の切り替え

                try {
                    const res = await fetch("/planpost/like", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({ planpost_id: {{ $planpost->id }} }),
                    });

                    if (!res.ok) throw new Error('Network response was not ok');
                    const data = await res.json();
                    
                    // いいね数の更新（要素が存在する場合のみ）
                    if (likeCount) {
                        likeCount.textContent = `${data.likes_count} いいね`;
                    }

                    // メッセージの表示と切り替え
                    likeMessage.textContent = likeBtn.classList.contains('liked') ? 'いいね' : 'いいねを解除しました';
                    likeMessage.classList.remove('hidden');
                    likeMessage.style.opacity = 1; // メッセージ表示
                    likeMessage.style.zIndex = 200;

                    setTimeout(() => {
                        likeMessage.style.opacity = 0; // メッセージをフェードアウト
                        likeMessage.classList.add('hidden');
                    }, 1000);

                } catch (error) {
                    alert('エラーが発生しました。通信環境を確認してください。');
                } finally {
                    likeBtn.disabled = false; // ボタンを再度有効化
                }
            });
        });
    </script>
</div>
