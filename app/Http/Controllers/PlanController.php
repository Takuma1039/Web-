<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Plan;
use App\Models\Spot;
use App\Models\Planpost;
use Illuminate\Http\Request;
use App\Http\Requests\PlanRequest;
use Carbon\Carbon;

class PlanController extends Controller
{
    // 計画一覧の表示
    public function index(Request $request) {
        
        $currentUrl = url()->current();
        $currentPageName = '旅行計画一覧';

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);

        $user = $request->user();

        // ユーザーの旅行計画を取得（目的地もリレーションで一緒に取得）
        $plans = $user->plans()->with('destinations')->get();
        
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

        return view('plans.index', compact('plans'));
    }
    
    // 計画作成画面の表示
    public function create(Request $request) {
        
        $currentUrl = url()->current();
        $currentPageName = '旅行計画作成';

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        
        $user = $request->user();

        // お気に入りスポットを取得
        $likedSpots = $user->spotlikes()->with('spot:id,name,lat,long')->get()->pluck('spot');
    
        return view('plans.create', compact('likedSpots'));
    }

    // 計画の保存処理
    public function store(PlanRequest $request) {
    
        try {
            // トランザクションの開始
            \DB::beginTransaction();
        
            // 旅行計画を作成
            $plan = Plan::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'start_date' => $request->start_date,
                'start_time' => $request->start_time,
            ]);

            // 目的地を plan_destinations テーブルに挿入
            foreach ($request->destinations as $index => $destinationId) {
                $plan->destinations()->attach($destinationId, ['order' => $index + 1]);
            }

            // トランザクションをコミット
            \DB::commit();
        
            return redirect()->route('plans.index')->with('success', '旅行計画が作成されました。');
        } catch (\Exception $e) {
            // エラー発生時にトランザクションをロールバック
            \DB::rollBack();
            return redirect()->back()->withErrors(['error' => '旅行計画の作成に失敗しました: ' . $e->getMessage()]);
        }
    }

    // 計画の表示
    public function show(Request $request, Planpost $planpost, $id) {
        
        $plan = Plan::with('destinations')->find($id);
    
        $planpost = Planpost::where('plan_id', $id)->first();
    
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
        
        return view('plans.show', compact('plan', 'api_key', 'apikey', 'planpost'));
    }
    
    public function edit(Request $request, Plan $plan) {
        
        $currentUrl = url()->current();
        $currentPageName = '旅行計画の編集';
        
        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        
        $user = $request->user();

        // お気に入りスポットを取得
        $likedSpots = $user->spotlikes()->with('spot:id,name,lat,long')->get()->pluck('spot');
    
        if (is_string($plan->start_date)) {
            $plan->start_date = Carbon::createFromFormat('Y-m-d', $plan->start_date);
        }
            
        if (is_string($plan->start_time)) {
            $plan->start_time = Carbon::createFromFormat('H:i:s', $plan->start_time);
        }

        return view('plans.edit', compact('plan', 'likedSpots'));
    }
    
    public function update(Request $request, Plan $plan) {
        
        $input = $request['plan'];

        try {
            $plan->update($input);
            \DB::beginTransaction();
            
            foreach ($request->destinations as $index => $destinationId) {
                $syncData[$destinationId] = ['order' => $index + 1];
            }
            $plan->destinations()->sync($syncData);
            
            \DB::commit();
            
        } catch (\Exception $e) {
            \Log::error('Error updating plan: ' . $e->getMessage());
            \DB::rollBack();
            return redirect()->back()->withErrors('エラーが発生しました: ' . $e->getMessage());
        }

        return redirect()->route('plans.index', $plan->id)->with('success', '旅行計画が更新されました！');
    }
    
    public function updateMemo(Request $request, $id) {
        
        $request->validate([
            'memo' => 'nullable|string|max:1000',
        ]);

        $plan = Plan::findOrFail($id);
        $plan->memo = $request->input('memo');
        $plan->save();

        return response()->json(['message' => 'メモが更新されました']);
    }
    
    public function destroy($id) {
        
        \DB::beginTransaction();
    
        try {
            
            $plan = Plan::with('destinations')->find($id);
            
            // 関連する目的地を削除
            $plan->destinations()->detach();
            
            $plan->delete();
            // トランザクションをコミット
            \DB::commit();
        
            // 成功メッセージと共にリダイレクト
            return redirect()->route('plans.index')->with('success', '旅行計画が削除されました。');
        } catch (\Exception $e) {
            // トランザクションをロールバック
            \DB::rollBack();
            // エラーメッセージを表示
            return redirect()->route('plans.index')->with('error', '旅行計画の削除中にエラーが発生しました。');
        }
    }

    // 履歴を更新するメソッド
    private function updateHistory(Request $request, $currentUrl, $currentPageName) {
        
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
