<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cinema App')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
    <nav class="bg-black p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-red-500 text-xl font-bold">Cinema</a>
            <div class="space-x-4">
                <a href="{{ route('home') }}" class="hover:text-red-500">Home</a>
                <a href="{{ route('films') }}" class="hover:text-red-500">Films</a>
                @auth
                    <span>Hi, {{ Auth::user()->nama }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="hover:text-red-500">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:text-red-500">Login</a>
                    <a href="{{ route('register') }}" class="hover:text-red-500">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="container mx-auto py-8">
        @if(session('success'))
            <div class="bg-green-600 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-600 text-white p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>