<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $schedules = Schedule::with(['film', 'studio'])
            ->orderBy('date', 'asc')
            ->orderBy('time', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Schedules retrieved successfully',
            'data' => $schedules
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'price_id' => 'required|exists:prices,id'
        ]);
        
        $validated['created_by'] = $request->user()->id ?? 1;
        $schedule = Schedule::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Schedule created successfully',
            'data' => $schedule->load(['film', 'studio'])
        ], 201);
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['film', 'studio']);
        return response()->json([
            'success' => true,
            'message' => 'Schedule retrieved successfully',
            'data' => $schedule
        ]);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'price_id' => 'required|exists:prices,id'
        ]);
        
        $schedule->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Schedule updated successfully',
            'data' => $schedule->load(['film', 'studio'])
        ]);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return response()->json([
            'success' => true,
            'message' => 'Schedule deleted successfully',
            'data' => null
        ]);
    }
}