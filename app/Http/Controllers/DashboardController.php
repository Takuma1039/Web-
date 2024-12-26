<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\Spot_image;
use App\Models\Review;
use App\Models\Category;
use App\Models\Season;
use App\Models\Local;
use App\Models\Month;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Category $category, Local $local, Season $season, Month $month, Request $request)
{
    // 現在のURLとページ名を取得
    $currentUrl = url()->current();
    $currentPageName = 'ホーム'; // 適切なページ名に変更

    // 履歴の管理
    $this->updateHistory($request, $currentUrl, $currentPageName);
    
    // スポットのデータを取得する共通メソッド
    $majorspots = $this->getSpotsWithLikes();
    $reviewspots = $this->getSpotsWithReviews();
    $seasonspots = $this->getSeasonSpots();
    $spotlists = Spot::withCount('likes')->orderBy('name', 'asc')->get();
    
    // 各スポットリストに対して共通処理を実行
    $this->processSpotData($majorspots);
    $this->processSpotData($reviewspots);
    $this->processSpotData($seasonspots);
    $this->processSpotData($spotlists);
    
    // スライドショー用の画像取得
    $slideImages = $this->getSlideImages($majorspots);
    
    return view("Toppage.dashboard")->with([
        'spotcategories' => $category->get(),
        'locals' => $local->get(),
        'seasons' => $season->get(),
        'months' => $month->get(),
        'majorspots' => $majorspots,
        'reviewspots' => $reviewspots,
        'seasonspots' => $seasonspots,
        'spotlists' => $spotlists,
        'slideImages' => $slideImages,
    ]);
}

    private function getSpotsWithLikes()
{
    return Spot::select('spots.*') //spotsテーブルのすべてのカラムを選択
        ->join('majorspots', 'spots.id', '=', 'majorspots.spot_id') //majorspotsテーブルとspotsテーブルを結合
        ->where(function ($query) { //サブクエリを定義
            $query->selectRaw('count(*)') //spotlikesテーブルのspot_idがspotsテーブルのidと一致するレコードの数をカウント
                ->from('spotlikes')
                ->whereColumn('spotlikes.spot_id', 'spots.id');
        }, '>', 0)  // likes_count をWHEREでフィルタ
        ->orderByRaw('(select count(*) from spotlikes where spotlikes.spot_id = spots.id) desc')
        ->get();
}

private function getSpotsWithReviews()
{
    return Spot::select('spots.*')
        ->join('review_spots', 'spots.id', '=', 'review_spots.spot_id')
        ->where(function ($query) {
            $query->selectRaw('count(*)')
                ->from('reviews')
                ->whereColumn('reviews.spot_id', 'spots.id');
        }, '>', 0) 
        ->orderByRaw('(select count(*) from reviews where reviews.spot_id = spots.id) desc') 
        ->get();
}

private function getSeasonSpots()
{
    return Spot::select('spots.*')
        ->join('season_spots', 'spots.id', '=', 'season_spots.spot_id')
        ->orderByRaw('(select count(*) from spotlikes where spotlikes.spot_id = spots.id) desc')
        ->get();
}

private function getSlideImages($majorspots)
{
    $slideImages = [];

    foreach ($majorspots as $spot) {
        if ($spot->images->isNotEmpty()) {
            foreach ($spot->images as $image) {
                $slideImages[] = $image->image_path;
            }
        }
    }

    return collect($slideImages)->shuffle()->take(5)->toArray();
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

    private function processSpotData($spots)
{
    // 事前に関連データを取得
    $spotIds = $spots->pluck('id'); // スポットIDを取得

    $images = Spot_Image::whereIn('spot_id', $spotIds)->get()->groupBy('spot_id');
    $reviews = Review::whereIn('spot_id', $spotIds)->get()->groupBy('spot_id');

    foreach ($spots as $spot) {
        // トランケート処理
        $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 60);
        
        // 画像を取得
        $spot->images = $images[$spot->id] ?? collect(); // 画像を関連付け
        
        // 口コミの取得
        $spot->reviews = $reviews[$spot->id] ?? collect(); // 口コミを関連付け
        
        // 総合評価の計算
        $totalReviews = $spot->reviews->count();
        $averageRating = $totalReviews > 0 ? $spot->reviews->sum('review') / $totalReviews : 0;
        
        // 各スポットに評価を追加
        $spot->average_rating = number_format($averageRating, 2);
    }
}

    public function truncateAtPunctuation($string, $maxLength)
    {
      if (mb_strlen($string) <= $maxLength) {
          return $string; // 文字数が上限を超えない場合はそのまま返す
      }

      // 最大長を超える部分を切り出す
      $truncated = mb_substr($string, 0, $maxLength) . '...';
      
      return $truncated;
    
    //   // 句読点を探す
    //   $lastPunctuation = mb_strrpos($truncated, '。');
    //   if ($lastPunctuation === false) {
    //       $lastPunctuation = mb_strrpos($truncated, '、');
    //   }

    //   // 最後の句読点が見つかった場合
    //   if ($lastPunctuation !== false) {
    //       return mb_substr($truncated, 0, $lastPunctuation + 1) . '...'; // 句読点まで含める
    //   }

    //   // 句読点が見つからない場合は、指定した文字数で切り捨てる
    //   return mb_substr($truncated, 0, $maxLength);
    }
}
