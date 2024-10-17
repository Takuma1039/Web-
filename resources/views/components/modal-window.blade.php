<!-- モーダルウィンドウ -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden" onclick="closeModal()">
    <div class="relative bg-white rounded-lg shadow-lg overflow-hidden w-full max-w-3xl" onclick="event.stopPropagation();">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <img id="modalImage" src="" alt="拡大画像" class="w-full h-auto object-contain">
    </div>
</div>
<!--モーダルウィンドウ用-->
<script>
    function openModal(imagePath) {
        document.getElementById('modalImage').src = imagePath;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('imageModal').classList.add('hidden');
    }
</script>