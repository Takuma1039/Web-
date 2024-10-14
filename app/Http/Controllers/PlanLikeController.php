<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Planpost;
use App\Models\PlanLike;
use Illuminate\Support\Facades\Log;

class PlanLikeController extends Controller
{
    public function likeplan(Request $request)
{
    $user_id = \Auth::id();
    if (!$user_id) {
        return response()->json(['error' => 'User not authenticated'], 403);
    }

    $planpost_id = $request->planpost_id;

    // いいねの状態を判定
    $alreadyLiked = PlanLike::where('user_id', $user_id)->where('planpost_id', $planpost_id)->exists();

    if (!$alreadyLiked) {
        // いいねを追加
        $like = new PlanLike();
        $like->planpost_id = $planpost_id;
        $like->user_id = $user_id;
        $like->save();
        $liked = true; // いいねした状態
    } else {
        // いいねを解除
        PlanLike::where('planpost_id', $planpost_id)->where('user_id', $user_id)->delete();
        $liked = false; // いいねを解除した状態
    }

    // いいね数を計算
    $planpost = Planpost::find($planpost_id);
    $likesCount = $planpost->likes->count();

    // いいねの状態といいね数をレスポンスに含める
    return response()->json(['likes_count' => $likesCount, 'liked' => !$alreadyLiked]);

}
}
