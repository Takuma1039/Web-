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
        'access',
        'opendate',
        'closedate',
        'price',
        'site',
        'lat',
        'long',
        'local_id',
        'category_ids', 
        'season_ids',   
        'month_ids',    
    ];

    public function spotcategories()
    {
        return $this->belongsToMany(Category::class, 'spot_categories', 'spot_id', 'category_id');
    }

    public function local()
    {
        return $this->belongsTo(Local::class);
    }

    public function seasons()
    {
        return $this->belongsToMany(Season::class, 'spot_seasons', 'spot_id', 'season_id');
    }

    public function months()
    {
        return $this->belongsToMany(Month::class, 'spot_months', 'spot_id', 'month_id');
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
    //口コミ
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_destinations')
                    ->withPivot('order_no'); //追加のフィールドorderを含める
    }
    
    public function likes()
    {
        return $this->hasMany(Spotlike::class);
    }

    // 自身がいいねしているのかどうか判定するメソッド（しているならtrue,していないならfalseを返す）
    public function isLikedByAuthUser(): bool
    {
        return $this->likes()->where('user_id', \Auth::id())->exists();
    }

    public function getPaginateByLimit(int $limit_count = 5)
    {
        return $this->with('spotcategories')->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }
}

