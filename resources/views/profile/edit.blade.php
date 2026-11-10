@extends('layouts.app')

@section('title', 'Profil Relawan')

@section('content')
<section class="max-w-[1152px] w-full mx-auto bg-white border border-[#E2E8D8] rounded-[24px] shadow-[0px_20px_25px_-5px_rgba(0,0,0,0.1),0px_8px_10px_-6px_rgba(0,0,0,0.1)] p-6 md:p-8">
    <div class="mb-8">
        <p class="text-sm font-semibold text-[#2D6A4F]">AKUN RELAWAN</p>
        <h1 class="mt-2 text-2xl md:text-3xl font-bold text-slate-900">Profil Saya</h1>
        <p class="mt-2 text-slate-600">Lengkapi data diri agar komunitas dapat mengenal Anda saat mendaftar kegiatan.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div class="md:col-span-2">
                <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nama lengkap</label>
                <input id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
            </div>
            <div>
                <label for="no_hp" class="mb-2 block text-sm font-semibold text-slate-700">Nomor telepon</label>
                <input id="no_hp" name="no_hp" type="tel" value="{{ old('no_hp', $user->profilUser?->no_hp) }}" maxlength="25" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
            </div>
            <div>
                <label for="tanggal_lahir" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal lahir</label>
                <input id="tanggal_lahir" name="tanggal_lahir" type="date" max="{{ now()->toDateString() }}" value="{{ old('tanggal_lahir', $user->profilUser?->tanggal_lahir?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
            </div>
            <div>
                <label for="jenis_kelamin" class="mb-2 block text-sm font-semibold text-slate-700">Jenis kelamin</label>
                <select id="jenis_kelamin" name="jenis_kelamin" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
                    <option value="">Pilih jenis kelamin</option>
                    @foreach(['Laki-laki', 'Perempuan', 'Memilih Tidak Menyebutkan'] as $gender)
                        <option value="{{ $gender }}" @selected(old('jenis_kelamin', $user->profilUser?->jenis_kelamin) === $gender)>{{ $gender }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="preferensi_lokasi" class="mb-2 block text-sm font-semibold text-slate-700">Preferensi lokasi</label>
                <input id="preferensi_lokasi" name="preferensi_lokasi" value="{{ old('preferensi_lokasi', $user->profilUser?->preferensi_lokasi) }}" maxlength="150" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
            </div>
            <div class="md:col-span-2">
                <label for="alamat" class="mb-2 block text-sm font-semibold text-slate-700">Alamat</label>
                <textarea id="alamat" name="alamat" rows="4" maxlength="1000" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">{{ old('alamat', $user->profilUser?->alamat) }}</textarea>
            </div>
            <div class="md:col-span-2">
                <label for="foto_profil" class="mb-2 block text-sm font-semibold text-slate-700">Foto profil (JPG, PNG, atau WebP; maks. 2 MB)</label>
                <input id="foto_profil" name="foto_profil" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">
                @if($user->profilUser?->foto_profil)
                    <div class="mt-3 flex items-center gap-4">
                        <img src="{{ asset('storage/'.$user->profilUser->foto_profil) }}" alt="Foto profil" class="h-16 w-16 rounded-full object-cover">
                        <form action="{{ route('profile.destroy-avatar') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-rose-600 hover:underline">Hapus Foto</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
        <div class="flex justify-end border-t border-slate-100 pt-5">
            <button type="submit" class="rounded-xl bg-[#2D6A4F] px-6 py-3 font-semibold text-white transition hover:bg-[#24583f]">Simpan perubahan</button>
        </div>
    </form>
</section>
@endsection
