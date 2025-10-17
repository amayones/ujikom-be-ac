<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index($studio_id = null)
    {
        $seats = [];
        $rows = ['A', 'B', 'C', 'D', 'E'];
        
        foreach ($rows as $row) {
            for ($i = 1; $i <= 10; $i++) {
                $seats[] = [
                    'id' => $row . $i,
                    'row' => $row,
                    'number' => $i,
                    'studio_id' => $studio_id ?: 1,
                    'status' => rand(0, 10) > 7 ? 'occupied' : 'available',
                    'type' => $i <= 6 ? 'regular' : 'vip'
                ];
            }
        }

        return response()->json(['success' => true, 'data' => $seats]);
    }

    public function update(Request $request, $id)
    {
        return response()->json(['success' => true, 'message' => 'Seat updated successfully']);
    }
}