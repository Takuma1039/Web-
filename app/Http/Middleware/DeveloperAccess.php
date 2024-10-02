<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeveloperAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 開発者のメールアドレスやIDを確認します
        $developerEmails = ['quj7yegnfi63ys2hwfbn@docomo.ne.jp']; // 開発者のメールアドレスを配列に追加

        if (!in_array($request->user()->email, $developerEmails)) {
            abort(403, 'このページにアクセスする権限がありません。');
        }
        
        return $next($request);
    }
}
