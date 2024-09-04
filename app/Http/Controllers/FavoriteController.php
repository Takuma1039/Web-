<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    //お気に入り登録
    public function store($spotId) {
        $user = \Auth::user();
        if (!$user->is_favorite($spotId)) {
            $user->favorite_spots()->attach($spotId);
        }
        return back();
    }
    //お気に入り解除
    public function destroy($spotId) {
        $user = \Auth::user();
        if ($user->is_favorite($spotId)) {
            $user->favorite_spots()->detach($spotId);
        }
        return back();
    }
}
