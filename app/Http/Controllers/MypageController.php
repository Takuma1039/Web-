<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Spot_image;
use App\Models\Review;
use Carbon\Carbon;

class MypageController extends Controller
{
    public function index(Request $request)
{
    // 現在のURLを取得
    $currentUrl = url()->current();
    $currentPageName = 'マイページ';

    // 履歴の管理
    $this->updateHistory($request, $currentUrl, $currentPageName);
    
    $user = $request->user();
    
    $plans = $user->plans()->with('destinations')->get();
    
    $planposts = $user->planlikes()->with('planpost.planimages', 'planpost.plan')->get();
    
    foreach ($plans as $plan) {
            // start_dateが文字列であるかどうかを確認
            if (is_string($plan->start_date)) {
                // Carbonインスタンスに変換
                $plan->start_date = Carbon::createFromFormat('Y-m-d', $plan->start_date);
            }
            
            if (is_string($plan->start_time)) {
                $plan->start_time = Carbon::createFromFormat('H:i:s', $plan->start_time);
            }
        }
    
    foreach ($planposts as $planpost) {
            $planpost->title = $planpost->planpost->title;
            $planpost->comment = $this->truncateAtPunctuation($planpost->planpost->comment, 150);
            // start_dateが文字列であるかどうかを確認
            if (is_string($planpost->planpost->plan->start_date)) {
                // Carbonインスタンスに変換
                $planpost->start_date = Carbon::createFromFormat('Y-m-d', $planpost->planpost->plan->start_date);
            }
            
            if (is_string($planpost->planpost->plan->start_time)) {
                $planpost->start_time = Carbon::createFromFormat('H:i:s', $planpost->planpost->plan->start_time);
            }
        }
    //dd($planposts);
    // お気に入りのスポットを取得
    $likedSpots = $user->spotlikes()->with('spot.spotimages')->get();

    // スポットデータを処理
    $this->processSpotData($likedSpots->pluck('spot'));

    return view('mypage.index', compact('likedSpots', 'plans', 'planposts'));
}

private function processSpotData($spots)
{
    // 事前に関連データを取得
    $spotIds = $spots->pluck('id'); // スポットIDを取得

    $images = Spot_Image::whereIn('spot_id', $spotIds)->get()->groupBy('spot_id');
    $reviews = Review::whereIn('spot_id', $spotIds)->get()->groupBy('spot_id');

    foreach ($spots as $spot) {
        // トランケート処理
        $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 70);
        
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
        return mb_substr($truncated, 0, $lastPunctuation + 1); // 句読点まで含める
      }

      // 句読点が見つからない場合は、指定した文字数で切り捨てる
      return mb_substr($truncated, 0, $maxLength);
    }
}
