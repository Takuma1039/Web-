<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\Spot;
use App\Models\Category;
use App\Models\Season;
use App\Models\Local;
use App\Models\Month;
use Cloudinary;

class ReviewController extends Controller
{
    public function index(Request $request, Category $category, Local $local, Season $season, Month $month)
{
    // 現在のURLを取得
    $currentUrl = url()->current();
    $currentPageName = '口コミ投稿一覧';

    // 履歴の管理
    $this->updateHistory($request, $currentUrl, $currentPageName);

    // スポット情報の取得
    $spots = Spot::with('spotimages', 'reviews.user', 'reviews.images') // 口コミのユーザーと画像も一緒に取得
                 ->withCount('reviews') // review数を取得
                 ->orderBy('name', 'asc')
                 ->paginate(5);
    
    // 各スポットの概要を取得
    foreach ($spots as $spot) {
        $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 150);
        
        // 各スポットの総合評価を計算
        $spot->average_rating = $spot->reviews->avg('review') ?? 0; // 各スポットのレビューの平均を取得
    }

    return view('reviews.index')->with([
        'spots' => $spots,
        'spotcategories' => $category->get(),
        'locals' => $local->get(),
        'seasons' => $season->get(),
        'months' => $month->get(),
    ]);
}

    
    public function store(ReviewRequest $request, $spotId)
{
    \Log::info('Request Payload:', $request->all());
    \DB::beginTransaction();

    try {
        // 口コミの保存
        $review = new Review();
        $review->title = $request->title;
        $review->comment = $request->comment;
        $review->review = $request->review;
        $review->spot_id = $spotId;
        $review->user_id = auth()->id();
        $review->is_anonymous = $request->has('is_anonymous');
        $review->save();

        // Cloudinaryに画像をアップロードしてReviewImageに保存
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                try {
                    $uploadResult = Cloudinary::upload($image->getRealPath());
                    $reviewImage = new ReviewImage();
                    $reviewImage->review_id = $review->id;
                    $reviewImage->image_path = $uploadResult->getSecurePath();
                    $reviewImage->name = $request->input('new_image_names')[$index] ?? 'Image';
                    $reviewImage->public_id = $uploadResult->getPublicId();
                    $reviewImage->save();
                } catch (\Exception $e) {
                    \Log::error('Error uploading image to Cloudinary: ' . $e->getMessage());
                    throw $e; // トランザクション全体を中止するために例外を再スロー
                }
            }
        }

        \DB::commit();

        return redirect()->route('spots.show', $spotId)->with('success', '口コミが投稿されました。');
    } catch (\Exception $e) {
        \DB::rollBack();
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


    public function update(ReviewRequest $request, $id)
{
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
        'title' => $request->title,
        'comment' => $request->comment,
        'review' => $request->review,
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

// 履歴を更新するメソッド
    private function updateHistory(Request $request, $currentUrl, $currentPageName)
    {
        $history = $request->session()->get('history', []);

        // 同じURLが既に履歴に存在するか確認
        foreach ($history as $key => $item) {
            if ($item['url'] === $currentUrl) {
                // すでに存在する場合は、そのエントリを更新
                $history[$key]['name'] = $currentPageName; // 名前を更新
                $request->session()->put('history', $history); // 更新後にセッションに保存
                return; // 処理を終了
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
    
    public function truncateAtPunctuation($string, $maxLength)
    {
        if (mb_strlen($string) <= $maxLength) {
            return $string; // 文字数が上限を超えない場合はそのまま返す
        }

        // 最大長を超える部分を切り出す
        $truncated = mb_substr($string, 0, $maxLength);
    
        // 句読点を探す
        $lastPunctuation = mb_strrpos($truncated, '。');
        if ($lastPunctuation === false) {
            $lastPunctuation = mb_strrpos($truncated, '、');
        }

        // 最後の句読点が見つかった場合
        if ($lastPunctuation !== false) {
            return mb_substr($truncated, 0, $lastPunctuation + 1) . '...'; // 句読点まで含める
        }

        // 句読点が見つからない場合は、指定した文字数で切り捨てる
        return mb_substr($truncated, 0, $maxLength) . '...';
    }
}
