@if (session('history'))
    <nav aria-label="breadcrumb" class="bg-white p-2 rounded-md">
        <ol class="flex items-center space-x-1 text-gray-700 text-sm break-words max-w-4xl">
            @foreach (session('history') as $item)
                <li>
                    <a href="{{ $item['url'] }}" class="text-blue-600 hover:text-blue-800 transition duration-300 ease-in-out">
                        {{ $item['name'] }}
                    </a>
                </li>
                @if (!$loop->last)
                    <li class="text-gray-400"> &gt; </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
