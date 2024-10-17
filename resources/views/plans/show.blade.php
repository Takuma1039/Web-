<x-app-layout>
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-extrabold text-gray-800 text-center">
            @if(Auth::id() === $plan->user_id)
                {{ $plan->title }}
            @else
                {{ $planpost->title }}
            @endif
        </h1>
        
        <div class="mt-6">
            <h2 class="text-2xl font-bold">旅行日程</h2>
            <p class="text-lg text-gray-600">{{ $plan->start_date->format('Y年m月d日') }} {{ $plan->start_time->format('H時i分') }}</p>
        </div>
        <label for="startTimeInput" class="mt-4 block text-lg font-bold">出発時刻を変更:</label>
        <input type="datetime-local" id="startTimeInput" value="{{ $plan->start_date->format('Y-m-d\TH:i') }}" class="mt-2 p-2 border rounded" />
        <button id="updateStartTimeButton" class="mt-2 p-2 bg-blue-500 text-white rounded hover:bg-blue-600">出発時刻を更新</button>
        <div id="message" class="text-green-600 mt-2 hidden"></div>

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
        let googleWaypoints = [];
        let navitimeWaypoints = [];
        let startTime = "{{ $plan->start_date->format('Y-m-d') }}T{{ $plan->start_time->format('H:i:s') }}";

        document.getElementById("updateStartTimeButton").addEventListener("click", function() {
            const startTimeInput = document.getElementById("startTimeInput").value;

            if (startTimeInput) {
                startTime = startTimeInput; 
                
                const messageDiv = document.getElementById("message");
                messageDiv.textContent = "出発時刻が変更されました。";
                messageDiv.classList.remove("hidden");
        
                setTimeout(() => {
                    messageDiv.classList.add("hidden");
                }, 3000);
            } else {
                alert('出発時刻を入力してください。');
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            initMap();
        });

        function initMap() {
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            getCurrentLocation();  //現在地の取得
            startTrackingLocation(); // 位置情報のトラッキング
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

        function updateRoute() {
            googleWaypoints = [];
            navitimeWaypoints = [];
            @foreach ($plan->destinations as $destination)
                googleWaypoints.push({
                    location: { lat: {{ $destination->lat }}, lng: {{ $destination->long }} },
                    stopover: true
                });
                navitimeWaypoints.push({ lat: {{ $destination->lat }}, lon: {{ $destination->long }} });
            @endforeach
            
            const travelMode = document.getElementById("travelModeSelect").value;
            const currentLocation = { lat: userMarker.getPosition().lat(), lng: userMarker.getPosition().lng() };
            if(travelMode === "TRANSIT") {
                if(navitimeWaypoints.length === 1) {
                    const goal = { lat: navitimeWaypoints[0].lat, lng: navitimeWaypoints[0].lon };
                    searchRoute(currentLocation, goal, [], travelMode);
                    goalMarker = new google.maps.Marker({
                                position: goal,
                                map: map,
                                title: "目的地",
                            });
                } else {
                    const goal = { lat: navitimeWaypoints[navitimeWaypoints.length - 1].lat, lng: navitimeWaypoints[navitimeWaypoints.length - 1].lon };
                searchRoute(currentLocation, goal, navitimeWaypoints.slice(0, -1), travelMode);
                goalMarker = new google.maps.Marker({
                                position: goal,
                                map: map,
                                title: "目的地",
                            });
                }
            } else {
                const goal = googleWaypoints[googleWaypoints.length - 1].location;
                searchRoute(currentLocation, goal, googleWaypoints.slice(0, -1), travelMode);
            }
        }
        
        function searchRoute(startLocation, goalLocation, waypoints, travelMode) {
            const now = new Date();
            const startTimeDate = new Date(startTime);

            if (startTimeDate < now) {
                alert('出発時刻が過去です。現在時刻以降を指定してください。');
                return;
            }
            
            if (travelMode === 'TRANSIT') {
                // 旅行モードがTRANSITかつ経由地がない場合
                if (waypoints.length === 0) {
                    const goals = `${goalLocation.lat},${goalLocation.lng}`;
                    const requestUrl = `https://navitime-route-totalnavi.p.rapidapi.com/route_transit?start=${startLocation.lat},${startLocation.lng}&goal=${goals}&datum=wgs84&term=1440&limit=5&start_time=${startTime}&coord_unit=degree&shape=true&options=railway_calling_at`;
                    
                    const options = {
                        method: 'GET',
                        headers: {
                            'x-rapidapi-key': '{{ $apikey }}',
                            'x-rapidapi-host': 'navitime-route-totalnavi.p.rapidapi.com'
                        }
                    };
                    
                    fetch(requestUrl, options)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data.items && data.items.length > 0) {
                            displayNavitimeRouteInfo(data.items);
                            map.data.addGeoJson(data.items[0].shapes);
                        } else {
                            document.getElementById('result').innerHTML = '<p>経路情報が見つかりませんでした。</p>';
                        }
                    })
                    .catch(error => {
                        console.error('エラーが発生しました:', error);
                        document.getElementById('result').innerHTML = `<p>エラー: ${error.message}</p>`;
                    });
                } else {
                    const filteredWaypoints = waypoints;
                    addWaypointMarkers(filteredWaypoints);
                    const waypointParam = encodeURIComponent(JSON.stringify(filteredWaypoints));
                    const goals = `${goalLocation.lat},${goalLocation.lng}`;
                    const requestUrl = `https://navitime-route-totalnavi.p.rapidapi.com/route_transit?start=${startLocation.lat},${startLocation.lng}&goal=${goals}&via=${waypointParam}&datum=wgs84&term=1440&limit=5&start_time=${startTime}&coord_unit=degree&shape=true&options=railway_calling_at`;
                    const options = {
                        method: 'GET',
                        headers: {
                            'x-rapidapi-key': '{{ $apikey }}',
                            'x-rapidapi-host': 'navitime-route-totalnavi.p.rapidapi.com'
                        }
                    };
                    
                    fetch(requestUrl, options)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data.items && data.items.length > 0) {
                            displayNavitimeRouteInfo(data.items);
                            map.data.addGeoJson(data.items[0].shapes);
                        } else {
                            document.getElementById('result').innerHTML = '<p>経路情報が見つかりませんでした。</p>';
                        }
                    })
                    .catch(error => {
                        console.error('エラーが発生しました:', error);
                        document.getElementById('result').innerHTML = `<p>エラー: ${error.message}</p>`;
                    });
                }

            } else {
                const request = {
                    origin: startLocation,
                    destination: goalLocation,
                    waypoints : waypoints,
                    travelMode: google.maps.TravelMode[travelMode],
                    optimizeWaypoints : true,
                };

                if (travelMode === 'DRIVING') {
                    request.drivingOptions = {
                        departureTime: startTimeDate,
                        trafficModel: 'bestguess'
                    };
                }
                directionsService.route(request, (result, status) => {
                    console.log(result);
                    if (status === google.maps.DirectionsStatus.OK) {
                        directionsRenderer.setDirections(result);
                        displayRouteInfo(result.routes[0].legs);
                    } else {
                        document.getElementById('result').innerHTML = `<p>経路情報が見つかりませんでした</p>`;
                    }
            　　});
            }
        }
        
        function addWaypointMarkers(waypoints) {
            waypoints.forEach((waypoint, index) => {
                const position = { lat: waypoint.lat, lng: waypoint.lon };
        
                new google.maps.Marker({
                    position: position,
                    map: map,
                    title: `経由地 ${index + 1}`,
                });
            });
        }
        
        function displayRouteInfo(legs) {
            const routesContainer = document.getElementById("routes");
            
            if (!routesContainer) {
                console.error('経路情報を表示する要素が見つかりません。');
                return;
            }
            
            routesContainer.innerHTML = "";

            legs.forEach((leg, index) => {
                const fromTime = new Date(startTime);
                const addTime = leg.duration.value;
                const toTime = new Date(fromTime.getTime() + Math.round(addTime / 60) * 60 * 1000);
                
                const departureTime = formatDate(fromTime);
                const arrivalTime = formatDate(toTime);

                let resultHTML = `
                    <div class="bg-gray-200 shadow rounded-lg p-4 mb-4">
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
        
        function displayNavitimeRouteInfo(routes) {
            const routesContainer = document.getElementById("routes");
            routesContainer.innerHTML = "";

            routes.forEach((route, index) => {
                const fromTime = formatDate(route.summary.move.from_time);
                const toTime = formatDate(route.summary.move.to_time);
                let resultHTML = `
                    <div class="bg-gray-200 shadow rounded-lg p-4 mb-4">
                        <h3 class="text-lg font-semibold">経路 ${index + 1}: ${fromTime} ➡ ${toTime}</h3>
                        <h4 class="text-md font-medium">合計時間: ${formatDuration(route.summary.move.time)}</h4>
                `;
                
                if (route.summary.move.fare && route.summary.move.fare.unit_0) {
                    resultHTML += `<h4 class="text-md font-medium">合計運賃: ¥${route.summary.move.fare.unit_0}</h4>`;
                }
                
                resultHTML += '<hr class="my-2 border-black">';

                if (route.sections && route.sections.length > 0) {
                    route.sections.forEach(section => {
                        const type = section.type;

                        if (type === "move") {
                            const sectionFromTime = formatDate(section.from_time);
                            resultHTML += `
                                <h4 class="text-md">${sectionFromTime}発 ${section.line_name} (${section.time}分)</h4>
                            `;
                            // 徒歩の場合、20分以上で注意書きを表示
                            if (section.line_name === "徒歩" && section.time >= 25) {
                                resultHTML += '<h4 class="text-md">※バスやタクシーなどの交通機関を利用することをおすすめします。</h4>';
                            }
                        
                        } else if (type === "point") {
                            if (section.name === "start") {
                                resultHTML += `<h3 class="text-lg font-bold">現在地</h3>`;
                            } else if (section.name === "goal") {
                                resultHTML += `<h3 class="text-lg font-bold">目的地</h3>`;
                            } else {
                                resultHTML += `<h3 class="text-lg font-bold">${section.name}</h3>`;
                            }
                        }
                    });
                } else {
                    resultHTML += '<h4 class="text-md text-red-500">セクション情報が見つかりませんでした。</h4>';
                }
                
                resultHTML += `</div>`;
                routesContainer.innerHTML += resultHTML;
            });
        }
        
        function formatDuration(totalMinutes) {
            const hours = Math.floor(totalMinutes / 60);  // 分から時間を計算
            const minutes = totalMinutes % 60;           // 残りの分を計算

            if (hours > 0) {
                return `${hours}時間${minutes}分`;
            } else {
                return `${minutes}分`;
            }
        }

        function formatDate(dateString) {
            const options = new Date(dateString);
            return options.toLocaleString('ja-JP', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false,
            }).replace(',', '');
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $api_key }}&callback=initMap&v=weekly&libraries=places,geometry"></script>
</x-app-layout>
