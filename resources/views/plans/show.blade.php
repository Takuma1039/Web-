<x-app-layout>
    <div class="container mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-800 text-center">{{ $plan->title }}</h1>
        
        <div class="mt-6">
            <h2 class="text-2xl font-bold">旅行日程</h2>
            <p class="text-lg text-gray-600">{{ $plan->start_date->format('Y年m月d日') }} {{ $plan->start_time->format('H時i分') }}</p>
        </div>

        <div class="mt-6">
            <h2 class="text-2xl font-bold">目的地</h2>
            <ul class="space-y-2">
                @foreach ($plan->destinations as $destination)
                    <li class="p-4 border rounded-lg shadow-md bg-gray-50">
                        @if ($destination->id)
                            <a href="/spots/{{ $destination->id }}" class="flex items-center space-x-4">
                                <div>
                                    <span class="font-semibold">{{ $destination->name }}</span>
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

        <div class="mt-6">
            <h2 class="text-2xl font-bold">経路</h2>
            <div id="map" class="w-full h-80 mb-4 rounded-lg border shadow-md"></div>
            <div id="result" class="p-4">
              <h2 class="text-xl font-bold mb-4">経路候補</h2>
              <div id="routes" class="space-y-4"></div>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('plans.index') }}" class="bg-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-indigo-700 transition-all duration-300 shadow-lg">
                旅行計画一覧に戻る
            </a>
        </div>
    </div>

    <script>
        let map;
        let userMarker;
        let goalMarker;
        let waypoints;
        const startTime = "{{ $plan->start_date->format('Y-m-d') }}T{{ $plan->start_time->format('H:i:s') }}";

        function initMap() {
            getCurrentLocation();
        }

        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const currentLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };

                    map = new google.maps.Map(document.getElementById("map"), {
                        zoom: 10,
                        center: currentLocation,
                    });

                    userMarker = new google.maps.Marker({
                        position: currentLocation,
                        map: map,
                        title: "現在地",
                        icon: {
                            url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
                        },
                    });

                    map.setCenter(currentLocation);
                    drawRoute();
                }, () => {
                    alert('現在地の取得に失敗しました。位置情報サービスが有効になっていることを確認してください。');
                });
            } else {
                alert('このブラウザはGeolocation APIをサポートしていません。');
            }
        }

        function drawRoute() {
            waypoints = [];
            const currentLocation = `${userMarker.getPosition().lat()},${userMarker.getPosition().lng()}`;
            
            @foreach ($plan->destinations as $destination)
                waypoints.push({ lat: {{ $destination->lat }}, lng: {{ $destination->long }} });
            @endforeach
            
            // 目的地が1つだけの場合の処理
            if (waypoints.length === 1) {
                const goalLocation = waypoints[0];
                console.log("Goal Location:", goalLocation);
                if (currentLocation === `${goalLocation.lat},${goalLocation.lng}`) {
                    document.getElementById('result').innerHTML = '<p>現在地と目的地が同じです。移動する必要はありません。</p>';
                    return;
                } else {
                    searchRoute(currentLocation, goalLocation, []);
                    goalMarker = new google.maps.Marker({
                        position: goalLocation,
                        map: map,
                        title: "目的地",
                        icon: {
                            url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
                        },
                    });
                }
            } else {
                const goalLocation = waypoints[waypoints.length - 1];
                console.log("Goal Location:", goalLocation);
                searchRoute(currentLocation, goalLocation, waypoints);
                goalMarker = new google.maps.Marker({
                        position: goalLocation,
                        map: map,
                        title: "目的地",
                        icon: {
                            url: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
                        },
                    });
            }
            
        }

        function searchRoute(startLocation, goalLocation, waypoints) {
            const filteredWaypoints = waypoints.slice(0, -1); // 最後の値を取り除いた配列
            const waypointParam = encodeURIComponent(JSON.stringify(filteredWaypoints));
            console.log("Waypoint Parameter:", waypointParam); // 確認用ログ
    
            const options = {
                method: 'GET',
                headers: {
                    'x-rapidapi-key': '{{ $apikey }}',
                    'x-rapidapi-host': 'navitime-route-totalnavi.p.rapidapi.com'
                }
            };

            // waypointsが空のときの処理
            if (waypoints.length === 0) {
                console.log("Waypointsがありません。直接の経路検索を行います。");
                const goals = `${goalLocation.lat},${goalLocation.lng}`; // 目的地の座標を取得
                const requestUrl = `https://navitime-route-totalnavi.p.rapidapi.com/route_transit?start=${startLocation}&goal=${goals}&datum=wgs84&term=1440&limit=5&start_time=${startTime}&coord_unit=degree&shape=true&options=railway_calling_at`;

                fetch(requestUrl, options)
                    .then(response => response.json())
                    .then(data => {
                        console.log(data); // レスポンス全体を表示
                        if (data.items && data.items.length > 0) {
                            map.data.addGeoJson(data.items[0].shapes);
                            displayRouteInfo(data.items);
                        } else {
                            document.getElementById('result').innerHTML = '<p>経路情報が見つかりませんでした。</p>';
                        }
                    })
                    .catch(error => {
                        console.error('エラーが発生しました:', error);
                        document.getElementById('result').innerHTML = `<p>エラー: ${error.message}</p>`;
                    });
                    return; // 処理を終了
            }
                const goals = `${goalLocation.lat},${goalLocation.lng}`; // 目的地の座標を取得
                
                // waypointsが1つ以上ある場合の処理
                const requestUrl = `https://navitime-route-totalnavi.p.rapidapi.com/route_transit?start=${startLocation}&goal=${goals}&via=${waypointParam}&via_type=specified&datum=wgs84&term=1440&limit=5&start_time=${startTime}&coord_unit=degree&shape=true&options=railway_calling_at`;

                fetch(requestUrl, options)
                    .then(response => response.json())
                    .then(data => {
            console.log(data); // レスポンス全体を表示
            if (data.items && data.items.length > 0) {
                map.data.addGeoJson(data.items[0].shapes);
                displayRouteInfo(data.items);
            } else {
                document.getElementById('result').innerHTML = '<p>経路情報が見つかりませんでした。</p>';
            }
        })
        .catch(error => {
            console.error('エラーが発生しました:', error);
            document.getElementById('result').innerHTML = `<p>エラー: ${error.message}</p>`;
        });
}

        function displayRouteInfo(routes) {
    const routesContainer = document.getElementById("routes");  //経路候補の配列を取得
    routesContainer.innerHTML = ""; // 既存の内容をクリア

    routes.forEach((route, index) => {
        // 日付のフォーマットを行う
        const fromTime = formatDate(route.summary.move.from_time);
        const toTime = formatDate(route.summary.move.to_time);
        
        let resultHTML = `
            <div class="bg-white shadow rounded-lg p-4">
                <h3 class="text-lg font-semibold">候補 ${index + 1}: ${fromTime} ➡ ${toTime}</h3> 
                <h4 class="text-md font-medium">合計時間: ${formatDuration(route.summary.move.time)}</h4>
        `;

        if (route.summary.move.fare && route.summary.move.fare.unit_0) {
            resultHTML += `<h4 class="text-md font-medium">合計運賃: ¥${route.summary.move.fare.unit_0}</h4>`;
        }

        resultHTML += '<hr class="my-2">';

        if (route.sections && route.sections.length > 0) {
            route.sections.forEach(function(section) {
                const type = section.type;

                if (type === "move") {
                    // セクションの時間をフォーマット
                    const sectionFromTime = formatDate(section.from_time);
                    resultHTML += `<h4 class="text-md">${sectionFromTime}発 ${section.line_name} (${section.time}分)</h4>`;
                    if (section.transport && section.transport.fare) {
                        resultHTML += `<h4 class="text-sm text-gray-600">運賃: ¥${section.transport.fare.unit_0} (${section.transport.fare.unit_1}円）</h4>`;
                    }
                } else if (type === "point") {
                    resultHTML += `<h3 class="text-lg font-bold">${section.name}</h3>`;
                }
            });
        } else {
            resultHTML += '<h4 class="text-md text-red-500">セクション情報が見つかりませんでした。</h4>';
        }

        resultHTML += '</div>'; // ルートのカードを閉じる
        routesContainer.innerHTML += resultHTML; // 新しい内容を追加
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
    const date = new Date(dateString);
    return date.toLocaleString('ja-JP', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        hour12: false, // 24時間形式にする
    }).replace(',', ''); // カンマを削除
}
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $api_key }}&callback=initMap&v=weekly&libraries=marker"></script>
</x-app-layout>
