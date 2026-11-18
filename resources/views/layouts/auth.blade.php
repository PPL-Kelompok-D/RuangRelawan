<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#eef3ec]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Masuk') - RuangRelawan</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-full flex items-center justify-center p-4 sm:p-6 lg:p-8 text-slate-800 antialiased bg-[#edf2eb]">

    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200/80 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">

        <!-- ================= SISI KIRI (HERO / BRANDING CARD) ================= -->
        <div class="lg:col-span-5 bg-gradient-to-br from-[#ebf5ea] via-[#f0f8ef] to-[#e4f0e2] p-8 sm:p-10 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-slate-200/70">
            <div>
                <!-- Brand Header -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center p-1.5 flex-shrink-0">
                        <!-- RuangRelawan Logo -->
                        <img src="{{ asset('images/logo.png') }}"
                             alt="Logo"
                             class="w-full h-full object-contain"
                             onerror="this.parentElement.innerHTML='<svg class=\'w-6 h-6 text-[#205c3b]\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'currentColor\' stroke-width=\'2\'><circle cx=\'12\' cy=\'8\' r=\'4\'/><path d=\'M6 20v-2a6 6 0 0 1 12 0v2\'/></svg>'">
                    </div>
                    <div>
                        <div class="text-base font-extrabold text-slate-900 leading-tight">Ruang<span class="text-[#205c3b]">Relawan</span></div>
                        <div class="text-[10px] text-slate-500 font-medium">Platform Kolaborasi Aksi Relawan & Lingkungan</div>
                    </div>
                </div>

                <!-- Tagline Badge -->
                <div class="mt-8 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/80 border border-emerald-200/80 text-[11px] font-bold text-slate-700 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    <span>AKSI NYATA BERDAMPAK</span>
                </div>

                <!-- Main Heading -->
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-4 leading-tight tracking-tight">
                    Satu Langkah Kecil, Ribuan Senyum Terukir.
                </h1>
                <p class="text-xs text-slate-600 mt-2.5 leading-relaxed">
                    Wadah kolaborasi terpercaya yang menghubungkan relawan peduli dengan komunitas pelindung bumi dan inisiatif kemanusiaan di seluruh Indonesia.
                </p>

                <!-- Volunteer Image with Quote Card -->
                <div class="mt-6 relative rounded-2xl overflow-hidden shadow-md group">
                    <img src="https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&q=80&w=600"
                         alt="Relawan Indonesia"
                         class="w-full h-44 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent flex items-end p-4">
                        <p class="text-white text-[11px] font-medium italic leading-snug">
                            "Bergabung bersama kami menjaga bumi tetap hijau dan lestari."
                        </p>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="mt-8 pt-6 border-t border-emerald-900/10 grid grid-cols-2 gap-4">
                <div class="bg-white/80 rounded-2xl p-3 border border-emerald-100 shadow-sm text-center">
                    <div class="text-xl font-extrabold text-[#205c3b]">15.000+</div>
                    <div class="text-[11px] text-slate-500 font-medium mt-0.5">Relawan Aktif</div>
                </div>
                <div class="bg-white/80 rounded-2xl p-3 border border-emerald-100 shadow-sm text-center">
                    <div class="text-xl font-extrabold text-[#205c3b]">300+</div>
                    <div class="text-[11px] text-slate-500 font-medium mt-0.5">Komunitas Terdaftar</div>
                </div>
            </div>
        </div>

        <!-- ================= SISI KANAN (FORM CARD) ================= -->
        <div class="lg:col-span-7 p-8 sm:p-10 flex flex-col justify-between">
            @yield('auth-content')
        </div>

    </div>

    @stack('auth-scripts')
</body>
</html>
