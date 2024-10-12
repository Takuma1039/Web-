<x-app-layout>
    <div class="container mx-auto ">
        <h1 class="text-4xl font-extrabold text-gray-800 text-center">旅行計画を作成</h1>

        <form action="{{ route('plans.store') }}" method="POST" class="bg-white p-8 rounded-lg shadow-lg space-y-6">
            @csrf
            
            <!-- タイトル -->
            <div>
                <label for="title" class="block text-lg font-semibold text-gray-700">旅行計画のタイトル</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required 
                    class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3 transition duration-200 hover:shadow-lg">
                @error('title')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 旅行日程 -->
            <div>
                <label for="date" class="block text-lg font-semibold text-gray-700">旅行日程</label>
                <input type="date" name="start_date" id="date" value="{{ old('start_date') }}" required 
                    class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3 transition duration-200 hover:shadow-lg">
                @error('start_date')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 旅行出発時間 -->
            <div>
                <label for="time" class="block text-lg font-semibold text-gray-700">出発時間</label>
                <input type="time" name="start_time" id="time" value="{{ old('start_time') }}" required 
                    class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-3 transition duration-200 hover:shadow-lg">
                @error('start_time')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 現在地 -->
            <div>
                <label for="initial_position" class="block text-lg font-semibold text-gray-700">現在地</label>
                <div class="flex py-2">
                    <input type="text" id="address-input" placeholder="住所や場所で現在地を検索" class="flex-grow border border-gray-300 rounded-lg p-2 shadow-sm hover:shadow-lg">
                    <button type="button" id="search-button" class="ml-2 bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200 shadow-lg">
                        検索
                    </button>
                </div>
                <div id="map" class="w-full h-80 mb-4 rounded-lg border shadow-md"></div>
                <input type="hidden" name="initial_position" id="initial_position" required>
                <p class="text-sm text-gray-500">地図上をクリックしても初期位置を選択できます。</p>
            </div>

            <!-- 目的地 -->
            <div id="destination-container">
                <div class="destination-item p-4 border rounded-lg shadow-md bg-gray-50 mb-4">
                    <label class="block text-lg font-semibold text-gray-700">目的地 1</label>
                    <select name="destinations[]" required 
                        class="mt-2 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm p-2 transition duration-200 hover:shadow-lg">
                        <option value="" disabled {{ old('destinations') ? '' : 'selected' }}>目的地をお気に入りしたスポットから選択してください</option>
                        @foreach($likedSpots as $likedspot)
                            <option value="{{ $likedspot->id }}" data-lat="{{ $likedspot->lat }}" data-lng="{{ $likedspot->long }}">
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
    let map;
    let userMarker;

    // ユーザーの現在地を取得して地図を初期化
    function initMap() {
        getCurrentLocation();
    }

    // ユーザーの現在地を取得して表示
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const currentLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    const initialPosition = `${position.coords.latitude},${position.coords.longitude}`;
                    //document.getElementById('initial_position').value = initialPosition;

                    // 地図を初期化
                    map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 10,
                        center: currentLocation,
                    });

                    // ユーザーの位置にマーカーを設定
                    userMarker = new google.maps.Marker({
                        position: currentLocation,
                        map: map,
                        title: "現在地",
                        draggable: true, // マーカーを動かせるようにする
                    });

                    map.setCenter(currentLocation);
                    
                    // マーカーの位置が変更されたときのイベントリスナー
                    google.maps.event.addListener(userMarker, 'dragend', function(event) {
                        updateUserLocation(event.latLng);
                    });
                },
                () => {
                    alert('現在地の取得に失敗しました。位置情報サービスが有効になっていることを確認してください。');
                }
            );
        } else {
            alert('このブラウザはGeolocation APIをサポートしていません。');
        }
    }

    // マーカーの位置を更新する関数
    function updateUserLocation(location) {
        if (userMarker) {
            userMarker.setPosition(location);
            const currentLocation = {
                lat: location.lat(),
                lng: location.lng(),
            };
            document.getElementById('initial_position').value = `${currentLocation.lat},${currentLocation.lng}`;
        }
    }
    
    // 住所や場所名から座標を取得する
    function geocodeAddress(address) {
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ address: address }, (results, status) => {
            if (status === "OK") {
                const location = results[0].geometry.location;

                // 地図を新しい位置に更新
                map.setCenter(location);
                userMarker.setPosition(location);
                document.getElementById('initial_position').value = `${location.lat()},${location.lng()}`;
            } else {
                alert('住所または場所が見つかりませんでした。');
            }
        });
    }

    // 検索ボタンのクリックイベント
    document.getElementById('search-button').addEventListener('click', function () {
        const address = document.getElementById('address-input').value;
        if (address) {
            geocodeAddress(address);
        } else {
            alert('住所または場所を入力してください。');
        }
    });

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
<script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $api_key }}&callback=initMap&v=weekly&libraries=marker"></script>
</x-app-layout>
