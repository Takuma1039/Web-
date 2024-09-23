<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpotCategory extends Model
{
    use HasFactory;
    
    public function spots()
    {
        return $this->hasMany(Spot::class);
    }
    
    public function getByCategory(int $limit_count = 5)
    {
      return $this->spots()->with('spotcategory')->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }
}
