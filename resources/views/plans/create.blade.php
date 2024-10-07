<x-app-layout>
    <div class="container mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-800 text-center">旅行計画を作成</h1>

        <form action="{{ route('plans.store') }}" method="POST" class="bg-white p-8 rounded-lg shadow-lg space-y-6">
            @csrf
            
            <!-- タイトル -->
            <div>
                <label for="title" class="block text-lg font-semibold text-gray-700">旅行計画のタイトル</label>
                <input type="text" name="title" id="title" required 
                    class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3 transition duration-200 hover:shadow-lg">
                @error('title')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- 検索機能 -->
            <div>
                <label for="search" class="block text-lg font-semibold text-gray-700">初期値にする場所の検索</label>
                <div class="flex">
                    <input type="text" id="search" placeholder="場所を検索..." 
                        class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3 transition duration-200 hover:shadow-lg">
                    <button type="button" id="search-button" 
                        class="ml-2 bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 shadow-lg">
                        検索
                    </button>
                </div>
            </div>
            
            <!-- 初期位置の選択 -->
            <div>
                <label for="initial_position" class="block text-lg font-semibold text-gray-700">初期位置の選択</label>
                <div id="map" class="w-full h-80 mb-4 rounded-lg border shadow-md"></div>
                <input type="hidden" name="initial_position" id="initial_position" required>
                <p class="text-sm text-gray-500">地図上をクリックしても初期位置を選択できます。</p>
                <button type="button" id="toggle-pin" class="mt-4 bg-gray-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-200 shadow-lg">
                    ピンを固定
                </button>
            </div>

            <!-- 目的地 -->
            <div id="destination-container">
                <div class="destination-item p-4 border rounded-lg shadow-md bg-gray-50 mb-4">
                    <label class="block text-lg font-semibold text-gray-700">目的地 1</label>
                    <select name="destinations[]" required 
                        class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 transition duration-200 hover:shadow-lg">
                        <option value="" disabled selected>目的地をお気に入りしたスポットから選択してください</option>
                        @foreach($likedSpots as $likedspot)
                            <option value="{{ $likedspot->id }}">
                                {{ $likedspot->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="remove-destination mt-2 bg-red-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-red-600 transition duration-200 shadow-lg">
                        削除
                    </button>
                </div>
            </div>

            <!-- 目的地追加ボタン -->
            <div class="mt-4">
                <button type="button" id="add-destination" class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-green-700 transition duration-200 shadow-lg">
                    次の目的地を追加
                </button>
            </div>

            <!-- 日付 -->
            <div>
                <label for="date" class="block text-lg font-semibold text-gray-700">旅行日程</label>
                <input type="date" name="start_date" id="date" required 
                    class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3 transition duration-200 hover:shadow-lg">
                @error('start_date')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 送信ボタン -->
            <div>
                <button type="submit" 
                    class="w-full bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 transition-all duration-300 shadow-lg">
                    旅行計画を作成
                </button>
            </div>
        </form>
    </div>

    <script>
        let map;
        let marker;
        let isPinned = false;

        // ユーザーの現在地を取得して地図を初期化
        function initMap() {
            getCurrentLocation();
        }

        // ユーザーの現在地を取得して表示
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const currentLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    console.log('Current Location:', currentLocation); // ここで位置を確認
                    console.log('Accuracy:', position.coords.accuracy); // 精度を表示

                    // 地図を初期化
                    map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 10,
                        center: currentLocation, // 現在地を中心に設定
                        mapId: "c62365d6bb595035",
                    });

                    // マーカーを設定
                    marker = new google.maps.marker.AdvancedMarkerElement({
                        position: currentLocation,
                        map: map,
                        title: "現在地",
                    });

                    // マップクリック時のイベントリスナー
                    map.addListener("click", (event) => {
                        if (!isPinned) {
                            const clickedLocation = event.latLng;
                            marker.position = clickedLocation; // 位置を変更
                            map.setCenter(clickedLocation);
                            document.getElementById("initial_position").value = `${clickedLocation.lat()},${clickedLocation.lng()}`;
                        }
                    });
                }, () => {
                    alert('現在地の取得に失敗しました。位置情報サービスが有効になっていることを確認してください。');
                });
            } else {
                alert('このブラウザはGeolocation APIをサポートしていません。');
            }
        }

        // ピンの固定/解除ボタンのイベントリスナー
        document.getElementById('toggle-pin').addEventListener('click', function () {
            isPinned = !isPinned;
            this.textContent = isPinned ? 'ピンを解除' : 'ピンを固定';
            this.classList.toggle('bg-red-600', isPinned);
            this.classList.toggle('bg-gray-600', !isPinned);
        });

        // 検索ボタンのイベントリスナー
        document.getElementById('search-button').addEventListener('click', function() {
            const location = document.getElementById('search').value;
            if (location === '現在地') {
                getCurrentLocation();
            } else {
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'address': location }, (results, status) => {
                    if (status === 'OK') {
                        const newLocation = results[0].geometry.location;
                        map.setCenter(newLocation);
                        marker.position = newLocation; // 位置を変更
                        document.getElementById("initial_position").value = `${newLocation.lat()},${newLocation.lng()}`;
                    } else {
                        alert('場所が見つかりませんでした: ' + status);
                    }
                });
            }
        });

        // Enterキーで検索を実行
        document.getElementById('search').addEventListener('keypress', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                document.getElementById('search-button').click();
            }
        });

        // 目的地追加機能
        document.addEventListener('DOMContentLoaded', function () {
            const destinationContainer = document.getElementById('destination-container');
            const addDestinationButton = document.getElementById('add-destination');

            addDestinationButton.addEventListener('click', function () {
                const destinationItem = document.createElement('div');
                destinationItem.classList.add('destination-item', 'p-4', 'border', 'rounded-lg', 'shadow-md', 'bg-gray-50', 'mb-4');
                destinationItem.innerHTML = `
                    <label class="block text-lg font-semibold text-gray-700">目的地</label>
                    <select name="destinations[]" required 
                        class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 transition duration-200 hover:shadow-lg">
                        <option value="" disabled selected>目的地をお気に入りしたスポットから選択してください</option>
                        @foreach($likedSpots as $likedspot)
                            <option value="{{ $likedspot->id }}">
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

            // 動的に追加された削除ボタンにイベントリスナーを追加
            destinationContainer.addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-destination')) {
                    event.target.closest('.destination-item').remove();
                }
            });
        });
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $api_key }}&callback=initMap&v=weekly&libraries=marker"></script>
</x-app-layout>
