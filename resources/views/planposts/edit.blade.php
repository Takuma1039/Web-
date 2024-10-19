<x-app-layout>
    <form action="/planposts/{{ $planpost->id }}" method="POST" enctype="multipart/form-data" class="max-w-2xl mx-auto p-6 bg-white rounded-lg shadow-md">
        @csrf
        @method('PATCH')
        <h1 class="text-2xl font-bold mb-4">投稿した旅行計画の編集</h1>
            
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">地域</label>
            <div class="mt-1 flex flex-wrap">
                @foreach($locals as $local)
                    <div class="flex items-center mr-4">
                        <input type="radio" name="planpost[local_id]" value="{{ $local->id }}" id="local-{{ $local->id }}" {{ in_array($local->id, $selectedLocalIds) ? 'checked' : '' }} class="mr-2">
                        <label for="local-{{ $local->id }}" class="text-sm text-gray-600">{{ $local->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
            
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">季節</label>
            <div class="mt-1 flex flex-wrap">
                @foreach($seasons as $season)
                    <div class="flex items-center mr-4">
                        <input type="radio" name="planpost[season_id]" value="{{ $season->id }}" id="season-{{ $season->id }}" {{ in_array($season->id, $selectedSeasonIds) ? 'checked' : '' }} class="mr-2">
                        <label for="season-{{ $season->id }}" class="text-sm text-gray-600">{{ $season->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
            
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">月</label>
            <div class="mt-1 flex flex-wrap">
                @foreach($months as $month)
                    <div class="flex items-center mr-4">
                        <input type="radio" name="planpost[month_id]" value="{{ $month->id }}" id="month-{{ $month->id }}" {{ in_array($month->id, $selectedMonthIds) ? 'checked' : '' }} class="mr-2">
                        <label for="month-{{ $month->id }}" class="text-sm text-gray-600">{{ $month->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
            
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">カテゴリー</label>
            <div class="mt-1 flex flex-wrap">
                @foreach($plantypes as $plantype)
                    <div class="flex items-center mr-4">
                        <input type="checkbox" name="planpost[plantype_ids][]" value="{{ $plantype->id }}" id="plantype-{{ $plantype->id }}" {{ in_array($plantype->id, $selectedPlantypeIds) ? 'checked' : '' }} class="mr-2">
                        <label for="plantype-{{ $plantype->id }}" class="text-sm text-gray-600">{{ $plantype->name }}</label>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">タイトル</label>
            <input type="text" name="planpost[title]" placeholder="タイトル" value="{{ $planpost->title }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"/>
            <p class="text-red-500 text-sm">{{ $errors->first('planpost.title') }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">コメント</label>
            <textarea name="planpost[comment]" placeholder="コメント" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">{{ $planpost->comment }}</textarea>
            <p class="text-red-500 text-sm">{{ $errors->first('planpost.comment') }}</p>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">現在の画像</label>
            <div class="mt-1 flex flex-wrap">
                @foreach($planImages as $planImage)
                    <div class="mr-4">
                        <img src="{{ $planImage->image_path }}" alt="Spot Image" class="w-32 h-32 object-cover mb-2">
                        <div>
                            <input type="checkbox" name="remove_images[]" value="{{ $planImage->id }}">
                            <label>削除</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">新しい画像ファイル（複数可）:</label>
            <label for="image-input" class="bg-blue-600 text-white py-2 px-4 rounded-lg cursor-pointer hover:bg-blue-700 transition-all duration-200 shadow-lg inline-block">
                画像を選択
            </label>
            <input type="file" id="image-input" class="hidden" name="images[]" multiple accept="image/*" onchange="previewImages()">
            <span id="file-count" class="text-sm text-gray-600 ml-4">0ファイル選択</span>
            <div id="image-preview" class="flex flex-wrap mt-1"></div>
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
    
    <script>
        let selectFiles = []; // 選択されたファイルを保持

        function previewImages() {
            const fileInput = document.getElementById('image-input'); // ファイル入力要素
            const preview = document.getElementById('image-preview'); // 画像のプレビュー
            const fileCount = document.getElementById('file-count');  // 選択されたファイルの数

            // 新しく選択されたファイルを追加
            const newFiles = Array.from(fileInput.files);
            selectFiles = selectFiles.concat(newFiles);

            fileCount.textContent = selectFiles.length + 'ファイル選択'; // ファイル数更新

            // プレビューをリセットしてから、選択されたすべてのファイルを表示
            updatePreview();
        }

        function updatePreview() {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = ''; // プレビューをリセット

            selectFiles.forEach((file, index) => {
                const reader = new FileReader();

                // 画像のプレビュー作成
                reader.onload = function(e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.classList.add('relative', 'mr-4', 'mb-2', 'flex', 'flex-col', 'items-center');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('h-32', 'w-32', 'object-cover', 'mb-2');

                    const removeButton = document.createElement('button');
                    removeButton.textContent = '削除';
                    removeButton.classList.add('bg-red-500', 'text-white', 'py-1', 'px-3', 'rounded', 'hover:bg-red-600', 'transition-all', 'duration-200', 'mr-2');
                
                    // ボタンクリック時の処理
                    removeButton.onclick = function() {
                        removeImage(index);
                    };

                    imgContainer.appendChild(img);
                    imgContainer.appendChild(removeButton);
                    preview.appendChild(imgContainer);
                }
                reader.readAsDataURL(file);
            });
        }

        // 画像を選択リストから削除
        function removeImage(index) {
            selectFiles.splice(index, 1);
            updatePreview();
            updateFileCount();
        }
    
        function updateFileCount() {
            const fileCount = document.getElementById('file-count');
            fileCount.textContent = selectFiles.length + 'ファイル選択';
        }
    </script>
</x-app-layout>
