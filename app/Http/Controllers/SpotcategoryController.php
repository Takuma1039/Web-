<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use App\Models\Category;
use Illuminate\Http\Request;

class SpotcategoryController extends Controller
{
    public function index(Category $category, Request $request)
{
    // 特定のカテゴリーに関連するスポットを取得し、関連するカテゴリーもロード
    $spotsQuery = Spot::whereHas('spotcategories', function ($query) use ($category) {
            $query->where('spot_categories.category_id', $category->id);
        })->with('spotcategories')->withCount('likes'); // 関連するカテゴリーといいねの数を取得
    
    // 検索機能の実装
    $query = $request->input('search');
    
    // 検索機能を追加
    if ($query) {
        $spotsQuery->where('name', 'like', '%' . $query . '%'); //nameカラムに対して部分一致の検索条件を追加する
    }

    // 最後にページネーションを適用
    $spots = $spotsQuery->orderBy('likes_Count', 'DESC')->paginate(10);
    foreach ($spots as $spot) {
        $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 200);
    }

    return view('spot_categories.index', compact('spots', 'category'));
}

    //テキストを特定の文字や句読点で切り捨てるメソッド
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

