<x-app-layout>
<!-- フォーム -->
<form action="/spots" method="POST" enctype="multipart/form-data">
    @csrf <!--他のサイトからのリクエスト送信などを許容しないため-->
    <div class="spot_category">
      <h2>Category</h2>
        <select name="spot[spot_category_id]">
          @foreach($spotcategories as $spotcategory)
            <option value="{{ $spotcategory->id }}">{{ $spotcategory->name }}</option>
          @endforeach
        </select>
        <select name="spot[local_id]">
          @foreach($locals as $local)
            <option value="{{ $local->id }}">{{ $local->name }}</option>
          @endforeach
        </select>
        <select name="spot[season_id]">
          @foreach($seasons as $season)
            <option value="{{ $season->id }}">{{ $season->name }}</option>
          @endforeach
        </select>
        <select name="spot[month_id]">
          @foreach($months as $month)
            <option value="{{ $month->id }}">{{ $month->name }}</option>
          @endforeach
        </select>
    </div>
    <div class="name">
      <h2>Name</h2>
      <input type="text" name="spot[name]" placeholder="名前" value="{{ old('spot.name') }}"/>　
      <!--「/>」はタグの終了の省略。oldで以前に入力したspotのname情報を取得して表示。エラーが出ても表示内容は保存される-->
      <p class="name__error" style="color:red">{{ $errors->first('spot.name') }}</p> <!--nameに関するエラーを取得して表示-->
    </div>
    <div class="body">
      <h2>Body</h2>
      <textarea name="spot[body]" placeholder="紹介文">{{ old('spot.body') }}</textarea>　<!--長い文章や改行を許容-->
      <p class="body__error" style="color:red">{{ $errors->first('spot.body') }}</p> <!--bodyに関するエラーを取得して表示-->
    </div>
    <div class="address">
      <h2>Adress</h2>
      <textarea name="spot[address]" placeholder="住所">{{ old('spot.address') }}</textarea>　<!--長い文章や改行を許容-->
      <p class="address__error" style="color:red">{{ $errors->first('spot.address') }}</p> <!--addressに関するエラーを取得して表示-->
    </div>
    <div class="lat">
      <h2>緯度</h2>
      <textarea name="spot[lat]" placeholder="緯度">{{ old('spot.lat') }}</textarea>　<!--長い文章や改行を許容-->
      <p class="lat__error" style="color:red">{{ $errors->first('spot.lat') }}</p> <!--latに関するエラーを取得して表示-->
    </div>
    <div class="long">
      <h2>経度</h2>
      <textarea name="spot[long]" placeholder="経度">{{ old('spot.long') }}</textarea>　<!--長い文章や改行を許容-->
      <p class="long__error" style="color:red">{{ $errors->first('spot.long') }}</p> <!--latに関するエラーを取得して表示-->
    </div>
    <div class="image">
      <label for="photo">画像ファイル（複数可）:</label>
      <input type="file" name="image[]" multiple>
    </div>
    <button type="submit" class="btn btn-success" value="store"> Upload </button>
</form>
<div class="footer">
    <a href="/dashboard">戻る</a>
</div>
</x-app-layout>