<x-app-layout>
    <div class="container mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-800 text-center mb-8">旅行計画の投稿</h1>

        <!-- 成功メッセージ -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- 旅行計画の投稿フォーム -->
        <form action="{{ route('planposts.store', $planId) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- 旅行計画の選択 -->
            <div class="mb-4">
                <label for="plan_id" class="block text-gray-700 font-bold mb-2">旅行計画を選択:</label>
                <select id="plan_id" name="plan_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">旅行計画を選択してください</option>
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}">
                            {{ $plan->title }} - {{ $plan->start_date->format('Y年m月d日') }} {{ $plan->start_time->format('H時i分') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="comment" class="block text-gray-700 font-bold mb-2">コメント:</label>
                <textarea id="comment" name="comment" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
            </div>

            <div class="mb-4">
                <label for="start_date" class="block text-gray-700 font-bold mb-2">開始日:</label>
                <input type="date" id="start_date" name="start_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="start_time" class="block text-gray-700 font-bold mb-2">開始時間:</label>
                <input type="time" id="start_time" name="start_time" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 transition duration-200 shadow-lg">
                    投稿する
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
