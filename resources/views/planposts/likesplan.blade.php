<x-app-layout>
    <div class="container mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-800 text-center mb-8">いいねした旅行計画一覧</h1>
        
        <div class="flex flex-row-reverse items-center gap-2">
            <!-- みんなの旅行計画 -->
            <div class="text-right mb-4">
                <a href="{{ route('planposts.index') }}" class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 transition duration-200 shadow-lg">
                    みんなの旅行計画から探す
                </a>
            </div>
        
            <!-- 旅行計画の新規投稿ボタン -->
            <div class="text-right mb-4">
                <a href="{{ route('plans.post') }}" class="bg-sky-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-sky-600 transition duration-200 shadow-lg">
                    旅行計画の投稿
                </a>
            </div>
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

        <!-- いいねした旅行計画一覧 -->
        @if ($planposts->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($planposts as $planpost)
                    <div class="flex flex-col justify-between p-6 max-w-sm bg-white rounded-lg border border-gray-200 shadow-md hover:shadow-lg transition duration-300">
                        <div>
                            <a href="{{ route('plans.show', $planpost->planpost->plan->id) }}"><h2 class="text-xl font-bold mr-2 transition duration-300 ease-in-out transform hover:text-indigo-600">{{ $planpost->title }}</h2></a>
                            <p class="text-gray-700 mb-2">旅行日: {{ $planpost->start_date->format('Y年m月d日') }} {{ $planpost->start_time->format('H時i分') }}</p>
                            @foreach($planpost->planpost->planimages as $plan_img)
                                <a class="group relative flex h-48 items-end overflow-hidden rounded-lg shadow-lg transition-transform duration-300 transform hover:scale-105" onclick="openModal('{{ $plan_img->image_path }}')">
                                    <img src="{{ $plan_img->image_path }}" loading="lazy" alt="Image" class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:opacity-90" />
                                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-40"></div>
                                </a>
                            @endforeach
                            <x-modal-window />
                            <p class="text-gray-700 mb-2">目的地:
                                <ul class="list-disc pl-5">
                                    @foreach ($planpost->planpost->plan->destinations as $destination)
                                        <li>{{ $destination->name }}</li>
                                    @endforeach
                                </ul>
                            </p>
                            <div class="border border-gray-300 bg-gray-50 rounded-md p-2 mt-4">
                                <p class="text-gray-700 mb-2">{{ $planpost->comment }}</p>
                            </div>
                        </div>
                        <div class="text-right mt-4">
                            <a href="{{ route('plans.show', $planpost->planpost->plan->id) }}" class="bg-teal-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-teal-700 transition duration-200 shadow-lg">
                                自分用に編集
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-600 mt-8">現在、いいねした旅行計画はありません。</p>
        @endif
    </div>
</x-app-layout>
