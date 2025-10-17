<?php

namespace App\Http\Controllers;

use App\Models\Studio;

class StudioController extends Controller
{
    public function index()
    {
        $studios = Studio::all();
        return response()->json(['success' => true, 'data' => $studios]);
    }
}