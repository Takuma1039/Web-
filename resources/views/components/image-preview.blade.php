<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">画像ファイル（複数可）:</label>
    <label for="image-input" class="bg-blue-600 text-white py-2 px-4 rounded-lg cursor-pointer hover:bg-blue-700 transition-all duration-200 shadow-lg inline-block">
        画像を選択
    </label>
    <input type="file" id="image-input" class="hidden" name="{{ $inputName }}" multiple accept="image/*" onchange="previewImages()">
    <span id="file-count" class="text-sm text-gray-600 ml-4">0ファイル選択</span>
    <div id="image-preview" class="grid grid-cols-2 gap-4 mt-2"></div>
</div>

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
