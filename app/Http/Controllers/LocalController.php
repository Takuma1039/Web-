<?php

namespace App\Http\Controllers;

use App\Models\Spot;
use Illuminate\Http\Request;
use App\Models\Local;

class LocalController extends Controller
{
    public function index(Local $local, Request $request)
{
    // 特定の地域に関連するスポットを取得し、関連する地域もロード
    $spots = $local->spots()->with('local')->withCount('likes');

    // 検索機能の実装
    $query = $request->input('search');
    
    // まず、クエリビルダを開始します
    $spotsQuery = $local->spots(); //特定の地域に関連するスポットを取得するためのクエリビルダーのインスタンスを生成する
    
    // 検索機能を追加
    if ($query) {
        $spotsQuery->where('name', 'like', '%' . $query . '%'); //nameカラムに対して部分一致の検索条件を追加する
    }

    // 最後にページネーションを適用
    $spots = $spotsQuery->orderBy('updated_at', 'DESC')->paginate(10);

    foreach ($spots as $spot) {
        $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 200);
    }

    return view('local.index', compact('spots', 'local'));
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
