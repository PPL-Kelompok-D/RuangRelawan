@extends('layouts.app')

@section('title', 'Eksplorasi Aktivitas')

@section('content')

@php

/*
|--------------------------------------------------------------------------
| 1. FONT, FOTO, DAN KONFIGURASI
|--------------------------------------------------------------------------
*/

$photos = [
    'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=900',
    'https://images.unsplash.com/photo-1531206715517-5c0ba140b2b8?w=900',
    'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=900',
    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=900',
    'https://images.unsplash.com/photo-1448375240586-882707db888b?w=900',
    'https://images.unsplash.com/photo-1472396961693-142e6e269027?w=900',
    'https://images.unsplash.com/photo-1497486751825-1233686d5d80?w=900',
    'https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=900',
    'https://images.unsplash.com/photo-1511497584788-876760111969?w=900',
    'https://images.unsplash.com/photo-1618477461853-cf6ed80faba5?w=900',
    'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=900',
    'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?w=900',
    'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?w=900',
    'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=900',
    'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?w=900',
    'https://images.unsplash.com/photo-1523712999610-f77fbcfc3843?w=900',
    'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?w=900',
    'https://images.unsplash.com/photo-1542810634-71277d95dcbb?w=900',
    'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=900',
    'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=900',
    'https://images.unsplash.com/photo-1500534623283-312aade485b7?w=900',
    'https://images.unsplash.com/photo-1513836279014-a89f7a76ae86?w=900',
    'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=900',
    'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=900',
    'https://images.unsplash.com/photo-1501004318641-b39e6451bec6?w=900',
];


/*
|--------------------------------------------------------------------------
| 2. DATA 25 AKTIVITAS TAMPILAN
|--------------------------------------------------------------------------
| Format:
| Judul, Komunitas, Kategori, Tanggal, Lokasi,
| Kuota Terisi, Total Kuota, Status
|--------------------------------------------------------------------------
*/

$demoActivities = [

    ['Aksi Tanam 1.000 Mangrove Pesisir Muara', 'Komunitas Bahari Lestari', 'Konservasi Pesisir', '22 Mar 2027', 'Muara Gembong, Bekasi', 30, 50, 'Pendaftaran Terbuka'],

    ['Clean Up & Audit Sampah Plastik Sungai', 'Cikapundung Clean Team', 'Sanitasi Sungai', '15 Apr 2027', 'Bandung, Jawa Barat', 45, 50, 'Tersisa 5 Kuota'],

    ['Sekolah Alam & Daur Ulang Edukasi Anak', 'Yayasan Lentera Pesisir', 'Edukasi Alam', '30 Apr 2027', 'Jakarta Utara', 18, 25, 'Pendaftaran Terbuka'],

    ['Operasi Bersih Pantai & Konservasi Terumbu Karang', 'Sahabat Laut Nusantara', 'Bahari', '05 Mei 2027', 'Kepulauan Seribu', 35, 40, 'Segera Dimulai'],

    ['Gerakan Satu Juta Pohon untuk Bumi', 'Green Future Indonesia', 'Tanam Pohon', '12 Mei 2027', 'Bogor, Jawa Barat', 22, 40, 'Pendaftaran Terbuka'],

    ['Peduli Satwa dan Pelestarian Habitat Hutan', 'Wildlife Care Foundation', 'Konservasi Alam', '18 Mei 2027', 'Sukabumi, Jawa Barat', 16, 30, 'Pendaftaran Terbuka'],

    ['Berbagi Buku dan Mengajar Anak Negeri', 'Pendidikan Untuk Semua', 'Pendidikan', '25 Mei 2027', 'Jakarta Selatan', 20, 35, 'Pendaftaran Terbuka'],

    ['Aksi Bersih Lingkungan dan Pengelolaan Sampah', 'Eco Movement Community', 'Bebas Sampah', '02 Jun 2027', 'Depok, Jawa Barat', 28, 40, 'Tersisa 12 Kuota'],

    ['Urban Farming untuk Ketahanan Pangan', 'Jakarta Eco Action', 'Urban Farming', '08 Jun 2027', 'Jakarta Pusat', 19, 30, 'Pendaftaran Terbuka'],

    ['Gerakan Sungai Bersih Indonesia', 'River Care Indonesia', 'Sanitasi Sungai', '12 Jun 2027', 'Tangerang Selatan', 40, 40, 'Kuota Penuh'],

    ['Donasi dan Berbagi untuk Anak Panti', 'Sahabat Berbagi', 'Aksi Sosial', '18 Jun 2027', 'Jakarta Timur', 32, 45, 'Pendaftaran Terbuka'],

    ['Edukasi Pengurangan Sampah Plastik', 'Plastic Free Movement', 'Bebas Sampah', '22 Jun 2027', 'Bekasi, Jawa Barat', 15, 30, 'Pendaftaran Terbuka'],

    ['Restorasi Hutan dan Penanaman Bibit', 'Forest Guardian', 'Konservasi Alam', '28 Jun 2027', 'Bogor, Jawa Barat', 27, 40, 'Tersisa 13 Kuota'],

    ['Kelas Kreatif untuk Anak Pesisir', 'Lentera Anak Bangsa', 'Pendidikan', '05 Jul 2027', 'Jakarta Utara', 20, 35, 'Segera Dimulai'],

    ['Gerakan Peduli Lingkungan Perkotaan', 'Urban Green Society', 'Lingkungan', '10 Jul 2027', 'Jakarta Selatan', 33, 50, 'Pendaftaran Terbuka'],

    ['Aksi Bersih dan Rawat Taman Kota', 'Green City Volunteers', 'Lingkungan', '15 Jul 2027', 'Jakarta Pusat', 18, 30, 'Pendaftaran Terbuka'],

    ['Program Literasi untuk Generasi Muda', 'Literasi Nusantara', 'Pendidikan', '20 Jul 2027', 'Tangerang, Banten', 24, 35, 'Tersisa 11 Kuota'],

    ['Kampanye Hemat Air dan Energi', 'Earth Saver Community', 'Edukasi Alam', '25 Jul 2027', 'Depok, Jawa Barat', 12, 25, 'Pendaftaran Terbuka'],

    ['Bakti Sosial dan Pemeriksaan Kesehatan', 'Peduli Sesama Foundation', 'Aksi Sosial', '02 Agu 2027', 'Bekasi, Jawa Barat', 30, 40, 'Segera Dimulai'],

    ['Konservasi Penyu dan Ekosistem Laut', 'Ocean Care Indonesia', 'Bahari', '08 Agu 2027', 'Anyer, Banten', 17, 30, 'Pendaftaran Terbuka'],

    ['Pelatihan Daur Ulang Barang Bekas', 'Recycle Together', 'Daur Ulang', '12 Agu 2027', 'Jakarta Barat', 21, 35, 'Pendaftaran Terbuka'],

    ['Gerakan Tanam Pohon di Area Sekolah', 'School Green Movement', 'Tanam Pohon', '18 Agu 2027', 'Tangerang Selatan', 29, 40, 'Tersisa 11 Kuota'],

    ['Berbagi Makanan untuk Masyarakat', 'Food Share Indonesia', 'Aksi Sosial', '22 Agu 2027', 'Jakarta Timur', 35, 50, 'Pendaftaran Terbuka'],

    ['Edukasi dan Perlindungan Satwa Liar', 'Wildlife Rescue Team', 'Konservasi Alam', '28 Agu 2027', 'Bogor, Jawa Barat', 14, 25, 'Segera Dimulai'],

    ['Festival Relawan Peduli Bumi', 'Indonesia Volunteer Network', 'Lingkungan', '05 Sep 2027', 'Jakarta Pusat', 60, 60, 'Kuota Penuh'],

];


/*
|--------------------------------------------------------------------------
| 3. AMBIL AKTIVITAS DATABASE
|--------------------------------------------------------------------------
*/

$activityCards = collect();

foreach (($activities ?? collect()) as $activity) {

    if ($activityCards->count() >= 25) {
        break;
    }

    $quota = (int) ($activity->kuota_relawan ?? 0);

    $accepted = (int) $activity->applications()
        ->where('status', 'diterima')
        ->count();

    $remaining = max(0, $quota - $accepted);

    $percentage = $quota > 0
        ? min(100, round(($accepted / $quota) * 100))
        : 0;

    $startDate = $activity->tanggal_mulai
        ? \Carbon\Carbon::parse($activity->tanggal_mulai)
        : null;

    $daysUntilStart = $startDate
        ? now()->startOfDay()->diffInDays(
            $startDate->copy()->startOfDay(),
            false
        )
        : null;

    if ($quota > 0 && $remaining === 0) {

        $status = 'Kuota Penuh';

    } elseif (
        $daysUntilStart !== null &&
        $daysUntilStart >= 0 &&
        $daysUntilStart <= 7
    ) {

        $status = 'Segera Dimulai';

    } elseif ($quota > 0 && $remaining <= 5) {

        $status = 'Tersisa '.$remaining.' Kuota';

    } else {

        $status = 'Pendaftaran Terbuka';

    }

    $activityCards->push([

        'title' => $activity->judul,

        'community' => $activity->community?->nama_community
            ?? 'Komunitas RuangRelawan',

        'category' => $activity->category?->nama_kategori
            ?? 'Aksi Sosial',

        'date' => $startDate
            ? $startDate->format('d M Y')
            : 'Jadwal menyusul',

        'location' => $activity->lokasi ?? 'Lokasi menyusul',

        'accepted' => $accepted,

        'quota' => $quota,

        'percentage' => $percentage,

        'remaining' => $remaining,

        'status' => $status,

        'image' => $activity->gambar_aktivitas
            ? asset('storage/'.$activity->gambar_aktivitas)
            : $photos[0],

        'url' => route('activities.show', $activity),

        'demo' => false,

    ]);

}


/*
|--------------------------------------------------------------------------
| 4. LENGKAPI AKTIVITAS SAMPAI 25 KARTU
|--------------------------------------------------------------------------
*/

foreach ($demoActivities as $index => $demo) {

    if ($activityCards->count() >= 25) {
        break;
    }

    $accepted = (int) $demo[5];
    $quota = (int) $demo[6];

    $remaining = max(0, $quota - $accepted);

    $percentage = $quota > 0
        ? min(100, round(($accepted / $quota) * 100))
        : 0;

    // Status selalu disesuaikan dengan kuota.
    $status = $remaining === 0
        ? 'Kuota Penuh'
        : $demo[7];

    $activityCards->push([

        'title' => $demo[0],

        'community' => $demo[1],

        'category' => $demo[2],

        'date' => $demo[3],

        'location' => $demo[4],

        'accepted' => $accepted,

        'quota' => $quota,

        'percentage' => $percentage,

        'remaining' => $remaining,

        'status' => $status,

        'image' => $photos[$index],

        'url' => null,

        'demo' => true,

    ]);

}


/*
|--------------------------------------------------------------------------
| 5. DATA 25 KOMUNITAS
|--------------------------------------------------------------------------
*/

$demoCommunities = [

    ['KB', 'Komunitas Bahari Lestari', 'Mitra Hijau Platinum', 'Fokus pada restorasi mangrove, konservasi terumbu karang pesisir utara.', 24, '1.200'],

    ['CC', 'Cikapundung Clean Team', 'Mitra Terverifikasi', 'Gerakan pemuda urban membersihkan dan merawat sungai Cikapundung.', 18, '850'],

    ['YL', 'Yayasan Lentera Pesisir', 'Mitra Terverifikasi', 'Pemberdayaan anak nelayan lewat pendidikan sains alam dan sirkular.', 12, '620'],

    ['JE', 'Jakarta Eco Action', 'Mitra Unggulan', 'Inisiator urban forest, komposting massal, dan bank sampah mandiri.', 31, '2.100'],

    ['GF', 'Green Future Indonesia', 'Mitra Terverifikasi', 'Komunitas penghijauan untuk penanaman pohon dan restorasi hutan.', 16, '950'],

    ['WF', 'Wildlife Care Foundation', 'Mitra Unggulan', 'Perlindungan satwa liar dan pelestarian habitat alami.', 21, '1.500'],

    ['SN', 'Sahabat Nusantara', 'Mitra Terverifikasi', 'Menggerakkan masyarakat melalui aksi sosial dan kemanusiaan.', 14, '700'],

    ['PF', 'Plastic Free Movement', 'Mitra Terverifikasi', 'Mengurangi penggunaan plastik sekali pakai melalui edukasi.', 19, '880'],

    ['OR', 'Ocean Rescue Indonesia', 'Mitra Unggulan', 'Melindungi ekosistem laut dan habitat biota laut.', 27, '1.800'],

    ['LP', 'Literasi Peduli Bangsa', 'Mitra Terverifikasi', 'Meningkatkan minat baca dan akses pendidikan.', 22, '1.100'],

    ['EC', 'Eco Community Jakarta', 'Mitra Terverifikasi', 'Menciptakan lingkungan perkotaan yang hijau.', 17, '760'],

    ['BS', 'Berbagi Sesama Foundation', 'Mitra Unggulan', 'Menyalurkan bantuan dan program kepedulian sosial.', 28, '2.000'],

    ['FG', 'Forest Guardian Indonesia', 'Mitra Terverifikasi', 'Perlindungan hutan dan rehabilitasi kawasan hijau.', 15, '900'],

    ['PA', 'Pendidikan Anak Indonesia', 'Mitra Terverifikasi', 'Membantu anak mendapatkan pengalaman belajar.', 20, '1.250'],

    ['RC', 'River Care Community', 'Mitra Unggulan', 'Pembersihan sungai dan pengelolaan limbah.', 25, '1.400'],

    ['UG', 'Urban Green Society', 'Mitra Terverifikasi', 'Pertanian perkotaan dan ruang hijau komunitas.', 13, '680'],

    ['SA', 'Sahabat Alam Indonesia', 'Mitra Terverifikasi', 'Konservasi dan edukasi lingkungan.', 23, '1.300'],

    ['TL', 'Tunas Literasi Nusantara', 'Mitra Unggulan', 'Membangun budaya literasi masyarakat.', 18, '990'],

    ['OC', 'Ocean Care Community', 'Mitra Terverifikasi', 'Menjaga pantai dan kelestarian ekosistem laut.', 26, '1.600'],

    ['PS', 'Peduli Sesama Indonesia', 'Mitra Terverifikasi', 'Program bantuan sosial untuk masyarakat.', 30, '1.750'],

    ['RM', 'Recycle Movement', 'Mitra Unggulan', 'Edukasi pemilahan dan daur ulang sampah.', 16, '820'],

    ['GA', 'Gerakan Anak Bangsa', 'Mitra Terverifikasi', 'Mengembangkan potensi generasi muda.', 21, '1.050'],

    ['FI', 'Food Share Indonesia', 'Mitra Terverifikasi', 'Mengurangi limbah makanan dan berbagi pangan.', 29, '1.900'],

    ['WR', 'Wildlife Rescue Indonesia', 'Mitra Unggulan', 'Penyelamatan dan perlindungan satwa liar.', 11, '570'],

    ['VN', 'Volunteer Network Indonesia', 'Mitra Hijau Platinum', 'Menghubungkan relawan dengan kegiatan sosial dan lingkungan.', 35, '2.500'],

];


/*
|--------------------------------------------------------------------------
| 6. AMBIL KOMUNITAS DATABASE
|--------------------------------------------------------------------------
*/

$communityCards = collect();

foreach (($communities ?? collect()) as $community) {

    if ($communityCards->count() >= 25) {
        break;
    }

    $initials = collect(explode(' ', trim($community->nama_community)))
        ->filter()
        ->take(2)
        ->map(fn($word) => strtoupper(substr($word, 0, 1)))
        ->implode('');

    $communityCards->push([

        'initial' => $initials,

        'name' => $community->nama_community,

        'badge' => 'Mitra Terverifikasi',

        'description' => $community->kota
            ?? 'Komunitas penggerak aksi sosial dan lingkungan.',

        'activities' => $community->activities_count ?? 0,

        'volunteers' => 'Relawan Aktif',

        'url' => route('communities.show', $community),

        'demo' => false,

    ]);

}


/*
|--------------------------------------------------------------------------
| 7. LENGKAPI KOMUNITAS SAMPAI 25 KARTU
|--------------------------------------------------------------------------
*/

foreach ($demoCommunities as $demo) {

    if ($communityCards->count() >= 25) {
        break;
    }

    $communityCards->push([

        'initial' => $demo[0],

        'name' => $demo[1],

        'badge' => $demo[2],

        'description' => $demo[3],

        'activities' => $demo[4],

        'volunteers' => $demo[5],

        'url' => null,

        'demo' => true,

    ]);

}


/*
|--------------------------------------------------------------------------
| 8. KATEGORI FILTER
|--------------------------------------------------------------------------
*/

$filterCategories = collect($demoActivities)
    ->pluck(2)
    ->merge(($categories ?? collect())->pluck('nama_kategori'))
    ->filter()
    ->unique()
    ->sort()
    ->values();


/*
|--------------------------------------------------------------------------
| 9. STATISTIK DASHBOARD
|--------------------------------------------------------------------------
*/

$statCards = [

    [
        'label' => 'Relawan aktif',
        'value' => '12.450+',
        'note' => 'Jiwa penggerak aksi nyata',
        'icon' => '♥',
        'color' => 'bg-green-100 text-green-800'
    ],

    [
        'label' => 'Komunitas terdaftar',
        'value' => '380+',
        'note' => 'Organisasi mitra terverifikasi',
        'icon' => '⬟',
        'color' => 'bg-green-100 text-green-800'
    ],

    [
        'label' => 'Aksi selesai',
        'value' => '1.240',
        'note' => 'Program terlaksana sukses',
        'icon' => '✓',
        'color' => 'bg-green-100 text-green-800'
    ],

    [
        'label' => 'Donasi diterima',
        'value' => 'Rp1,85 M',
        'note' => 'Tersalurkan 100% transparan',
        'icon' => '◈',
        'color' => 'bg-orange-100 text-orange-700'
    ],

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


        @foreach($activityCards as $card)

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

                        {{ $card['title'] }}

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

                            <button
                                type="button"
                                disabled
                                aria-disabled="true"
                                class="button-full mt-4 flex w-full items-center justify-center rounded-full px-4 py-3 text-xs font-bold">

                                ✓ Kuota Penuh

                            </button>

                        @elseif(!$card['demo'])

                            <a
                                href="{{ $card['url'] }}"
                                class="mt-4 flex w-full items-center justify-center rounded-full bg-[#397D38] px-4 py-3 text-xs font-bold text-white transition hover:bg-green-800">

                                Daftar Relawan ›

                            </a>

                        @else

                            <button
                                type="button"
                                class="mt-4 flex w-full items-center justify-center rounded-full bg-[#397D38] px-4 py-3 text-xs font-bold text-white">

                                Daftar Relawan ›

                            </button>

                        @endif


                    </div>

                </div>

            </article>

        @endforeach


    </div>



    {{-- EMPTY STATE --}}

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

        @foreach($communityCards as $card)

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

                    @if(!$card['demo'])

                        <a href="{{ $card['url'] }}" class="hover:text-green-800">

                            {{ $card['name'] }}

                        </a>

                    @else

                        {{ $card['name'] }}

                    @endif

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

        @endforeach

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
        const cardCategory = (card.dataset.category || '').toLowerCase();

        const matchesSearch =
            !keyword ||
            title.includes(keyword) ||
            community.includes(keyword) ||
            location.includes(keyword) ||
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
