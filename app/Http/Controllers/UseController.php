<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UseController extends Controller
{
    public function index(Request $request) {
        
        $currentUrl = url()->current();
        $currentPageName = '使い方';

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        
        return view('Use.index');
    }
    
    private function updateHistory(Request $request, $currentUrl, $currentPageName) {
        
        $history = $request->session()->get('history', []);

        // 同じURLが既に履歴に存在するか確認
        foreach ($history as $key => $item) {
            if ($item['url'] === $currentUrl) {
                // すでに存在する場合は、更新
                $history[$key]['name'] = $currentPageName; // 名前を更新
                $request->session()->put('history', $history); // 更新後にセッションに保存
                return;
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
