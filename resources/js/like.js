const fetch = require('node-fetch');

fetch('https://api.codedamn.com/some-endpoint')
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('Error:', error));

const likeBtn = document.querySelector(".like-btn");
likeBtn.addEventListener("click", async (e) => {
    const clickedEl = e.target;
    clickedEl.classList.toggle("liked");

    const postId = e.target.id;

    const res = await fetch("/spot/like", {
        //リクエストメソッドはPOST
        method: "POST",
        headers: {
            //Content-Typeでサーバーに送るデータの種類を伝える。今回はapplication/json
            "Content-Type": "application/json",
            //csrfトークンを付与
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ post_id: postId }),
    })
        .then((res) => res.json())
        .then((data) => {
            clickedEl.nextElementSibling.innerHTML = data.likesCount;
        })
        .catch(() =>
            alert(
                "処理が失敗しました。画面を再読み込みし、通信環境の良い場所で再度お試しください。"
            )
        );
});