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

    // 履歴をセッションに保存
    $history = $request->session()->get('history', []);
    
    // 古い履歴を削除するロジック（オプション）
    if (count($history) >= 5) { // 5件を超えたら古いものを削除
        $request->session()->forget('history.0'); // 最初の履歴を削除
    }
    
    // majorspotsテーブルからいいね数の多い順でスポットを取得
    $majorspots = Spot::withCount('likes')
        ->join('majorspots', 'spots.id', '=', 'majorspots.spot_id')
        ->having('likes_count', '>', 0)
        ->orderBy('likes_count', 'desc')
        ->get(['spots.*']);

    // reviewspotsテーブルからスポットごとのレビュー数をカウントし、レビュー数順でスポットを取得
    $reviewspots = Spot::withCount('reviews')
        ->join('review_spots', 'spots.id', '=', 'review_spots.spot_id')
        ->having('reviews_count', '>', 0)
        ->orderBy('reviews_count', 'desc')
        ->get();

    // seasonspotsテーブルからスポットごとのいいね数をカウントし、いいね数順でスポットを取得
    $seasonspots = Spot::withCount('likes')
        ->with('months')
        ->join('season_spots', 'spots.id', '=', 'season_spots.spot_id')
        ->having('likes_count', '>', 0)
        ->orderBy('likes_count', 'desc')
        ->take(10)
        ->get();
    
    // スポット一覧
    $spotlists = Spot::withCount('likes')->orderBy('name', 'asc')->get();
    
    // 各スポットリストに対して共通処理を実行
    $this->processSpotData($majorspots);
    $this->processSpotData($reviewspots);
    $this->processSpotData($seasonspots);
    $this->processSpotData($spotlists);
    
    // スライドショー用にランダムなスポットの画像を取得
    $randomSpots = $spotlists->random(5); // ランダムに5つのスポットを選択
    $slideImages = [];
    
    foreach ($majorspots as $spot) {
        if ($spot->images->isNotEmpty()) {
            // 画像を全て追加
            foreach ($spot->images as $image) {
                $slideImages[] = $image->image_path; // 画像パスを配列に追加
            }
        }
    }

    // 画像をシャッフルしてから最初の5つを取得
    $slideImages = collect($slideImages)->shuffle()->take(5)->toArray();
    // 最新のページがすでに履歴にある場合は更新
    if (count($history) > 0 && end($history)['url'] == $currentUrl) {
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

    // 新しい履歴を追加
    $request->session()->push('history', [
        'url' => $currentUrl,
        'name' => $currentPageName
    ]);
    
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


    private function processSpotData($spots)
{
    foreach ($spots as $spot) {
        // トランケート処理
        $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 70); // 文字数制限
        
        // 画像を取得
        $spot->images = Spot_Image::where('spot_id', $spot->id)->get(); // 各スポットの画像を取得
        
        // 口コミの取得
        $spot->reviews = Review::where('spot_id', $spot->id)->get();
        
        // 総合評価の計算
        $totalReviews = $spot->reviews->count();
        $averageRating = $totalReviews > 0 ? $spot->reviews->sum('review') / $totalReviews : 0; // 0で割るのを防ぐためのチェック
        
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
      $truncated = mb_substr($string, 0, $maxLength);
    
      // 句読点を探す
      $lastPunctuation = mb_strrpos($truncated, '。');
      if ($lastPunctuation === false) {
        $lastPunctuation = mb_strrpos($truncated, '、');
      }

      // 最後の句読点が見つかった場合
      if ($lastPunctuation !== false) {
        return mb_substr($truncated, 0, $lastPunctuation + 1); // 句読点まで含める
      }

      // 句読点が見つからない場合は、指定した文字数で切り捨てる
      return mb_substr($truncated, 0, $maxLength);
    }
}
