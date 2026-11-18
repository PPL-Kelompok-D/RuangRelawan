@extends('layouts.auth')

@section('title', 'Daftar Akun Baru')

@section('auth-content')
<div>
    <!-- Top Action Switcher: Masuk vs Daftar Baru -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Daftar Akun Baru</h2>
            <p class="text-xs text-slate-500 mt-1">Pilih peran Anda dan mulai berkolaborasi di RuangRelawan.</p>
        </div>

        <div class="flex items-center p-1 bg-slate-100 rounded-xl">
            <a href="{{ route('login') }}" class="px-4 py-1.5 rounded-lg text-xs font-medium text-slate-600 hover:text-slate-900 transition-all">
                Masuk
            </a>
            <a href="{{ route('register') }}" class="px-4 py-1.5 rounded-lg text-xs font-semibold bg-[#205c3b] text-white shadow-sm transition-all">
                Daftar Baru
            </a>
        </div>
    </div>

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

    <form action="{{ route('register.perform') }}" method="POST" class="space-y-4">
        @csrf

        <!-- 1. PILIH PERAN PENDAFTARAN (Relawan vs Komunitas) -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="text-[11px] font-bold uppercase tracking-wider text-slate-700">1. PILIH PERAN PENDAFTARAN</label>
                <span class="text-[10px] text-slate-400">Pilih tipe akun Anda</span>
            </div>

            @php $currentRole = old('role', 'relawan'); @endphp
            <div class="grid grid-cols-2 gap-3">
                <!-- Option 1: Relawan -->
                <label class="role-register-card p-3 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between {{ $currentRole === 'relawan' ? 'border-[#205c3b] bg-emerald-50/40 ring-1 ring-[#205c3b]' : 'border-slate-200 bg-white hover:bg-slate-50' }}" data-role="relawan">
                    <input type="radio" name="role" value="relawan" class="hidden" {{ $currentRole === 'relawan' ? 'checked' : '' }}>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100/70 text-[#205c3b] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full {{ $currentRole === 'relawan' ? 'bg-[#205c3b]' : 'border border-slate-300' }} reg-dot"></span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900">Sebagai Relawan (Individu)</div>
                        <div class="text-[10px] text-slate-500 mt-0.5 leading-tight">Ikuti aksi kerelawanan, dapatkan jam kontribusi & sertifikat.</div>
                    </div>
                </label>

                <!-- Option 2: Komunitas -->
                <label class="role-register-card p-3 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between {{ $currentRole === 'komunitas' ? 'border-[#205c3b] bg-emerald-50/40 ring-1 ring-[#205c3b]' : 'border-slate-200 bg-white hover:bg-slate-50' }}" data-role="komunitas">
                    <input type="radio" name="role" value="komunitas" class="hidden" {{ $currentRole === 'komunitas' ? 'checked' : '' }}>
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="w-2.5 h-2.5 rounded-full {{ $currentRole === 'komunitas' ? 'bg-[#205c3b]' : 'border border-slate-300' }} reg-dot"></span>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-900">Sebagai Komunitas / Yayasan</div>
                        <div class="text-[10px] text-slate-500 mt-0.5 leading-tight">Buat program sosial, rekrut relawan & kelola aksi.</div>
                    </div>
                </label>
            </div>
        </div>

        <!-- 2. DATA FORM INPUT -->
        <!-- Field Nama Lengkap / Komunitas -->
        <div>
            <label id="name-label" for="name" class="block text-xs font-semibold text-slate-700 mb-1">
                {{ $currentRole === 'komunitas' ? 'Nama Komunitas / Organisasi' : 'Nama Lengkap Sesuai KTP' }} <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       placeholder="Contoh: Rian Ramadhan Kusuma"
                       class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-[#205c3b] focus:border-transparent transition-all">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 2 Kolom: Email & WhatsApp -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label id="email-label" for="email" class="block text-xs font-semibold text-slate-700 mb-1">
                    {{ $currentRole === 'komunitas' ? 'Email Resmi Komunitas' : 'Alamat Email Aktif' }} <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="email"
                           id="email"
                           name="email"
                           value="{{ old('email') }}"
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

            <div>
                <label for="phone" class="block text-xs font-semibold text-slate-700 mb-1">
                    Nomor WhatsApp / Ponsel <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="text"
                           id="phone"
                           name="phone"
                           value="{{ old('phone') }}"
                           required
                           placeholder="+62 812-3456-7890"
                           class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-[#205c3b] focus:border-transparent transition-all">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Field Khusus Komunitas: Kota Basis (Tampil jika Komunitas dipilih) -->
        <div id="city-field" class="{{ $currentRole === 'komunitas' ? 'block' : 'hidden' }}">
            <label for="city" class="block text-xs font-semibold text-slate-700 mb-1">
                Kota / Domisili Basis Komunitas <span class="text-rose-500">*</span>
            </label>
            <div class="relative">
                <input type="text"
                       id="city"
                       name="city"
                       value="{{ old('city') }}"
                       placeholder="Contoh: Kota Bandung, Jawa Barat"
                       class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-[#205c3b] focus:border-transparent transition-all">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 2 Kolom: Kata Sandi & Konfirmasi -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">
                    Kata Sandi <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           placeholder="Minimal 8 karakter"
                           class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-[#205c3b] focus:border-transparent transition-all">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">
                    Ulangi Kata Sandi <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           required
                           placeholder="Ulangi kata sandi"
                           class="w-full text-sm rounded-xl border border-slate-200 px-3.5 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-[#205c3b] focus:border-transparent transition-all">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checkbox Syarat & Ketentuan -->
        <div class="flex items-start pt-1">
            <input type="checkbox" id="terms" name="terms" required class="w-4 h-4 mt-0.5 text-[#205c3b] border-slate-300 rounded focus:ring-[#205c3b]">
            <label for="terms" class="ml-2 block text-xs text-slate-600 leading-relaxed">
                Saya menyetujui <a href="#" class="text-[#205c3b] font-semibold underline">Syarat & Ketentuan</a> serta <a href="#" class="text-[#205c3b] font-semibold underline">Kebijakan Privasi</a> RuangRelawan.
            </label>
        </div>

        <!-- SUBMIT BUTTON -->
        <button type="submit" class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-[#205c3b] hover:bg-[#16432b] text-white font-semibold text-sm shadow-md shadow-emerald-950/10 transition-all hover:scale-[1.01] mt-2">
            <span>Daftar Akun Sekarang</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </button>
    </form>
</div>
@endsection

@push('auth-scripts')
<script>
    document.querySelectorAll('.role-register-card').forEach(card => {
        card.addEventListener('click', function() {
            const role = this.getAttribute('data-role');

            // Reset cards
            document.querySelectorAll('.role-register-card').forEach(c => {
                c.classList.remove('border-[#205c3b]', 'bg-emerald-50/40', 'ring-1', 'ring-[#205c3b]');
                c.classList.add('border-slate-200', 'bg-white');
                const dot = c.querySelector('.reg-dot');
                if (dot) {
                    dot.classList.remove('bg-[#205c3b]');
                    dot.classList.add('border', 'border-slate-300');
                }
            });

            // Activate current card
            this.classList.remove('border-slate-200', 'bg-white');
            this.classList.add('border-[#205c3b]', 'bg-emerald-50/40', 'ring-1', 'ring-[#205c3b]');
            const activeDot = this.querySelector('.reg-dot');
            if (activeDot) {
                activeDot.classList.remove('border', 'border-slate-300');
                activeDot.classList.add('bg-[#205c3b]');
            }

            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;

            // Dynamically adjust label text
            const nameLabel = document.getElementById('name-label');
            const nameInput = document.getElementById('name');
            const emailLabel = document.getElementById('email-label');
            const cityField = document.getElementById('city-field');

            if (role === 'komunitas') {
                nameLabel.innerHTML = 'Nama Komunitas / Organisasi <span class="text-rose-500">*</span>';
                nameInput.placeholder = 'Contoh: Komunitas Sahabat Bumi';
                emailLabel.innerHTML = 'Email Resmi Komunitas <span class="text-rose-500">*</span>';
                cityField.classList.remove('hidden');
                document.getElementById('city').required = true;
            } else {
                nameLabel.innerHTML = 'Nama Lengkap Sesuai KTP <span class="text-rose-500">*</span>';
                nameInput.placeholder = 'Contoh: Rian Ramadhan Kusuma';
                emailLabel.innerHTML = 'Alamat Email Aktif <span class="text-rose-500">*</span>';
                cityField.classList.add('hidden');
                document.getElementById('city').required = false;
            }
        });
    });
</script>
@endpush
