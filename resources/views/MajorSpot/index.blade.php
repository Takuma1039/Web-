<x-app-layout>
  <div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-8">
      <!-- タイトル -->
      <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">
        人気の観光スポットランキング
      </h1>

      <!--検索バーコンポーネント-->
        <x-search-bar :spotcategories="$spotcategories" :locals="$locals" :seasons="$seasons" :months="$months" />

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="col-span-2">
          @php
            $currentRank = 1;  // 現在の順位
            $previousLikeCount = null;  // 前のいいね数を保存するための変数
          @endphp

          @forelse($majorranking as $index => $spot)
            @php
              // 現在のスポットのいいね数と前のスポットのいいね数を比較
              if ($previousLikeCount === null || $previousLikeCount !== $spot->likes_count) {
                // いいね数が異なる場合は、現在の順位をインクリメント
                $currentRank = $index + 1;  // 順位を更新
              }
              $previousLikeCount = $spot->likes_count;  // 現在のいいね数を前のいいね数に更新
            @endphp
            <div class="p-4 bg-white rounded-lg shadow-md mb-4">
              <div class="flex items-center mb-2">
                <span class="text-xl font-semibold text-blue-500">第{{ $currentRank }}位</span>
                <a href="/spots/{{ $spot->id }}" class="ml-4 text-xl font-bold text-indigo-600 hover:underline">
                  {{ $spot->name }}
                </a>
                @auth
                  <div class="ml-4">
                    <!--お気に入りボタンコンポーネント追加-->
                    <x-like-button :spot="$spot" />
                  </div>
                @endauth
              </div>
          
              <div class="flex mb-2">
                <div class="w-1/2 pr-2">
                  <p class="text-gray-600 mb-2">
                    {{ $spot->truncated_body }}
                  </p>
                  <span class="text-sm font-medium text-gray-600" id="likeCount-{{ $spot->id }}">{{ $spot->likes_count }} いいね</span>
                </div>
                
                <div class="w-1/2 pl-2">
                  <!-- 画像を表示 -->
                  @if ($spot->spotimages->isNotEmpty())
                    <img src="{{ $spot->spotimages->first()->image_path }}" alt="{{ $spot->name }}" class="w-full h-48 object-cover rounded-lg">
                  @else
                    <img src="/images/no_image_available.png" alt="No image" class="w-full h-48 object-cover rounded-lg">
                  @endif
                </div>
              </div>
            </div>
          @empty
            <p class="text-center text-gray-500">現在、スポット情報はありません。</p>
          @endforelse
        </div>
      </div>
      <div class="footer mt-6">
        <a href="javascript:history.back();" class="text-blue-500 hover:underline">戻る</a>
      </div>
  
      <!-- ページネーション -->
      <div class="flex justify-center mt-8">
        <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Previous</button>
        <button class="px-4 py-2 mx-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">1</button>
        <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">2</button>
        <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">3</button>
        <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">Next</button>
      </div>
    </div>
  </div>
</x-app-layout>
