<x-app-layout>
    <div class="bg-white min-h-screen py-8">
        <div class="container mx-auto">
            <!-- タイトル -->
            <h1 class="text-4xl font-extrabold text-gray-800 text-center mb-8">
                検索結果
                @if (!empty($query))
                    (キーワード: {{ $query }})
                @endif
            </h1>
      
            <div class="text-center mb-8">
                @php
                    $filters = [];
                    $colors = [
                        'plantype' => 'bg-teal-500 text-white',
                        'local' => 'bg-yellow-500 text-white',
                        'season' => 'bg-pink-500 text-white',
                        'month' => 'bg-cyan-500 text-white',
                    ];
                @endphp

                @if (!empty($plantypeIds))
                    @php
                        $filters[] = [
                            'text' => 'カテゴリー: ' . implode('・', $plantypes->whereIn('id', $plantypeIds)->pluck('name')->toArray()),
                            'color' => $colors['plantype']
                        ];
                    @endphp
                @endif

                @if (!empty($localIds))
                    @php
                        $filters[] = [
                            'text' => '地域: ' . implode('・', $locals->whereIn('id', $localIds)->pluck('name')->toArray()),
                            'color' => $colors['local']
                        ];
                    @endphp
                @endif

                @if (!empty($seasonIds))
                    @php
                        $filters[] = [
                            'text' => '季節: ' . implode('・', $seasons->whereIn('id', $seasonIds)->pluck('name')->toArray()),
                            'color' => $colors['season']
                        ];
                    @endphp
                @endif

                @if (!empty($monthIds))
                    @php
                        $filters[] = [
                            'text' => '月: ' . implode('・', $months->whereIn('id', $monthIds)->pluck('name')->toArray()),
                            'color' => $colors['month']
                        ];
                    @endphp
                @endif

                @if (count($filters) > 0)
                    <span class="text-lg text-gray-600 font-semibold">絞り込み条件:</span>
                    <div class="flex justify-center space-x-2 mt-2">
                        @foreach($filters as $filter)
                            <span class="inline-block {{ $filter['color'] }} text-sm font-medium mr-1 px-2.5 py-0.5 rounded">
                                {{ $filter['text'] }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 検索バーコンポーネント -->
            <x-plan-search-bar :plantypes="$plantypes" :locals="$locals" :seasons="$seasons" :months="$months" />
      
            @if($results->isEmpty())
                <p class="text-center text-gray-500 text-xl">キーワードに該当する旅行計画はありません。</p>
            @else
                <div class="space-y-8">
                    @foreach ($results as $planpost)
                        <div class="flex justify-between border border-gray-300 rounded-lg p-6 shadow-sm">
                            <div class="w-3/5">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('plans.show', $planpost->plan->id) }}">
                                            <h2 class="text-xl font-bold transition duration-300 ease-in-out transform hover:text-indigo-600">{{ $planpost->title }}</h2>
                                        </a>
                                        @auth
                                            @if(Auth::id() !== $planpost->user_id)
                                                <x-planpost-like :planpost="$planpost" />
                                            @endif
                                        @endauth
                                    </div>
                                    @if(Auth::id() === $planpost->user_id)
                                        <form action="{{ route('planposts.destroy', $planpost->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline font-semibold transition duration-200 text-lg">削除</button>
                                        </form>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-gray-700 font-semibold text-md">投稿者: 
                                        @if(Auth::id() === $planpost->user_id)
                                            {{ $planpost->user->name }}
                                        @else
                                            {{ $planpost->is_anonymous ? '匿名' : $planpost->user->name }}
                                        @endif
                                    </span>
                                    <span class="inline-block bg-teal-100 text-teal-800 text-sm font-medium mr-1 px-2.5 py-0.5 rounded">カテゴリー: 
                                        {{ $planpost->local->name }} 
                                        {{ $planpost->season->name }}
                                        {{ $planpost->month->name }}
                                        {{ $planpost->plantype->name }}
                                    </span>
                                </div>
                                <div class="border border-gray-300 bg-gray-50 rounded-md p-5 mt-4">
                                    <p class="text-base text-gray-600">{{ $planpost->comment }}</p>
                                </div>
                            </div>

                            <div class="w-2/5 ml-6 flex flex-col gap-4">
                                @foreach($planpost->planimages as $plan_img)
                                    <a class="group relative flex-grow overflow-hidden rounded-lg shadow-lg transition-transform duration-300 transform hover:scale-105" onclick="openModal('{{ $plan_img->image_path }}')">
                                        <img src="{{ asset($plan_img->image_path) }}" loading="lazy" alt="Image" class="absolute inset-0 h-full w-full object-cover object-center transition duration-200 group-hover:opacity-90" />
                                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-gray-800 via-transparent to-transparent opacity-40"></div>
                                    </a>
                                @endforeach
                            </div>
                            <x-modal-window />
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- ページネーション -->
            <div class='mt-8'>
                <x-pagination :paginator="$results" />
            </div>
        </div>
    </div>
</x-app-layout>
