<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public function register(Request $request): JsonResponse
    {
        try {
            $validated = $this->validateRequest($request, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed'
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'customer'
            ]);

            return $this->success($user, 'Registrasi berhasil', 201);
        } catch (\Exception $e) {
            return $this->error('Registrasi gagal: ' . $e->getMessage(), 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $validated = $this->validateRequest($request, [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (!Auth::attempt($validated)) {
                return $this->error('Email atau password salah', 401);
            }

            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->success([
                'user' => $user,
                'token' => $token
            ], 'Login berhasil');
        } catch (\Exception $e) {
            return $this->error('Login gagal: ' . $e->getMessage(), 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->success(null, 'Logout berhasil');
        } catch (\Exception $e) {
            return $this->error('Logout gagal: ' . $e->getMessage(), 500);
        }
    }

    public function me(Request $request): JsonResponse
    {
        return $this->success($request->user());
    }
}