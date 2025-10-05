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
        $status = $request->get('status', 'play_now');
        return Film::where('status', $status)->get();
    }

    public function getFilmDetail($id)
    {
        return Film::with('schedules.studio')->findOrFail($id);
    }

    public function getSchedules($filmId)
    {
        return Schedule::with(['studio', 'price'])
            ->where('film_id', $filmId)
            ->where('date', '>=', now()->toDateString())
            ->get();
    }

    public function getAvailableSeats($scheduleId)
    {
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

        foreach ($validated['seat_ids'] as $seatId) {
            ScheduleSeat::where('id', $seatId)->update(['status' => 'booked']);
        }

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