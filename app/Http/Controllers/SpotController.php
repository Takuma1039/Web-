<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Models\Spot;
use App\Models\Spot_image;
use Cloudinary;

class SpotController extends Controller
{
    
    public function index()
    {
        $spots = Spot::orderBy('created_at', 'desc')->paginate(10); //spotsテーブルにあるレコードを10件に制限して取得
        $data = ['spots' => $spots]; //得られたspotデータを連想配列にして変数dataに入れる
        return view('Spot.index', $data);
    }
    
    public function show(Spot $spot, Spot_image $spot_image)
    {
        //変数の中身の確認
        $image_get = Spot_Image::where('spot_id', '=', $spot->id)->get();
        return view("Spot.show")->with(['spot' => $spot,'spot_image' => $image_get]);
    }
    
    public function create()
    {
        return view('Spot.create');  //create.blade.phpを表示
    }
    
    public function store(ItemRequest $request, Spot_image $spot_image, Spot $spot)
    {
        $images = $request->file('image');
        $input = $request['spot'];
        $spot->fill($input)->save();
        //dd($spot);
        foreach($images as $image){
            //cloudinaryへ画像を送信し、画像のURLを$image_urlに代入している
            $image_url = Cloudinary::upload($image->getRealPath())->getSecurePath();
            //dd($image_url);  //画像のURLを画面に表示
            $spot_image = New Spot_image();
            $spot_image->spot_id = $spot->id;
            $spot_image->image_path = $image_url;
            dd($spot_image);
            $spot_image->save();
        }
        return redirect('/spots/' . $spot->id);
    }
    
    public function favprote_spots()
    {
        $articles = \Auth::user()->favorite_spots()->orderBy('created_at', 'desc')->paginate(10);
        $data = [
            'spots' => $spots,
        ];
        return view('mypage.favorite', $data);
    }
}
