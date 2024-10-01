<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
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
        'last_activity', 
    ];
    
    protected $dates = [
        'last_activity', 
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
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->last_activity = now(); // 新規登録時に現在の時間を設定
        });
    }
    
    //ユーザーがお気に入り登録したスポットを操作できるようにする
    public function spotlikes()
    {
        return $this->hasMany(Spotlike::class);
    }
    //口コミ
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    //オンライン・オフライン表示
    public function isOnline()
    {
        \Log::info('Current Time: ' . now());
        \Log::info('Last Activity: ' . $this->last_activity);
        return $this->last_activity && now()->diffInMinutes($this->last_activity) < 5; // 5分以内にアクティブならオンライン
    }
}
