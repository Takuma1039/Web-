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
        // 現在のURLを取得
        $currentUrl = url()->current();
        $currentPageName = 'スポット一覧'; // 適切なページ名に変更

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);

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

        // 口コミと関連する画像を一括取得
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
        
        // カテゴリ、地域、シーズン、月のデータを取得
        $spotcategories = Category::all(); 
        $locals = Local::all();                 
        $seasons = Season::all();               
        $months = Month::all();                

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
    public function edit(Request $request, Spot $spot)
    {
        $currentUrl = url()->current();
        $currentPageName = 'スポット編集';
        
        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
    
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
    
        // ユーザーを取得
        $user = $request->user();

        // お気に入りスポットを取得
        $likedSpots = $user->spotlikes()->with('spot')->get()->pluck('spot');

        return view('Spot.favorite', compact('likedSpots'));
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
    
    public function search(Request $request, Category $category, Local $local, Season $season, Month $month)
    {
        // 検索キーワード
        $query = $request->input('query');

        // 各フィルタ条件
        $filters = [
            'spotCategoryIds' => $request->input('spot.spot_category_ids', []),
            'localIds' => $request->input('spot.local_ids', []),
            'seasonIds' => $request->input('spot.season_ids', []),
            'monthIds' => $request->input('spot.month_ids', []),
        ];
    
        // 検索条件を文字列に変換
        $searchConditions = [];

        if ($query) {
            $searchConditions[] = "キーワード: " . $query;
        }

        if (!empty($filters['spotCategoryIds'])) {
            $categoryNames = Category::whereIn('id', $filters['spotCategoryIds'])->pluck('name')->toArray();
            $searchConditions[] = "カテゴリー: " . implode(', ', $categoryNames);
        }

        if (!empty($filters['localIds'])) {
            $localNames = Local::whereIn('id', $filters['localIds'])->pluck('name')->toArray();
            $searchConditions[] = "地域: " . implode(', ', $localNames);
        }

        if (!empty($filters['seasonIds'])) {
            $seasonNames = Season::whereIn('id', $filters['seasonIds'])->pluck('name')->toArray();
            $searchConditions[] = "季節: " . implode(', ', $seasonNames);
        }

        if (!empty($filters['monthIds'])) {
            $monthNames = Month::whereIn('id', $filters['monthIds'])->pluck('name')->toArray();
            $searchConditions[] = "月: " . implode(', ', $monthNames);
        }

        // 検索条件を結合
        $currentPageName = '検索結果(' . implode(' / ', $searchConditions) . ')';

        // URLを変更する
        $currentUrl = url()->current() . '?' . http_build_query(array_filter([
            'query' => $query,
            'spot[spot_category_ids]' => $filters['spotCategoryIds'],
            'spot[local_ids]' => $filters['localIds'],
            'spot[season_ids]' => $filters['seasonIds'],
            'spot[month_ids]' => $filters['monthIds'],
        ]));
    
        // 履歴管理の関数を呼び出す
        $this->manageHistory($request, $currentUrl, $currentPageName, $filters);
    
        // 検索クエリの基本
        $spots = Spot::query();
        
        // 検索キーワードが存在する場合
        if ($query) {
            $spots->where(function($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%')
                  ->orWhere('address', 'LIKE', '%' . $query . '%')
                  ->orWhereHas('spotcategories', function($q) use ($query) {
                        $q->where('name', 'LIKE', '%' . $query . '%');
                    })
                  ->orWhereHas('local', function($q) use ($query) {
                        $q->where('name', 'LIKE', '%' . $query . '%');
                    })
                  ->orWhereHas('seasons', function($q) use ($query) {
                        $q->where('name', 'LIKE', '%' . $query . '%');
                    })
                  ->orWhereHas('months', function($q) use ($query) {
                        $q->where('name', 'LIKE', '%' . $query . '%');
                    });
            });
        }

        // フィルタリングの適用
        $relationships = [
            'spotCategoryIds' => 'spotcategories',
            'localIds' => 'local',
            'seasonIds' => 'seasons',
            'monthIds' => 'months',
        ];

        foreach ($filters as $key => $ids) {
            if (!empty($ids)) {
                $spots->whereHas($relationships[$key], function($q) use ($ids) {
                    $q->whereIn('id', $ids);
                });
            }
        }

        // 検索結果をページネーションで取得（1ページに12件表示）
        $results = $spots->paginate(12);

        return view('Spot.search_results')->with([
            'results' => $results,
            'spotcategories' => $category->get(),
            'locals' => $local->get(),
            'seasons' => $season->get(),
            'months' => $month->get(),
            'query' => $query,
            'spotCategoryIds' => $filters['spotCategoryIds'], 
            'localIds' => $filters['localIds'],  
            'seasonIds' => $filters['seasonIds'], 
            'monthIds' => $filters['monthIds'],  
        ]);
    }
    
    private function manageHistory(Request $request, $currentUrl, $currentPageName, $filters)
    {
        // 履歴の管理
        $history = $request->session()->get('history', []);

        // ユニークな履歴IDを作成
        $historyId = md5($currentUrl . json_encode($filters));

        // 履歴を確認して更新または追加
        $found = false; // 履歴が見つかったかどうかを示すフラグを初期化

        foreach ($history as &$item) {
            // IDが一致するかを確認
            if (isset($item['id']) && $item['id'] === $historyId) {
                $item['name'] = $currentPageName; // 名前を更新
                $found = true; // 履歴が見つかったのでフラグを更新
                return;
            }
        }

        // 最大履歴数を設定（例: 5件まで）
        if (count($history) >= 5) {
            array_shift($history); // 古い履歴を削除
        }

        // URLが新しい場合は履歴に追加
        if (!$found) {
            $history[] = [
                'id' => $historyId,
                'url' => $currentUrl,
                'name' => $currentPageName,
            ];
        }

        // セッションに履歴を保存
        $request->session()->put('history', $history);
    }
}

