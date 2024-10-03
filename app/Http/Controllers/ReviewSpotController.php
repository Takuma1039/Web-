<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\ReviewSpot;

class ReviewSpotController extends Controller
{
    public function index()
    {
        // スポットをいいね数の多い順で取得
        $reviewranking = Spot::withCount('reviews') // reviewsはリレーション名
                ->having('reviews_count', '>', 0)      // review数が0より大きいスポットだけを取得
                ->orderBy('reviews_count', 'desc')  // review数で降順に並び替え
                ->take(10)  // 上位10件を取得
                ->get();
        // 各スポットのbodyを切り捨てる
        foreach ($reviewranking as $spot) {
          $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 200); // 例: 100文字で切り捨て
        }
        
        // MajorspotsテーブルにスポットIDを保存
        foreach ($reviewranking as $spot) {
            ReviewSpot::updateOrCreate(
                ['spot_id' => $spot->id], // すでに存在する場合は更新
            );
        }
        
        // ランキングページのビューにデータを渡す        
        return view('ReviewSpot.index')->with(['reviewranking' => $reviewranking]);
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
