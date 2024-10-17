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
        'memo',
        'start_date',
        'start_time',
    ];
    
    public function destinations()
    {
        return $this->belongsToMany(Spot::class, 'plan_destinations')
                    ->withPivot('order')
                    ->orderBy('pivot_order');
    }
    
    public function planpost()
    {
        return $this->belongsTo(Planpost::class);
    }
}
