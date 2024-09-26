<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'body',
        'address',
        'lat',
        'long',
        'local_id',
        'season_id',
        'month_id',
        'spot_category_id',
    ];
    
    public function spotcategory() //1対多なのでspotcategory単数形
    {
        return $this->belongsTo(SpotCategory::class, 'spot_category_id');
    }
    
    public function local()
    {
        return $this->belongsTo(Local::class);
    }
    
    public function season()
    {
        return $this->belongsTo(Season::class);
    }
    
    public function month()
    {
        return $this->belongsTo(Month::class);
    }
    
    public function spotimages()
    {
        return $this->hasMany(Spot_image::class);
    }
    
    public function majorspot()
    {
        return $this->belongsTo(Majorspot::class);
    }
    
    public function favoritespot()
    {
        return $this->belongsTo(Favoritespot::class);
    }
    
    public function recommendspot()
    {
        return $this->belongsTo(Recommendspot::class);
    }
    
    public function likes()
    {
        return $this->hasMany(Spotlike::class);
    }
    
    //自身がいいねしているのかどうか判定するメソッド（しているならtrue,していないならfalseを返す）
    public function isLikedByAuthUser() :bool
    {
        //認証済ユーザーid（自身のid）を取得
        $authUserId = \Auth::id();
        //空の配列を定義。後続の処理で、いいねしたユーザーのidを全て格納していくときに使う。
        $likersArr = array();
       
        //$thisは言葉の似た通り、クラス自身を指す。具体的にはこのPostクラスをインスタンス化した際の変数のことを指す。（後続のビューで登場する$postになります）
        foreach($this->likes as $spotlike){
            //array_pushメソッドで第一引数に配列、第二引数に配列に格納するデータを定義し、配列を作成できる。
            //今回は$likersArrという空の配列にいいねをした全てのユーザーのidを格納している。
            array_push($likersArr,$spotlike->user_id);

        }
        //in_arrayメソッドを利用し、認証済ユーザーid（自身のid）が上記で作成した配列の中に存在するかどうか判定している
        if (in_array($authUserId,$likersArr)){
            //存在したらいいねをしていることになるため、trueを返す
            return true;
        }else{
            return false;
        }
    }
    
    public function getPaginateByLimit(int $limit_count = 5)
    {
        return $this::with('spotcategory')->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }
}
