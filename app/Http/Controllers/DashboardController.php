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
    public function index(Category $category, Local $local, Season $season, Month $month){
        // majorspotsテーブルからいいね数の多い順でスポットを取得
        $majorspots = Spot::withCount('likes') // likesはリレーション名
            ->join('majorspots', 'spots.id', '=', 'majorspots.spot_id') // majorspotsテーブルと結合
            ->having('likes_count', '>', 0)      // いいね数が0より大きいスポットだけを取得
            ->orderBy('likes_count', 'desc') // likes_countで降順に並び替え
            ->get(['spots.*']); // spotsテーブルのすべてのカラムを取得
        
        // reviewspotsテーブルからスポットごとのレビュー数をカウントし、レビュー数順でスポットを取得
        $reviewspots = Spot::withCount('reviews') // reviewsのカウントを取得
            ->join('review_spots', 'spots.id', '=', 'review_spots.spot_id') // review_spotsテーブルと結合
            ->having('reviews_count', '>', 0)      // review数が0より大きいスポットだけを取得
            ->orderBy('reviews_count', 'desc') // reviews_countで降順に並び替え
            ->get();
            
        // seasonspotsテーブルからスポットごとのいいね数をカウントし、いいね数順でスポットを取得
        $seasonspots = Spot::withCount('likes', 'months') // likes, monthsはリレーション名
                ->join('season_spots', 'spots.id', '=', 'season_spots.spot_id') // season_spotsテーブルと結合
                ->having('likes_count', '>', 0)      // いいね数が0より大きいスポットだけを取得
                ->orderBy('likes_count', 'desc')  // いいね数で降順に並び替え
                ->take(10)  // 上位10件を取得
                ->get();
                
        // 各人気スポットに対してトランケート処理と画像、レビューを取得
        foreach ($majorspots as $spot) {
            // トランケート処理
            $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 70); //文字数制限
            
            // 画像を取得
            $spot->images = Spot_Image::where('spot_id', $spot->id)->get(); // 各スポットの画像を取得
            
            //口コミの取得
            $spot->reviews = Review::where('spot_id', $spot->id)->get();
            
            // 総合評価の計算
            $totalReviews = $spot->reviews->count();
            $averageRating = $totalReviews > 0 ? $spot->reviews->sum('review') / $totalReviews : 0; // 0で割るのを防ぐためのチェック
            
            // 各スポットに評価を追加
            $spot->average_rating = number_format($averageRating, 2);
        }
        
        // 各口コミ人気スポットに対してトランケート処理と画像、レビューを取得
        foreach ($reviewspots as $spot) {
            // トランケート処理
            $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 70); //文字数制限
            
            // 画像を取得
            $spot->images = Spot_Image::where('spot_id', $spot->id)->get(); // 各スポットの画像を取得
            
            //口コミの取得
            $spot->reviews = Review::where('spot_id', $spot->id)->get();
            
            // 総合評価の計算
            $totalReviews = $spot->reviews->count();
            $averageRating = $totalReviews > 0 ? $spot->reviews->sum('review') / $totalReviews : 0; // 0で割るのを防ぐためのチェック
            
            // 各スポットに評価を追加
            $spot->average_rating = number_format($averageRating, 2);
        }
        
        // 各シーズン人気スポットに対してトランケート処理と画像、レビューを取得
        foreach ($seasonspots as $spot) {
            // トランケート処理
            $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 70); //文字数制限
            
            // 画像を取得
            $spot->images = Spot_Image::where('spot_id', $spot->id)->get(); // 各スポットの画像を取得
            
            //口コミの取得
            $spot->reviews = Review::where('spot_id', $spot->id)->get();
            
            // 総合評価の計算
            $totalReviews = $spot->reviews->count();
            $averageRating = $totalReviews > 0 ? $spot->reviews->sum('review') / $totalReviews : 0; // 0で割るのを防ぐためのチェック
            
            // 各スポットに評価を追加
            $spot->average_rating = number_format($averageRating, 2);
        }
        
        return view("Toppage.dashboard")->with([
            'spotcategories' => $category->get(), 
            'locals' => $local->get(), 
            'seasons' => $season->get(), 
            'months' => $month->get(), 
            'majorspots' => $majorspots, 
            'reviewspots' => $reviewspots,
            'seasonspots' => $seasonspots,
        ]);
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
