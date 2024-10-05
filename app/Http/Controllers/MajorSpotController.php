<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\Category;
use App\Models\Local;
use App\Models\Season;
use App\Models\Month;
use App\Models\Majorspot;

class MajorSpotController extends Controller
{
    public function index(Request $request, Category $category, Local $local, Season $season, Month $month)
{
    // 現在のURLを取得
    $currentUrl = url()->current();
    $currentPageName = '人気スポットランキング'; // 適切なページ名に変更

    // 履歴の管理
    $this->updateHistory($request, $currentUrl, $currentPageName);

    // 履歴を取得
    $history = $request->session()->get('history', []);

    // スポット情報の取得
    $majorranking = Spot::withCount('likes') // いいね数をカウントして取得
        ->having('likes_count', '>', 0) // いいね数が0より大きいスポットだけを取得
        ->orderBy('likes_count', 'desc') // いいね数で降順に並び替え
        ->take(10) // 上位10件を取得
        ->get();
    
    // 各スポットのbodyを切り捨てる
    foreach ($majorranking as $spot) {
        $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 200);
    }
    
    // 最新のページがすでに履歴にある場合はビューを返す
    if (count($history) > 0 && end($history)['url'] == $currentUrl) {
        return view('MajorSpot.index', [
            'majorranking' => $majorranking,
            'spotcategories' => $category->get(),
            'locals' => $local->get(),
            'seasons' => $season->get(),
            'months' => $month->get(),
        ]); // 履歴が同じ場合はビューを返すだけ
    }

    // ランキングページのビューにデータを渡す        
    return view('MajorSpot.index')->with([
        'spotcategories' => $category->get(),
        'locals' => $local->get(),
        'seasons' => $season->get(),
        'months' => $month->get(),
        'majorranking' => $majorranking,
    ]);
}

    private function updateHistory(Request $request, $currentUrl, $currentPageName)
{
    $history = $request->session()->get('history', []);
    
    // 古い履歴を削除するロジック
    if (count($history) >= 5) {
        array_shift($history); // 最初の履歴を削除
    }

    // 同じURLが連続して追加されないようにする
    if (count($history) > 0 && end($history)['url'] == $currentUrl) {
        $request->session()->put('history', $history); // 更新後にセッションに保存
        return;
    }

    // 新しい履歴を追加
    $history[] = [
        'url' => $currentUrl,
        'name' => $currentPageName
    ];

    $request->session()->put('history', $history); // 更新後にセッションに保存
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
