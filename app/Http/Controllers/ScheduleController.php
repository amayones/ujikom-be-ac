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

        return response()->json(['success' => true, 'data' => $schedules]);
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
        
        $validated['created_by'] = 1; // Default user ID
        $schedule = Schedule::create($validated);

        return response()->json(['success' => true, 'data' => $schedule->load(['film', 'studio']), 'message' => 'Schedule created successfully'], 201);
    }

    public function show(Schedule $schedule)
    {
        $schedule->load(['film', 'studio']);
        return response()->json(['success' => true, 'data' => $schedule]);
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

        return response()->json(['success' => true, 'data' => $schedule->load(['film', 'studio']), 'message' => 'Schedule updated successfully']);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return response()->json(['success' => true, 'message' => 'Schedule deleted successfully']);
    }
}