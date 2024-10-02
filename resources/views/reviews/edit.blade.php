<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">口コミを編集</h2>
                <form action="{{ route('reviews.update', $review->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <!-- タイトル入力欄 -->
    <div class="mb-4">
        <label for="title" class="block mb-2 text-gray-700 font-semibold">タイトル:</label>
        <input type="text" id="title" name="title" value="{{ old('title', $review->title) }}" required maxlength="50" class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
    </div>

    <!-- レビューの評価入力欄 -->
    <div class="mb-4">
        <label for="review" class="block mb-2 text-gray-700 font-semibold">評価:</label>
        <input type="number" id="review" name="review" value="{{ old('review', $review->review) }}" required step="0.1" min="1.0" max="5.0" class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="1.0 - 5.0の範囲で入力">
    </div>

    <!-- 口コミコメント入力欄 -->
    <div class="mb-4">
        <label for="comment" class="block mb-2">口コミ:</label>
        <textarea id="comment" name="comment" rows="4" required class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('comment', $review->comment) }}</textarea>
    </div>
    
    <!-- 既存の画像表示 -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">現在の画像</label>
        <div class="mt-1 flex flex-wrap">
            @foreach($reviewImages as $reviewImage)
                <div class="mr-4">
                    <img src="{{ $reviewImage->image_path }}" alt="口コミ画像" class="h-32 w-auto object-cover mb-2">
                    <div>
                        <input type="checkbox" name="remove_images[]" value="{{ $reviewImage->id }}">
                        <label>削除</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 新しい画像を追加 -->
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">新しい画像ファイル（複数可）:</label>
        <input type="file" name="images[]" id="image-input" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" accept="image/*" onchange="previewImages()">
        <div id="image-preview" class="grid grid-cols-2 gap-4 mt-2"></div>
    </div>

    <!-- 既存の画像名の編集 -->
    @foreach ($reviewImages as $index => $reviewImage)
        <div class="mt-2">
            <label for="image_name_{{ $index }}" class="block text-sm font-medium text-gray-700">画像名:</label>
            <input type="text" id="image_name_{{ $index }}" name="image_names[]" value="{{ old('image_names.'.$index, $reviewImage->name) }}" class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="画像の名前を入力">
            <input type="hidden" name="image_ids[]" value="{{ $reviewImage->id }}">
        </div>
    @endforeach

    <!-- エラー表示 -->
    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 匿名投稿チェックボックス -->
    <div class="mb-4">
        <label class="inline-flex items-center">
            <input type="checkbox" id="is_anonymous" name="is_anonymous" value="1" class="form-checkbox h-5 w-5 text-blue-600" {{ old('is_anonymous', $review->is_anonymous) ? 'checked' : '' }}>
            <span class="ml-2 text-gray-700">匿名で投稿する</span>
        </label>
    </div>
    
    <!-- 更新ボタン -->
    <button type="submit" class="bg-blue-600 text-white rounded-lg px-4 py-2">更新</button>
    <a href="javascript:history.back();" class="ml-2 bg-gray-300 rounded-lg px-4 py-2">キャンセル</a>
</form>

            </div>
        </div>
    </div>

    <script>
        function previewImages() {
    const preview = document.getElementById('image-preview'); //IDがimage-previewの要素を取得
    preview.innerHTML = ''; // プレビューをリセット

    const files = document.getElementById('image-input').files; // IDがimage-inputの要素から選択されたファイルを取得
    //もしファイルが選択されていなければ、プレビュー領域に「画像が選択されていません。」というメッセージを表示
    if (files.length === 0) {
        preview.innerHTML = '<p class="text-gray-500">画像が選択されていません。</p>';
        return;
    }
    //選択されたすべてのファイルに対してループを実行
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader(); //FileReaderオブジェクトを作成

        // 画像のプレビューと名前入力欄を作成
        reader.onload = function(e) { //FileReaderオブジェクトがファイルを読み込んだときに呼び出されるイベントハンドラー
            const imgContainer = document.createElement('div'); //新しいdiv要素の作成
            imgContainer.classList.add('flex', 'flex-col', 'items-center', 'mb-4'); //imgContainerにTailwind CSSのスタイルクラスを追加

            const img = document.createElement('img'); //新しいimg要素を作成
            img.src = e.target.result; //FileReaderが読み込んだ画像データをimg要素のsrc属性に設定
            img.classList.add('h-32', 'w-full', 'object-cover', 'rounded-lg', 'shadow-lg'); //imgに対して、Tailwind CSSのスタイルクラスを追加

            // 新しく追加された画像に対する名前入力欄を生成
            const nameInput = document.createElement('input'); //新しいinput要素を作成
            nameInput.type = 'text'; //入力フィールドのタイプをテキストに設定
            nameInput.name = `new_image_names[]`; // 入力フィールドの名前を設定
            nameInput.placeholder = '画像の名前を入力'; //入力フィールドが空のときに表示されるプレースホルダーテキストを設定
            nameInput.classList.add('mt-1', 'block', 'w-full', 'border', 'border-gray-300', 'rounded-md', 'shadow-sm', 'focus:ring', 'focus:ring-indigo-200'); //入力フィールドにTailwind CSSのスタイルクラスを追加

            imgContainer.appendChild(img); //生成した画像をコンテナimgContainerに追加
            imgContainer.appendChild(nameInput); //生成した名前入力欄をコンテナに追加
            preview.appendChild(imgContainer); //previewという親要素にimgContainerを追加
        }

        reader.readAsDataURL(file); //選択されたファイルを読み込み、onloadイベントがトリガーされるのを待つ
    }
}
    </script>
</x-app-layout>
