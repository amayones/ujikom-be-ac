@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-black rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center text-white mb-6">
            Login Akun Cinema
        </h2>

        @if ($errors->any())
            <div class="bg-red-600 text-white p-3 rounded mb-4 text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm text-gray-300 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="masukkan email"
                    required
                    class="w-full px-4 py-2 rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-red-500"
                />
            </div>

            <div>
                <label class="block text-sm text-gray-300 mb-1">Password</label>
                <input
                    type="password"
                    name="password"
                    placeholder="masukkan password"
                    required
                    class="w-full px-4 py-2 rounded bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-red-500"
                />
            </div>

            <button
                type="submit"
                class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 rounded transition"
            >
                Login
            </button>
        </form>

        <p class="text-sm text-center text-gray-400 mt-6">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="text-red-500 hover:underline">
                Daftar sekarang
            </a>
        </p>
    </div>
</div>
@endsection