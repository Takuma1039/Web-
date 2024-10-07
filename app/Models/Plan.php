<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', 
        'title', 
        'start_date'
    ];
    
    public function destinations()
    {
        return $this->belongsToMany(Spot::class, 'plan_destinations')
                    ->withPivot('order')
                    ->orderBy('order'); //目的地を指定された順序で取得
    }
}
