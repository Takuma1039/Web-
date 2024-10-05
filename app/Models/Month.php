<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    use HasFactory;
    
    public function spots()
    {
        return $this->belongsToMany(Spot::class, 'spot_months', 'month_id', 'spot_id');
    }
    
    public function getByCategory(int $limit_count = 5)
    {
      return $this->spots()->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }
}
