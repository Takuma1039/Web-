<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Spot;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    // 計画作成画面の表示
    public function create(Request $request)
    {
        // 現在のURLを取得
        $currentUrl = url()->current();
        $currentPageName = '旅行計画作成'; // 適切なページ名に変更

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        
        // ユーザーを取得
        $user = $request->user();

        // お気に入りスポットを取得
        $likedSpots = $user->spotlikes()->with('spot')->get()->pluck('spot');
        
        // Google Map APIキー
        $api_key = config('app.google_maps_api_key');
    
        return view('plans.create', compact('likedSpots', 'api_key'));
    }

    // 計画の保存処理
    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'start_date' => 'required|date',
        'destinations' => 'required|array',
        'destinations.*' => 'exists:spots,id', // 目的地のIDがspotsテーブルに存在するか確認
    ]);

    // 旅行計画を作成
    $plan = Plan::create([
        'user_id' => auth()->id(),
        'title' => $request->title,
        'start_date' => $request->start_date,
    ]);

    // 目的地をplan_destinationsテーブルに挿入
    foreach ($request->destinations as $index => $destinationId) {
        $plan->destinations()->attach($destinationId, ['order' => $index + 1]);
    }

    return redirect()->route('plans.index')->with('success', '旅行計画が作成されました。');
}


    // 計画の表示
    public function show($id)
    {
        $plan = Plan::findOrFail($id);
        return view('plans.show', compact('plan'));
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
