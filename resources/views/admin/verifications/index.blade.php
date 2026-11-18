
@extends('layouts.app')

@section('title', 'Verifikasi Aktivitas')

@section('content')
<section class="max-w-[1152px] w-full mx-auto bg-white border border-[#E2E8D8] rounded-[24px] shadow-[0px_20px_25px_-5px_rgba(0,0,0,0.1),0px_8px_10px_-6px_rgba(0,0,0,0.1)] p-6 md:p-8">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-[#2D6A4F]">Panel Admin</p>
            <h1 class="mt-2 text-2xl font-bold text-slate-900 md:text-3xl">Antrean verifikasi aktivitas</h1>
            <p class="mt-2 text-slate-600">Tinjau kelengkapan dan kelayakan pengajuan sebelum dipublikasikan.</p>
        </div>
        <span class="inline-flex w-fit rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800">
            {{ $activities->total() }} menunggu
        </span>
    </div>

    @if(session('success'))
        <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-8 grid grid-cols-1 gap-4">
        @forelse($activities as $activity)
            <article class="rounded-2xl border border-slate-200 p-5 transition hover:border-[#2D6A4F]/40 hover:shadow-sm md:p-6">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                    <div class="min-w-0">
                        <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-800">
                            Menunggu verifikasi
                        </span>

                        <h2 class="mt-3 text-lg font-bold text-slate-900">
                            {{ $activity->judul ?? $activity->title }}
                        </h2>

                        <p class="mt-1 text-sm text-slate-600">
                            {{ $activity->community?->nama_community }} ·
                            {{ $activity->category?->nama_kategori ?? 'Tanpa kategori' }}
                        </p>

                        <div class="mt-4 flex flex-wrap gap-x-5 gap-y-2 text-sm text-slate-600">
                            <span>📍 {{ $activity->lokasi ?? $activity->location }}</span>
                            <span>🗓 {{ $activity->tanggal_mulai?->format('d M Y H:i') ?? 'Jadwal belum tersedia' }}</span>
                            <span>Dikirim {{ $activity->dibuat_pada?->diffForHumans() }}</span>
                        </div>
                    </div>

                    <a href="{{ route('admin.verifications.show', $activity) }}"
                        class="shrink-0 rounded-xl bg-[#2D6A4F] px-5 py-3 text-center text-sm font-semibold text-white hover:bg-[#24583f]">
                        Tinjau pengajuan
                    </a>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-[#E2E8D8] bg-[#F4F6F0] px-6 py-14 text-center">
                <p class="font-semibold text-slate-900">Antrean kosong</p>
                <p class="mt-1 text-sm text-slate-600">Semua pengajuan aktivitas sudah ditinjau.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $activities->links() }}
    </div>
</section>
@endsection
