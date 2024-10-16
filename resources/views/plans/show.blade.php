<x-app-layout>
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-extrabold text-gray-800 text-center">
            @if(Auth::id() === $plan->user_id)
                {{ $plan->title }}
            @else
                {{ $planpost->title }}
            @endif
        </h1>
        
        <div class="text-right text-sm">
            <a href="{{ route('plans.post') }}" class="bg-teal-500 text-white border-2 border-teal-500 rounded-full px-2 py-1 font-bold uppercase tracking-wide hover:bg-white hover:text-teal-700 transition-all duration-300">
                自分用に編集
            </a>
        </div>
        
        <div class="mt-6">
            <h2 class="text-2xl font-bold">旅行日程</h2>
            <p class="text-lg text-gray-600">{{ $plan->start_date->format('Y年m月d日') }} {{ $plan->start_time->format('H時i分') }}</p>
        </div>

        <div class="mt-6">
            <h2 class="text-2xl font-bold">スポット</h2>
            <ul class="space-y-2">
                @foreach ($plan->destinations as $index => $destination)
                    <li class="p-4 border rounded-lg shadow-md bg-gray-50">
                        @if ($destination->id)
                            <a href="/spots/{{ $destination->id }}" class="flex items-center space-x-4">
                                <div>
                                    @if ($loop->last)
                                        <span class="font-semibold">目的地: {{ $destination->name }}</span>
                                    @else
                                        <span class="font-semibold">経由地: {{ $destination->name }}</span>
                                    @endif
                                    <span class="text-gray-500">（{{ $destination->lat }}, {{ $destination->long }}）</span>
                                </div>
                            </a>
                        @else
                            <div class="text-red-500">この目的地にはスポットが存在しません。</div>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="flex flex-col items-center mt-6">
            <label for="travelModeSelect" class="text-lg font-medium mb-2">移動手段を選択:</label>
            <select id="travelModeSelect" class="w-full max-w-xs bg-white border border-gray-300 rounded-lg shadow-md p-2 focus:outline-none focus:ring-2 md:focus:ring-blue-400 focus:border-transparent">
                <option value="DRIVING">車</option>
                <option value="WALKING">徒歩</option>
                <option value="BICYCLING">自転車</option>
                <option value="TRANSIT">公共交通機関</option>
            </select>

            <button onclick="updateRoute()" class="mt-4 w-full max-w-xs bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
                経路を検索
            </button>
        </div>

        <div class="mt-6">
            <h2 class="text-2xl font-bold">経路</h2>
            <div id="map" class="w-full h-80 mb-4 rounded-lg border shadow-md"></div>
            <div id="result" class="p-4">
                <h2 class="text-xl font-bold mb-4">経路候補</h2>
                <div id="routes" class="space-y-4"></div>
            </div>
        </div>
        
        <div class="mt-6">
            @if(Auth::id() === $plan->user_id)
                <a href="{{ route('plans.index') }}" class="bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 transition-all duration-300 shadow-lg">
                    旅行計画一覧に戻る
                </a>
            @else
                <a href="{{ route('planposts.index') }}" class="bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 transition-all duration-300 shadow-lg">
                    みんなの旅行計画へ
                </a>
            @endif
        </div>
    </div>

    <script>
        let map;
        let userMarker;
        let goalMarker;
        let directionsService;
        let directionsRenderer;
        let waypoints = [];
        const startTime = "{{ $plan->start_date->format('Y-m-d') }}T{{ $plan->start_time->format('H:i:s') }}";

        function initMap() {
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            getCurrentLocation();  //旅行計画作成時に設定した現在地の取得
            startTrackingLocation(); // 位置情報のトラッキングを開始
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const currentLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };

                        // マップの初期化
                        map = new google.maps.Map(document.getElementById("map"), {
                            zoom: 10,
                            center: currentLocation,
                        });

                        // 現在地マーカーの設定
                        userMarker = new google.maps.Marker({
                            position: currentLocation,
                            map: map,
                            title: "現在地",
                        });

                        // 経路描画
                        directionsRenderer.setMap(map);
                        map.setCenter(currentLocation);
                        drawRoute();
                    },
                    (error) => {
                        console.error("位置情報の取得に失敗しました。", error);
                        alert('現在地の取得に失敗しました。位置情報サービスが有効になっていることを確認してください。');
                    },
                    { enableHighAccuracy: true, maximumAge: 0, timeout: 5000 }
                );
            } else {
                alert('位置情報サービスがサポートされていません。');
            }
        }

        
        function startTrackingLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(
                    (position) => {
                        const currentLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };

                        // 現在地マーカーの更新
                        if (userMarker) {
                            userMarker.setPosition(currentLocation);
                        } else {
                            userMarker = new google.maps.Marker({
                                position: currentLocation,
                                map: map,
                                title: "現在地",
                                icon: {
                                    url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
                                },
                            });
                        }
                    },
                    (error) => {
                        console.error("位置情報の取得に失敗しました。", error);
                    },
                    { enableHighAccuracy: true, maximumAge: 0, timeout: 5000 }
                );
            } else {
                alert('位置情報サービスがサポートされていません。');
            }
        }

        function drawRoute() {
            waypoints = [];
            const currentLocation = { lat: userMarker.getPosition().lat(), lng: userMarker.getPosition().lng() };

            @foreach ($plan->destinations as $destination)
                waypoints.push({
                    location: { lat: {{ $destination->lat }}, lng: {{ $destination->long }} },
                    stopover: true
                });
            @endforeach

            const goal = waypoints[waypoints.length - 1].location;
            const goalLocation = { lat: goal.lat, lng: goal.lng };

            if (currentLocation.lat === goalLocation.lat && currentLocation.lng === goalLocation.lng) {
                document.getElementById('result').innerHTML = '<p>現在地と目的地が同じです。移動する必要はありません。</p>';
            } else {
                searchRoute(currentLocation, goalLocation, waypoints.slice(0, -1));
            }
        }

        function updateRoute() {
            const travelMode = document.getElementById("travelModeSelect").value;
            const currentLocation = { lat: userMarker.getPosition().lat(), lng: userMarker.getPosition().lng() };
            const goal = waypoints[waypoints.length - 1].location;

            searchRoute(currentLocation, goal, waypoints.slice(0, -1), travelMode);
        }

        function searchRoute(startLocation, goalLocation, waypoints, travelMode) {
            const now = new Date();
            const startTimeDate = new Date(startTime);

            if (startTimeDate < now) {
                alert('出発時刻が過去です。現在時刻以降を指定してください。');
                return;
            }

            const request = {
                origin: startLocation,
                destination: goalLocation,
                waypoints: waypoints.length ? waypoints : undefined,
                travelMode: google.maps.TravelMode[travelMode],
                optimizeWaypoints: true // 経由地を最適化
            };

            if (travelMode === 'DRIVING') {
                request.drivingOptions = {
                    departureTime: startTimeDate,
                    trafficModel: 'bestguess' // 交通状況を考慮
                };
            } else if (travelMode === 'TRANSIT') {
                request.transitOptions = {
                    departureTime: startTimeDate,
                    modes: ['BUS', 'RAIL'],
                };
            }

            directionsService.route(request, (result, status) => {
            console.log(result); // 結果をログ出力して詳細を確認
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(result);
                    displayRouteInfo(result.routes[0].legs);
                } else {
                    document.getElementById('result').innerHTML = `<p>経路情報が見つかりませんでした</p>`;
                }
            });
        }

        function displayRouteInfo(legs) {
            const routesContainer = document.getElementById("routes");
            routesContainer.innerHTML = "";

            legs.forEach((leg, index) => {
                const fromTime = new Date(startTime);
                const addTime = leg.duration.value;
                const toTime = new Date(fromTime.getTime() + Math.round(addTime / 60) * 60 * 1000);
                
                const departureTime = formatDate(fromTime);
                const arrivalTime = formatDate(toTime);

                let resultHTML = `
                    <div class="bg-gray-200 shadow rounded-lg p-4">
                        <h3 class="text-lg font-semibold">経路 ${index + 1}: ${departureTime} ➡ ${arrivalTime}</h3>
                        <h4 class="text-md font-medium">合計時間: ${leg.duration.text}</h4>
                        <h4 class="text-md font-medium">距離: ${leg.distance.text}</h4>
                        <hr class="my-2 border-black">
                `;

                leg.steps.forEach(step => {
                    resultHTML += `
                        <div>${step.instructions} (${step.distance.text})</div>
                    `;
                });
                resultHTML += `</div>`;
                routesContainer.innerHTML += resultHTML;
            });
        }

        function formatDate(date) {
            const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', hour12: false };
            return date.toLocaleString('ja-JP', options);
        }
    </script>

    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $api_key }}&callback=initMap&v=weekly&libraries=routes"></script>
</x-app-layout>
