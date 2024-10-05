    @for ($i = 0; $i < 5; $i++)
      @php
        $currentValue = $i + 1; // 現在の星の値（1〜5）
        $fullStar = $averageRating >= $currentValue; // 完全な星の表示
        $partialStar = !$fullStar && $averageRating > $currentValue - 1 && $averageRating < $currentValue; // 部分的な星の表示
        $fillPercentage = $partialStar ? ($averageRating - ($currentValue - 1)) * 100 : 0; // 部分的な星の塗り
      @endphp

      <div class="relative inline-block">
        <!-- 空の星のベースを描画 -->
        <i class="fas fa-star text-gray-300 text-xl"></i>

        @if ($fullStar)
          <!-- 完全な星の塗り -->
          <i class="fas fa-star text-yellow-500 text-xl absolute top-0 left-0"></i>
        @elseif ($partialStar)
          <!-- 部分的な星の塗り -->
          <div class="absolute top-0 left-0 h-full overflow-hidden" style="width: {{ $fillPercentage }}%;">
            <i class="fas fa-star text-yellow-500 text-xl"></i>
          </div>
        @endif
      </div>
    @endfor
