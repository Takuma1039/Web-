<x-app-layout>
<div class="p-2 w-1/2 mx-auto">
<div class="relative">
<label for="image" class="leading-7 text-sm text-gray-600">画像</label>
{{-- 画像の場合file,name属性でコントローラのほうでimageとすれば画像を取得できる --}}
<input type="file" id="image" name="files[][image]" multiple accept=“image/png,image/jpeg,image/jpg” class="w-full ~~">
</div>
</div>
@foreach ($spots as $spot)
<body class="spot-item">
    <div class="spot-title">
        <a href="{{ route('Spot.show', $spot) }}">{{ $spot->title }}</a>
    </div>
    <div class="spot-info">
        {{ $spot->created_at }}｜{{ $spot->user->name }}
    </div>
    <div class="spot-control">
        @if (!Auth::user()->is_favorite($spot->id))
        <form action="{{ route('favorite.store', $spot) }}" method="post">
            @csrf
            <button>お気に入り登録</button>
        </form>
        @else
        <form action="{{ route('favorite.destroy', $spot) }}" method="post">
            @csrf
            @method('delete')
            <button>お気に入り解除</button>
        </form>
        @endif
    </div>
</body>
@endforeach
{{ $spots->links() }}
</x-app-layout>