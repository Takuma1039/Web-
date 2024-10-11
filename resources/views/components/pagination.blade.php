@props(['paginator'])

<div class="mb-6">
    <nav class="flex justify-center">
        <ul class="inline-flex space-x-2">
            {{-- 最初に戻るボタン --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="@lang('pagination.first')">
                    <span class="px-4 py-2 text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed"><<</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->url(1) }}" rel="first" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300"><<</a>
                </li>
            @endif

            {{-- 前へボタン --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="px-4 py-2 text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed">前へ</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300">前へ</a>
                </li>
            @endif

            {{-- ページネーションリンク --}}
            @foreach ($paginator->links() as $element)
                {{-- 文字列セパレーター --}}
                @if (is_string($element))
                    <li aria-disabled="true">
                        <span class="px-4 py-2 text-gray-500 bg-gray-200 rounded-lg">{{ $element }}</span>
                    </li>
                @endif

                {{-- 配列内のページ番号 --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page">
                                <span class="px-4 py-2 bg-indigo-600 text-white font-bold rounded-lg">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-indigo-600 hover:text-white transition duration-300">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- 次へボタン --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300">次へ</a>
                </li>
            @else
                <li aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="px-4 py-2 text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed">次へ</span>
                </li>
            @endif

            {{-- 最後に戻るボタン --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->url($paginator->lastPage()) }}" rel="last" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300">>></a>
                </li>
            @else
                <li aria-disabled="true" aria-label="@lang('pagination.last')">
                    <span class="px-4 py-2 text-gray-500 bg-gray-200 rounded-lg cursor-not-allowed">>></span>
                </li>
            @endif
        </ul>
    </nav>
</div>
