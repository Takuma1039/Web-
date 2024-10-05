<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReviewLike;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewLikeController extends Controller
{
    public function likeReview(Request $request)
    {
        // リクエストのバリデーション
        $request->validate([
            'review_id' => 'required|exists:reviews,id',
        ]);

        try {
            // リクエストからreview_idを取得
            $reviewId = $request->input('review_id');

            // いいねがすでに存在するか確認
            $like = ReviewLike::where('user_id', Auth::id())
                ->where('review_id', $reviewId)
                ->first();

            // いいねの追加または削除
            if ($like) {
                // いいねを削除
                $like->delete();
                $liked = false; // いいね解除
            } else {
                // 新しいいいねを作成
                ReviewLike::create([
                    'user_id' => Auth::id(),
                    'review_id' => $reviewId,
                ]);
                $liked = true; // いいね追加
            }

            // 現在のいいねの数を取得
            $likeCount = ReviewLike::where('review_id', $reviewId)->count();

            return response()->json(['liked' => $liked, 'likeCount' => $likeCount]);
        } catch (\Exception $e) {
            // エラーログを出力
            \Log::error('Error liking review: '.$e->getMessage());

            // 500エラーを返す
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}

