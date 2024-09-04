    <x-app-layout>
      <body>
        <div class="edit">
          <a href="/spots/{{ $spot->id }}/edit">edit</a>
        </div>
        @foreach($spot_image as $spot_img)
          <figure class="relative w-full h-60">
            <img src="{{ $spot_img->image_path }}" alt="画像が読み込めません"/>
          </figure>
        @endforeach
        <h1 class="title">
          <h3>[スポット{{ $spot->id }}]</h3>
            {{ $spot->name }}
        </h1>
        <div class="content">
            <div class="content_post">
                <h3>[スポット紹介]</h3>
                <p>{{ $spot->body }}</p>
                <h3>[住所]</h3>
                <p>{{ $spot->address }}</p>
                <h3>[緯度・経度]</h3>
                <p>緯度 : {{ $spot->lat }}</p>
                <p>経度 : {{ $spot->long }}</p>
            </div>
        </div>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
        <p class='user'>ログインユーザー:{{ Auth::user()->name }}</p>
      </body>
    </x-app-layout>
