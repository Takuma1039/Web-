<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanImage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'planpost_id',
        'image_path',
    ];
    
    public function planpost()
    {
        return $this->belongsTo(Planpost::class);
    }
}
