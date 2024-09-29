<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Models\Spot;
use App\Models\Spot_image;
use App\Models\Category;
use App\Models\Season;
use App\Models\Local;
use App\Models\Month;
use Cloudinary;

class SpotController extends Controller
{
    public function index()
    {
        $spots = Spot::orderBy('created_at', 'desc')->paginate(10);
        return view('Spot.index', ['spots' => $spots]);
    }

    public function show(Spot $spot)
    {
        $categoryIds = json_decode($spot->category_ids);
        $seasonIds = json_decode($spot->season_ids);
        $monthIds = json_decode($spot->month_ids);

        $categories = Category::whereIn('id', $categoryIds)->get();
        $seasons = Season::whereIn('id', $seasonIds)->get();
        $months = Month::whereIn('id', $monthIds)->get();

        $spotImages = Spot_image::where('spot_id', $spot->id)->get();

        return view("Spot.show")->with([
            'spot' => $spot,
            'spotImages' => $spotImages,
            'categories' => $categories,
            'seasons' => $seasons,
            'months' => $months,
        ]);
    }

    public function create()
    {
        $spotcategories = Category::all();
        $locals = Local::all();
        $seasons = Season::all();
        $months = Month::all();

        return view('Spot.create', compact('spotcategories', 'locals', 'seasons', 'months'));
    }

    public function store(ItemRequest $request, Spot $spot)
    {
        $images = $request->file('image');
        $input = $request['spot'];

        // local_idを単一の値に変更
        $input['local_id'] = $request->input('spot.local_id.0');

        // カテゴリー、シーズン、月のデータをJSONとして保存
        $input['category_ids'] = json_encode($request->input('spot.category_ids', []));
        $input['season_ids'] = json_encode($request->input('spot.season_id', []));
        $input['month_ids'] = json_encode($request->input('spot.month_id', []));

        try {
            if (!$spot->fill($input)->save()) {
                return redirect()->back()->withErrors('スポットの保存に失敗しました。');
            }

            // 中間テーブルへのカテゴリーの挿入
            $spot->spotcategories()->attach($request->input('spot.category_ids', []));
            $spot->seasons()->sync($request->input('spot.season_ids', []));
            $spot->months()->sync($request->input('spot.month_ids', []));
            
            // 画像データの保存
            foreach ($images as $image) {
                $uploadResult = Cloudinary::upload($image->getRealPath());
                $spot_image = new Spot_image();
                $spot_image->spot_id = $spot->id;
                $spot_image->image_path = $uploadResult->getSecurePath();
                $spot_image->public_id = $uploadResult->getPublicId(); // public_idを保存
                $spot_image->save();
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('エラーが発生しました: ' . $e->getMessage());
        }

        return redirect('/spots/' . $spot->id)->with('success', 'スポットが作成されました！');
    }

    public function edit(Spot $spot)
    {
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

    public function favorite(Request $request)
    {
        $user = Auth::user();
        $likedSpots = $user->spotlikes()->with('spot')->get()->pluck('spot');
    
        return view('Spot.favorite', compact('likedSpots'));
    }
}

