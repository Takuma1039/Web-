<x-app-layout>
  <div class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-8">
      <!-- タイトル -->
      <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">
        今の時期におすすめなスポットランキング({{ $currentMonth }}月)
      </h1>
      
      <!--検索バーコンポーネント-->
        <x-search-bar :spotcategories="$spotcategories" :locals="$locals" :seasons="$seasons" :months="$months" />

      <!-- グリッドレイアウト -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="col-span-2">
          @php
            $currentRank = 1;  // 現在の順位
            $previousLikeCount = null;  // 前のいいね数を保存するための変数
            $skipCount = 0;  // 同じ順位が連続した場合にスキップするための変数
          @endphp

          @forelse($seasonranking as $index => $spot)
            @php
              // 現在のスポットのいいね数と前のスポットのいいね数を比較
              if ($previousLikeCount === null || $previousLikeCount !== $spot->likes_count) {
                // 前のスポットといいね数が異なる場合は、順位を更新
                $currentRank += $skipCount;  // スキップしていた分を順位に加算
                $skipCount = 1;  // スキップ数をリセット
              } else {
                // 同じいいね数ならスキップ数を増やす
                $skipCount++;
              }
              $previousLikeCount = $spot->likes_count;  // 現在のいいね数を前のいいね数に更新
            @endphp
            <div class="p-4 bg-white rounded-lg shadow-md mb-4">
              <div class="flex items-center mb-2">
                <span class="text-xl font-semibold text-blue-500">第{{ $index + 1 }}位</span>
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
                <div class="w-full sm:w-1/2 pr-0 sm:pr-2">
                  <p class="text-gray-600 mb-2">
                    {{ $spot->truncated_body }} <!-- 切り捨てた本文を表示 -->
                  </p>
                  <span class="text-sm font-medium text-gray-600" id="likeCount-{{ $spot->id }}">{{ $spot->likes_count }} いいね</span>
                  @if($matchingMonth)
                    <span class="text-sm font-medium text-gray-600" id="Season-{{ $matchingMonth->id }}">{{ $matchingMonth->name }}</span>
                  @else
                    <span class="text-sm font-medium text-gray-600">現在の月に該当するものはありません</span>
                  @endif
                </div>
                
                <div class="w-full sm:w-1/2 pl-0 sm:pl-2">
                  <!-- 画像を表示 -->
                  @if ($spot->spotimages->isNotEmpty())
                    <a onclick="openModal('{{ $spot->spotimages->first()->image_path ?? asset('images/default-image.jpg') }}')">
                      <img src="{{ $spot->spotimages->first()->image_path }}" alt="{{ $spot->name }}" class="w-full h-auto sm:h-48 object-cover rounded-lg">
                    </a>
                  @else
                    <img src="/images/no_image_available.png" alt="No image" class="w-full h-auto sm:h-48 object-cover rounded-lg">
                  @endif
                </div>
                <!-- モーダルコンポーネント -->
                <x-modal-window />
              </div>
            </div>
          @empty
            <p class="text-center text-gray-500">現在、スポット情報はありません。</p>
          @endforelse
        </div>
      </div>
      
      <!-- ページネーション -->
     <!-- ページネーションの表示 -->
<x-pagination :paginator="$seasonranking" />
    </div>
  </div>
</x-app-layout>


