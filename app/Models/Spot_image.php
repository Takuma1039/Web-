<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot_image extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'spot_id',
        'image_path',
    ];
    
    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
