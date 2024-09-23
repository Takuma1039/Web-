<?php

namespace App\Http\Controllers;
use App\Models\Spot_category;
use Illuminate\Http\Request;
use App\Models\Spot;

class SpotcategoryController extends Controller
{
    public function index(Spot_category $spot_category)
    {
      //dd($spot_category);
      return view('spot_categories.index')->with(['spots' => $spot_category->getByCategory()]);
    }
}
