<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanpostController extends Controller
{
    public function index(Planpost $planpost){
        
        // 現在のURLを取得
        $currentUrl = url()->current();
        $currentPageName = '旅行計画投稿'; // 適切なページ名に変更

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        
        return view('planpost.index')->with(['planpost'=>$planpost]);
    }
    
    // 旅行計画投稿画面の表示
    public function create(Request $request)
    {
        // 現在のURLを取得
        $currentUrl = url()->current();
        $currentPageName = '旅行計画投稿'; // 適切なページ名に変更

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        
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
    
        return view('planposts.create', compact('plans'));
    }
    
    public function store(PlanRequest $request)
{
    //dd($request);
    try {
        // トランザクションの開始
        \DB::beginTransaction();
        
        // 旅行計画を作成
        $planpost = Planpost::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'comment' => $request->comment,
            'plan_id' => $request->$plan->id(),
            'season' => $request->season->id,
            'month' => $request->month->id,
            'local' => $request->local->id,
        ]);

        // トランザクションをコミット
        \DB::commit();
        
        return redirect()->route('plans.index')->with('success', '旅行計画が投稿されました。');
    } catch (\Exception $e) {
        // エラー発生時にトランザクションをロールバック
        \DB::rollBack();
        \Log::error('Plan creation failed', ['error' => $e->getMessage()]);
        return redirect()->back()->withErrors(['error' => '旅行計画の投稿に失敗しました: ' . $e->getMessage()]);
    }
}
    
    // 計画の表示
public function show(Request $request, $id)
{
    $plan = Plan::with('destinations')->find($id);
    
    // 現在のURLを取得
    $currentUrl = url()->current();
    $currentPageName = $plan->title; // 計画のタイトルをページ名に使用

    // 履歴の管理
    $this->updateHistory($request, $currentUrl, $currentPageName);
    
    // 開始日が文字列の場合、Carbonインスタンスに変換
    if (is_string($plan->start_date)) {
        $plan->start_date = Carbon::createFromFormat('Y-m-d', $plan->start_date);
    }
    
    if (is_string($plan->start_time)) {
        $plan->start_time = Carbon::createFromFormat('H:i:s', $plan->start_time);
    }
    
    // Google Map APIキー
    $api_key = config('app.google_maps_api_key');
        
    // Navitime APIキー
    $apikey = config('app.navitime_api_key');
        
    return view('plans.show', compact('plan', 'api_key', 'apikey'));
}

    
    public function destroy($id)
{
    \DB::beginTransaction();
    
    try {
        // IDに対応する旅行計画を取得
        $plan = Plan::findOrFail($id);
        
        // リレーションを解除 (目的地)
        $plan->destinations()->detach();
        
        // 旅行計画自体を削除
        $plan->delete();
        
        // トランザクションをコミット
        \DB::commit();
        
        // 成功メッセージと共にリダイレクト
        return redirect()->route('plans.index')->with('success', '旅行計画が削除されました。');
    } catch (\Exception $e) {
        // トランザクションをロールバック
        \DB::rollBack();
        
        // エラーメッセージのログ (任意)
        \Log::error('旅行計画の削除中にエラーが発生: ' . $e->getMessage());
        
        // エラーメッセージを表示
        return redirect()->route('plans.index')->with('error', '旅行計画の削除中にエラーが発生しました。');
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
}
