<div class="flex-none sm:w-48 md:w-64 h-auto snap-center">
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden relative h-full flex flex-col justify-between">
        <div>
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