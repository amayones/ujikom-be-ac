<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\StudioSeat;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index($studio_id)
    {
        $studio = Studio::find($studio_id);
        if (!$studio) {
            return response()->json([
                'success' => false, 
                'message' => 'Studio not found'
            ], 404);
        }

        // Get studio seats from database
        $studioSeats = StudioSeat::where('studio_id', $studio_id)
            ->orderBy('seat_code')
            ->get();

        // If no seats exist, generate them
        if ($studioSeats->isEmpty()) {

            // Generate seats for studio
            $seats = [];
            $rows = ['A', 'B', 'C', 'D', 'E'];
            
            foreach ($rows as $row) {
                for ($i = 1; $i <= 10; $i++) {
                    $seatCode = $row . $i;
                    $seat = StudioSeat::create([
                        'studio_id' => $studio_id,
                        'seat_code' => $seatCode
                    ]);
                    
                    $seats[] = [
                        'id' => $seat->id,
                        'seat_code' => $seatCode,
                        'row' => $row,
                        'number' => $i,
                        'studio_id' => $studio_id,
                        'status' => 'available',
                        'type' => $i <= 6 ? 'regular' : 'vip'
                    ];
                }
            }
            
            $studioSeats = StudioSeat::where('studio_id', $studio_id)->get();
        }
        
        $seats = $studioSeats->map(function ($seat) {
            $row = substr($seat->seat_code, 0, 1);
            $number = (int) substr($seat->seat_code, 1);
            
            return [
                'id' => $seat->id,
                'seat_code' => $seat->seat_code,
                'row' => $row,
                'number' => $number,
                'studio_id' => $seat->studio_id,
                'status' => 'available',
                'type' => $number <= 6 ? 'regular' : 'vip'
            ];
        });

        return response()->json([
            'success' => true, 
            'message' => 'Seats retrieved successfully',
            'data' => $seats
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,booked,maintenance'
        ]);

        $seat = StudioSeat::findOrFail($id);
        $seat->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Seat updated successfully',
            'data' => $seat
        ]);
    }
}