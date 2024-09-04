<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlanpostController extends Controller
{
    public function show(Planpost $planpost){
        return view('planpost.show')->with(['planpost'=>$planpost]);
    }
}
