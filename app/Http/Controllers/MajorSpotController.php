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

    // スポット情報の取得
    $spots = Spot::withCount('likes') // いいね数をカウントして取得
    ->where(function($query) {
        $query->selectRaw('count(*)')
            ->from('spotlikes')
            ->whereColumn('spotlikes.spot_id', 'spots.id');
    }, '>', 0) // サブクエリを使っていいね数が0より大きいものだけを取得
    ->orderByRaw('(select count(*) from spotlikes where spotlikes.spot_id = spots.id) desc') // いいね数で降順に並び替え
    ->get();
    
    foreach ($spots as $spot) {
        Majorspot::updateOrCreate(
            ['spot_id' => $spot->id], // すでに存在する場合は更新
            ['created_at' => now()], 
        );
    }
    
    // ページネーションの適用
    $paginate = 10; // 1ページあたりの表示件数
    $currentPage = $request->input('page', 1); // 現在のページ
    $offset = ($currentPage - 1) * $paginate; // 取得するスポットの開始位置を指定
    $majorranking = new \Illuminate\Pagination\LengthAwarePaginator(
        $spots->slice($offset, $paginate), // 現在のページのスポットを取得
        $spots->count(), // 全体のスポット数
        $paginate, // 1ページあたりの表示件数
        $currentPage, // 現在のページ番号
        ['path' => $request->url(), 'query' => $request->query()] // ページネーションリンクの生成
    );
        
    // ランキングを計算するための配列
    $rankings = [];
    $currentRank = 0; // 現在の順位
    $previousLikeCount = null; // 前のスポットのいいね数を保存
    $samerankCount = 0; // 同じ順位のスポット数をカウント
    
    //同じ順位のもの(いいね数が同じもの)が複数ある場合に次の順位をスキップする
    foreach ($spots as $spot) {
        if ($previousLikeCount === null || $spot->likes_count !== $previousLikeCount) {
            $currentRank += $samerankCount + 1; // 同じ順位のスポット数を加算して、順位を更新
            $samerankCount = 0; // 同じ順位のスポット数のカウントをリセット
        } else {
            // 同じいいね数が続く場合はカウントを増やす
            $samerankCount++;
        }
        
        $rankings[$spot->id] = $currentRank; // スポットIDをキーにして順位を保存
        $previousLikeCount = $spot->likes_count; // 現在のいいね数を前のスポットのいいね数に更新
    }
    
    // 各スポットのbodyを切り捨てる
    foreach ($majorranking as $spot) {
        $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 200);
    }

    // ランキングページのビューにデータを渡す        
    return view('MajorSpot.index')->with([
        'spotcategories' => $category->get(),
        'locals' => $local->get(),
        'seasons' => $season->get(),
        'months' => $month->get(),
        'majorranking' => $majorranking,
        'rankings' => $rankings
    ]);
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
