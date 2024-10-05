<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\SeasonSpot;
use App\Models\Category;
use App\Models\Local;
use App\Models\Season;
use App\Models\Month;
use Carbon\Carbon;

class SeasonSpotController extends Controller
{
    public function index(Request $request, Category $category, Local $local, Season $season, Month $month)
{
    // 現在の月を取得
    $currentMonth = Carbon::now()->month; // 現在の月を取得
    $matchingMonth = Month::where('name', $currentMonth . '月')->first(); // 現在の月に対応するレコードを取得

    if (!$matchingMonth) {
        return redirect()->back()->with('error', '該当する月が見つかりませんでした。');
    }
    
    // 現在のURLとページ名を取得
    $currentUrl = url()->current();
    $currentPageName = '今の時期におすすめなスポットランキング';

    // 履歴の管理
    $this->updateHistory($request, $currentUrl, $currentPageName);

    // 履歴を取得
    $history = $request->session()->get('history', []);

    // スポット情報の取得
    $spotsQuery = Spot::whereHas('months', function ($query) use ($matchingMonth) {
        $query->where('months.id', $matchingMonth->id);
    })->with('months')->withCount('likes');

    // 検索機能を追加
    $query = $request->input('search');
    if ($query) {
        $spotsQuery->where('name', 'like', '%' . $query . '%'); // 部分一致検索
    }

    // ページネーションとソートを適用
    $seasonranking = $spotsQuery->having('likes_count', '>', 0)->orderBy('likes_count', 'DESC')->paginate(10);
    
    // 各スポットのbodyを切り捨てる
    foreach ($seasonranking as $spot) {
        $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 200);
    }
    
    // 現在の月に関連していないスポットを削除
    $spotIdsInSeason = SeasonSpot::pluck('spot_id')->toArray(); // 現在season_spotsに保存されているスポットIDを取得
    $validSpotIds = $seasonranking->pluck('id')->toArray(); // 現在の月に合ったスポットのIDを取得

    // 月に合わないスポットを削除
    SeasonSpot::whereNotIn('spot_id', $validSpotIds)
        ->orWhereNotIn('spot_id', $spotIdsInSeason)
        ->delete();
        
    // season_spotsテーブルにスポットIDを保存
    foreach ($seasonranking as $spot) {
        SeasonSpot::updateOrCreate(
            ['spot_id' => $spot->id], // すでに存在する場合は更新
            ['created_at' => now()], 
        );
    }

    // 最新のページがすでに履歴にある場合はビューを返す
    if (count($history) > 0 && end($history)['url'] == $currentUrl) {
        return view('SeasonSpot.index', [
            'spotcategories' => $category->get(),
            'locals' => $local->get(),
            'seasons' => $season->get(),
            'months' => $month->get(),
            'seasonranking' => $seasonranking,
            'currentMonth' => $currentMonth,
            'matchingMonth' => $matchingMonth,
        ]); // 履歴が同じ場合はビューを返すだけ
    }

    // ランキングページのビューにデータを渡す        
    return view('SeasonSpot.index')->with([
        'spotcategories' => $category->get(),
        'locals' => $local->get(),
        'seasons' => $season->get(),
        'months' => $month->get(),
        'seasonranking' => $seasonranking,
        'currentMonth' => $currentMonth,
        'matchingMonth' => $matchingMonth,
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
