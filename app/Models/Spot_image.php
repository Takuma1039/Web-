<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot_image extends Model
{
    use HasFactory;
    
    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
