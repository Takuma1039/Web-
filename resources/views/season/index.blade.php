<x-app-layout>
    <div class="bg-white min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-6">Season:
                @if($season)
                    {{ $season->name }}
                @else
                    スポットが属するシーズンはありません
                @endif
            </h1>

            <div class="col-span-2">
                @forelse($spots as $spot)
                    <div class="p-4 bg-white rounded-lg shadow-md mb-4">
                        <div class="flex items-center mb-2">
                            <a href="/spots/{{ $spot->id }}" class="ml-4 text-xl font-bold text-indigo-600 hover:underline">
                                {{ $spot->name }}
                            </a>
                            @auth
                                <div class="ml-4">
                                    <x-like-button :spot="$spot" />
                                </div>
                            @endauth
                        </div>
          
                        <div class="flex flex-col lg:flex-row mb-2">
                            <div class="w-full lg:w-1/2 pr-2">
                                <p class="text-gray-600 mb-2">
                                    {{ $spot->truncated_body }}
                                </p>
                                <span class="text-sm font-medium text-gray-600" id="likeCount-{{ $spot->id }}">{{ $spot->likes->count() }} いいね</span>
                            </div>
                
                            <div class="w-full lg:w-1/2 pl-2">
                                @if ($spot->spotimages->isNotEmpty())
                                    <img src="{{ $spot->spotimages->first()->image_path }}" alt="{{ $spot->name }}" class="w-full h-auto sm:h-48 object-cover rounded-lg">
                                @else
                                    <img src="/images/no_image_available.png" alt="No image" class="w-full h-auto sm:h-48 object-cover rounded-lg">
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                        <p class="text-center text-gray-500">現在、スポット情報はありません。</p>
                @endforelse
            </div>

            <div class="mt-8">
                <x-pagination :paginator="$spots" />
            </div>
        </div>
    </div>
</x-app-layout>
