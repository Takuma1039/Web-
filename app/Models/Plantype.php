<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plantype extends Model
{
    use HasFactory;
    
    public function planposts()
    {
        return $this->belongsToMany(Planpost::class, 'planpost_plantype');
    }
}
