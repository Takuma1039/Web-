<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanLike extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'planpost_id'];
    
    public function planpost()
    {
        return $this->belongsTo(Planpost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
