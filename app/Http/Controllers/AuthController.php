<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:1|max:255'
            ]);

            // Rate limiting per IP
            $key = 'login-attempts:' . $request->ip();
            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                return response()->json([
                    'message' => 'Terlalu banyak percobaan login. Coba lagi dalam ' . $seconds . ' detik.',
                    'retry_after' => $seconds
                ], 429);
            }

            if (Auth::attempt($credentials)) {
                RateLimiter::clear($key);
                
                $user = Auth::user();
                $token = $user->createToken('auth-token', ['*'], now()->addDays(7))->plainTextToken;
                
                return response()->json([
                    'success' => true,
                    'user' => [
                        'id' => $user->id,
                        'nama' => $user->nama,
                        'email' => $user->email,
                        'role' => $user->role,
                        'no_hp' => $user->no_hp,
                        'alamat' => $user->alamat
                    ],
                    'token' => $token,
                    'message' => 'Login berhasil'
                ], 200);
            }

            RateLimiter::hit($key, 300); // 5 minutes penalty
            
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
            
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            'no_hp' => 'required|string|regex:/^[0-9+\-\s]+$/|min:10|max:15',
            'alamat' => 'required|string|max:500'
        ]);

        $user = User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
            'role' => 'customer'
        ]);

        return response()->json([
            'user' => $user,
            'message' => 'Registrasi berhasil'
        ], 201);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Logout error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat logout'
            ], 500);
        }
    }
}