@extends('layouts.app')

@section('title', 'Home - Cinema')

@section('content')
<div class="text-center mb-12">
    <h1 class="text-4xl font-bold text-white mb-4">Welcome to Cinema</h1>
    <p class="text-gray-400 text-lg">Nikmati pengalaman menonton film terbaik</p>
</div>

<div class="mb-8">
    <h2 class="text-2xl font-bold text-white mb-6">Film Sedang Tayang</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($films as $film)
            <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg">
                @if($film->poster)
                    <img src="{{ $film->poster }}" alt="{{ $film->title }}" class="w-full h-64 object-cover">
                @else
                    <div class="w-full h-64 bg-gray-700 flex items-center justify-center">
                        <span class="text-gray-400">No Image</span>
                    </div>
                @endif
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-white mb-2">{{ $film->title }}</h3>
                    <p class="text-gray-400 text-sm mb-2">{{ $film->genre }}</p>
                    <p class="text-gray-400 text-sm mb-4">{{ $film->duration }} menit</p>
                    <a href="{{ route('film.detail', $film->id) }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm">
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-400">
                <p>Belum ada film yang tersedia</p>
            </div>
        @endforelse
    </div>
</div>

<div class="text-center">
    <a href="{{ route('films') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg text-lg">
        Lihat Semua Film
    </a>
</div>
@endsection