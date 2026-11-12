@extends('layouts.app')

@section('title', 'Profil Komunitas')

@section('content')
<section class="max-w-[1152px] w-full mx-auto bg-white border border-[#E2E8D8] rounded-[24px] shadow-[0px_20px_25px_-5px_rgba(0,0,0,0.1),0px_8px_10px_-6px_rgba(0,0,0,0.1)] p-6 md:p-8">
    <div class="mb-8"><p class="text-sm font-semibold text-[#2D6A4F]">AKUN KOMUNITAS</p><h1 class="mt-2 text-2xl md:text-3xl font-bold text-slate-900">Profil Komunitas</h1><p class="mt-2 text-slate-600">Informasi ini membantu relawan memahami organisasi dan kegiatan Anda.</p></div>
    @if(session('success'))<div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    <form action="{{ route('community.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div class="md:col-span-2"><label for="nama_community" class="mb-2 block text-sm font-semibold text-slate-700">Nama komunitas</label><input id="nama_community" name="nama_community" value="{{ old('nama_community', $user->community?->nama_community ?? $user->name) }}" required maxlength="255" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20"></div>
            <div class="md:col-span-2"><label for="deskripsi" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi</label><textarea id="deskripsi" name="deskripsi" rows="5" maxlength="5000" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">{{ old('deskripsi', $user->community?->deskripsi) }}</textarea></div>
            <div><label for="kota" class="mb-2 block text-sm font-semibold text-slate-700">Kota</label><input id="kota" name="kota" value="{{ old('kota', $user->community?->kota) }}" maxlength="150" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20"></div>
            <div><label for="kontak" class="mb-2 block text-sm font-semibold text-slate-700">Kontak</label><input id="kontak" name="kontak" value="{{ old('kontak', $user->community?->kontak) }}" maxlength="50" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20"></div>
            <div class="md:col-span-2"><label for="alamat" class="mb-2 block text-sm font-semibold text-slate-700">Alamat</label><textarea id="alamat" name="alamat" rows="3" maxlength="1000" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">{{ old('alamat', $user->community?->alamat) }}</textarea></div>
            <div class="md:col-span-2"><label for="logo" class="mb-2 block text-sm font-semibold text-slate-700">Logo (JPG, PNG, atau WebP; maks. 2 MB)</label><input id="logo" name="logo" type="file" accept="image/jpeg,image/png,image/webp" class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-sm">@if($user->community?->logo)<img src="{{ asset('storage/'.$user->community->logo) }}" alt="Logo {{ $user->community->nama_community }}" class="mt-3 h-20 w-20 rounded-xl object-cover">@endif</div>
        </div>
        <div class="flex justify-end border-t border-slate-100 pt-5"><button type="submit" class="rounded-xl bg-[#2D6A4F] px-6 py-3 font-semibold text-white transition hover:bg-[#24583f]">Simpan perubahan</button></div>
    </form>
</section>
@endsection