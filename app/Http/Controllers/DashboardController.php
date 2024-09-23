<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Spot;
use App\Models\Spot_image;
use App\Models\SpotCategory;

class DashboardController extends Controller
{
    public function index(Spot $spot, SpotCategory $spot_category, Spot_image $spot_image){
    
      $image_get = Spot_Image::where('spot_id', '=', $spot->id)->get();
      return view("Toppage.dashboard")->with(['spotcategories' => $spot_category->get(), 'spot' => $spot, 'spot_image' => $image_get]);
    }
}
