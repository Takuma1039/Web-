<x-app-layout>
    <div class="container mx-auto p-1">
        <h1 class="text-4xl font-extrabold text-gray-800 text-center mb-8">口コミ投稿一覧</h1>
    
        <div class="mb-6 p-4 bg-white border-l-4 border-pink-500 rounded-lg shadow-md">
            <p class="text-lg font-semibold text-gray-800">
                各スポット詳細画面に行って口コミを投稿しよう！
            </p>
            <a href="{{ route('spots.index') }}" class="inline-block mt-2 px-4 py-2 bg-pink-500 text-white rounded-full shadow transition duration-300 ease-in-out hover:bg-pink-600 hover:scale-105">
                スポットを探したい場合はこちら
            </a>
        </div>

        @foreach($spots as $spot)
            <div class="mb-6 p-4 border rounded-lg shadow">
                <div class="flex items-center">
        <a href="{{ route('spots.show', $spot->id) }}" class="transition duration-300 ease-in-out transform hover:text-indigo-600">
            <h2 class="text-xl font-semibold leading-tight">{{ $spot->name }}</h2>
        </a>
        <div class="flex items-center ml-4">
            <x-rating :average-rating="$spot->average_rating" class="h-6" />
            <span class="text-gray-600 ml-2 font-semibold leading-tight">総合評価: {{ number_format($spot->average_rating, 2) }}</span>
        </div>
    </div>
            
            <!-- 画像の表示 -->
            @if($spot->spotimages->isNotEmpty())
                <div class="mt-4">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($spot->spotimages as $image)
                            <div class="relative group">
                                <a onclick="openModal('{{ $image->image_path }}')">
                                    <img src="{{ asset($image->image_path) }}" alt="{{ $spot->name }}" class="w-full h-40 object-cover rounded-lg border-2 border-gray-300 shadow-md transition-transform duration-300 group-hover:scale-105">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-500">画像はまだありません。</p>
            @endif
            
            <x-modal-window />
            
            <!-- 口コミの表示 -->
@if($spot->reviews->isNotEmpty())
    <div class="mt-4">
        <h3 class="text-lg font-bold border-b-2 border-indigo-600 pb-2 mb-4">口コミ一覧:</h3>
        <p class="text-sm text-gray-500">口コミ数: {{ $spot->reviews_count }}</p>
        <ul class="space-y-4">
            @foreach($spot->reviews->take(2) as $review)
                <li class="p-4 bg-white rounded-lg shadow-md border border-gray-200 transition duration-300 hover:shadow-lg">
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <p class="text-gray-800 font-semibold">{{ $review->title }}</p>
                            <p class="text-sm text-gray-500">投稿者: 匿名 &bull; 投稿日: {{ $review->created_at->format('Y年m月d日') }}</p>
                        </div>
                        <div class="flex items-center">
                            <x-rating :average-rating="$review->review" />
                            <span class="text-gray-600 ml-2 font-semibold">評価: {{ $review->review }}</span>
                        </div>
                    </div>
                    <p class="text-gray-800">{{ $review->comment }}</p>

                    <!-- 口コミ画像の表示 -->
                    @if($review->images->isNotEmpty())
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 mt-2">
                            @foreach($review->images as $reviewImage)
                                <div class="relative group">
                                    <a onclick="openModal('{{ $reviewImage->image_path }}')">
                                        <img src="{{ asset($reviewImage->image_path) }}" alt="Review image" class="w-full h-24 object-cover rounded-lg border-2 border-gray-300 shadow-md transition-transform duration-300 group-hover:scale-105">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@else
    <p class="text-sm text-gray-500 mt-2">口コミはまだありません。</p>
@endif
</div>
@endforeach
<!-- ページネーションの表示 -->
<x-pagination :paginator="$spots" />
</div>
</x-app-layout>
