@extends('layouts.app')

@section('title', 'Eksplorasi Aktivitas')

@section('content')

@php
$activityCards = $activities->map(function ($activity) {
    $quota = (int) ($activity->kuota_relawan ?? 0);
    $accepted = (int) $activity->accepted_applications_count;
    $remaining = max(0, $quota - $accepted);
    $percentage = $quota > 0 ? min(100, (int) round(($accepted / $quota) * 100)) : 0;
    $startDate = $activity->tanggal_mulai;
    $daysUntilStart = $startDate?->copy()->startOfDay()->diffInDays(now()->startOfDay(), false);

    if ($quota > 0 && $remaining === 0) {
        $status = 'Kuota Penuh';
    } elseif ($daysUntilStart !== null && $daysUntilStart >= -7 && $daysUntilStart <= 0) {
        $status = 'Segera Dimulai';
    } elseif ($quota > 0 && $remaining <= 5) {
        $status = 'Tersisa '.$remaining.' Kuota';
    } else {
        $status = 'Pendaftaran Terbuka';
    }

    return [
        'title' => $activity->judul,
        'description' => $activity->deskripsi ?? '',
        'community' => $activity->community?->nama_community ?? 'Komunitas RuangRelawan',
        'category' => $activity->category?->nama_kategori ?? 'Aksi Sosial',
        'date' => $startDate?->format('d M Y') ?? 'Jadwal menyusul',
        'location' => $activity->lokasi ?? 'Lokasi menyusul',
        'accepted' => $accepted,
        'quota' => $quota,
        'percentage' => $percentage,
        'remaining' => $remaining,
        'status' => $status,
        'image' => $activity->gambar_aktivitas ? asset('storage/'.$activity->gambar_aktivitas) : asset('images/default-activity.svg'),
        'url' => route('activities.show', $activity),
    ];
});

$communityCards = $communities->map(function ($community) {
    $initial = collect(explode(' ', trim($community->nama_community)))
        ->filter()
        ->take(2)
        ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
        ->implode('');
    $volunteers = (int) ($community->accepted_volunteers_count ?? 0);

    return [
        'initial' => $initial,
        'name' => $community->nama_community,
        'badge' => $community->status_verifikasi === 'verified' ? 'Mitra Terverifikasi' : 'Komunitas',
        'description' => $community->deskripsi ?: ($community->kota ?? 'Komunitas penggerak aksi sosial dan lingkungan.'),
        'activities' => $community->activities_count ?? 0,
        'volunteers' => number_format($volunteers, 0, ',', '.'),
        'url' => route('communities.show', $community),
    ];
});

$filterCategories = $categories->pluck('nama_kategori')->filter()->unique()->sort()->values();
$statCards = [
    ['label' => 'Relawan terdaftar', 'value' => number_format($statistics['relawan_aktif'], 0, ',', '.'), 'note' => 'Akun relawan di RuangRelawan', 'icon' => '♥', 'color' => 'bg-green-100 text-green-800'],
    ['label' => 'Komunitas terdaftar', 'value' => number_format($statistics['komunitas_terdaftar'], 0, ',', '.'), 'note' => 'Organisasi di RuangRelawan', 'icon' => '⬟', 'color' => 'bg-green-100 text-green-800'],
    ['label' => 'Aksi selesai', 'value' => number_format($statistics['aksi_selesai'], 0, ',', '.'), 'note' => 'Program terlaksana', 'icon' => '✓', 'color' => 'bg-green-100 text-green-800'],
    ['label' => 'Donasi diterima', 'value' => 'Rp '.number_format($statistics['total_donasi'], 0, ',', '.'), 'note' => 'Total donasi terverifikasi', 'icon' => '◈', 'color' => 'bg-orange-100 text-orange-700'],
];
@endphp


{{-- ========================================================= --}}
{{-- FONT --}}
{{-- ========================================================= --}}

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">


{{-- ========================================================= --}}
{{-- CUSTOM CSS --}}
{{-- ========================================================= --}}

<style>

    .ruangrelawan-page {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #172033;
    }

    .ruangrelawan-page h1,
    .ruangrelawan-page h2,
    .ruangrelawan-page h3,
    .ruangrelawan-page button,
    .ruangrelawan-page input,
    .ruangrelawan-page select {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .ruangrelawan-page h1,
    .ruangrelawan-page h2,
    .ruangrelawan-page h3 {
        letter-spacing: -0.035em;
    }

    .activity-card,
    .community-card {
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .activity-card:hover,
    .community-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(24, 65, 39, .09);
    }

    .activity-image {
        transition: transform .5s ease;
    }

    .activity-card:hover .activity-image {
        transform: scale(1.06);
    }

    .filter-select {
        cursor: pointer;
        outline: none;
    }

    .filter-select:focus {
        box-shadow: 0 0 0 3px rgba(57, 125, 56, .13);
    }

    .status-open {
        background: #D9F99D;
        color: #365314;
    }

    .status-limited {
        background: #FFEDD5;
        color: #C2410C;
    }

    .status-soon {
        background: #DBEAFE;
        color: #1D4ED8;
    }

    .status-full {
        background: #FEE2E2;
        color: #B91C1C;
    }

    .button-full {
        background: #E5E7EB !important;
        color: #6B7280 !important;
        cursor: not-allowed !important;
        pointer-events: none;
        opacity: 1;
        box-shadow: none !important;
    }

    .activity-card[hidden] {
        display: none !important;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

</style>


<div class="ruangrelawan-page mx-auto w-full max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">


{{-- ========================================================= --}}
{{-- HERO BANNER --}}
{{-- ========================================================= --}}

<section
    class="relative flex min-h-[300px] items-center overflow-hidden rounded-[24px] bg-cover bg-center px-7 py-12 text-white shadow-sm md:min-h-[370px] md:px-11"
    style="background-image: linear-gradient(90deg, rgba(12,48,25,.88), rgba(20,75,35,.65), rgba(20,75,35,.35)), url('https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=1800');">

    <div class="relative z-10 max-w-[680px]">

        <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-2 text-xs font-bold text-white backdrop-blur">

            <span class="h-2 w-2 rounded-full bg-lime-300"></span>

            Platform Relawan Lingkungan Terverifikasi

        </span>

        <h1 class="mt-6 text-4xl font-extrabold leading-tight tracking-tight text-white md:text-5xl">

            Teman aksi nyata di sekitarmu

        </h1>

        <p class="mt-4 max-w-[560px] text-sm leading-7 text-white/90 md:text-base">

            Temukan gerakan sosial dan lingkungan terdekat,
            ambil peran sebagai relawan, dan wujudkan dampak
            positif bersama ribuan penggerak perubahan.

        </p>

        <div class="mt-6 flex flex-wrap gap-3">

            <a href="#aktivitas"
               class="rounded-full bg-white px-6 py-3 text-sm font-bold text-green-900 transition hover:bg-green-100">

                Eksplorasi Program ↓

            </a>

            <a href="#komunitas"
               class="rounded-full border border-white/30 bg-white/15 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/25">

                ♣ Lihat Komunitas

            </a>

        </div>

    </div>

</section>



{{-- ========================================================= --}}
{{-- STATISTIK --}}
{{-- ========================================================= --}}

<section class="mt-7 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

    @foreach($statCards as $stat)

        <article class="rounded-[20px] border border-gray-100 bg-white p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <p class="text-sm font-semibold text-slate-500">

                    {{ $stat['label'] }}

                </p>

                <span class="flex h-10 w-10 items-center justify-center rounded-full text-lg {{ $stat['color'] }}">

                    {{ $stat['icon'] }}

                </span>

            </div>

            <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900">

                {{ $stat['value'] }}

            </h2>

            <p class="mt-2 text-xs text-slate-500">

                {{ $stat['note'] }}

            </p>

        </article>

    @endforeach

</section>



{{-- ========================================================= --}}
{{-- SEARCH DAN FILTER --}}
{{-- ========================================================= --}}

<div class="mt-7 grid grid-cols-1 gap-3 rounded-[22px] border border-gray-100 bg-white p-4 shadow-sm md:grid-cols-[minmax(0,1fr)_210px_190px_auto] md:items-center">


    {{-- SEARCH INPUT --}}

    <label class="flex items-center gap-3 rounded-full bg-[#F4F6F0] px-5 py-3">

        <svg class="h-5 w-5 shrink-0 text-slate-500"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">

            <circle cx="11" cy="11" r="7" stroke-width="2"/>

            <path d="m16 16 4 4"
                  stroke-width="2"
                  stroke-linecap="round"/>

        </svg>

        <input
            id="activity-search"
            type="search"
            placeholder="Judul, deskripsi, atau lokasi..."
            class="w-full border-0 bg-transparent p-0 text-sm text-slate-700 outline-none placeholder:text-slate-400 focus:ring-0">

    </label>


    {{-- CATEGORY DROPDOWN --}}

    <select id="activity-category"
            class="filter-select rounded-full border-0 bg-[#F4F6F0] px-5 py-3 text-sm font-semibold text-slate-700">

        <option value="">Semua Kategori</option>

        @foreach($filterCategories as $category)

            <option value="{{ $category }}">

                {{ $category }}

            </option>

        @endforeach

    </select>


    {{-- SORT DROPDOWN --}}

    <select id="activity-sort"
            class="filter-select rounded-full border-0 bg-[#F4F6F0] px-5 py-3 text-sm font-semibold text-slate-700">

        <option value="default">Terbaru</option>

        <option value="quota">Kuota Terbanyak</option>

        <option value="title">Nama A-Z</option>

    </select>


    {{-- SEARCH BUTTON --}}

    <button
        type="button"
        onclick="filterActivities()"
        class="rounded-full bg-[#397D38] px-7 py-3 text-sm font-bold text-white transition hover:bg-[#205c3b]">

        <span class="flex items-center justify-center gap-2">

            <svg class="h-4 w-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <circle cx="11" cy="11" r="7" stroke-width="2"/>

                <path d="m16 16 4 4"
                      stroke-width="2"
                      stroke-linecap="round"/>

            </svg>

            Cari

        </span>

    </button>

</div>



{{-- ========================================================= --}}
{{-- AKTIVITAS TERSEDIA --}}
{{-- ========================================================= --}}

<section id="aktivitas" class="mt-9 scroll-mt-24">


    {{-- JUDUL AKTIVITAS --}}

    <div class="mb-6 flex flex-col justify-between gap-4 xl:flex-row xl:items-end">

        <div>

            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">

                Aktivitas tersedia

            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-500">

                Pilih dan daftarkan dirimu pada berbagai kegiatan
                kerelawanan yang sedang membuka pendaftaran.

            </p>

        </div>


        {{-- QUICK CATEGORY FILTER --}}

        <div class="flex flex-wrap gap-2">

            <button
                type="button"
                onclick="resetActivityFilter()"
                class="rounded-full bg-green-800 px-5 py-2 text-xs font-bold text-white">

                Semua

            </button>

            @foreach($filterCategories->take(4) as $category)

                <button
                    type="button"
                    onclick="selectActivityCategory(@js($category))"
                    class="rounded-full bg-white px-4 py-2 text-xs font-semibold text-slate-600 transition hover:bg-green-100">

                    {{ $category }}

                </button>

            @endforeach

        </div>

    </div>



    {{-- GRID 25 AKTIVITAS --}}

    <div id="activity-card-grid"
         class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">


        @forelse($activityCards as $card)

            @php

                $percentage = $card['percentage'];

                $isFull = $card['quota'] > 0 && $card['remaining'] === 0;

                $statusClass = match(true) {

                    $card['status'] === 'Pendaftaran Terbuka'
                        => 'status-open',

                    str_starts_with($card['status'], 'Tersisa')
                        => 'status-limited',

                    $card['status'] === 'Segera Dimulai'
                        => 'status-soon',

                    $card['status'] === 'Kuota Penuh'
                        => 'status-full',

                    default => 'status-open',

                };

            @endphp


            <article
                data-activity-card
                data-title="{{ $card['title'] }}"
                data-category="{{ $card['category'] }}"
                data-community="{{ $card['community'] }}"
                data-location="{{ $card['location'] }}"
                data-description="{{ $card['description'] }}"
                data-quota="{{ $card['quota'] }}"
                data-index="{{ $loop->index }}"
                class="activity-card flex min-w-0 flex-col overflow-hidden rounded-[22px] border border-gray-100 bg-white shadow-sm">


                {{-- FOTO AKTIVITAS --}}

                <div class="relative h-[180px] overflow-hidden bg-green-100">

                    <img
                        src="{{ $card['image'] }}"
                        alt="{{ $card['title'] }}"
                        class="activity-image h-full w-full object-cover"
                        loading="lazy">


                    {{-- STATUS BADGE --}}

                    <span class="absolute left-3 top-3 rounded-full px-3 py-1 text-[10px] font-bold {{ $statusClass }}">

                        {{ $card['status'] }}

                    </span>


                    {{-- CATEGORY BADGE --}}

                    <span class="absolute right-3 top-3 max-w-[55%] truncate rounded-full bg-white/95 px-3 py-1 text-[10px] font-bold text-green-800">

                        {{ $card['category'] }}

                    </span>

                </div>



                {{-- DETAIL AKTIVITAS --}}

                <div class="flex flex-1 flex-col p-4">


                    {{-- NAMA KOMUNITAS --}}

                    <p class="truncate text-[10px] font-semibold text-slate-500">

                        <span class="mr-1 text-green-700">✿</span>

                        {{ $card['community'] }}

                    </p>


                    {{-- JUDUL --}}

                    <h3 class="mt-3 line-clamp-2 min-h-[48px] text-base font-extrabold leading-6 text-slate-900">

                        <a href="{{ $card['url'] }}" class="hover:text-green-800">{{ $card['title'] }}</a>

                    </h3>


                    {{-- TANGGAL DAN LOKASI --}}

                    <div class="mt-3 space-y-2 text-xs text-slate-500">

                        <p class="flex items-start gap-2">

                            <span>▦</span>

                            {{ $card['date'] }}

                        </p>

                        <p class="flex items-start gap-2">

                            <span>⌖</span>

                            <span class="truncate">

                                {{ $card['location'] }}

                            </span>

                        </p>

                    </div>



                    {{-- KUOTA DAN PROGRESS --}}

                    <div class="mt-auto pt-5">


                        <div class="mb-2 flex items-center justify-between gap-2 text-[10px] font-semibold">

                            <span class="text-slate-500">

                                Kuota Relawan

                            </span>

                            <span class="{{ $percentage >= 85 ? 'text-orange-600' : 'text-green-800' }}">

                                @if($card['quota'] > 0)

                                    {{ $card['accepted'] }} / {{ $card['quota'] }}
                                    ({{ $percentage }}%)

                                @else

                                    Belum tersedia

                                @endif

                            </span>

                        </div>


                        <div class="h-1.5 overflow-hidden rounded-full bg-gray-100">

                            <div
                                class="h-full rounded-full {{ $percentage >= 85 ? 'bg-orange-600' : 'bg-[#43883C]' }}"
                                style="width: {{ $percentage }}%">

                            </div>

                        </div>



                        {{-- TOMBOL DAFTAR --}}

                        @if($isFull)
                            <button type="button" disabled aria-disabled="true" class="button-full mt-4 flex w-full items-center justify-center rounded-full px-4 py-3 text-xs font-bold">✓ Kuota Penuh</button>
                        @else
                            <a href="{{ $card['url'] }}#registration" class="mt-4 flex w-full items-center justify-center rounded-full bg-[#397D38] px-4 py-3 text-xs font-bold text-white transition hover:bg-green-800">Daftar Relawan ›</a>
                        @endif


                    </div>

                </div>

            </article>

        @empty
            <div class="rounded-2xl border border-dashed border-green-200 bg-white px-6 py-14 text-center sm:col-span-2 xl:col-span-4">
                <div class="text-4xl">🌱</div>
                <h3 class="mt-3 font-bold text-slate-800">Belum ada aktivitas tersedia</h3>
                <p class="mt-2 text-sm text-slate-500">Aktivitas yang telah dipublikasikan akan tampil di sini.</p>
            </div>
        @endforelse


    </div>



    {{-- EMPTY STATE --}}

    @if($activityCards->isNotEmpty())
    <div id="activity-empty"
         class="mt-5 hidden rounded-2xl border border-dashed border-green-200 bg-white px-6 py-14 text-center">

        <div class="text-4xl">

            🌱

        </div>

        <h3 class="mt-3 font-bold text-slate-800">

            Aktivitas tidak ditemukan

        </h3>

        <p class="mt-2 text-sm text-slate-500">

            Coba gunakan kata kunci atau kategori yang berbeda.

        </p>

        <button
            type="button"
            onclick="resetActivityFilter()"
            class="mt-4 rounded-full bg-green-800 px-5 py-2 text-xs font-bold text-white">

            Reset Filter

        </button>

    </div>
    @endif



    {{-- JUMLAH AKTIVITAS --}}

    <p id="activity-result-count"
       class="mt-4 text-right text-xs font-medium text-slate-400">

        Menampilkan {{ $activityCards->count() }} dari {{ $activityCards->count() }} aktivitas

    </p>

</section>



{{-- ========================================================= --}}
{{-- KOMUNITAS PENYELENGGARA --}}
{{-- ========================================================= --}}

<section id="komunitas" class="mt-14 scroll-mt-24">


    {{-- JUDUL KOMUNITAS --}}

    <div class="mb-6 flex flex-col justify-between gap-3 md:flex-row md:items-end">

        <div>

            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">

                Penyelenggara – Komunitas yang bergerak

            </h2>

            <p class="mt-2 text-sm text-slate-500">

                Organisasi dan komunitas terpercaya yang menggerakkan
                perubahan nyata di berbagai wilayah.

            </p>

        </div>

        <a href="#komunitas"
           class="text-sm font-bold text-green-800 hover:underline">

            Gabung Sebagai Komunitas Penyelenggara →

        </a>

    </div>



    {{-- GRID 25 KOMUNITAS --}}

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

        @forelse($communityCards as $card)

            <article class="community-card rounded-[21px] border border-gray-100 bg-white p-5 shadow-sm">


                {{-- INISIAL DAN BADGE --}}

                <div class="flex items-center justify-between gap-2">

                    <span class="flex h-11 w-11 items-center justify-center rounded-full {{ $loop->even ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-800' }} text-sm font-extrabold">

                        {{ $card['initial'] }}

                    </span>

                    <span class="rounded-full bg-green-50 px-3 py-1 text-[9px] font-bold text-green-800">

                        {{ $card['badge'] }}

                    </span>

                </div>



                {{-- NAMA KOMUNITAS --}}

                <h3 class="mt-4 min-h-[48px] text-base font-extrabold leading-6 text-slate-900">

                    <a href="{{ $card['url'] }}" class="hover:text-green-800">{{ $card['name'] }}</a>

                </h3>



                {{-- DESKRIPSI --}}

                <p class="mt-2 min-h-[60px] text-xs leading-5 text-slate-500">

                    {{ $card['description'] }}

                </p>



                {{-- JUMLAH AKSI DAN RELAWAN --}}

                <div class="mt-4 flex items-center justify-between gap-2 border-t border-gray-100 pt-4 text-[11px] font-semibold text-slate-700">

                    <span>

                        {{ $card['activities'] }} Aksi

                    </span>

                    <span class="text-slate-300">

                        •

                    </span>

                    <span>

                        {{ $card['volunteers'] }} Relawan

                    </span>

                </div>

            </article>

        @empty
            <div class="rounded-2xl border border-dashed border-green-200 bg-white p-8 text-center text-sm text-slate-500 sm:col-span-2 xl:col-span-4">Belum ada komunitas penyelenggara yang tersedia.</div>
        @endforelse

    </div>



    <p class="mt-4 text-right text-xs text-slate-400">

        Menampilkan {{ $communityCards->count() }} komunitas

    </p>

</section>

</div>



{{-- ========================================================= --}}
{{-- JAVASCRIPT FILTER --}}
{{-- ========================================================= --}}

<script>

function filterActivities() {

    const searchInput = document.getElementById('activity-search');
    const categoryInput = document.getElementById('activity-category');
    const sortInput = document.getElementById('activity-sort');

    const grid = document.getElementById('activity-card-grid');

    const cards = Array.from(
        document.querySelectorAll('[data-activity-card]')
    );

    const emptyMessage = document.getElementById('activity-empty');
    const resultCount = document.getElementById('activity-result-count');

    if (!grid) return;

    const keyword = searchInput.value.trim().toLowerCase();
    const category = categoryInput.value.trim().toLowerCase();
    const sort = sortInput.value;


    // FILTER PENCARIAN DAN KATEGORI

    let visibleCards = cards.filter(card => {

        const title = (card.dataset.title || '').toLowerCase();
        const community = (card.dataset.community || '').toLowerCase();
        const location = (card.dataset.location || '').toLowerCase();
        const description = (card.dataset.description || '').toLowerCase();
        const cardCategory = (card.dataset.category || '').toLowerCase();

        const matchesSearch =
            !keyword ||
            title.includes(keyword) ||
            community.includes(keyword) ||
            location.includes(keyword) ||
            description.includes(keyword) ||
            cardCategory.includes(keyword);

        const matchesCategory =
            !category || cardCategory === category;

        return matchesSearch && matchesCategory;

    });


    // SORTING

    if (sort === 'quota') {

        visibleCards.sort((a, b) => {

            return Number(b.dataset.quota) - Number(a.dataset.quota);

        });

    } else if (sort === 'title') {

        visibleCards.sort((a, b) => {

            return a.dataset.title.localeCompare(
                b.dataset.title,
                'id'
            );

        });

    } else {

        visibleCards.sort((a, b) => {

            return Number(a.dataset.index) - Number(b.dataset.index);

        });

    }


    // SEMBUNYIKAN SEMUA KARTU

    cards.forEach(card => {

        card.hidden = true;

    });


    // TAMPILKAN KARTU YANG SESUAI

    visibleCards.forEach(card => {

        card.hidden = false;

        grid.appendChild(card);

    });


    // EMPTY STATE

    if (emptyMessage) {

        emptyMessage.classList.toggle(
            'hidden',
            visibleCards.length > 0
        );

    }


    // JUMLAH HASIL

    if (resultCount) {

        resultCount.textContent =
            'Menampilkan ' +
            visibleCards.length +
            ' dari ' +
            cards.length +
            ' aktivitas';

    }

}


// RESET FILTER

function resetActivityFilter() {

    document.getElementById('activity-search').value = '';

    document.getElementById('activity-category').value = '';

    document.getElementById('activity-sort').value = 'default';

    filterActivities();

}


// PILIH KATEGORI

function selectActivityCategory(category) {

    document.getElementById('activity-category').value = category;

    filterActivities();

    document.getElementById('aktivitas').scrollIntoView({

        behavior: 'smooth',

        block: 'start'

    });

}


// EVENT LISTENERS

document.addEventListener('DOMContentLoaded', function() {

    const search = document.getElementById('activity-search');
    const category = document.getElementById('activity-category');
    const sort = document.getElementById('activity-sort');

    if (search) {

        search.addEventListener('input', filterActivities);

    }

    if (category) {

        category.addEventListener('change', filterActivities);

    }

    if (sort) {

        sort.addEventListener('change', filterActivities);

    }

    filterActivities();

});

</script>

@endsection