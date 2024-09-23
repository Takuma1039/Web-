<?php

namespace App\Http\Controllers;
use App\Models\Majorspot;
use Illuminate\Http\Request;

class MajorspotController extends Controller
{
    public function index(Majorspot $majorspot)
    {
        
        return view('Major.index')->with(['majorspots' => $majorspot->get()]);
    }
}
