<x-app-layout>
    <div class="container mx-auto max-w-screen-xl">
        <h1 class="text-4xl font-extrabold text-gray-800 text-center">旅行計画をリメイク</h1>

        <form action="{{ route('planposts.planstore') }}" method="POST" class="bg-white p-8 rounded-lg shadow-lg space-y-6">
            @csrf
            
            <!-- タイトル -->
            <div>
                <label for="title" class="block text-lg font-semibold text-gray-700">旅行計画のタイトル</label>
                <input type="text" name="title" id="title" value="{{ $planpost->title }}" required 
                    class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3 transition duration-200 hover:shadow-lg">
                @error('title')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 旅行日程 -->
            <div>
                <label for="date" class="block text-lg font-semibold text-gray-700">旅行日程</label>
                <input type="date" name="start_date" id="date" value="{{ $planpost->start_date->format('Y-m-d') }}" required 
                    class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3 transition duration-200 hover:shadow-lg">
                @error('start_date')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 旅行出発時間 -->
            <div>
                <label for="time" class="block text-lg font-semibold text-gray-700">出発時間</label>
                <input type="time" name="start_time" id="time" value="{{ $planpost->start_time->format('H:i') }}" required 
                    class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3 transition duration-200 hover:shadow-lg">
                @error('start_time')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 目的地 -->
            <div id="destination-container">
                @foreach($planpost->destinations as $index => $destination)
                <div class="destination-item p-4 border rounded-lg shadow-md bg-gray-50 mb-4">
                    <label class="block text-lg font-semibold text-gray-700">目的地 {{ $index + 1 }}</label>
                    <select name="destinations[]" required 
                        class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 transition duration-200 hover:shadow-lg">
                        <option value="" disabled {{ old('destinations') ? '' : 'selected' }}>目的地をお気に入りしたスポットから選択してください</option>
                        @foreach($likedSpots as $likedspot)
                            <option value="{{ $likedspot->id }}" data-lat="{{ $likedspot->lat }}" data-lng="{{ $likedspot->long }}"
                                {{ $destination->id == $likedspot->id ? 'selected' : '' }}>
                                {{ $likedspot->name }}
                            </option>
                        @endforeach
                        
                        <!-- likedspotに存在しない目的地を追加 -->
                        @if(!$likedSpots->contains('id', $destination->id))
                            <option value="{{ $destination->id }}" data-lat="{{ $destination->lat }}" data-lng="{{ $destination->long }}" selected>
                                {{ $destination->name }} (未登録の目的地)
                            </option>
                        @endif
                    </select>
                    <button type="button" class="remove-destination mt-2 bg-red-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-600 transition duration-200 shadow-lg">
                        削除
                    </button>
                </div>
                @endforeach
            </div>

            <!-- 目的地追加ボタン -->
            <div class="mt-4">
                <button type="button" id="add-destination" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition duration-200 shadow-lg">
                    次の目的地を追加
                </button>
            </div>

            <!-- 送信ボタン -->
            <div>
                <button type="submit" 
                    class="w-full bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 transition-all duration-300 shadow-lg">
                    旅行計画を作成
                </button>
            </div>
        </form>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <script>
    // 目的地を追加する機能
    document.getElementById('add-destination').addEventListener('click', function () {
        const destinationContainer = document.getElementById('destination-container');
        const destinationCount = destinationContainer.children.length + 1;

        const destinationItem = document.createElement('div');
        destinationItem.classList.add('destination-item', 'p-4', 'border', 'rounded-lg', 'shadow-md', 'bg-gray-50', 'mb-4');
        destinationItem.innerHTML = `
            <label class="block text-lg font-semibold text-gray-700">目的地 ${destinationCount}</label>
            <select name="destinations[]" required 
                class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 transition duration-200 hover:shadow-lg">
                <option value="" disabled selected>目的地をお気に入りしたスポットから選択してください</option>
                @foreach($likedSpots as $likedspot)
                    <option value="{{ $likedspot->id }}" data-lat="{{ $likedspot->lat }}" data-lng="{{ $likedspot->long }}">
                        {{ $likedspot->name }}
                    </option>
                @endforeach
            </select>
            <button type="button" class="remove-destination mt-2 bg-red-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-600 transition duration-200 shadow-lg">
                削除
            </button>
        `;
        destinationContainer.appendChild(destinationItem);
    });

    // 目的地の削除機能
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-destination')) {
            event.target.closest('.destination-item').remove();
        }
    });
    </script>
</x-app-layout>
