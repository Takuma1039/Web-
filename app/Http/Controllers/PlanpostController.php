<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PlanpostRequest;
use App\Http\Requests\PlanRequest;
use App\Models\Season;
use App\Models\Local;
use App\Models\Month;
use App\Models\Plantype;
use App\Models\Planpost;
use App\Models\Plan;
use App\Models\PlanImage;
use Cloudinary;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PlanpostController extends Controller {
    
    public function index(Request $request, Plantype $plantype, Local $local, Season $season, Month $month) {
        
        // 現在のURLを取得
        $currentUrl = url()->current();
        $currentPageName = 'みんなの旅行計画';

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        
        $planposts = Planpost::with('planimages')->paginate(10);
        
        return view('planposts.index')->with([
            'planposts' => $planposts,
            'plantypes' => $plantype->get(),
            'locals' => $local->get(),
            'seasons' => $season->get(),
            'months' => $month->get(),
        ]);
    }
    
    public function likesplan(Request $request) {
        
        // 現在のURLを取得
        $currentUrl = url()->current();
        $currentPageName = 'いいねした旅行計画一覧';

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        
        $user = $request->user();
        
        $planposts = $user->planlikes()->with('planpost.planimages', 'planpost.plan')->paginate(10);
        
        foreach ($planposts as $planpost) {
            $planpost->title = $planpost->planpost->title;
            $planpost->comment = $this->truncateAtPunctuation($planpost->planpost->comment, 150);
            
            if (is_string($planpost->planpost->plan->start_date)) {
            
                $planpost->start_date = Carbon::createFromFormat('Y-m-d', $planpost->planpost->plan->start_date);
            }
            
            if (is_string($planpost->planpost->plan->start_time)) {
                $planpost->start_time = Carbon::createFromFormat('H:i:s', $planpost->planpost->plan->start_time);
            }
        }
        
        return view('planposts.likesplan')->with(['planposts'=>$planposts]);
    }
    
    public function create(Request $request) {
        // 現在のURLを取得
        $currentUrl = url()->current();
        $currentPageName = '旅行計画投稿';

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        
        $locals = Local::all();                 
        $seasons = Season::all();               
        $months = Month::all(); 
        $plantypes = Plantype::all();
        
        $user = $request->user();

        // 作成した旅行計画を取得
        $plans = $user->plans()->get();
        
        foreach ($plans as $plan) {
            
            if (is_string($plan->start_date)) {
                
                $plan->start_date = Carbon::createFromFormat('Y-m-d', $plan->start_date);
            }
            
            if (is_string($plan->start_time)) {
                $plan->start_time = Carbon::createFromFormat('H:i:s', $plan->start_time);
            }
        }
    
        return view('planposts.create', compact('plans', 'locals', 'seasons', 'months', 'plantypes'));
    }
    
    public function store(PlanpostRequest $request, Planpost $planpost) {
        
        $images = $request->file('images');
        if (is_null($images) || !is_array($images)) {
            return redirect()->back()->withErrors('画像が選択されていません。');
        }
    
        $input = $request['planpost'];
        $input['local_id'] = $request->input('planpost.local_id');
        $input['season_id'] = $request->input('planpost.season_id');
        $input['month_id'] = $request->input('planpost.month_id');
        $input['plantype_id'] = $request->input('planpost.plantype_id');
        $input['user_id'] = auth()->id();
        $input['plan_id'] = $request->input('planpost.plan_id');
        $input['is_anonymous'] = $request->input('planpost.is_anonymous');
    
        try {
            // トランザクションの開始
            \DB::beginTransaction();
        
            $planpost = new Planpost();
            if (!$planpost->fill($input)->save()) {
                throw new \Exception('旅行計画の投稿に失敗しました。');
            }
        
            foreach ($images as $image) {
                try {
                    $uploadResult = Cloudinary::upload($image->getRealPath());
                    $spot_image = new PlanImage();
                    $spot_image->planpost_id = $planpost->id;
                    $spot_image->image_path = $uploadResult->getSecurePath();
                    $spot_image->public_id = $uploadResult->getPublicId();
                    $spot_image->save();
                } catch (\Exception $e) {
                    throw new \Exception('画像のアップロードに失敗しました: ' . $e->getMessage());
                }
            }

            \DB::commit();
        
            return redirect()->route('planposts.index')->with('success', '旅行計画が投稿されました。');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Plan creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => '旅行計画の投稿に失敗しました: ' . $e->getMessage()]);
        }
    }
    
    public function destroy($id) {
        
        \DB::beginTransaction();
    
        try {
            // IDに対応する旅行計画を取得
            $planpost = Planpost::findOrFail($id);
            // 旅行計画を削除
            $planpost->delete();
        
            \DB::commit();
        
            return redirect()->route('planposts.index')->with('success', '投稿した旅行計画が削除されました。');
        } catch (\Exception $e) {
    
            \DB::rollBack();
        
            \Log::error('旅行計画の削除中にエラーが発生: ' . $e->getMessage());
        
            // エラーメッセージを表示
            return redirect()->route('planposts.index')->with('error', '旅行計画の削除中にエラーが発生しました。');
        }
    }
    
    public function addPlan(Request $request, $id) {
        
        // 現在のURLを取得
        $currentUrl = url()->current();
        $currentPageName = '旅行計画のリメイク';

        // 履歴の管理
        $this->updateHistory($request, $currentUrl, $currentPageName);
        
        $user = $request->user();
        
        $planpost = Plan::with('destinations')->find($id);
        //dd($planpost);
        if (is_string($planpost->start_date)) {
                
            $planpost->start_date = Carbon::createFromFormat('Y-m-d', $planpost->start_date);
        }
            
        if (is_string($planpost->start_time)) {
            $planpost->start_time = Carbon::createFromFormat('H:i:s', $planpost->start_time);
        }
            
        $likedSpots = $user->spotlikes()->with('spot:id,name,lat,long')->get()->pluck('spot');
        
        return view('planposts.createmyplan', compact('planpost', 'likedSpots'));
    }
    
    public function planstore(PlanRequest $request) {
    
        try {
            // トランザクションの開始
            \DB::beginTransaction();
        
            // 旅行計画を作成
            $plan = Plan::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'start_date' => $request->start_date,
                'start_time' => $request->start_time,
            ]);

            // 目的地を plan_destinations テーブルに挿入
            foreach ($request->destinations as $index => $destinationId) {
                $plan->destinations()->attach($destinationId, ['order' => $index + 1]);
            }

            // トランザクションをコミット
            \DB::commit();
        
            return redirect()->route('plans.index')->with('success', '旅行計画が作成されました。');
        } catch (\Exception $e) {
            // エラー発生時にトランザクションをロールバック
            \DB::rollBack();
            \Log::error('Plan creation failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => '旅行計画の作成に失敗しました: ' . $e->getMessage()]);
        }
    }

    public function search(Request $request, Plantype $plantype, Local $local, Season $season, Month $month) {
        
        $query = $request->input('query'); //検索したキーワードの取得

        // 検索に使用するフィルター条件を取得
        $filters = [
            'plantypeIds' => $request->input('planpost.plantype_ids', []),
            'localIds' => $request->input('planpost.local_ids', []),
            'seasonIds' => $request->input('planpost.season_ids', []),
            'monthIds' => $request->input('planpost.month_ids', []),
        ];
    
        // 履歴表示用に検索条件を文字列に変換
        $searchConditions = [];

        if ($query) {
            $searchConditions[] = "キーワード: " . $query;
        }

        if (!empty($filters['plantypeIds'])) {
            $plantypeNames = Plantype::whereIn('id', $filters['plantypeIds'])->pluck('name')->toArray();
            $searchConditions[] = "カテゴリー: " . implode(', ', $plantypeNames);
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
            'planpost[plantype_ids]' => $filters['plantypeIds'],
            'planpost[local_ids]' => $filters['localIds'],
            'planpost[season_ids]' => $filters['seasonIds'],
            'planpost[month_ids]' => $filters['monthIds'],
        ]));
    
        // 履歴の管理
        $this->manageHistory($request, $currentUrl, $currentPageName, $filters);
    
        // 旅行計画を取得するためのクエリビルダーを初期化
        $planposts = Planpost::query();
        
        // 検索キーワードが存在する場合(名前、カテゴリー、地域、シーズン、月に存在するキーワード)
        if ($query) {
            $planposts->where(function($q) use ($query) {
                $q->where('title', 'LIKE', '%' . $query . '%')
                  ->orWhere('comment', 'LIKE', '%' . $query . '%')
                  ->orWhereHas('plantype', function($q) use ($query) {
                        $q->where('name', 'LIKE', '%' . $query . '%');
                    })
                  ->orWhereHas('local', function($q) use ($query) {
                        $q->where('name', 'LIKE', '%' . $query . '%');
                    })
                  ->orWhereHas('season', function($q) use ($query) {
                        $q->where('name', 'LIKE', '%' . $query . '%');
                    })
                  ->orWhereHas('month', function($q) use ($query) {
                        $q->where('name', 'LIKE', '%' . $query . '%');
                    });
            });
        }

        // キーワード以外の場合フィルタリングの適用
        $categories = [
            'plantypeIds' => 'plantype',
            'localIds' => 'local',
            'seasonIds' => 'season',
            'monthIds' => 'month',
        ];
        
        //フィルタリングの適用
        foreach ($filters as $key => $ids) {
            if (!empty($ids)) {
                $planposts->whereHas($categories[$key], function($q) use ($ids) {
                    $q->whereIn('id', $ids);
                });
            }
        }

        // 検索結果をページネーションで取得
        $results = $planposts->paginate(10);

        return view('planposts.search_results')->with([
            'results' => $results,
            'plantypes' => $plantype->get(),
            'locals' => $local->get(),
            'seasons' => $season->get(),
            'months' => $month->get(),
            'query' => $query,
            'plantypeIds' => $filters['plantypeIds'], 
            'localIds' => $filters['localIds'],  
            'seasonIds' => $filters['seasonIds'], 
            'monthIds' => $filters['monthIds'],  
        ]);
    }
    
    // 履歴を更新するメソッド
    private function updateHistory(Request $request, $currentUrl, $currentPageName) {
        
        $history = $request->session()->get('history', []);

        // 同じURLが既に履歴に存在するか確認
        foreach ($history as $key => $item) {
            if ($item['url'] === $currentUrl) {
                // すでに存在する場合は、更新
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
    
    public function truncateAtPunctuation($string, $maxLength) {
        
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
            return mb_substr($truncated, 0, $lastPunctuation + 1); // 句読点まで含める
        }

        // 句読点が見つからない場合は、指定した文字数で切り捨てる
        return mb_substr($truncated, 0, $maxLength);
    }
    
    private function manageHistory(Request $request, $currentUrl, $currentPageName, $filters) {
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
