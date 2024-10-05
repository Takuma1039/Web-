<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryController extends Controller
{
    // 履歴を削除
    public function clearHistory(Request $request)
    {
        // セッションから履歴を削除
        $request->session()->forget('history');

        return redirect()->back()->with('success', '履歴が削除されました！');
    }
}
