<?php

namespace App\Http\Controllers;
use App\Models\Majorspot;
use Illuminate\Http\Request;
use App\Models\Spot;

class MajorspotController extends Controller
{
    public function index()
    {
        // スポットをいいね数の多い順で取得
        $majorranking = Spot::withCount('likes') // likesはリレーション名
                ->orderBy('likes_count', 'desc')  // いいね数で降順に並び替え
                ->take(10)  // 上位10件を取得
                ->get();
        // 各スポットのbodyを切り捨てる
        foreach ($majorranking as $spot) {
          $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 200); // 例: 100文字で切り捨て
        }

        // ランキングページのビューにデータを渡す        
        return view('Major.index')->with(['majorranking' => $majorranking]);
    }
    
    public function truncateAtPunctuation($string, $maxLength)
    {
      if (mb_strlen($string) <= $maxLength) {
        return $string; // 文字数が上限を超えない場合はそのまま返す
      }

      // 最大長を超える部分を切り出す
      $truncated = mb_substr($string, 0, $maxLength);
    
      // 句読点を探す
      $lastPunctuation = mb_strrpos($truncated, '。');
      if ($lastPunctuation === false) {
        $lastPunctuation = mb_strrpos($truncated, '、');
      }

      // 最後の句読点が見つかった場合
      if ($lastPunctuation !== false) {
        return mb_substr($truncated, 0, $lastPunctuation + 1) . '...'; // 句読点まで含める
      }

      // 句読点が見つからない場合は、指定した文字数で切り捨てる
      return mb_substr($truncated, 0, $maxLength) . '...';
    }

}
