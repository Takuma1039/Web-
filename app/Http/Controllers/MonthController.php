<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use App\Models\Month;

class MonthController extends Controller
{
    public function index(Month $month, Request $request)
{
    // 特定の月に関連するスポットを取得するクエリ
        $spotsQuery = Spot::whereHas('months', function ($query) use ($month) {
            $query->where('months.id', $month->id);
        })->with('months')->withCount('likes'); // 関連する月といいねの数を取得

        // 検索機能を追加
        $query = $request->input('search');
        if ($query) {
            $spotsQuery->where('name', 'like', '%' . $query . '%'); // 部分一致検索
        }

        // ページネーションとソートを適用
        $spots = $spotsQuery->orderBy('likes_Count', 'DESC')->paginate(10);

        // 各スポットの本文をトランケート
        foreach ($spots as $spot) {
            $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 200);
        }
        
        return view('month.index', compact('spots', 'month'));
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
