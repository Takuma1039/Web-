<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'body',
        'address',
        'lat',
        'long',
        'local_id',
        'season_id',
        'month_id',
        'spot_category_id',
    ];
    
    public function spotcategory() //1対多なのでspotcategory単数形
    {
        return $this->belongsTo(Spotcategory::class);
    }
    
    public function local()
    {
        return $this->belongsTo(Local::class);
    }
    
    public function season()
    {
        return $this->belongsTo(Season::class);
    }
    
    public function month()
    {
        return $this->belongsTo(Month::class);
    }
    
    public function spotimages()
    {
        return $this->hasMany(Spotimage::class);
    }
}
