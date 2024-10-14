<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\Category;
use App\Models\Local;
use App\Models\Season;
use App\Models\Month;
use App\Models\ReviewSpot;

class ReviewSpotController extends Controller
{
    public function index(Category $category, Local $local, Season $season, Month $month, Request $request)
{
    // 現在のURLとページ名を取得
    $currentUrl = url()->current();
    $currentPageName = '口コミランキング';

    // 履歴の管理
    $this->updateHistory($request, $currentUrl, $currentPageName);

    // スポットをいいね数の多い順で取得
    $spots = Spot::withCount('reviews')
    ->where(function ($query) { // サブクエリを定義
        $query->selectRaw('count(*)') // reviewsテーブルのspot_idがspotsテーブルのidと一致するレコードの数をカウント
            ->from('reviews')
            ->whereColumn('reviews.spot_id', 'spots.id');
    }, '>', 0) // review数が0より大きいものだけを取得
    ->orderByRaw('(select count(*) from reviews where reviews.spot_id = spots.id) desc') // review数で降順に並び替え
    ->get();
    
    // 現在の口コミスポットに関連していないスポットを削除
    $spotIdsInReview = ReviewSpot::pluck('spot_id')->toArray(); // 現在review_spotsに保存されているスポットIDを取得
    $validSpotIds = $spots->pluck('id')->toArray(); // 現在の口コミスポットに合ったスポットのIDを取得

    // 口コミスポットに合わないスポットを削除
    ReviewSpot::whereNotIn('spot_id', $validSpotIds)
        ->orWhereNotIn('spot_id', $spotIdsInReview)
        ->delete();

    // ReviewSpotテーブルにスポットIDを保存
    foreach ($spots as $spot) {
        ReviewSpot::updateOrCreate(
            ['spot_id' => $spot->id], // すでに存在する場合は更新
            ['created_at' => now()],
        );
    }
    
    // ページネーションの適用
    $paginate = 10; // 1ページあたりの表示件数
    $currentPage = $request->input('page', 1); // 現在のページ
    $offset = ($currentPage - 1) * $paginate; // 取得するスポットの開始位置を指定
    $reviewranking = new \Illuminate\Pagination\LengthAwarePaginator(
        $spots->slice($offset, $paginate), // 現在のページのスポットを取得
        $spots->count(), // 全体のスポット数
        $paginate, // 1ページあたりの表示件数
        $currentPage, // 現在のページ番号
        ['path' => $request->url(), 'query' => $request->query()] // ページネーションリンクの生成
    );
        
    // ランキングを計算するための配列
    $rankings = [];
    $currentRank = 0; // 現在の順位
    $previousReviewCount = null; // 前のスポットのレビュー数を保存
    $samerankCount = 0; // 同じ順位のスポット数をカウント
    
    //同じ順位のもの(レビュー数が同じもの)が複数ある場合に次の順位をスキップする
    foreach ($spots as $spot) {
        if ($previousReviewCount === null || $spot->reviews_count !== $previousReviewCount) {
            $currentRank += $samerankCount + 1; // 同じ順位のスポット数を加算して、順位を更新
            $samerankCount = 0; // 同じ順位のスポット数のカウントをリセット
        } else {
            // 同じレビュー数が続く場合はカウントを増やす
            $samerankCount++;
        }
        
        $rankings[$spot->id] = $currentRank; // スポットIDをキーにして順位を保存
        $previousReviewCount = $spot->reviews_count; // 現在のレビュー数を前のスポットのレビュー数に更新
    }
    
    // 各スポットのbodyを切り捨てる
    foreach ($reviewranking as $spot) {
        $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 200); // 例: 200文字で切り捨て
    }

    // ランキングページのビューにデータを渡す        
    return view('ReviewSpot.index')->with([
        'spotcategories' => $category->get(),
        'locals' => $local->get(),
        'seasons' => $season->get(),
        'months' => $month->get(),
        'reviewranking' => $reviewranking,
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
