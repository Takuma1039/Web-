<x-app-layout>
  <div class="bg-white min-h-screen">
    <div class="container mx-auto px-1 py-8">
      <!-- タイトル -->
      <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">
        今の時期におすすめなスポットランキング({{ $currentMonth }}月)
      </h1>
      
      <!--検索バーコンポーネント-->
        <x-search-bar :spotcategories="$spotcategories" :locals="$locals" :seasons="$seasons" :months="$months" />

      <div>
        <div class="col-span-2">
          @forelse($seasonranking as $spot)
            <div class="p-4 bg-white rounded-lg shadow-lg mb-4 border border-gray-300 hover:shadow-xl transition-shadow duration-300">
              <div class="flex items-center mb-2">
                <span class="text-xl font-semibold text-blue-500">第{{ $rankings[$spot->id] }}位</span>
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
          
              <div class="flex flex-col lg:flex-row mb-2">
                <div class="w-full lg:w-1/2 pr-2">
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
                
                <div class="w-full lg:w-1/2 pl-2">
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
      
      <!-- ページネーションの表示 -->
      <x-pagination :paginator="$seasonranking" />
    </div>
  </div>
</x-app-layout>


