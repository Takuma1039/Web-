<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use App\Models\Month;

class MonthController extends Controller
{
    public function index(Month $month, Request $request)
{
    // 現在のURLを取得
    $currentUrl = url()->current();
    $currentPageName = $month->name; // 適切なページ名に変更

    // 履歴の管理
    $this->updateHistory($request, $currentUrl, $currentPageName);
        
    // 特定の月に関連するスポットを取得するクエリ
        $spotsQuery = Spot::whereHas('months', function ($query) use ($month) {
            $query->where('months.id', $month->id);
        })->with('months')->withCount('likes'); // 関連する月といいねの数を取得


        // ページネーションとソートを適用
        $spots = $spotsQuery->orderByRaw('(select count(*) from spotlikes where spotlikes.spot_id = spots.id) desc')
            ->paginate(10);

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
    
    // 履歴を更新するメソッド
    private function updateHistory(Request $request, $currentUrl, $currentPageName)
    {
        $history = $request->session()->get('history', []);

        // 同じURLが既に履歴に存在するか確認
        foreach ($history as $key => $item) {
            if ($item['url'] === $currentUrl) {
                // すでに存在する場合は、そのエントリを更新
                $history[$key]['name'] = $currentPageName; // 名前を更新
                $request->session()->put('history', $history); // 更新後にセッションに保存
                return; // 処理を終了
            }
        }
    
        // 古い履歴を削除するロジック
        if (count($history) >= 5) {
            array_shift($history); // 最初の履歴を削除
        }

        // 新しい履歴を追加
        $history[] = [
            'url' => $currentUrl,
            'name' => $currentPageName
        ];

        $request->session()->put('history', $history); // 更新後にセッションに保存
    }
}
