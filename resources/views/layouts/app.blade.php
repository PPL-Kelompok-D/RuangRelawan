<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'RuangRelawan'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#2D6A4F',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#F8FAF9] font-sans text-slate-800 antialiased min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="bg-white border-b border-[#E2E8D8] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-xl font-bold text-[#2D6A4F]">
                        RuangRelawan
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <span class="text-sm font-medium text-slate-700">{{ Auth::user()->name }}</span>
                        <a href="{{ route('profile.edit') }}" class="text-sm font-medium text-[#2D6A4F] hover:underline">Profil</a>
                    @else
                        <a href="{{ route('login') ?? '#' }}" class="text-sm font-medium text-slate-700 hover:text-[#2D6A4F]">Masuk</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-[#E2E8D8] py-6 text-center text-xs text-slate-500">
        &copy; {{ date('Y') }} RuangRelawan - Kelompok D PPL. All rights reserved.
    </footer>
</body>
</html>
