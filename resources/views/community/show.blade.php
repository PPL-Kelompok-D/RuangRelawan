@extends('layouts.app')
@section('title', $community->nama_community)
@section('content')
<section class="mx-auto w-full max-w-[1152px] rounded-[24px] border border-[#E2E8D8] bg-white p-6 shadow-sm md:p-8">
    <a href="{{ route('home') }}#komunitas" class="text-sm font-semibold text-[#2D6A4F] hover:underline">← Kembali ke komunitas</a>
    <header class="mt-5 flex flex-col gap-5 rounded-3xl bg-[#F4F6F0] p-5 sm:flex-row sm:items-start md:p-7">
        @if($community->logo)
            @php($logoSrc = filter_var($community->logo, FILTER_VALIDATE_URL) ? $community->logo : asset('storage/'.$community->logo))
            <img src="{{ $logoSrc }}" alt="Logo {{ $community->nama_community }}" class="h-24 w-24 shrink-0 rounded-2xl border border-[#E2E8D8] bg-white object-cover">
        @else
            <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-2xl bg-[#205c3b] text-3xl font-bold text-white">{{ mb_substr($community->nama_community, 0, 1) }}</div>
        @endif
        <div class="min-w-0 flex-1"><p class="text-sm font-semibold uppercase tracking-wide text-[#2D6A4F]">Komunitas RuangRelawan</p><h1 class="mt-1 text-2xl font-bold text-slate-900 md:text-3xl">{{ $community->nama_community }}</h1><div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-sm text-slate-600">@if($community->kota)<span>{{ $community->kota }}</span>@endif @if($community->kontak)<span>Kontak: {{ $community->kontak }}</span>@endif</div>@if($community->deskripsi)<p class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-700">{{ $community->deskripsi }}</p>@endif</div>
    </header>

    <div class="mt-7 flex flex-wrap gap-2 border-b border-slate-200" role="tablist" aria-label="Informasi komunitas">
        <button type="button" data-gallery-tab="gallery" class="rounded-t-xl border-b-2 border-[#2D6A4F] px-4 py-3 text-sm font-semibold text-[#2D6A4F]">Galeri Foto Komunitas <span class="ml-1 text-xs">({{ $community->documentations->count() }})</span></button>
        <button type="button" data-gallery-tab="active" class="rounded-t-xl border-b-2 border-transparent px-4 py-3 text-sm font-semibold text-slate-500">Aktivitas Aktif <span class="ml-1 text-xs">({{ $activeActivities->count() }})</span></button>
        <button type="button" data-gallery-tab="completed" class="rounded-t-xl border-b-2 border-transparent px-4 py-3 text-sm font-semibold text-slate-500">Aktivitas Selesai <span class="ml-1 text-xs">({{ $completedActivities->count() }})</span></button>
    </div>

    <section data-gallery-panel="gallery" class="pt-6"><h2 class="text-xl font-bold text-slate-900">Galeri Foto Komunitas</h2><div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">@forelse($community->documentations as $photo)<figure class="group relative overflow-hidden rounded-2xl border border-[#E2E8D8] bg-[#F4F6F0]"><img src="{{ asset('storage/'.$photo->file_dokumentasi) }}" alt="{{ $photo->keterangan ?: 'Foto '.$community->nama_community }}" title="{{ $photo->keterangan }}" class="h-56 w-full object-cover"><figcaption class="p-4"><p class="line-clamp-2 text-sm text-slate-700" title="{{ $photo->keterangan }}">{{ $photo->keterangan ?: 'Dokumentasi kegiatan komunitas' }}</p>@if($photo->activity)<a href="{{ route('activities.show', $photo->activity) }}" class="mt-2 inline-block text-xs font-semibold text-[#2D6A4F] hover:underline">{{ $photo->activity->judul }}</a>@endif</figcaption></figure>@empty<div class="rounded-2xl border border-dashed border-[#E2E8D8] p-10 text-center text-sm text-slate-500 sm:col-span-2 lg:col-span-3">Komunitas ini belum menambahkan foto ke galeri.</div>@endforelse</div></section>

    <section data-gallery-panel="active" class="hidden pt-6"><h2 class="text-xl font-bold text-slate-900">Aktivitas Aktif</h2><div class="mt-4 grid gap-4 md:grid-cols-2">@forelse($activeActivities as $activity)@include('communities._activity_card', ['activity' => $activity])@empty<div class="rounded-2xl border border-dashed border-[#E2E8D8] p-10 text-center text-sm text-slate-500 md:col-span-2">Belum ada aktivitas aktif.</div>@endforelse</div></section>

    <section data-gallery-panel="completed" class="hidden pt-6"><h2 class="text-xl font-bold text-slate-900">Aktivitas Selesai</h2><div class="mt-4 grid gap-4 md:grid-cols-2">@forelse($completedActivities as $activity)@include('communities._activity_card', ['activity' => $activity])@empty<div class="rounded-2xl border border-dashed border-[#E2E8D8] p-10 text-center text-sm text-slate-500 md:col-span-2">Belum ada aktivitas selesai.</div>@endforelse</div></section>
</section>
<script>
    document.querySelectorAll('[data-gallery-tab]').forEach((tab) => {
        tab.addEventListener('click', () => {
            const selected = tab.dataset.galleryTab;
            document.querySelectorAll('[data-gallery-panel]').forEach((panel) => panel.classList.toggle('hidden', panel.dataset.galleryPanel !== selected));
            document.querySelectorAll('[data-gallery-tab]').forEach((button) => {
                const active = button === tab;
                button.classList.toggle('border-[#2D6A4F]', active);
                button.classList.toggle('text-[#2D6A4F]', active);
                button.classList.toggle('border-transparent', ! active);
                button.classList.toggle('text-slate-500', ! active);
            });
        });
    });
</script>
@endsection
