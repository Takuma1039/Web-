@props(['spot', 'ranking' => null])

<div class="flex-none sm:w-36 md:w-48 lg:w-56 h-full snap-center"> 
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden relative h-full flex flex-col">
        <div class="flex-1 flex flex-col">
            <!-- ランキング表示 -->
            @if ($ranking !== null)
                <div class="absolute top-1 px-2 py-1">
                    @if ($ranking == 1)
                        <img src="{{ cloudinary_url('gold-medal_duja3h.png') }}" alt="1st place" class="w-8 h-8"> 
                    @elseif ($ranking == 2)
                        <img src="{{ cloudinary_url('silver-medal_p8dxig.png') }}" alt="2nd place" class="w-8 h-8">
                    @elseif ($ranking == 3)
                        <img src="{{ cloudinary_url('bronze-medal_gbru8n.png') }}" alt="3rd place" class="w-8 h-8">
                    @endif
                </div>
            @endif

            <!-- 画像の表示 -->
            <a onclick="openModal('{{ $spot->spotimages->first()->image_path ?? asset('images/default-image.jpg') }}')" aria-label="スポットの詳細ページへ移動">
                <img src="{{ $spot->spotimages->first()->image_path ?? asset('images/default-image.jpg') }}" alt="" class="w-full h-32 object-cover" loading="lazy">
            </a>

            <div class="flex-1 p-3 flex flex-col justify-between"> 
                <h3 class="text-md leading-5 font-bold text-gray-900"> 
                    <a href="/spots/{{ $spot->id }}" class="transition duration-300 ease-in-out transform hover:text-indigo-600">{{ $spot->name }}</a>
                </h3>
                <p class="text-gray-600 mt-1 text-sm">{{ $spot->truncated_body }}</p> 

                <div class="flex items-center">
                    <x-rating :average-rating="$spot->average_rating" />
                    <span class="ml-1 text-gray-600 text-sm"> 
                        <span class="text-gray-500 italic font-extrabold">{{ $spot->reviews->count() }} reviews</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
