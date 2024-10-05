<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\Spotlike;

class SpotlikeController extends Controller
{
    public function likespot(Request $request)
{
    $user_id = \Auth::id();
    if (!$user_id) {
        return response()->json(['error' => 'User not authenticated'], 403);
    }

    $spot_id = $request->spot_id;

    // いいねの状態を判定
    $alreadyLiked = Spotlike::where('user_id', $user_id)->where('spot_id', $spot_id)->exists();

    if (!$alreadyLiked) {
        // いいねを追加
        $like = new Spotlike();
        $like->spot_id = $spot_id;
        $like->user_id = $user_id;
        $like->save();
        $liked = true; // いいねした状態
    } else {
        // いいねを解除
        Spotlike::where('spot_id', $spot_id)->where('user_id', $user_id)->delete();
        $liked = false; // いいねを解除した状態
    }

    // いいね数を計算
    $spot = Spot::find($spot_id);
    $likesCount = $spot->likes->count();

    // いいねの状態といいね数をレスポンスに含める
    return response()->json(['likes_count' => $likesCount, 'liked' => !$alreadyLiked]);

}

}
