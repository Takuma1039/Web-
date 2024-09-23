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
        //jsのfetchメソッドでspotのidを送信しているため受け取ります。
        if (!$user_id) {
          return response()->json(['error' => 'User not authenticated'], 403);
        }
        $spot_id = $request->spot_id;
        // デバッグ用
        \Log::info('Request received: ', $request->all());
        //自身がいいね済みなのか判定します
        $alreadyLiked = Spotlike::where('user_id', $user_id)->where('spot_id', $spot_id)->first();

        if (!$alreadyLiked) {
        //こちらはいいねをしていない場合の処理です。つまり、post_likesテーブルに自身のid（user_id）といいねをしたspotのid（spot_id）を保存する処理になります。
            $like = new Spotlike();
            $like->spot_id = $spot_id;
            $like->user_id = $user_id;
            $like->save();
        } else {
            //すでにいいねをしていた場合は、以下のようにspotlikesテーブルからレコードを削除します。
            Spotlike::where('spot_id', $spot_id)->where('user_id', $user_id)->delete();
        }
        //ビューにそのspotのいいね数を渡すため、いいね数を計算しています。
        $spot = Spot::find($spot_id);
        $likesCount = $spot->likes->count();
        
        //ビューにいいね数を渡しています。名前は上記のlikesCountとなるため、フロントでlikesCountといった表記で受け取っているのがわかると思います。
        return response()->json(['likesCount' => $likesCount]);
    }
}
