<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    public function index(): JsonResponse
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return $this->success($users);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateRequest($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,owner,cashier,customer'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return $this->success($user, 'User berhasil ditambahkan', 201);
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $validated = $this->validateRequest($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,owner,cashier,customer',
            'password' => 'nullable|string|min:6'
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);
        return $this->success($user, 'User berhasil diupdate');
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return $this->success(null, 'User berhasil dihapus');
    }
}