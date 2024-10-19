<x-app-layout>
    <div class="container mx-auto bg-rose-100 p-6 rounded-lg shadow-lg">
        <h1 class="text-4xl font-extrabold text-gray-800 text-center mb-8">アプリの使い方</h1>
        
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <h2 class="text-2xl font-semibold mb-4">ステップ1: 新規登録しよう！</h2>
            <p class="mt-2 text-gray-600 text-lg">アプリを開いたら、ホーム画面が表示されます。最初はゲストモードになっているので、新規登録ボタンをクリックして新規登録しましょう。</p>
            <p class="mt-2 text-gray-600 text-lg">ゲストモードでは一部の機能は利用できませんが、十分このアプリを楽しめます。ゲストモードでできること・できないことについては以下を確認してください。</p>
            <p class="mt-2 text-gray-600 text-lg">ゲストモードでできること</p>
            <ul class="list-none ml-2">
                <li class="flex items-center mb-2">
                    <i class="fa-regular fa-circle text-green-500 mr-2"></i> ランキングやスポットの詳細画面が見れる
                </li>
                <li class="flex items-center mb-2">
                    <i class="fa-regular fa-circle text-green-500 mr-2"></i> みんなの旅行計画が見れる
                </li>
                <li class="flex items-center mb-2">
                    <i class="fa-regular fa-circle text-green-500 mr-2"></i> スポットごとの口コミ投稿一覧が見れる
                </li>
            </ul>
            <p class="mt-2 text-gray-600 text-lg">ゲストモードではできないこと</p>
            <ul class="list-none ml-2">
                <li class="flex items-center mb-2">
                    <i class="fas fa-xmark text-red-500 mr-2"></i> スポットのお気に入り登録
                </li>
                <li class="flex items-center mb-2">
                    <i class="fas fa-xmark text-red-500 mr-2"></i> マイページ機能
                </li>
                <li class="flex items-center mb-2">
                    <i class="fas fa-xmark text-red-500 mr-2"></i> 旅行計画作成
                </li>
                <li class="flex items-center mb-2">
                    <i class="fas fa-xmark text-red-500 mr-2"></i> 口コミ投稿やいいね
                </li>
                <li class="flex items-center mb-2">
                    <i class="fas fa-xmark text-red-500 mr-2"></i> みんなの旅行計画へのいいね
                </li>
            </ul>
            <div class="bg-gray-300 rounded-lg shadow-md p-1 mb-2">
                <img src="{{ cloudinary_url('ホーム画面_ygyqai.png') }}" alt="Step 1" class="mx-auto rounded-lg shadow-md" onclick="openModal('{{ cloudinary_url('ホーム画面_ygyqai.png') }}')">
            </div>
        </div>
        <x-modal-window />
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <h2 class="text-2xl font-semibold mb-4">ステップ2: スポットをお気に入り登録しよう！</h2>
            <p class="mt-2 text-gray-600 text-lg">ランキングや検索バーから気になるスポットや行きたいスポットを探してみましょう。スポット名をクリックすると詳細画面を見ることができます。</p>
            <p class="mt-2 text-gray-600 text-lg">スポット詳細画面ではスポットの写真や詳細情報、口コミを見ることができます。</p>
            <p class="mt-2 text-gray-600 text-lg">気になるスポットや行きたいスポットが見つかったら、スポット名の隣にある星をクリックするとお気に入り登録できます。</p>
            <div class="bg-gray-300 rounded-lg shadow-md p-1 mt-2 mb-2">
                <img src="{{ cloudinary_url('スポット詳細_lhrhrr.png') }}" alt="Step 2" class="mx-auto rounded-lg shadow-md" onclick="openModal('{{ cloudinary_url('スポット詳細_lhrhrr.png') }}')">
            </div>
            <p class="mt-2 text-gray-600 text-lg">お気に入り登録したスポットはマイページのお気に入りスポットに表示されます。</p>
            <div class="bg-gray-300 rounded-lg shadow-md p-1 mt-2 mb-2">
                <img src="{{ cloudinary_url('マイページ_e24pro.png') }}" alt="Step 2" class="mx-auto rounded-lg shadow-md" onclick="openModal('{{ cloudinary_url('マイページ_e24pro.png') }}')">
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <h2 class="text-2xl font-semibold mb-4">ステップ3: 旅行プランを作成してみよう！</h2>
            <p class="mt-2 text-gray-600 text-lg">サイドバーの旅行プラン作成項目から旅行計画を作成します。</p>
            <div class="bg-gray-300 rounded-lg shadow-md p-1 mt-2 mb-2">
                <img src="{{ cloudinary_url('サイドバーから旅行計画を作成_xf4cg6.png') }}" alt="Step 3" class="mx-auto rounded-lg shadow-md" onclick="openModal('{{ cloudinary_url('サイドバーから旅行計画を作成_xf4cg6.png') }}')">
            </div>
            <p class="mt-2 text-gray-600 text-lg">旅行計画作成画面ではお気に入り登録したスポットを目的地とした旅行計画を作成することができます。</p>
            <p class="mt-2 text-gray-600 text-lg">目的地の追加ボタンを押すと新たな目的地をお気に入りスポットから選択できます。</p>
            <p class="mt-2 text-gray-600 text-lg">最後に[旅行計画を作成]ボタンをクリックすると旅行計画が作成されます。</p>
            <div class="bg-gray-300 rounded-lg shadow-md p-1 mt-2 mb-2">
                <img src="{{ cloudinary_url('旅行プラン作成_aijowv.png') }}" alt="Step 3" class="mx-auto rounded-lg shadow-md" onclick="openModal('{{ cloudinary_url('旅行プラン作成_aijowv.png') }}')">
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <h2 class="text-2xl font-semibold mb-4">ステップ4: 作成したマイ旅行プランを投稿してみよう！</h2>
            <p class="mt-2 text-gray-600 text-lg">旅行計画一覧から[旅行計画投稿]ボタンをクリックすると旅行計画画面に移ります。</p>
            <div class="bg-gray-300 rounded-lg shadow-md p-1 mt-2 mb-2">
                <img src="{{ cloudinary_url('旅行計画投稿_q6gvhu.png') }}" alt="Step 3" class="mx-auto rounded-lg shadow-md" onclick="openModal('{{ cloudinary_url('旅行計画投稿_q6gvhu.png') }}')">
            </div>
            <p class="mt-2 text-gray-600 text-lg">旅行計画投稿画面では、作成した旅行計画を選択して、タイトル・コメント・写真を含めて投稿することができます。匿名で投稿したい場合は[匿名で投稿する]にチェックを入れて投稿しましょう。</p>
            <p class="mt-2 text-gray-600 text-lg">投稿するとみんなの旅行計画に自分が投稿した旅行プランが表示されます。</p>
            <div class="bg-gray-300 rounded-lg shadow-md p-1 mt-2 mb-2">
                <img src="{{ cloudinary_url('みんなの旅行計画_pbsjv6.png') }}" alt="Step 3" class="mx-auto rounded-lg shadow-md" onclick="openModal('{{ cloudinary_url('みんなの旅行計画_pbsjv6.png') }}')">
            </div>
            <p class="mt-2 text-gray-600 text-lg">行ったスポットの感想やおすすめポイント、思い出写真をみんなと共有しましょう！</p>
            <p class="mt-2 text-gray-600 text-lg">ここまでがこのアプリの使い方の流れです。使い方の流れが理解できたら、[アプリを始める]ボタンを押してアプリを始めましょう！</p>
        </div>

        <div class="text-center">
            <a href="{{ route('Toppage') }}" class="inline-block bg-pink-600 text-white rounded-full px-4 py-2 mt-4 hover:bg-pink-500 transition duration-300">
                アプリを始める
            </a>
        </div>
</x-app-layout>
