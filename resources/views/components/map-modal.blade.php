<!-- Mapボタン -->
<div>
<a href="javascript:void(0);" class="inline-block rounded-lg border bg-white dark:bg-gray-700 dark:border-none px-4 py-2 text-center text-sm font-semibold text-gray-500 dark:text-gray-200 outline-none ring-indigo-300 transition duration-100 hover:bg-gray-100 focus-visible:ring active:bg-gray-200 md:px-8 md:py-3 md:text-base" onclick="openMapModal()">
    Map
</a>

<!-- Mapモーダルウィンドウ -->
<div id="mapModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" onclick="closeMapModal()">
    <div class="relative top-1/2 transform -translate-y-1/2 mx-auto p-5 border w-full max-w-lg shadow-2xl rounded-lg bg-white z-60" onclick="event.stopPropagation();">
        <button class="absolute top-2 right-2 text-gray-500 hover:text-red-600 transition duration-150" onclick="closeMapModal()">
            &times;
        </button>
        <div id="map" style="width: 100%; height: 400px;"></div>
    </div>
</div>

<script>
    // Mapモーダル表示用のJavaScript関数
    function openMapModal() {
        document.getElementById('mapModal').classList.remove('hidden');

        // 既にGoogle Mapsがロードされているかどうかを確認
        if (!window.google || !window.google.maps) {
            // Google Maps APIを非同期でロード
            const script = document.createElement('script');
            script.src = `https://maps.googleapis.com/maps/api/js?key={{ $apiKey }}&callback=initMap`;
            script.async = true;
            script.defer = true;
            document.body.appendChild(script);
        } else {
            // 既にロード済みの場合はマップを初期化
            initMap();
        }
    }

    // モーダル閉じる用のJavaScript関数
    function closeMapModal() {
        document.getElementById('mapModal').classList.add('hidden');
    }

    // Google Maps APIでマップを初期化する関数
    function initMap() {
        var spotLocation = { lat: {{ $latitude }}, lng: {{ $longitude }} };

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 14,
            center: spotLocation
        });

        var marker = new google.maps.Marker({
            position: spotLocation,
            map: map,
            title: '{{ $spotName }}'
        });
    }
</script>
</div>