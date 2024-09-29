<x-app-layout>
    <form action="/spots/{{ $spot->id }}" method="POST" enctype="multipart/form-data" class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
        @csrf
        @method('PATCH')
        <h2 class="text-2xl font-bold mb-4">スポット情報を編集</h2>

        <!-- カテゴリー -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">カテゴリー</label>
            <div class="mt-1 flex flex-wrap">
                @foreach($spotcategories as $spotcategory)
    <div class="flex items-center mr-4">
        <input type="checkbox" name="spot[category_ids][]" value="{{ $spotcategory->id }}" id="category-{{ $spotcategory->id }}" {{ in_array($spotcategory->id, $categoryIds) ? 'checked' : '' }} class="mr-2">
        <label for="category-{{ $spotcategory->id }}" class="text-sm text-gray-600">{{ $spotcategory->name }}</label>
    </div>
@endforeach
            </div>
        </div>

        <!-- 地域 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">地域</label>
            <div class="mt-1 flex flex-wrap">
                @foreach($locals as $local)
                    <div class="flex items-center mr-4">
                        <input type="checkbox" name="spot[local_id][]" value="{{ $local->id }}" id="local-{{ $local->id }}" {{ in_array($local->id, (array)$localIds) ? 'checked' : '' }} class="mr-2">
                        <label for="local-{{ $local->id }}" class="text-sm text-gray-600">{{ $local->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 季節 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">季節</label>
            <div class="mt-1 flex flex-wrap">
                @foreach($seasons as $season)
                    <div class="flex items-center mr-4">
                        <input type="checkbox" name="spot[season_ids][]" value="{{ $season->id }}" id="season-{{ $season->id }}" {{ in_array($season->id, $seasonIds) ? 'checked' : '' }} class="mr-2">
                        <label for="season-{{ $season->id }}" class="text-sm text-gray-600">{{ $season->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 月 -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">月</label>
            <div class="mt-1 flex flex-wrap">
                @foreach($months as $month)
                    <div class="flex items-center mr-4">
                        <input type="checkbox" name="spot[month_ids][]" value="{{ $month->id }}" id="month-{{ $month->id }}" {{ in_array($month->id, $monthIds) ? 'checked' : '' }} class="mr-2">
                        <label for="month-{{ $month->id }}" class="text-sm text-gray-600">{{ $month->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">名前</label>
            <input type="text" name="spot[name]" value="{{ old('spot.name', $spot->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"/>
            <p class="text-red-500 text-sm">{{ $errors->first('spot.name') }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">紹介文</label>
            <textarea name="spot[body]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">{{ old('spot.body', $spot->body) }}</textarea>
            <p class="text-red-500 text-sm">{{ $errors->first('spot.body') }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">住所</label>
            <textarea name="spot[address]" placeholder="住所" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">{{ old('spot.address', $spot->address) }}</textarea>
            <p class="text-red-500 text-sm">{{ $errors->first('spot.address') }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">緯度</label>
            <input type="text" name="spot[lat]" placeholder="緯度" value="{{ old('spot.lat', $spot->lat) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"/>
            <p class="text-red-500 text-sm">{{ $errors->first('spot.lat') }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">経度</label>
            <input type="text" name="spot[long]" placeholder="経度" value="{{ old('spot.long', $spot->long) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"/>
            <p class="text-red-500 text-sm">{{ $errors->first('spot.long') }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">アクセス</label>
            <textarea name="spot[access]" placeholder="アクセス" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">{{ old('spot.access', $spot->access) }}</textarea>
            <p class="text-red-500 text-sm">{{ $errors->first('spot.access') }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">開館日</label>
            <textarea type="text" name="spot[opendate]" placeholder="開館日" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">{{ old('spot.opendate', $spot->opendate) }}</textarea>
            <p class="text-red-500 text-sm">{{ $errors->first('spot.opendate') }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">閉館日</label>
            <textarea type="text" name="spot[closedate]" placeholder="閉館日" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">{{ old('spot.closedate', $spot->closedate) }}</textarea>
            <p class="text-red-500 text-sm">{{ $errors->first('spot.closedate') }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">料金</label>
            <textarea type="text" name="spot[price]" placeholder="料金" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">{{ old('spot.price', $spot->price) }}</textarea>
            <p class="text-red-500 text-sm">{{ $errors->first('spot.price') }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">公式サイト</label>
            <input type="text" name="spot[site]" placeholder="公式サイト" value="{{ old('spot.site', $spot->site) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"/>
            <p class="text-red-500 text-sm">{{ $errors->first('spot.site') }}</p>
        </div>

        <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">現在の画像</label>
    <div class="mt-1 flex flex-wrap">
        @foreach($spotImages as $spotImage)
            <div class="mr-4">
                <img src="{{ $spotImage->image_path }}" alt="Spot Image" class="w-32 h-32 object-cover mb-2">
                <div>
                    <input type="checkbox" name="remove_images[]" value="{{ $spotImage->id }}">
                    <label>削除</label>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">新しい画像ファイル（複数可）:</label>
    <input type="file" name="image[]" multiple class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
</div>
        <button type="submit" class="mt-4 bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">更新</button>
    </form>
    @if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="footer mt-6 text-center">
        <a href="javascript:history.back();" class="text-blue-600 hover:underline">戻る</a>
    </div>
</x-app-layout>
