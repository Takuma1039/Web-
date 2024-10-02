<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $fillable = [
    'title',
    'comment',
    'review',
    'spot_id',
    'user_id',
    'is_anonymous',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
    
    public function images()
    {
        return $this->hasMany(ReviewImage::class, 'review_id');
    }
    
    public function likes()
    {
        return $this->hasMany(ReviewLike::class); 
    }
}
