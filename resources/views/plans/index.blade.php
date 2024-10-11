<x-app-layout>
    <div class="container mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-800 text-center mb-8">旅行計画一覧</h1>

        <!-- 成功メッセージ -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <!-- 旅行計画の新規作成ボタン -->
        <div class="text-right mb-4">
            <a href="{{ route('plans.create') }}" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-indigo-700 transition duration-200 shadow-lg">
                新しい旅行計画を作成
            </a>
        </div>
        
        <!-- 旅行計画の新規投稿ボタン -->
          <div class="text-right mb-4">
            <a href="" class="bg-sky-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-emerald-700 transition duration-200 shadow-lg">
                旅行計画の投稿
            </a>
        </div>

              <!-- エラーメッセージの表示 -->
              @if ($errors->any())
                <div class="mb-4 text-red-600">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

        <!-- 旅行計画一覧 -->
        @if ($plans->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($plans as $plan)
                    <div class="p-6 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition duration-300">
                        <h2 class="text-xl font-bold mb-4">{{ $plan->title }}</h2>
                        <p class="text-gray-700 mb-2">旅行日: {{ $plan->start_date->format('Y年m月d日') }} {{ $plan->start_time->format('H時i分') }}</p>
                        <ul class="list-disc pl-5">
                            @foreach ($plan->destinations as $destination)
                                <li>{{ $destination->name }}</li>
                            @endforeach
                        </ul>
                        <div class="mt-4 flex justify-between">
                            <a href="{{ route('plans.show', $plan->id) }}" class="text-blue-600 hover:underline">
                                詳細を見る
                            </a>
                            <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">削除</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-600 mt-8">現在、旅行計画は作成されていません。</p>
        @endif
    </div>
</x-app-layout>
