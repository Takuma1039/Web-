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

Route::get('/dashboard', [DashboardController::class, 'index'])->name('Toppage');
Route::get('/spotcategories/{spotcategory}', [SpotcategoryController::class,'index']);
Route::get('/locals/{local}', [LocalController::class,'index']);
Route::get('/months/{month}', [MonthController::class,'index']);
Route::get('/seasons/{season}', [SeasonController::class,'index']);
Route::get('/majorspots/{majorspot}', [MajorspotController::class,'index']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/spots/create', [SpotController::class, 'create']);  //スポット作成フォーム
    Route::get('/index', [SpotController::class, 'index']);
    Route::get('/spots/{spot}', [SpotController::class, 'show'])->name('show'); //スポット詳細画面表示
    Route::get('/favorites', [SpotController::class, 'favorite_spots'])->name('favorites');
    Route::post('/spot/like', [SpotlikeController::class, 'likespot']); //spotのいいね機能
    Route::post('/spots', [SpotController::class, 'store'])->name('store'); //画像を含めたスポット投稿の保存機能
    Route::post('/spots/{spot}/favorite', [FavoriteController::class, 'store'])->name('favorite.store');
    Route::delete('/spots/{spot}/unfavorite', [FavoriteController::class, 'destroy'])->name('favorite.destroy');
});

//Route::get('/', [ToppageController::class, 'index'])->name('index');

//Route::get('notifications/get',[NotificationsController::class, 'getNotificationsData'])->name('notifications.get');

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
