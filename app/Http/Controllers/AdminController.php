<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Price;
use App\Models\Studio;
use App\Models\StudioSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // Film Management
    public function getFilms()
    {
        return Film::with('creator')->get();
    }

    public function storeFilm(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string',
            'genre' => 'required|string',
            'durasi' => 'required|integer',
            'deskripsi' => 'required|string',
            'status' => 'required|in:play_now,coming_soon,history',
            'poster' => 'nullable|string'
        ]);

        $film = Film::create([
            ...$validated,
            'created_by' => Auth::id()
        ]);

        return response()->json(['film' => $film], 201);
    }

    public function updateFilm(Request $request, $id)
    {
        $film = Film::findOrFail($id);
        $validated = $request->validate([
            'judul' => 'string',
            'genre' => 'string',
            'durasi' => 'integer',
            'deskripsi' => 'string',
            'status' => 'in:play_now,coming_soon,history',
            'poster' => 'nullable|string'
        ]);

        $film->update($validated);
        return response()->json(['film' => $film]);
    }

    public function deleteFilm($id)
    {
        Film::findOrFail($id)->delete();
        return response()->json(['message' => 'Film deleted']);
    }

    // Customer Management
    public function getCustomers()
    {
        return User::where('role', 'pelanggan')->get();
    }

    public function updateCustomer(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'string',
            'email' => 'email|unique:users,email,' . $id,
            'no_hp' => 'string',
            'alamat' => 'string'
        ]);

        $user->update($validated);
        return response()->json(['user' => $user]);
    }

    // Schedule Management
    public function getSchedules()
    {
        return Schedule::with(['film', 'studio', 'price'])->get();
    }

    public function storeSchedule(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'studio_id' => 'required|exists:studios,id',
            'tanggal' => 'required|date',
            'jam' => 'required|string',
            'harga_id' => 'required|exists:prices,id'
        ]);

        $schedule = Schedule::create([
            ...$validated,
            'created_by' => Auth::id()
        ]);

        return response()->json(['schedule' => $schedule], 201);
    }

    // Price Management
    public function getPrices()
    {
        return Price::all();
    }

    public function storePrice(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|numeric'
        ]);

        $price = Price::create($validated);
        return response()->json(['price' => $price], 201);
    }

    // Cashier Management
    public function getCashiers()
    {
        return User::where('role', 'kasir')->get();
    }

    public function storeCashier(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'no_hp' => 'required|string',
            'alamat' => 'required|string'
        ]);

        $cashier = User::create([
            ...$validated,
            'role' => 'kasir'
        ]);

        return response()->json(['cashier' => $cashier], 201);
    }

    // Seat Management
    public function getSeats()
    {
        return StudioSeat::with('studio')->get();
    }

    public function storeSeat(Request $request)
    {
        $validated = $request->validate([
            'studio_id' => 'required|exists:studios,id',
            'nomor_kursi' => 'required|string',
            'tipe' => 'required|string'
        ]);

        $seat = StudioSeat::create($validated);
        return response()->json(['seat' => $seat], 201);
    }
}