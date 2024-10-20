<form action="{{ route('reviews.store', $spotId) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label for="title" class="block mb-2 text-gray-700 font-semibold">タイトル:</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" required maxlength="50" 
            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 transition duration-150 ease-in-out">
        @error('title')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="review" class="block mb-2 text-gray-700 font-semibold">評価:</label>
        <input type="number" id="review" name="review" value="{{ old('review') }}" required step="0.1" min="1.0" max="5.0" 
            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 transition duration-150 ease-in-out" 
            placeholder="1.0 - 5.0の範囲で入力">
        @error('review')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-4">
        <label for="comment" class="block mb-2 text-gray-700 font-semibold">口コミ:</label>
        <textarea id="comment" name="comment" rows="4" required 
            class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 transition duration-150 ease-in-out">{{ old('comment') }}</textarea>
        @error('comment')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <div>
        <!--画像のプレビューコンポーネント追加-->
        <x-image-preview input-name="images[]" />
        @error('images')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>
    
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

    <!-- エラーメッセージの表示 -->
    @if ($errors->any())
        <div class="mb-4 text-red-600 bg-red-100 p-3 rounded-md">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</form>
<script>
    // 口コミ投稿モーダルの表示/非表示切替
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
</script>