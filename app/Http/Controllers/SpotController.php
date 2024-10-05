<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Models\Spot;
use App\Models\Spot_image;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\Category;
use App\Models\Season;
use App\Models\Local;
use App\Models\Month;
use Cloudinary;
use Illuminate\Support\Facades\DB;

class SpotController extends Controller
{
    //スポット一覧
    public function index(Request $request, Category $category, Local $local, Season $season, Month $month)
    {
        //dd($request->session()->get('history'));
        // 現在のURLを取得
        $currentUrl = url()->current();
        $currentPageName = 'スポット一覧'; // 適切なページ名に変更

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        // 履歴を取得
        $history = $request->session()->get('history', []);
        
        // 最新のページがすでに履歴にある場合は更新
        if (count($history) > 0 && end($history)['url'] == $currentUrl) {
            return view('Spot.index', [
                'spots' => Spot::with('spotimages')
                    ->withCount('likes')
                    ->orderBy('name', 'asc')
                    ->paginate(10),
                'spotcategories' => $category->get(),
                'locals' => $local->get(),
                'seasons' => $season->get(),
                'months' => $month->get(),
            ]); // 履歴が同じ場合はビューを返すだけ
        }

        // スポット情報の取得
        $spots = Spot::with('spotimages') // 関連する画像を一緒に取得
                     ->withCount('likes') // いいねの数を取得
                     ->orderBy('name', 'asc')
                     ->paginate(10);
        
        foreach ($spots as $spot) {
            $spot->truncated_body = $this->truncateAtPunctuation($spot->body, 150);
        }
        
        return view('Spot.index')->with([
            'spots' => $spots,
            'spotcategories' => $category->get(),
            'locals' => $local->get(),
            'seasons' => $season->get(),
            'months' => $month->get(),
        ]);
    }
    
    //スポットの詳細画面
    public function show(Request $request, Spot $spot)
{
    $currentUrl = url()->current();
    $currentPageName = $spot->name;
    
    // リレーションを使用してカテゴリ、シーズン、月を取得
    $categories = $spot->spotcategories;
    $seasons = $spot->seasons;
    $months = $spot->months;

    // スポット画像を取得
    $spotImages = $spot->spotimages;

    // 口コミと関連する画像を一括取得 (Eager Loading)
    $reviews = $spot->reviews()->with('user', 'images')->get();

    // 口コミ画像の構築
    $reviewImages = $reviews->mapWithKeys(function ($review) {
        return [$review->id => $review->images];
    });

    // 総合評価の計算
    $averageRating = $reviews->avg('review') ?? 0;

    // Google Map APIキー
    $api_key = config('app.google_maps_api_key');
    
    // 履歴の管理
    $this->updateHistory($request, $currentUrl, $currentPageName);
    // 履歴を取得
    $history = $request->session()->get('history', []);
    
    // 同じURLが連続して追加されないようにする
    if (count($history) > 0 && end($history)['url'] == $currentUrl) {
        return view('Spot.show', compact(
            'spot', 'spotImages', 'categories', 
            'seasons', 'months', 'reviews', 'reviewImages', 
            'api_key', 'averageRating'
        ));
    }

    // ビューにデータを渡す
    return view('Spot.show', compact(
        'spot', 'spotImages', 'categories', 
        'seasons', 'months', 'reviews', 'reviewImages', 
        'api_key', 'averageRating'
    ));
}

    
    //スポット作成
    public function create(Request $request)
{
    $currentUrl = url()->current();
    $currentPageName = 'スポット作成';

    // 履歴の管理
    $this->updateHistory($request, $currentUrl, $currentPageName);
    // 履歴を取得
    $history = $request->session()->get('history', []);
    
    // 最新のページがすでに履歴にある場合は更新
    if (count($history) > 0 && end($history)['url'] == $currentUrl) {
        return view('Spot.create', [
            'spotcategories' => Category::all(),
            'locals' => Local::all(),
            'seasons' => Season::all(),
            'months' => Month::all(),
        ]); // 履歴が同じ場合はビューを返すだけ
    }

    return view('Spot.create', compact('spotcategories', 'locals', 'seasons', 'months'));
}

    //スポット保存
    public function store(ItemRequest $request, Spot $spot)
{
    $images = $request->file('images');
    if (is_null($images) || !is_array($images)) {
        return redirect()->back()->withErrors('画像が選択されていません。');
    }

    $input = $request['spot'];
    $localIds = $request->input('spot.local_id', []);
    $input['local_id'] = !empty($localIds) ? $localIds[0] : null;

    // JSONとして保存
    $input['category_ids'] = json_encode($request->input('spot.category_ids', []));
    $input['season_ids'] = json_encode($request->input('spot.season_ids', []));
    $input['month_ids'] = json_encode($request->input('spot.month_ids', []));

    try {
        DB::transaction(function () use ($spot, $input, $request, $images) {
            if (!$spot->fill($input)->save()) {
                throw new \Exception('スポットの保存に失敗しました。');
            }

            // 中間テーブルへの挿入
            $spot->spotcategories()->attach($request->input('spot.category_ids', []));
            $spot->seasons()->sync($request->input('spot.season_ids', []));
            $spot->months()->sync($request->input('spot.month_ids', []));

            // 画像データの保存
            foreach ($images as $image) {
                try {
                    $uploadResult = Cloudinary::upload($image->getRealPath());
                    $spot_image = new Spot_image();
                    $spot_image->spot_id = $spot->id;
                    $spot_image->image_path = $uploadResult->getSecurePath();
                    $spot_image->public_id = $uploadResult->getPublicId();
                    $spot_image->save();
                } catch (\Exception $e) {
                    throw new \Exception('画像のアップロードに失敗しました: ' . $e->getMessage());
                }
            }
        });
    } catch (\Exception $e) {
        return redirect()->back()->withErrors('エラーが発生しました: ' . $e->getMessage());
    }

    return redirect('/spots/' . $spot->id)->with('success', 'スポットが作成されました！');
}

    //スポット編集
    public function edit(Spot $spot)
    {
        $currentUrl = url()->current();
        $currentPageName = 'スポット作成';
        
        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        // 履歴を取得
        $history = $request->session()->get('history', []);
        
        // 最新のページがすでに履歴にある場合は更新
    if (count($history) > 0 && end($history)['url'] == $currentUrl) {
        return view('Spot.edit', compact('spot', 'spotcategories', 'locals', 'seasons', 'months', 'categoryIds', 'seasonIds', 'monthIds', 'localIds', 'spotImages')); // 履歴が同じ場合はビューを返すだけ
    }
    
        $categoryIds = json_decode($spot->category_ids, true) ?? [];
        $seasonIds = json_decode($spot->season_ids, true) ?? [];
        $monthIds = json_decode($spot->month_ids, true) ?? [];
        $localIds = (array) $spot->local_id; 
        $spotImages = Spot_image::where('spot_id', $spot->id)->get();
        $spotcategories = Category::all();
        $locals = Local::all();
        $seasons = Season::all();
        $months = Month::all();

        return view('Spot.edit', compact('spot', 'spotcategories', 'locals', 'seasons', 'months', 'categoryIds', 'seasonIds', 'monthIds', 'localIds', 'spotImages'));
    }
    //スポット更新
    public function update(ItemRequest $request, Spot $spot)
    {
        \Log::info('Update method called for spot ID: ' . $spot->id);
        $images = $request->file('image');
        $input = $request['spot'];
        $input['local_id'] = $request->input('spot.local_id.0');

        $input['category_ids'] = json_encode($request->input('spot.category_ids', [])); 
        $input['season_ids'] = json_encode($request->input('spot.season_ids', []));
        $input['month_ids'] = json_encode($request->input('spot.month_ids', []));

        try {
            $spot->update($input);

            // 中間テーブルのカテゴリーの更新
            $spot->spotcategories()->sync($request->input('spot.category_ids', []));
            $spot->seasons()->sync($request->input('spot.season_ids', []));
            $spot->months()->sync($request->input('spot.month_ids', []));
            
            // 画像削除処理
            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $removeImageId) {
                    $spotImage = Spot_image::find($removeImageId);
                    if ($spotImage) {
                        // Cloudinaryから画像を削除
                        if (!empty($spotImage->public_id)) {
                            try {
                                Cloudinary::destroy($spotImage->public_id);
                            } catch (\Exception $e) {
                                \Log::error('Error deleting image from Cloudinary: ' . $e->getMessage());
                            }
                        } else {
                            \Log::warning('Public ID is missing for image ID: ' . $spotImage->id);
                        }
                        $spotImage->delete();
                    }
                }
            }

            // 新しい画像の処理
            if ($images) {
                foreach ($images as $image) {
                    try {
                        $uploadResult = Cloudinary::upload($image->getRealPath());
                        $spot_image = new Spot_image();
                        $spot_image->spot_id = $spot->id;
                        $spot_image->image_path = $uploadResult->getSecurePath();
                        $spot_image->public_id = $uploadResult->getPublicId(); // public_idを保存
                        $spot_image->save();
                    } catch (\Exception $e) {
                        \Log::error('Error uploading image to Cloudinary: ' . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error updating spot: ' . $e->getMessage());
            return redirect()->back()->withErrors('エラーが発生しました: ' . $e->getMessage());
        }

        return redirect()->route('spots.show', $spot->id)->with('success', 'スポットが更新されました！');
    }
    
    // お気に入りスポット一覧
public function favorite(Request $request)
{
    // 現在のURLを取得
    $currentUrl = url()->current();
    $currentPageName = 'お気に入りスポット一覧'; // 適切なページ名に変更

    // 履歴の管理
    $this->updateHistory($request, $currentUrl, $currentPageName);
    // 履歴を取得
    $history = $request->session()->get('history', []);
    
    // ユーザーを取得
    $user = $request->user();
    
    // 最新のページがすでに履歴にある場合は更新
    if (count($history) > 0 && end($history)['url'] == $currentUrl) {
        return view('Spot.favorite', [
            'likedSpots' => $user->spotlikes()->with('spot')->get()->pluck('spot'),
        ]); // 履歴が同じ場合はビューを返すだけ
    }

    // お気に入りスポットを取得
    $likedSpots = $user->spotlikes()->with('spot')->get()->pluck('spot');

    return view('Spot.favorite', compact('likedSpots'));
}

// 履歴を更新するメソッド
private function updateHistory(Request $request, $currentUrl, $currentPageName)
{
    $history = $request->session()->get('history', []);
    
    // 古い履歴を削除するロジック
    if (count($history) >= 5) {
        array_shift($history); // 最初の履歴を削除
    }

    // 同じURLが連続して追加されないようにする
    if (count($history) > 0 && end($history)['url'] == $currentUrl) {
        $request->session()->put('history', $history); // 更新後にセッションに保存
        return;
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

