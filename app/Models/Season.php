<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;
    
    public function spots()
    {
        return $this->belongsToMany(Spot::class, 'spot_seasons', 'spot_id', 'season_id');
    }
    
    // public function getByCategory(int $limit_count = 5)
    // {
    //   return $this->spots()->orderBy('updated_at', 'DESC')->paginate($limit_count);
    // }
}
