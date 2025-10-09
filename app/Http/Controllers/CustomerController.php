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
    public function home()
    {
        $films = Film::where('status', 'play_now')->take(6)->get();
        return view('customer.home', compact('films'));
    }

    public function films(Request $request)
    {
        $status = $request->get('status', 'play_now');
        $films = Film::where('status', $status)->paginate(12);
        return view('customer.films', compact('films', 'status'));
    }

    public function filmDetail($id)
    {
        $film = Film::with('schedules.studio')->findOrFail($id);
        return view('customer.film-detail', compact('film'));
    }

    public function profile()
    {
        return view('customer.profile');
    }

    public function booking($filmId)
    {
        $film = Film::findOrFail($filmId);
        $schedules = Schedule::with(['studio', 'price'])
            ->where('film_id', $filmId)
            ->where('date', '>=', now()->toDateString())
            ->get();
        return view('customer.booking', compact('film', 'schedules'));
    }

    public function history()
    {
        $orders = Order::with(['schedule.film'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('customer.history', compact('orders'));
    }
}