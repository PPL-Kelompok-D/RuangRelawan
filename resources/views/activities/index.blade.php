@extends('layouts.app')

@section('title', 'Jelajahi Aktivitas Relawan')

@section('content')
<div class="mx-auto max-w-[1152px] space-y-8">
    <!-- Hero / Header -->
    <header class="rounded-[24px] border border-[#E2E8D8] bg-white p-6 shadow-sm md:p-10">
        <p class="text-sm font-semibold uppercase tracking-wide text-[#2D6A4F]">Platform RuangRelawan</p>
        <h1 class="mt-2 text-3xl font-bold text-slate-900 md:text-4xl">Temukan Aktivitas &amp; Buat Dampak Nyata</h1>
        <p class="mt-3 text-slate-600 max-w-2xl">Bergabunglah bersama ribuan relawan lainnya untuk membantu komunitas dan aksi sosial di seluruh Indonesia.</p>

        <!-- Search Form -->
        <form action="{{ route('activities.index') }}" method="GET" class="mt-6 flex flex-col gap-3 sm:flex-row">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari aktivitas, lokasi, atau topik..." class="flex-1 rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
            <button type="submit" class="rounded-xl bg-[#2D6A4F] px-6 py-3 font-semibold text-white transition hover:bg-[#24583f] text-sm">Cari Aktivitas</button>
        </form>
    </header>

    <!-- Activity List -->
    <section>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-slate-900">Aktivitas Tersedia</h2>
            <span class="text-sm text-slate-500">{{ $activities->count() }} aktivitas</span>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($activities as $activity)
                <article class="flex flex-col overflow-hidden rounded-[24px] border border-[#E2E8D8] bg-white shadow-sm transition hover:shadow-md">
                    <div class="h-48 w-full overflow-hidden bg-[#E8F0E9]">
                        @if($activity->gambar_aktivitas)
                            <img src="{{ asset('storage/'.$activity->gambar_aktivitas) }}" alt="{{ $activity->judul }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center font-bold text-[#2D6A4F]">{{ $activity->judul }}</div>
                        @endif
                    </div>
                    <div class="flex flex-1 flex-col p-5">
                        <span class="w-fit rounded-full bg-[#F4F6F0] px-3 py-1 text-xs font-semibold text-[#2D6A4F]">{{ $activity->category?->nama_kategori ?? 'Aksi Sosial' }}</span>
                        <h3 class="mt-3 text-lg font-bold text-slate-900 line-clamp-2">{{ $activity->judul }}</h3>
                        <p class="mt-2 text-sm text-slate-600 line-clamp-2">{{ $activity->deskripsi }}</p>
                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-500">{{ $activity->lokasi }}</span>
                            <a href="{{ route('activities.show', $activity) }}" class="rounded-lg bg-[#2D6A4F] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#24583f]">Detail</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-2xl border border-dashed border-[#E2E8D8] bg-white p-12 text-center">
                    <p class="font-semibold text-slate-900">Belum ada aktivitas yang dipublikasikan</p>
                    <p class="mt-1 text-sm text-slate-500">Cek kembali nanti untuk melihat aktivitas baru.</p>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
