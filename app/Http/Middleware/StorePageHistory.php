<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class StorePageHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 現在のページURLを取得
        $currentUrl = $request->fullUrl();

        // セッションから履歴を取得（なければ空の配列）
        $pageHistory = Session::get('page_history', []);

        // 履歴に現在のページが含まれていなければ追加
        if (!in_array($currentUrl, $pageHistory)) {
            // 最新のページを配列に追加（最大5件保持）
            $pageHistory[] = $currentUrl;
            if (count($pageHistory) > 5) {
                array_shift($pageHistory); // 古い履歴を削除
            }

            // セッションに履歴を保存
            Session::put('page_history', $pageHistory);
        }

        // リクエストを次の処理に渡す
        return $next($request);
    }
}

