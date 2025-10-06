<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Order;
use App\Models\Schedule;
use App\Models\ScheduleSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function getFilms(Request $request)
    {
        $request->validate(['status' => 'nullable|in:play_now,coming_soon,history']);
        $status = $request->get('status', 'play_now');
        return Film::where('status', $status)->get();
    }

    public function getFilmDetail($id)
    {
        return Film::with('schedules.studio')->findOrFail($id);
    }

    public function getSchedules($filmId)
    {
        $filmId = (int) $filmId;
        return Schedule::with(['studio', 'price'])
            ->where('film_id', $filmId)
            ->where('date', '>=', now()->toDateString())
            ->get();
    }

    public function getAvailableSeats($scheduleId)
    {
        $scheduleId = (int) $scheduleId;
        return ScheduleSeat::where('schedule_id', $scheduleId)
            ->where('status', 'available')
            ->get();
    }

    public function bookTicket(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'seat_ids' => 'required|array',
            'seat_ids.*' => 'exists:schedule_seats,id'
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'schedule_id' => $validated['schedule_id'],
            'order_date' => now(),
            'status' => 'pending'
        ]);

        // Use database transaction for seat booking
        \DB::transaction(function () use ($validated, $order) {
            foreach ($validated['seat_ids'] as $seatId) {
                $updated = ScheduleSeat::where('id', $seatId)
                    ->where('status', 'available')
                    ->update(['status' => 'booked']);
                
                if (!$updated) {
                    throw new \Exception('Seat no longer available');
                }
            }
        });

        return response()->json(['order' => $order], 201);
    }

    public function getOrderHistory()
    {
        return Order::with(['schedule.film', 'orderDetails'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'string',
            'phone' => 'string',
            'address' => 'string'
        ]);

        Auth::user()->update($validated);
        return response()->json(['user' => Auth::user()]);
    }
}