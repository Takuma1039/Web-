<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">新しい画像ファイル（複数可）:</label>
    <input type="file" name="{{ $inputName }}" id="image-input" multiple 
           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200" 
           accept="image/*" onchange="previewImages()">
    <div id="image-preview" class="grid grid-cols-2 gap-4 mt-2"></div>
</div>

<script>
    function previewImages() {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = ''; // プレビューをリセット

        const files = document.getElementById('image-input').files;

        if (files.length === 0) {
            preview.innerHTML = '<p class="text-gray-500">画像が選択されていません。</p>';
            return;
        }

        for (let i = 0; i < files.length; i++) {
            const file = files[i];

            // 画像ファイルかどうかチェック
            if (!file.type.startsWith('image/')) {
                preview.innerHTML += `<p class="text-red-500">"${file.name}"は画像ファイルではありません。</p>`;
                continue;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                const imgContainer = document.createElement('div');
                imgContainer.classList.add('flex', 'flex-col', 'items-center', 'mb-4');

                const img = document.createElement('img');
                img.src = e.target.result;
                img.classList.add('h-32', 'w-full', 'object-cover', 'rounded-lg', 'shadow-lg');

                const nameInput = document.createElement('input');
                nameInput.type = 'text';
                nameInput.name = `new_image_names[]`; // 新しい画像の名前用
                nameInput.placeholder = '画像の名前を入力';
                nameInput.classList.add('mt-1', 'block', 'w-full', 'border', 'border-gray-300', 'rounded-md', 'shadow-sm', 'focus:ring', 'focus:ring-indigo-200');

                imgContainer.appendChild(img);
                imgContainer.appendChild(nameInput);
                preview.appendChild(imgContainer);
            }

            reader.readAsDataURL(file);
        }
    }
</script>
