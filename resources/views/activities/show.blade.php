@extends('layouts.app')

@section('title', $activity->judul)

@section('content')
<main class="mx-auto max-w-5xl space-y-6 p-6">
    <a href="{{ route('activities.index') }}" class="font-semibold text-emerald-700">
        ← Kembali ke daftar aktivitas
    </a>

    <article class="space-y-6 rounded-2xl border bg-white p-6">
        @if ($activity->gambar_aktivitas)
            <img
                src="{{ asset('storage/' . $activity->gambar_aktivitas) }}"
                alt="{{ $activity->judul }}"
                class="max-h-96 w-full rounded-xl object-cover"
            >
        @endif

        <header>
            <p class="text-sm font-semibold text-emerald-700">
                {{ $activity->category?->nama_kategori ?? 'Aksi sosial' }}
            </p>
            <h1 class="mt-2 text-3xl font-bold">{{ $activity->judul }}</h1>
            <p class="mt-2 text-slate-600">
                Diselenggarakan oleh {{ $activity->community?->nama_community ?? 'Komunitas' }}
            </p>
        </header>

        <section>
            <h2 class="text-xl font-semibold">Tentang aktivitas</h2>
            <p class="mt-2 whitespace-pre-line">{{ $activity->deskripsi ?? 'Deskripsi belum tersedia.' }}</p>
        </section>

        <dl class="grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="font-semibold">Lokasi</dt>
                <dd>{{ $activity->lokasi ?? 'Belum ditentukan' }}</dd>
            </div>
            <div>
                <dt class="font-semibold">Mulai</dt>
                <dd>{{ $activity->tanggal_mulai?->format('d M Y, H:i') ?? 'Belum dijadwalkan' }}</dd>
            </div>
            <div>
                <dt class="font-semibold">Selesai</dt>
                <dd>{{ $activity->tanggal_selesai?->format('d M Y, H:i') ?? 'Belum dijadwalkan' }}</dd>
            </div>
            <div>
                <dt class="font-semibold">Kuota relawan</dt>
                <dd>{{ $activity->accepted_applications_count }} / {{ $activity->kuota_relawan ?? 'Belum ditentukan' }}</dd>
            </div>
        </dl>
    </article>
</main>
@endsection
