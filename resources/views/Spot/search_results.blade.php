<x-app-layout>
  <div class="bg-white min-h-screen py-8">
    <div class="container mx-auto">
      <!-- タイトル -->
      <h1 class="text-4xl font-extrabold text-gray-800 text-center mb-8">
        検索結果
      </h1>
      
      <div class="text-center mb-8">
    @php
        $filters = [];
        $colors = [
            'category' => 'bg-green-500 text-white',
            'local' => 'bg-yellow-500 text-white',
            'season' => 'bg-pink-500 text-white',
            'month' => 'bg-sky-500 text-white',
        ];
    @endphp

    @if (!empty($spotCategoryIds))
        @php
            $filters[] = [
                'text' => 'カテゴリー: ' . implode('・', $spotcategories->whereIn('id', $spotCategoryIds)->pluck('name')->toArray()),
                'color' => $colors['category']
            ];
        @endphp
    @endif

    @if (!empty($localIds))
        @php
            $filters[] = [
                'text' => '地域: ' . implode('・', $locals->whereIn('id', $localIds)->pluck('name')->toArray()),
                'color' => $colors['local']
            ];
        @endphp
    @endif

    @if (!empty($seasonIds))
        @php
            $filters[] = [
                'text' => 'おすすめシーズン: ' . implode('・', $seasons->whereIn('id', $seasonIds)->pluck('name')->toArray()),
                'color' => $colors['season']
            ];
        @endphp
    @endif

    @if (!empty($monthIds))
        @php
            $filters[] = [
                'text' => '月: ' . implode('・', $months->whereIn('id', $monthIds)->pluck('name')->toArray()),
                'color' => $colors['month']
            ];
        @endphp
    @endif

    @if (count($filters) > 0)
        <span class="text-lg text-gray-600 font-semibold">絞り込み条件:</span>
        <div class="flex justify-center space-x-2 mt-2">
            @foreach($filters as $filter)
                <span class="inline-block {{ $filter['color'] }} text-sm font-medium mr-1 px-2.5 py-0.5 rounded">
                    {{ $filter['text'] }}
                </span>
            @endforeach
        </div>
    @endif
</div>

      <!-- 検索バーコンポーネント -->
      <x-search-bar :spotcategories="$spotcategories" :locals="$locals" :seasons="$seasons" :months="$months" />

      <!-- スポット一覧 -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($results as $spot)
          <div class="bg-white p-6 rounded-lg shadow-lg transition-transform transform relative z-0 hover:shadow-xl hover:scale-105 duration-300 overflow-visible">
            <div class="relative">
              <a href="{{ route('spots.show', $spot->id) }}" class="hover:opacity-80 transition-opacity duration-150">
                <!-- 画像表示 -->
                @if ($spot->spotimages->isNotEmpty())
                  <img src="{{ $spot->spotimages->first()->image_path }}" 
                    alt="{{ $spot->name }}の画像" class="w-full h-48 object-cover rounded-lg mb-4">
                @else
                  <img src="/images/no_image_available.png" alt="画像がありません" 
                    class="w-full h-48 object-cover rounded-lg mb-4">
                @endif
              </a>
              <!-- スポット名とお気に入りボタン -->
              <h2 class="text-2xl font-semibold text-gray-800 mt-2 flex items-center">
                {{ $spot->name }}
                @auth
                  <div class="ml-4 relative">
                    <x-like-button :spot="$spot" />
                  </div>
                @endauth
              </h2>
              <!-- 住所表示 -->
              <p class="text-gray-600 mt-1">{{ $spot->address }}</p>
              <!-- カテゴリー表示 -->
              <p class="text-gray-500 mt-1">カテゴリー:
                @foreach($spot->spotcategories as $category)
                  <span class="inline-block bg-blue-100 text-blue-800 text-sm font-medium mr-1 px-2.5 py-0.5 rounded">{{ $category->name }}</span>
                @endforeach
              </p>
              <!-- おすすめシーズン -->
              <p class="text-gray-500 mt-1">おすすめシーズン:
                @foreach($spot->seasons as $season)
                  <span class="inline-block bg-blue-100 text-blue-800 text-sm font-medium mr-1 px-2.5 py-0.5 rounded">{{ $season->name }}</span>
                @endforeach
              </p>
              <!-- 月 -->
              <p class="text-gray-500 mt-1">月:
                @foreach($spot->months as $month)
                  <span class="inline-block bg-blue-100 text-blue-800 text-sm font-medium mr-1 px-2.5 py-0.5 rounded">{{ $month->name }}</span>
                @endforeach
              </p>
              <!-- 地域 -->
              <p class="text-gray-500 mt-1">地域:
                @if ($spot->local)
                  <span class="inline-block bg-blue-100 text-blue-800 text-sm font-medium mr-1 px-2.5 py-0.5 rounded">{{ $spot->local->name }}</span>
                @else
                  <span class="text-gray-400">地域情報はありません</span>
                @endif
              </p>
            </div>
          </div>
        @empty
          <p class="text-center text-gray-500 mt-8">現在、該当するスポットはありません。</p>
        @endforelse
      </div>

      <!-- ページネーション -->
      <div class="mt-8">
        {{ $results->links() }}
      </div>
    </div>
  </div>
</x-app-layout>
