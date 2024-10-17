<form method="POST" action="{{ route('profile.updateIcon') }}" enctype="multipart/form-data">
    @csrf
    <!-- アイコンアップロード用のファイル入力 -->
    <div class="mb-4">
        <label for="image_path" class="block text-lg font-medium text-gray-700">Upload Icon</label>
        <input type="file" name="image_path" accept="image/*" required class="mt-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-indigo-500" />
    </div>
    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        Update
    </button>

    @if ($errors->any())
        <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
            {{ session('error') }}
        </div>
    @endif
</form>
