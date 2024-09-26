<?php

namespace App\Http\Controllers;
use App\Models\SpotCategory;
use Illuminate\Http\Request;
use App\Models\Spot;

class SpotcategoryController extends Controller
{
    public function index(SpotCategory $spotcategory)
    {
      //dd($spot_category);
      return view('spot_categories.index')->with(['spots' => $spotcategory->getByCategory()]);
    }
}
