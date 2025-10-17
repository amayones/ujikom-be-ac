<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {
        $prices = Price::all();
        return response()->json(['success' => true, 'data' => $prices]);
    }

    public function update(Request $request, Price $price)
    {
        $validated = $request->validate([
            'weekday' => 'required|numeric|min:0',
            'weekend' => 'required|numeric|min:0'
        ]);
        
        $price->update($validated);
        return response()->json(['success' => true, 'data' => $price, 'message' => 'Price updated successfully']);
    }
}