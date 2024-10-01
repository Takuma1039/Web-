<x-app-layout>
    <div class="bg-gray-50 dark:bg-gray-900 min-h-screen py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-4">口コミを編集</h2>
                <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="title" class="block mb-2 text-gray-700 font-semibold">タイトル:</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $review->title) }}" required maxlength="50" class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="review" class="block mb-2 text-gray-700 font-semibold">評価:</label>
                        <input type="number" id="review" name="review" value="{{ old('review', $review->review) }}" required step="0.1" min="1.0" max="5.0" class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="1.0 - 5.0の範囲で入力">
                    </div>

                    <div class="mb-4">
                        <label for="comment" class="block mb-2">口コミ:</label>
                        <textarea id="comment" name="comment" rows="4" required class="block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('comment', $review->comment) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="is_anonymous" class="inline-flex items-center">
                            <input type="checkbox" id="is_anonymous" name="is_anonymous" value="1" class="form-checkbox h-5 w-5 text-blue-600" {{ old('is_anonymous', $review->is_anonymous) ? 'checked' : '' }}>
                            <span class="ml-2 text-gray-700">匿名で投稿する</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="bg-blue-600 text-white rounded-lg px-4 py-2">更新</button>
                    <a href="javascript:history.back();" class="ml-2 bg-gray-300 rounded-lg px-4 py-2">キャンセル</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>



