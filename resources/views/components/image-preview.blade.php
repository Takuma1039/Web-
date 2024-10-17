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
        const fileInput = document.getElementById('image-input');　//ファイル入力要素
        const preview = document.getElementById('image-preview'); //画像のプレビュー
        const fileCount = document.getElementById('file-count');  //選択されたファイルの数

        // 新しく選択されたファイルを追加
        const newFiles = Array.from(fileInput.files);
        selectFiles = selectFiles.concat(newFiles);
        
        fileCount.textContent = selectFiles.length + 'ファイル選択'; //ファイル数更新

        // プレビューをリセットしてから、選択されたすべてのファイルを表示
        preview.innerHTML = '';

        selectFiles.forEach((file, index) => {
            const reader = new FileReader();

            // 画像のプレビュー作成
            reader.onload = function(e) {
                const imgContainer = document.createElement('div');
                imgContainer.classList.add('relative', 'flex', 'flex-col', 'items-center', 'mb-4');

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('h-32', 'w-full', 'object-cover', 'rounded-lg', 'shadow-lg');
                    
                const removeBtn = document.createElement('button');
                removeBtn.textContent = '削除';
                removeBtn.classList.add('absolute', 'top-2', 'right-2', 'bg-red-500', 'text-white', 'px-2', 'py-1', 'rounded-lg', 'text-sm', 'hover:bg-red-600', 'focus:outline-none');
                removeBtn.onclick = function() {
                    removeImage(index);
                };
                
                const nameInput = document.createElement('input');
                nameInput.type = 'text';
                nameInput.name = `new_image_names[]`;
                nameInput.placeholder = '画像の名前を入力';
                nameInput.classList.add('mt-1', 'block', 'w-full', 'border', 'border-gray-300', 'rounded-md', 'shadow-sm', 'focus:ring', 'focus:ring-indigo-200');
                    
                imgContainer.appendChild(img);
                imgContainer.appendChild(nameInput);
                imgContainer.appendChild(removeBtn);
                preview.appendChild(imgContainer);
            }

            reader.readAsDataURL(file);
        });
    }
        
    // 画像を選択リストから削除
    function removeImage(index) {
        selectFiles.splice(index, 1); // 選択されたファイルリストから削除
        previewImages(); // プレビューを再描画
    }
</script>
