<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planpost extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'title',
        'comment',
        'plan_id',
        'season_id',
        'month_id',
        'local_id',
        'plantype_id',
        'is_anonymous',
    ];
    
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function plantype()
    {
        return $this->belongsTo(Plantype::class);
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

    public function planimages()
    {
        return $this->hasMany(PlanImage::class);
    }
    
    public function likes()
    {
        return $this->hasMany(PlanLike::class);
    }

    // 自身がいいねしているのかどうか判定するメソッド（しているならtrue,していないならfalseを返す）
    public function isLikedByAuthUser(): bool
    {
        return $this->likes()->where('user_id', \Auth::id())->exists();
    }
    
}
