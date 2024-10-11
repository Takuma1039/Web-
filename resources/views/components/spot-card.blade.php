@props(['spot', 'ranking' => null])

<div class="flex-none sm:w-48 md:w-64 h-auto snap-center">
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden relative h-full flex flex-col justify-between">
        <div>
            <!-- ランキング表示 -->
            @if ($ranking !== null)
                @if ($ranking == 1)
                    <!-- 1位の画像アイコン -->
                    <div class="absolute top-2 px-3 py-1">
                        <img src="{{ asset('storage/image/gold-medal.png') }}" alt="1st place" class="w-10 h-10">
                    </div>
                @elseif ($ranking == 2)
                    <!-- 2位の画像アイコン -->
                    <div class="absolute top-2 px-3 py-1">
                        <img src="{{ asset('storage/image/silver-medal.png') }}" alt="2nd place" class="w-10 h-10">
                    </div>
                @elseif ($ranking == 3)
                    <!-- 3位の画像アイコン -->
                    <div class="absolute top-2 px-3 py-1">
                        <img src="{{ asset('storage/image/bronze-medal.png') }}" alt="3rd place" class="w-10 h-10">
                    </div>
                @endif
            @endif

            <!-- 画像の表示 -->
            <a onclick="openModal('{{ $spot->spotimages->first()->image_path ?? asset('images/default-image.jpg') }}')" aria-label="スポットの詳細ページへ移動">
                <img src="{{ $spot->spotimages->first()->image_path ?? asset('images/default-image.jpg') }}" alt="" class="w-full h-40 object-cover" loading="lazy">
            </a>
            <div class="p-4">
                <h3 class="text-lg leading-6 font-bold text-gray-900">
                    <a href="/spots/{{ $spot->id }}" class="transition duration-300 ease-in-out transform hover:text-indigo-600">{{ $spot->name }}</a>
                </h3>
                <p class="text-gray-600 mt-2 text-sm">{{ $spot->truncated_body }}</p>
            </div>
        </div>
        <div class="p-4">
            <div class="flex items-center mt-2">
                <!-- 総合評価評価コンポーネント追加 -->
                <x-rating :average-rating="$spot->average_rating" />
                <span class="ml-2 text-gray-600 text-sm">
                    <span class="text-gray-500 italic font-extrabold">{{ $spot->reviews->count() }} reviews</span>
                </span>
            </div>
        </div>
    </div>
</div>
