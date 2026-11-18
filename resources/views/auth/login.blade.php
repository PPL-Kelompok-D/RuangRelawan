@extends('layouts.auth')

@section('title', 'Masuk ke Akun')

@section('auth-content')
<div>
    <!-- Top Action Switcher: Masuk vs Daftar Baru -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Masuk ke Akun</h2>
            <p class="text-xs text-slate-500 mt-1">Silakan pilih peran dan masukkan kredensial Anda.</p>
        </div>

        <div class="flex items-center p-1 bg-slate-100 rounded-xl">
            <a href="{{ route('login') }}" class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-[#205c3b] text-white shadow-sm transition-all">
                Masuk
            </a>
            <a href="{{ route('register') }}" class="px-4 py-1.5 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 transition-all">
                Daftar Baru
            </a>
        </div>
    </div>

    <!-- Flash Error / Notification -->
    @if(session('success'))
        <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-2">
            <svg class="w-4 h-4 text-rose-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login.perform') }}" method="POST" class="space-y-5">
        @csrf

        <!-- 1. PILIH PERAN AKUN -->
        <div>
            <div class="flex items-center justify-between mb-2.5">
                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-700">1. PILIH PERAN AKUN</label>
                <span class="text-[10px] text-slate-400">Menentukan batasan hak akses</span>
            </div>

            @php $currentRole = old('role', 'relawan'); @endphp
            <div class="grid grid-cols-3 gap-2.5">
                <!-- Role Card 1: Relawan -->
                <label class="role-card relative p-3 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between {{ $currentRole === 'relawan' ? 'border-[#205c3b] bg-emerald-50/40 ring-1 ring-[#205c3b]' : 'border-slate-200 bg-white hover:bg-slate-50' }}" data-role="relawan">
                    <input type="radio" name="role" value="relawan" class="hidden" {{ $currentRole === 'relawan' ? 'checked' : '' }}>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100/70 text-[#205c3b] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full {{ $currentRole === 'relawan' ? 'bg-[#205c3b]' : 'border border-slate-300' }} indicator-dot"></span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900">Relawan</div>
                        <div class="text-[10px] text-slate-500 leading-tight mt-0.5">Eksplorasi aksi sosial, daftar kegiatan & kontribusi.</div>
                    </div>
                </label>

                <!-- Role Card 2: Komunitas -->
                <label class="role-card relative p-3 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between {{ $currentRole === 'komunitas' ? 'border-[#205c3b] bg-emerald-50/40 ring-1 ring-[#205c3b]' : 'border-slate-200 bg-white hover:bg-slate-50' }}" data-role="komunitas">
                    <input type="radio" name="role" value="komunitas" class="hidden" {{ $currentRole === 'komunitas' ? 'checked' : '' }}>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full {{ $currentRole === 'komunitas' ? 'bg-[#205c3b]' : 'border border-slate-300' }} indicator-dot"></span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900">Komunitas</div>
                        <div class="text-[10px] text-slate-500 leading-tight mt-0.5">Publikasi aksi, kelola relawan & laporan dampak.</div>
                    </div>
                </label>

                <!-- Role Card 3: Admin -->
                <label class="role-card relative p-3 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between {{ $currentRole === 'admin' ? 'border-[#205c3b] bg-emerald-50/40 ring-1 ring-[#205c3b]' : 'border-slate-200 bg-white hover:bg-slate-50' }}" data-role="admin">
                    <input type="radio" name="role" value="admin" class="hidden" {{ $currentRole === 'admin' ? 'checked' : '' }}>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full {{ $currentRole === 'admin' ? 'bg-[#205c3b]' : 'border border-slate-300' }} indicator-dot"></span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900">Admin</div>
                        <div class="text-[10px] text-slate-500 leading-tight mt-0.5">Verifikasi organisasi & moderasi seluruh sistem.</div>
                    </div>
                </label>
            </div>

            <!-- Dynamic Role Description Banner -->
            <div id="role-info-banner" class="mt-3 p-3 rounded-xl bg-[#e8f5ec] border border-emerald-200/80 text-[11px] text-[#16432b] flex items-start gap-2">
                <svg class="w-4 h-4 text-[#205c3b] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span id="role-info-text">
                    <strong>Hak Akses Relawan:</strong> Dapat mendaftar kegiatan kerelawanan, mengakses modul pelatihan, mencatat jam kontribusi, serta mengunduh sertifikat aksi digital.
                </span>
            </div>
        </div>

        <!-- 2. EMAIL / NOMOR PONSEL -->
        <div>
            <label for="login" class="block text-xs font-semibold text-slate-700 mb-1.5">Email / Nomor Ponsel</label>
            <div class="relative">
                <input type="text"
                       id="login"
                       name="login"
                       value="{{ old('login') }}"
                       required
                       placeholder="nama@email.com"
                       class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-[#205c3b] focus:border-transparent transition-all">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 3. KATA SANDI -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-semibold text-slate-700">Kata Sandi</label>
                <a href="#" class="text-xs font-medium text-slate-500 hover:text-[#205c3b] transition-colors">Lupa kata sandi?</a>
            </div>
            <div class="relative">
                <input type="password"
                       id="password"
                       name="password"
                       required
                       placeholder="Minimal 8 karakter"
                       class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 pl-10 pr-10 focus:outline-none focus:ring-2 focus:ring-[#205c3b] focus:border-transparent transition-all">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <!-- Toggle Password Visibility -->
                <button type="button"
                        onclick="togglePasswordVisibility('password', this)"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- 4. INGAT SAYA -->
        <div class="flex items-center">
            <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-[#205c3b] border-slate-300 rounded focus:ring-[#205c3b]">
            <label for="remember" class="ml-2 block text-xs text-slate-600">Ingat saya di perangkat ini</label>
        </div>

        <!-- SUBMIT BUTTON -->
        <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-[#205c3b] hover:bg-[#16432b] text-white font-semibold text-sm shadow-md shadow-emerald-950/10 transition-all hover:scale-[1.01]">
            <span>Masuk ke Akun</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </button>

        <!-- DIVIDER -->
        <div class="relative my-4">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-200"></div></div>
            <div class="relative flex justify-center text-[10px] font-bold uppercase tracking-wider text-slate-400">
                <span class="bg-white px-3">ATAU LANJUTKAN DENGAN</span>
            </div>
        </div>

        <!-- GOOGLE OAUTH BUTTON -->
        <button type="button" class="w-full flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-sm transition-all">
            <svg class="w-4 h-4" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span>Lanjutkan dengan Google</span>
        </button>
    </form>
</div>
@endsection

@push('auth-scripts')
<script>
    // Info text mapping per role
    const roleDescriptions = {
        relawan: "<strong>Hak Akses Relawan:</strong> Dapat mendaftar kegiatan kerelawanan, mengakses modul pelatihan, mencatat jam kontribusi, serta mengunduh sertifikat aksi digital.",
        komunitas: "<strong>Hak Akses Komunitas:</strong> Dapat mempublikasikan kegiatan aksi sosial, merekrut & mengelola relawan lapangan, memvalidasi kehadiran, serta menerbitkan laporan dampak.",
        admin: "<strong>Hak Akses Admin:</strong> Dapat memverifikasi legalitas komunitas, memoderasi kegiatan yang tayang, serta mengawasi keamanan & ekosistem platform RuangRelawan."
    };

    // Role switcher logic
    document.querySelectorAll('.role-card').forEach(card => {
        card.addEventListener('click', function() {
            const role = this.getAttribute('data-role');

            // Reset all cards
            document.querySelectorAll('.role-card').forEach(c => {
                c.classList.remove('border-[#205c3b]', 'bg-emerald-50/40', 'ring-1', 'ring-[#205c3b]');
                c.classList.add('border-slate-200', 'bg-white');
                const dot = c.querySelector('.indicator-dot');
                if (dot) {
                    dot.classList.remove('bg-[#205c3b]');
                    dot.classList.add('border', 'border-slate-300');
                }
            });

            // Activate clicked card
            this.classList.remove('border-slate-200', 'bg-white');
            this.classList.add('border-[#205c3b]', 'bg-emerald-50/40', 'ring-1', 'ring-[#205c3b]');
            const activeDot = this.querySelector('.indicator-dot');
            if (activeDot) {
                activeDot.classList.remove('border', 'border-slate-300');
                activeDot.classList.add('bg-[#205c3b]');
            }

            // Update radio
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;

            // Update banner text
            document.getElementById('role-info-text').innerHTML = roleDescriptions[role] || roleDescriptions.relawan;
        });
    });

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.classList.add('text-[#205c3b]');
        } else {
            input.type = 'password';
            btn.classList.remove('text-[#205c3b]');
        }
    }
</script>
@endpush
