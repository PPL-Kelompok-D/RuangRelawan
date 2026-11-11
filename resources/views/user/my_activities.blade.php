@extends('layouts.app')

@section('title', 'Aktivitas Saya')

@section('content')
<div class="mx-auto max-w-6xl">

    <div class="mb-7">
        <p class="text-sm font-semibold text-[#2D6A4F]">DASHBOARD RELAWAN</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-900">
            Aktivitas Saya
        </h1>
        <p class="mt-2 text-sm text-slate-500">
            Lihat daftar aktivitas yang sudah kamu daftarkan.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="mb-5 flex items-center justify-between">
        <h2 class="font-bold text-slate-800">Riwayat Pendaftaran</h2>
        <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
            {{ $applications->total() }} Pendaftaran
        </span>
    </div>

    @forelse($applications as $application)
        <div class="mb-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-3">

                <div>
                    <h3 class="font-bold text-slate-800">
                        Pendaftaran Aktivitas #{{ $application->activity_id }}
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Didaftarkan pada
                        {{ $application->created_at?->format('d M Y, H:i') ?? '-' }}
                    </p>
                </div>

                <span class="rounded-full px-3 py-1 text-xs font-bold
                    {{ $application->status === 'diterima'
                        ? 'bg-green-100 text-green-700'
                        : ($application->status === 'ditolak'
                            ? 'bg-red-100 text-red-700'
                            : 'bg-yellow-100 text-yellow-700') }}">
                    {{ ucfirst($application->status) }}
                </span>

            </div>

            @if($application->motivation)
                <div class="mt-4 rounded-xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold text-slate-600">
                        Motivasi Pendaftaran
                    </p>
                    <p class="mt-2 text-sm text-slate-600">
                        {{ $application->motivation }}
                    </p>
                </div>
            @endif
        </div>
    @empty
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
            <div class="text-4xl">🌱</div>
            <h3 class="mt-4 font-bold text-slate-800">
                Belum Ada Pendaftaran
            </h3>
            <p class="mt-2 text-sm text-slate-500">
                Aktivitas yang kamu daftarkan akan muncul di halaman ini.
            </p>
            <a href="{{ route('home') }}"
               class="mt-5 inline-block rounded-xl bg-[#2D6A4F] px-5 py-3 text-sm font-semibold text-white">
                Jelajahi Aktivitas
            </a>
        </div>
    @endforelse

    <div class="mt-5">
        {{ $applications->links() }}
    </div>

</div>
@endsection
