<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonSpot extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'spot_id',
    ];
}
