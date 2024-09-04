<x-app-layout>
<!-- メッセージ -->
@if (count($errors) > 0)
<ul>
    @foreach($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
</ul>
@endif
<!-- フォーム -->
<form action="{{ url('upload') }}" method="POST" enctype="multipart/form-data">
    @csrf <!--他のサイトからのリクエスト送信などを許容しないため-->
    <label for="name">名前:</label>
    <input type="text" class="form-control" name="name" value="">
    <br>
    <label for="body">本文:</label>
    <input type="text" class="form-control" name="body" value="">
    <br>
    <label for="body">住所:</label>
    <input type="text" class="form-control" name="address" value="">
    <br>
    <label for="body">緯度:</label>
    <input type="text" class="form-control" name="lat" value="">
    <br>
    <label for="body">経度:</label>
    <input type="text" class="form-control" name="long" value="">
    <br>
    <label for="photo">画像ファイル（複数可）:</label>
    <input type="file" class="form-control" name="files[][photo]" multiple>
    <br>
    <hr>
    <button type="submit" class="btn btn-success" value="store"> Upload </button>
</form>
</x-app-layout>