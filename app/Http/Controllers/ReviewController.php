<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Cloudinary;

class ReviewController extends Controller
{
    public function store(Request $request, $spotId)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:50',
            'comment' => 'required|string|max:500',
            'review' => 'required|numeric|between:1,5', // 1.0から5.0の評価を確認
            'is_anonymous' => 'nullable|boolean',
        ]);

        $review = new Review();
        $review->title = $validatedData['title'];
        $review->comment = $validatedData['comment'];
        $review->review = $validatedData['review'];
        $review->spot_id = $spotId;
        $review->user_id = auth()->id();
        
        // 匿名投稿のチェックボックスの値を保存
        $review->is_anonymous = $request->has('is_anonymous');

        $review->save();

        return redirect()->route('spots.show', $spotId)->with('success', '口コミが投稿されました。');
    }

    public function edit($id)
    {
        $review = Review::findOrFail($id);
        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:50',
            'comment' => 'required|string|max:500',
            'review' => 'required|numeric|between:1,5', // 1.0から5.0の評価を確認
            'is_anonymous' => 'nullable|boolean',
        ]);

        $review = Review::findOrFail($id);
        $spotId = $review->spot_id;

        // 更新するフィールドに匿名投稿のチェックを追加
        $review->update([
            'title' => $request->input('title'),
            'comment' => $request->input('comment'),
            'review' => $request->input('review'),
            'is_anonymous' => $request->has('is_anonymous') ? 1 : 0, // チェックされていない場合は0
        ]);

        return redirect()->route('spots.show', $spotId)->with('success', '口コミを更新しました。');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $spotId = $review->spot_id;
        $review->delete();

        return redirect()->route('spots.show', $spotId)->with('success', '口コミを削除しました。');
    }
}

