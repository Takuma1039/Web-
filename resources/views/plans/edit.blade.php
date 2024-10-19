<x-app-layout>
    <form action="/plans/{{ $plan->id }}" method="POST" enctype="multipart/form-data" class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
        @csrf
        @method('PATCH')
        <h1 class="text-2xl font-bold mb-4">旅行計画の編集</h1>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">タイトル</label>
            <input type="text" name="plan[title]" placeholder="タイトル" value="{{ $plan->title }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"/>
            <p class="text-red-500 text-sm">{{ $errors->first('plan.title') }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">旅行日程</label>
            <textarea name="planpost[comment]" placeholder="旅行日程" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">{{ $planpost->comment }}</textarea>
            <p class="text-red-500 text-sm">{{ $errors->first('planpost.comment') }}</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">目的地</label>
            <textarea name="planpost[comment]" placeholder="コメント" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">{{ $planpost->comment }}</textarea>
            <p class="text-red-500 text-sm">{{ $errors->first('planpost.comment') }}</p>
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
</x-app-layout>
