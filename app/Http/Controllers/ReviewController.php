<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ReviewImage;
use Cloudinary;

class ReviewController extends Controller
{
    public function store(Request $request, $spotId)
{
    $validatedData = $request->validate([
        'title' => 'required|max:50',
        'comment' => 'required|string|max:500',
        'review' => 'required|numeric|between:1,5',
        'images.*' => 'image|mimes:jpg,png,jpeg,gif|max:2048',
        'image_names.*' => 'nullable|string|max:255', // 画像名のバリデーション
        'is_anonymous' => 'nullable|boolean',
    ]);

    try {
        // 口コミの保存
        $review = new Review();
        $review->title = $validatedData['title'];
        $review->comment = $validatedData['comment'];
        $review->review = $validatedData['review'];
        $review->spot_id = $spotId;
        $review->user_id = auth()->id();
        $review->is_anonymous = $request->has('is_anonymous');
        $review->save();

        // Cloudinaryに画像をアップロードしてReview_imageに保存
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                try {
                    $uploadResult = Cloudinary::upload($image->getRealPath());
                    $reviewImage = new ReviewImage(); // Review_imageモデルを使用
                    $reviewImage->review_id = $review->id;
                    $reviewImage->image_path = $uploadResult->getSecurePath();
                    $reviewImage->name = $request->input('new_image_names')[$index] ?? 'Image'; // 名前が入力されていない場合、'Image'とする
                    $reviewImage->public_id = $uploadResult->getPublicId();
                    $reviewImage->save();
                } catch (\Exception $e) {
                    \Log::error('Error uploading image to Cloudinary: ' . $e->getMessage());
                }
            }
        }
        
        return redirect()->route('spots.show', $spotId)->with('success', '口コミが投稿されました。');
    } catch (\Exception $e) {
        \Log::error('Error storing review: ' . $e->getMessage());
        return redirect()->route('spots.show', $spotId)->with('error', '口コミ投稿中にエラーが発生しました。');
    }
}


    public function edit($id)
    {
        $review = Review::findOrFail($id);
        $reviewImages = $review->images; // 口コミに関連するスポットの画像を取得
        return view('reviews.edit', compact('review', 'reviewImages'));
    }


    public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'title' => 'required|max:50',
        'comment' => 'required|string|max:500',
        'review' => 'required|numeric|between:1,5',
        'images.*' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        'is_anonymous' => 'nullable|boolean',
        'image_names.*' => 'nullable|string|max:255', // 画像名のバリデーション
        'image_ids.*' => 'nullable|integer|exists:review_images,id' // 画像IDのバリデーション
    ]);

    $review = Review::findOrFail($id);
    $spotId = $review->spot_id;

    // 画像削除処理
    if ($request->has('remove_images')) {
        foreach ($request->remove_images as $removeImageId) {
            $reviewImage = ReviewImage::find($removeImageId);
            if ($reviewImage) {
                if (!empty($reviewImage->public_id)) {
                    try {
                        Cloudinary::destroy($reviewImage->public_id);
                    } catch (\Exception $e) {
                        \Log::error('Error deleting image from Cloudinary: ' . $e->getMessage());
                    }
                }
                $reviewImage->delete();
            }
        }
    }

    // 既存の画像名を更新
    if ($request->has('image_names') && $request->has('image_ids')) {
        foreach ($request->image_names as $index => $name) {
            if (isset($request->image_ids[$index])) {
                $reviewImage = ReviewImage::find($request->image_ids[$index]);
                if ($reviewImage) {
                    \Log::info("Updating image ID {$reviewImage->id} with name: $name");
                    $reviewImage->name = $name; // 画像名を更新
                    $reviewImage->save(); // 保存
                }
            } else {
                \Log::warning("Image ID does not exist for index $index");
            }
        }
    }

    // 新しい画像の処理
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $index => $image) {
            try {
                $uploadResult = Cloudinary::upload($image->getRealPath());
                $reviewImage = new ReviewImage();
                $reviewImage->review_id = $review->id;
                $reviewImage->image_path = $uploadResult->getSecurePath();
                $reviewImage->public_id = $uploadResult->getPublicId();
                $reviewImage->name = isset($request->image_names[$index]) ? $request->image_names[$index] : 'Image'; // デフォルト名 'Image'
                $reviewImage->save();
            } catch (\Exception $e) {
                \Log::error('Error uploading image to Cloudinary: ' . $e->getMessage());
            }
        }
    }

    // 口コミの更新
    $review->update([
        'title' => $validatedData['title'],
        'comment' => $validatedData['comment'],
        'review' => $validatedData['review'],
        'is_anonymous' => $request->has('is_anonymous') ? 1 : 0,
    ]);

    return redirect()->route('spots.show', $spotId)->with('success', '口コミを更新しました。');
}


    public function destroy($id)
{
    $review = Review::findOrFail($id);
    $spotId = $review->spot_id;

    // トランザクションの開始
    DB::beginTransaction();

    try {
        // 口コミに関連する画像も削除
        foreach ($review->images as $reviewImage) {
            if (!empty($reviewImage->public_id)) {
                try {
                    Cloudinary::destroy($reviewImage->public_id);
                } catch (\Exception $e) {
                    \Log::error('Error deleting image from Cloudinary: ' . $e->getMessage());
                }
            }
            $reviewImage->delete(); // データベース上の画像も削除
        }

        $review->delete(); // 口コミ自体の削除

        // トランザクションのコミット
        DB::commit();

        return redirect()->route('spots.show', $spotId)->with('success', '口コミを削除しました。');
    } catch (\Exception $e) {
        // 何か問題があった場合、トランザクションをロールバック
        DB::rollBack();

        \Log::error('Error deleting review or images: ' . $e->getMessage());

        return redirect()->route('spots.show', $spotId)->with('error', '口コミ削除中にエラーが発生しました。');
    }
}

}
