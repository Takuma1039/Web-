<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    //スポットに対するリレーション
    public function favorites(){
    //スポットは複数のユーザーにお気に入り登録される
    return $this->hasMany(Favorite::class);
    }
    
    //ユーザーがお気に入り登録したスポットを操作できるようにする
    public function favorite_spots()
    {
        return $this->belongsToMany(Spot::class, 'favorite_spots', 'user_id', 'spot_id');
    }
    
    //ユーザーがお気に入り登録をする
    public function is_favorite($spotId)
    {
        return $this->favorite_spots()->where('spot_id', $aspotId)->exists();
    }
}
