@extends('layouts.app')

@section('title', 'Aktivitas Saya - Komunitas')

@section('content')
<section class="mx-auto w-full max-w-[1152px] rounded-[24px] border border-[#E2E8D8] bg-white p-6 shadow-sm md:p-8">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end"><div><p class="text-sm font-semibold uppercase tracking-wide text-[#2D6A4F]">{{ $community->nama_community }}</p><h1 class="mt-2 text-2xl font-bold text-slate-900 md:text-3xl">Aktivitas Saya</h1><p class="mt-2 text-slate-600">Kelola pendaftar, donasi, dokumentasi, dan status kegiatan.</p></div><a href="{{ route('community.activities.create') }}" class="rounded-xl bg-[#2D6A4F] px-5 py-3 text-center text-sm font-semibold text-white hover:bg-[#24583f]">+ Buat aktivitas</a></div>
    @if(session('success'))<div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>@endif
    @if(session('info'))<div class="mt-5 rounded-xl border border-sky-200 bg-sky-50 p-4 text-sm text-sky-800">{{ session('info') }}</div>@endif
    <div class="mt-7 grid gap-4">
        @forelse($activities as $activity)
            @php($statusClass = match($activity->status_aktivitas) { 'dipublikasikan' => 'border-emerald-200 bg-emerald-50 text-emerald-800', 'ditolak' => 'border-rose-200 bg-rose-50 text-rose-800', 'selesai' => 'border-slate-200 bg-slate-100 text-slate-700', default => 'border-amber-200 bg-amber-50 text-amber-800' })
            <article class="rounded-2xl border border-[#E2E8D8] bg-white p-5">
                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start"><div><p class="text-xs font-semibold uppercase tracking-wide text-[#2D6A4F]">{{ $activity->category?->nama_kategori ?? 'Aktivitas' }}</p><h2 class="mt-1 text-lg font-bold text-slate-900">{{ $activity->judul }}</h2><p class="mt-1 text-sm text-slate-600">{{ $activity->lokasi }} · {{ $activity->tanggal_mulai?->format('d M Y, H:i') ?? 'Jadwal belum tersedia' }}</p></div><span class="w-fit rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ str_replace('_', ' ', ucfirst($activity->status_aktivitas)) }}</span></div>
                @if($activity->activityVerification?->catatan && $activity->status_aktivitas === 'ditolak')<p class="mt-3 rounded-xl bg-rose-50 p-3 text-sm text-rose-800">Catatan admin: {{ $activity->activityVerification->catatan }}</p>@endif
                <div class="mt-4 flex flex-wrap gap-2 border-t border-[#E2E8D8] pt-4">
                    <a href="{{ route('community.activities.applicants.index', $activity) }}" class="rounded-xl bg-[#F4F6F0] px-3 py-2 text-sm font-semibold text-[#2D6A4F] hover:bg-[#E2E8D8]">Kelola Relawan ({{ $activity->applications_count }})</a>
                    <a href="{{ route('community.activities.documentations.index', $activity) }}" class="rounded-xl bg-[#F4F6F0] px-3 py-2 text-sm font-semibold text-[#2D6A4F] hover:bg-[#E2E8D8]">Galeri Dokumentasi ({{ $activity->documentations_count }})</a>
                    <a href="{{ route('community.donations.index', $activity) }}" class="rounded-xl bg-[#F4F6F0] px-3 py-2 text-sm font-semibold text-[#2D6A4F] hover:bg-[#E2E8D8]">Cek Donasi Masuk ({{ $activity->donations_count }})</a>
                    @if($activity->status_aktivitas === 'dipublikasikan' && ! $activity->completion)
                        <a href="{{ route('community.activities.complete', $activity->id) }}" class="rounded-xl bg-[#2D6A4F] px-3 py-2 text-sm font-semibold text-white hover:bg-[#24583f]">Ajukan Penyelesaian & Dokumentasi</a>
                    @elseif($activity->status_aktivitas === 'selesai')
                        <a href="{{ route('activities.show', $activity->id) }}" class="rounded-xl bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">Lihat Detail Aktivitas</a>
                    @endif
                    @if(in_array($activity->status_aktivitas, ['menunggu_verifikasi', 'ditolak'], true))
                        <a href="{{ route('community.activities.edit', $activity) }}" class="rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Edit Aktivitas</a>
                    @endif
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-[#E2E8D8] bg-[#F4F6F0] px-6 py-12 text-center"><p class="font-semibold text-slate-900">Belum ada aktivitas</p><p class="mt-1 text-sm text-slate-600">Buat aktivitas pertama komunitas Anda.</p></div>
        @endforelse
    </div>
    <div class="mt-5">{{ $activities->links() }}</div>
</section>
@endsection
