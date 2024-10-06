<div class="min-h-screen bg-gray-100 flex flex-col items-center justify-start">
  <header class="w-full bg-white py-4 shadow">
    <div class="container mx-auto">
      <h1 class="text-2xl font-bold text-center">旅行計画作成</h1>
    </div>
  </header>

  <div class="container mx-auto mt-10 space-y-10">
    <!-- Start Button -->
    <div class="flex justify-center">
      <button class="bg-white px-4 py-2 border border-gray-300 rounded-md shadow">start</button>
    </div>

    <!-- Plan Section -->
    <div class="flex flex-col space-y-10">
      <!-- Transportation -->
      <div class="flex justify-center">
        <p class="text-lg">交通手段</p>
      </div>

      <!-- Spot Addition -->
      <div class="flex justify-center space-x-4">
        <div class="bg-white border border-gray-300 rounded-lg p-4 w-64 h-32 flex items-center justify-center text-center">
          <p>+ボタンからスポットを追加</p>
        </div>
        <div class="bg-white border border-gray-300 rounded-lg p-4 w-64 h-32 flex items-center justify-center">
          <p>所要時間やメモ</p>
        </div>
      </div>

      <!-- Additional Spots -->
      <div class="flex justify-center space-x-4">
        <div class="bg-white border border-gray-300 rounded-lg p-4 w-64 h-32 flex items-center justify-center"></div>
        <div class="bg-white border border-gray-300 rounded-lg p-4 w-64 h-32 flex items-center justify-center">
          <p>所要時間やメモ</p>
        </div>
      </div>

      <div class="flex justify-center space-x-4">
        <div class="bg-white border border-gray-300 rounded-lg p-4 w-64 h-32 flex items-center justify-center"></div>
        <div class="bg-white border border-gray-300 rounded-lg p-4 w-64 h-32 flex items-center justify-center">
          <p>所要時間やメモ</p>
        </div>
      </div>

      <!-- Finish Button -->
      <div class="flex justify-center">
        <button class="bg-white px-4 py-2 border border-gray-300 rounded-md shadow">finish</button>
      </div>
    </div>

    <!-- Confirm Button -->
    <div class="flex justify-center mt-10">
      <button class="bg-blue-500 text-white px-6 py-2 rounded-md">確認</button>
    </div>
  </div>
</div>
