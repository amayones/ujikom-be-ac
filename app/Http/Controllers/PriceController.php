<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {
        $prices = Price::all()->groupBy('type')->map(function ($typeGroup) {
            $weekday = $typeGroup->where('day_type', 'weekday')->first();
            $weekend = $typeGroup->where('day_type', 'weekend')->first();
            
            return [
                'id' => $weekday->id ?? $weekend->id,
                'type' => $typeGroup->first()->type,
                'weekday' => $weekday->price ?? 0,
                'weekend' => $weekend->price ?? 0,
                'description' => $weekday->description ?? $weekend->description ?? ''
            ];
        })->values();

        return response()->json([
            'success' => true,
            'message' => 'Prices retrieved successfully',
            'data' => $prices
        ]);
    }

    public function update(Request $request, $type)
    {
        $validated = $request->validate([
            'weekday' => 'required|numeric|min:0',
            'weekend' => 'required|numeric|min:0'
        ]);

        // Update weekday price
        Price::where('type', $type)
            ->where('day_type', 'weekday')
            ->update(['price' => $validated['weekday']]);

        // Update weekend price
        Price::where('type', $type)
            ->where('day_type', 'weekend')
            ->update(['price' => $validated['weekend']]);

        return response()->json([
            'success' => true,
            'message' => 'Prices updated successfully',
            'data' => null
        ]);
    }
}