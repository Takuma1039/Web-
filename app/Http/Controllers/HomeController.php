<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use app\Models\User;
use App\Models\Spot;

class HomeController extends Controller
{
     public function index()
    {
        $spots = \Auth::user()->favorite_spots()->orderBy('created_at', 'desc')->paginate(10);
        $data = [
            'spots' => $spots,
        ];
        return view('mypage.index', $data);
    }
}
