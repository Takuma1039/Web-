<!-- resources/views/breadcrumbs.blade.php -->
@if(Session::has('page_history'))
    <nav class="text-gray-600">
        <ul class="flex space-x-2">
            @foreach(Session::get('page_history') as $index => $url)
                @if($loop->last)
                    <li>{{ $index === 2 ? '現在の履歴' : '過去の履歴' }}</li> <!-- 現在のページ -->
                @else
                    <li><a href="{{ $url }}" class="text-blue-500">{{ '過去の履歴' }}</a></li> <!-- 過去の履歴 -->
                    <span>></span>
                @endif
            @endforeach
        </ul>
    </nav>
@endif
