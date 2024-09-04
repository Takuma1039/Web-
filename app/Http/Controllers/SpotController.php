<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Models\Spot;

class SpotController extends Controller
{
    
    public function index()
    {
        $spots = Spot::orderBy('created_at', 'desc')->paginate(10); //spotsテーブルにあるレコードを10件に制限して取得
        $data = ['spots' => $spots]; //得られたspotデータを連想配列にして変数dataに入れる
        return view('Spot.index', $data);
    }
    
    public function create(ItemRequest $request)
    {
        // POST
        if ($request->isMethod('POST')) {
            dd($request->all());
            // 商品情報の保存
            $item = Spot::create(['name'=> $request->name, 
                                  'body'=> $request->body,
                                  'address' => $request->address,
                                  'lat' => $request->lat,
                                  'long' => $request->long]);

            // 商品画像の保存
            foreach ($request->file('files') as $index=> $e) {
                $ext = $e['photo']->guessExtension();
                $filename = "{$request->name}_{$index}.{$ext}";
                $path = $e['photo']->storeAs('image', $filename);
                // photosメソッドにより、商品に紐付けられた画像を保存する
                $item->spotimages()->create(['image_path'=> $path]);
            }

            return redirect('/')->with(['success'=> '保存しました！']);
        }

        // GET
        return view('Spot.create');
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
