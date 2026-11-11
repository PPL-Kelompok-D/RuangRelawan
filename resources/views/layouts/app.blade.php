<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F4F6F0]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pengaturan Profil Relawan') - RuangRelawan</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                            primary: '#205c3b',
                            dark: '#16432b',
                            light: '#e8f5ec',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="min-h-full flex flex-col text-slate-800 antialiased bg-[#F4F6F0]">
    @php($role = $user?->role)

    <!-- Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand Logo -->
                <div class="flex items-center gap-8">
                    <a href="{{ $role === 'admin' ? route('admin.dashboard') : route('home') }}" class="flex items-center gap-2.5">
                        <!-- Icon Logo Gambar -->
                        <img src="{{ asset('images/image.png') }}" alt="Logo RuangRelawan" class="h-9 w-auto object-contain">

                        <!-- Tulisan RuangRelawan -->
                        <span class="text-xl font-bold tracking-tight text-[#205c3b]">
                            RuangRelawan
                        </span>
                    </a>
                    <!-- Nav Links -->
                    <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-600">
                        @if($role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-primary transition-colors">Dashboard Admin</a>
                        @else
                            <a href="{{ route('home') }}" class="hover:text-brand-primary transition-colors">Eksplorasi Aksi</a>
                            <a href="{{ route('home') }}#komunitas" class="hover:text-brand-primary transition-colors">Komunitas</a>
                        @endif
                        @if($role === 'user')
                            <a href="{{ route('user.my-activities') }}" class="hover:text-brand-primary transition-colors">Aktivitas Saya</a>
                        @elseif($role === 'community')
                            <a href="{{ route('community.my-activities') }}" class="hover:text-brand-primary transition-colors">Aktivitas Saya</a>
                            <a href="{{ route('community.gallery.index') }}" class="hover:text-brand-primary transition-colors">Galeri Saya</a>
                        @endif
                    </nav>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    @if($role === 'community')<a href="{{ route('community.gallery.index') }}" class="text-sm font-semibold text-[#2D6A4F] hover:underline md:hidden">Galeri Saya</a>@endif
                    <!-- Notification Bell -->
                    <button type="button" class="relative p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-full transition-colors">
                        <span class="sr-only">Notifikasi</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                    </button>

                    @if($user)
                    <!-- User Profile Dropdown button -->
                    <div class="relative" id="profile-dropdown-container">
                        <button type="button"
                                onclick="toggleProfileDropdown()"
                                class="flex items-center gap-2 pl-2 border-l border-slate-200 focus:outline-none hover:opacity-90 transition-opacity">
                            <div class="w-8 h-8 rounded-full bg-[#205c3b] text-white flex items-center justify-center font-semibold text-xs overflow-hidden shadow-sm">
                                @if(isset($user) && ($user->profilUser?->foto_profil ?? $user->community?->logo))
                                    <img src="{{ asset('storage/' . ($user->profilUser?->foto_profil ?? $user->community?->logo)) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                            <span class="hidden sm:inline text-sm font-semibold text-slate-700">{{ $role === 'admin' ? 'Admin' : ($role === 'community' ? 'Profil Komunitas' : 'Profil User') }}</span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="profile-dropdown-menu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 animate-fade-in">
                            <div class="px-4 py-2.5 border-b border-slate-100">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ $user->name ?? 'Pengguna' }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ $user->email ?? 'relawan@ruangrelawan.id' }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-[#205c3b] border border-emerald-200/80 uppercase">
                                    {{ ['user' => 'Relawan', 'community' => 'Komunitas', 'admin' => 'Admin'][$user->role ?? 'user'] }}
                                </span>
                            </div>

                            <a href="{{ match($role) { 'community' => route('community.profile.edit'), 'admin' => route('admin.dashboard'), default => route('profile.edit') } }}" class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-[#205c3b] transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>{{ $role === 'admin' ? 'Dashboard Admin' : ($role === 'community' ? 'Profil Komunitas' : 'Pengaturan Profil') }}</span>
                            </a>

                            <div class="border-t border-slate-100 my-1"></div>

                            <!-- Logout Action -->
                            <form action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors text-left">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                    </svg>
                                    <span>Keluar dari Akun</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Keluar</button></form>
                    @else
                        <div class="flex items-center gap-2"><a href="{{ route('login') }}" class="rounded-xl bg-[#2D6A4F] px-4 py-2 text-sm font-semibold text-white hover:bg-[#24583f]">Masuk ke Akun</a><a href="{{ route('register') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">Daftar</a></div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 mt-12 text-sm text-slate-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-800">RuangRelawan</span>
                <span>© {{ date('Y') }} Wadah aksi nyata keberlanjutan & sosial.</span>
            </div>
            <div class="flex items-center gap-6 text-slate-500">
                <a href="#" class="hover:text-[#205c3b] transition-colors">Panduan Relawan</a>
                <a href="#" class="hover:text-[#205c3b] transition-colors">Kebijakan Komunitas</a>
                <a href="#" class="hover:text-[#205c3b] transition-colors">Pusat Bantuan</a>
            </div>
        </div>
    </footer>

    <script>
        function toggleProfileDropdown() {
            const menu = document.getElementById('profile-dropdown-menu');
            const arrow = document.getElementById('dropdown-arrow');
            if (menu) {
                menu.classList.toggle('hidden');
                if (arrow) {
                    arrow.classList.toggle('rotate-180');
                }
            }
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const container = document.getElementById('profile-dropdown-container');
            const menu = document.getElementById('profile-dropdown-menu');
            const arrow = document.getElementById('dropdown-arrow');
            if (container && !container.contains(e.target) && menu && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
                if (arrow) {
                    arrow.classList.remove('rotate-180');
                }
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
