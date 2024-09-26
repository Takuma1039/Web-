<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\Spot_image;
use App\Models\SpotCategory;
use App\Models\Season;
use App\Models\Local;
use App\Models\Month;

class DashboardController extends Controller
{
    public function index(SpotCategory $spotcategory, Local $local, Season $season, Month $month, Spot_image $spot_image){
    
      $spots = Spot::all();
      foreach ($spots as $spot) {
            // トランケート処理
            $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 70); //文字数制限
            
            // 画像を取得
            $spot->images = Spot_Image::where('spot_id', $spot->id)->get(); // 各スポットの画像を取得
        }
      return view("Toppage.dashboard")->with(['spotcategories' => $spotcategory->get(), 'locals' => $local->get(), 'seasons' => $season->get(), 'months' => $month->get(), 'spots' => $spots]);
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
