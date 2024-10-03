<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlanpostController;
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

Route::get('/', function () {
    return view('welcome');
});

//開発者のみ
Route::middleware('auth', 'activity', 'history', 'developer')->group(function () {
    Route::get('/spots/create', [SpotController::class, 'create'])->name('spots.create');  //スポット作成フォーム
    Route::get('/spots/{spot}/edit', [SpotController::class, 'edit'])->name('spots.edit');  // 編集画面へのルート
    Route::patch('/spots/{spot}', [SpotController::class, 'update'])->name('spots.update');  // 更新処理へのルート
    Route::post('/spots', [SpotController::class, 'store'])->name('store'); //画像を含めたスポット投稿の保存機能
});

//login後使用可能
Route::middleware('auth', 'activity', 'history')->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('Mypage'); //マイページ
    Route::get('/favorites', [SpotController::class, 'favorite'])->name('favoritespot'); //お気に入りしたスポット表示
    Route::post('/spot/like', [SpotlikeController::class, 'likespot']); //spotのお気に入り機能
    Route::post('/spots/{spot}/reviews', [ReviewController::class, 'store'])->name('reviews.store'); //口コミ投稿機能
    Route::post('/reviews/like', [ReviewLikeController::class, 'likeReview'])->name('reviews.like'); //口コミいいね機能
    // 口コミの編集
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    // 口コミの削除
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');


});
//guestでも閲覧・操作できるページ
Route::middleware('activity', 'history')->group(function () {
    Route::get('/spotlist', [SpotController::class, 'index'])->name('spots.index'); //スポット一覧
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('Toppage');  //トップページ
    Route::get('/spotcategories/{category}', [SpotcategoryController::class,'index']); //スポットカテゴリーごとの一覧
    Route::get('/locals/{local}', [LocalController::class,'index']); //地域ごとの一覧
    Route::get('/months/{month}', [MonthController::class,'index']); //月ごとの一覧
    Route::get('/seasons/{season}', [SeasonController::class,'index']); //季節ごとの一覧
    Route::get('/majorspots', [MajorSpotController::class,'index'])->name('major.ranking'); //人気のスポットランキング
    Route::get('/reviewspots', [ReviewSpotController::class,'index'])->name('review.ranking'); //口コミスポットランキング
    Route::get('/seasonspots', [SeasonSpotController::class,'index'])->name('season.ranking'); //今の時期におすすめなスポットランキング
    Route::get('/spots/{spot}', [SpotController::class, 'show'])->name('spots.show'); //スポット詳細画面表示
});
//プロフィール
Route::middleware('auth', 'activity', 'history')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/update-icon', [ProfileController::class, 'updateIcon'])->name('profile.updateIcon');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*Route::controller(PlanpostController::class)->middleware(['auth'])->group(function(){
    Route::get('/', 'index')->name('index');
    Route::post('/planposts', 'store')->name('store');
    Route::get('/planposts/create', 'create')->name('create');
    Route::get('/planposts/{planpost}', 'show')->name('show');
    Route::put('/planposts/{planpost}', 'update')->name('update');
    Route::delete('/planposts/{planpost}', 'delete')->name('delete');
    Route::get('/planposts/{planpost}/edit', 'edit')->name('edit');
});

Route::get('/plan_categories/{plan_category}', [PlancategoryController::class,'index'])->middleware("auth");
Route::get('/user', [UserController::class, 'index']); */

require __DIR__.'/auth.php';
