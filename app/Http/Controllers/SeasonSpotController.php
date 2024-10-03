<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\SeasonSpot;
use Carbon\Carbon;

class SeasonSpotController extends Controller
{
    public function index()
    {
        // スポットをいいね数の多い順で取得
        $seasonranking = Spot::withCount('likes', 'months') // likes, monthsはリレーション名
                ->having('likes_count', '>', 0)      // いいね数が0より大きいスポットだけを取得
                ->orderBy('likes_count', 'desc')  // いいね数で降順に並び替え
                ->take(10)  // 上位10件を取得
                ->get();
        // 各スポットのbodyを切り捨てる
        foreach ($seasonranking as $spot) {
          $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 200);
        }
        
        // season_spotsテーブルにスポットIDを保存
        foreach ($seasonranking as $spot) {
            SeasonSpot::updateOrCreate(
                ['spot_id' => $spot->id], // すでに存在する場合は更新
            );
        }
        
        $currentMonth = Carbon::now()->month; // 現在の月を取得
        // 現在の月に対応する month_id を取得
        $matchingMonth = $spot->months->firstWhere('id', $currentMonth);
        
        // ランキングページのビューにデータを渡す        
        return view('SeasonSpot.index', compact('seasonranking', 'currentMonth', 'matchingMonth'));
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
