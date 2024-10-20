<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToppageController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\SpotController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SpotcategoryController;
use App\Http\Controllers\SpotlikeController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\MonthController;
use App\Http\Controllers\MajorSpotController;
use App\Http\Controllers\ReviewSpotController;
use App\Http\Controllers\SeasonSpotController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewLikeController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanpostController;
use App\Http\Controllers\PlanLikeController;
use App\Http\Controllers\UseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//開発者のみ
Route::controller(SpotController::class)->middleware(['auth', 'activity', 'developer'])->group(function () {
    Route::get('/spots/create', 'create')->name('spots.create');  //スポット作成フォーム
    Route::get('/spots/{spot}/edit', 'edit')->name('spots.edit');  // 編集画面へのルート
    Route::patch('/spots/{spot}', 'update')->name('spots.update');  // 更新処理へのルート
    Route::post('/spots/store', 'store')->name('store'); //画像を含めたスポット投稿の保存機能
    Route::delete('/spots/{spot}', 'destroy')->name('spots.destroy');
});

//login後使用可能
Route::middleware('auth', 'activity')->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('Mypage'); //マイページ
    Route::get('/favorites', [SpotController::class, 'favorite'])->name('favoritespot'); //お気に入りしたスポット表示
    Route::post('/spot/like', [SpotlikeController::class, 'likespot']); //spotのお気に入り機能
    Route::post('/spots/{spot}/reviews', [ReviewController::class, 'store'])->name('reviews.store'); //口コミ投稿機能
    Route::post('/reviews/like', [ReviewLikeController::class, 'likeReview'])->name('reviews.like'); //口コミいいね機能
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');  // 口コミの編集
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');  // 口コミの削除
    Route::get('/planposts/likesplan', [PlanpostController::class, 'likesplan'])->name('planposts.likesplan'); //いいねした旅行計画一覧
    Route::get('/planposts/create', [PlanpostController::class, 'create'])->name('plans.post'); //旅行計画投稿
    Route::get('/planpost/{planpost}/add', [PlanpostController::class, 'addPlan'])->name('planposts.add'); //いいねした旅行計画の追加
    Route::post('/planposts/planstore', [PlanpostController::class, 'planstore'])->name('planposts.planstore'); //いいねした旅行計画の保存
    Route::post('/planposts/store', [PlanpostController::class, 'store'])->name('planposts.store'); //旅行計画投稿の保存
    Route::post('/planpost/like', [PlanLikeController::class, 'likeplan']);  //旅行計画いいね機能
    Route::delete('/planposts/{planpost}', [PlanpostController::class, 'destroy'])->name('planposts.destroy'); //投稿した旅行計画の削除
    Route::get('/planposts/{planpost}/edit', [PlanpostController::class, 'edit'])->name('planposts.edit'); //投稿した旅行計画の編集
    Route::patch('/planposts/{planpost}', [PlanpostController::class, 'update'])->name('planposts.update'); //投稿した旅行計画の更新
});

Route::controller(PlanController::class)->middleware(['auth', 'activity'])->group(function(){
    Route::get('/plans', 'index')->name('plans.index'); //旅行計画一覧
    Route::get('/plans/create', 'create')->name('plans.create'); //旅行計画の作成
    Route::get('/plans/{plan}/edit', 'edit')->name('plans.edit'); //旅行計画の編集
    Route::put('/plans/{plan}', 'update')->name('plans.update'); //旅行計画の更新
    Route::delete('/plans/{plan}', 'destroy')->name('plans.destroy'); //旅行計画の削除
    Route::post('/plans/store', 'store')->name('plans.store'); //旅行計画の保存
    Route::post('/plans/{plan}/memo', 'updateMemo')->name('plans.memo');
});

//guestでも閲覧・操作できるページ
Route::middleware('activity')->group(function () {
    Route::get('/use', [UseController::class, 'index'])->name('use'); //使い方画面
    Route::get('/spots', [SpotController::class, 'index'])->name('spots.index'); //スポット一覧
    Route::get('/', [DashboardController::class, 'index'])->name('Toppage');  //トップページ
    Route::get('/spotcategories/{category}', [SpotcategoryController::class,'index']); //スポットカテゴリーごとの一覧
    Route::get('/locals/{local}', [LocalController::class,'index']); //地域ごとの一覧
    Route::get('/months/{month}', [MonthController::class,'index']); //月ごとの一覧
    Route::get('/seasons/{season}', [SeasonController::class,'index']); //季節ごとの一覧
    Route::get('/majorspots', [MajorSpotController::class,'index'])->name('major.ranking'); //人気のスポットランキング
    Route::get('/reviewspots', [ReviewSpotController::class,'index'])->name('review.ranking'); //口コミスポットランキング
    Route::get('/seasonspots', [SeasonSpotController::class,'index'])->name('season.ranking'); //今の時期におすすめなスポットランキング
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews'); //口コミ
    Route::get('/spots/{spot}', [SpotController::class, 'show'])->name('spots.show'); //スポット詳細画面表示
    Route::get('/search', [SpotController::class, 'search'])->name('spot.search'); //検索
    Route::get('/planposts/index', [PlanpostController::class, 'index'])->name('planposts.index');
    Route::get('/plansearch', [PlanpostController::class, 'search'])->name('planposts.search'); //検索
    Route::get('/plans/{plan}', [PlanController::class, 'show'])->name('plans.show'); //旅行計画の詳細
    Route::post('/history/clear', [HistoryController::class, 'clearHistory'])->name('history.clear'); //履歴の削除
});
//プロフィール
Route::middleware('auth', 'activity')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-icon', [ProfileController::class, 'updateIcon'])->name('profile.updateIcon');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
