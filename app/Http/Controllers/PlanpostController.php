<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PlanpostRequest;
use App\Models\Season;
use App\Models\Local;
use App\Models\Month;
use App\Models\Plantype;
use App\Models\Planpost;
use App\Models\PlanImage;
use Cloudinary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PlanpostController extends Controller
{
    public function index(Request $request){
        
        // 現在のURLを取得
        $currentUrl = url()->current();
        $currentPageName = 'みんなの旅行計画';

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        
        $planposts = Planpost::with('planimages')->paginate(10);
        
        return view('planposts.index')->with(['planposts'=>$planposts]);
    }
    
    public function likesplan(Request $request){
        
        // 現在のURLを取得
        $currentUrl = url()->current();
        $currentPageName = 'いいねした旅行計画一覧';

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        
        $user = $request->user();
        
        $planposts = $user->planlikes()->with('planpost.planimages', 'planpost.plan')->paginate(10);
        
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
        
        return view('planposts.likesplan')->with(['planposts'=>$planposts]);
    }
    // 旅行計画投稿画面の表示
    public function create(Request $request)
    {
        // 現在のURLを取得
        $currentUrl = url()->current();
        $currentPageName = '旅行計画投稿'; // 適切なページ名に変更

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        
        $locals = Local::all();                 
        $seasons = Season::all();               
        $months = Month::all(); 
        $plantypes = Plantype::all();
        
        // ユーザーを取得
        $user = $request->user();

        // 作成した旅行計画を取得
        $plans = $user->plans()->get();
        
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
    
        return view('planposts.create', compact('plans', 'locals', 'seasons', 'months', 'plantypes'));
    }
    
    public function store(PlanpostRequest $request, Planpost $planpost)
{
    //dd($request);
    $images = $request->file('images');
    if (is_null($images) || !is_array($images)) {
        return redirect()->back()->withErrors('画像が選択されていません。');
    }
    
    $input = $request['planpost'];
    $input['local_id'] = $request->input('planpost.local_id');
    $input['season_id'] = $request->input('planpost.season_id');
    $input['month_id'] = $request->input('planpost.month_id');
    $input['plantype_id'] = $request->input('planpost.plantype_id');
    $input['user_id'] = auth()->id();
    $input['plan_id'] = $request->input('planpost.plan_id');
    $input['is_anonymous'] = $request->input('planpost.is_anonymous');
    
    try {
        // トランザクションの開始
        \DB::beginTransaction();
        
        $planpost = new Planpost();
        if (!$planpost->fill($input)->save()) {
            throw new \Exception('旅行計画の投稿に失敗しました。');
        }
        
        foreach ($images as $image) {
            try {
                    $uploadResult = Cloudinary::upload($image->getRealPath());
                    $spot_image = new PlanImage();
                    $spot_image->planpost_id = $planpost->id;
                    $spot_image->image_path = $uploadResult->getSecurePath();
                    $spot_image->public_id = $uploadResult->getPublicId();
                    $spot_image->save();
            } catch (\Exception $e) {
                throw new \Exception('画像のアップロードに失敗しました: ' . $e->getMessage());
            }
        }

        // トランザクションをコミット
        \DB::commit();
        
        return redirect()->route('planposts.index')->with('success', '旅行計画が投稿されました。');
    } catch (\Exception $e) {
        // エラー発生時にトランザクションをロールバック
        \DB::rollBack();
        \Log::error('Plan creation failed', ['error' => $e->getMessage()]);
        return redirect()->back()->withErrors(['error' => '旅行計画の投稿に失敗しました: ' . $e->getMessage()]);
    }
}
    
    public function destroy($id)
{
    \DB::beginTransaction();
    
    try {
        // IDに対応する旅行計画を取得
        $planpost = Planpost::findOrFail($id);
        // 旅行計画自体を削除
        $planpost->delete();
        
        // トランザクションをコミット
        \DB::commit();
        
        // 成功メッセージと共にリダイレクト
        return redirect()->route('planposts.index')->with('success', '投稿した旅行計画が削除されました。');
    } catch (\Exception $e) {
        // トランザクションをロールバック
        \DB::rollBack();
        
        // エラーメッセージのログ (任意)
        \Log::error('旅行計画の削除中にエラーが発生: ' . $e->getMessage());
        
        // エラーメッセージを表示
        return redirect()->route('planposts.index')->with('error', '旅行計画の削除中にエラーが発生しました。');
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
