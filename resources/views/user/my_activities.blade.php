@extends('layouts.app')

@section('title', 'Aktivitas Saya')

@section('content')
<section class="mx-auto w-full max-w-[1152px] rounded-[24px] border border-[#E2E8D8] bg-white p-6 shadow-sm md:p-8">
    <p class="text-sm font-semibold uppercase tracking-wide text-[#2D6A4F]">Area Relawan</p>
    <h1 class="mt-2 text-2xl font-bold text-slate-900 md:text-3xl">Aktivitas Saya</h1>
    <p class="mt-2 text-slate-600">Pantau pendaftaran dan riwayat donasi dalam satu tempat.</p>
    @if(session('success'))<div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>@endif

    <div class="mt-7 flex flex-wrap gap-2 border-b border-[#E2E8D8] pb-3">
        <a href="{{ route('user.my-activities', ['tab' => 'applications']) }}" @class(['rounded-xl px-4 py-2.5 text-sm font-semibold', 'bg-[#2D6A4F] text-white' => $tab === 'applications', 'bg-[#F4F6F0] text-slate-700 hover:bg-[#E2E8D8]' => $tab !== 'applications'])>Pendaftaran Relawan</a>
        <a href="{{ route('user.my-activities', ['tab' => 'donations']) }}" @class(['rounded-xl px-4 py-2.5 text-sm font-semibold', 'bg-[#2D6A4F] text-white' => $tab === 'donations', 'bg-[#F4F6F0] text-slate-700 hover:bg-[#E2E8D8]' => $tab !== 'donations'])>Riwayat Donasi</a>
    </div>

    @if($tab === 'applications')
        <div class="mt-5 space-y-4">
            @forelse($applications as $application)
                @php($activity = $application->activity)
                @php($statusClass = match($application->status) { 'diterima' => 'border-emerald-200 bg-emerald-50 text-emerald-800', 'ditolak' => 'border-rose-200 bg-rose-50 text-rose-800', default => 'border-amber-200 bg-amber-50 text-amber-800' })
                <article class="rounded-2xl border border-[#E2E8D8] p-5">
                    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start"><div><span class="rounded-full bg-[#F4F6F0] px-3 py-1 text-xs font-semibold text-[#2D6A4F]">{{ $activity?->category?->nama_kategori ?? 'Aksi sosial' }}</span><h2 class="mt-3 text-lg font-bold text-slate-900">{{ $activity?->judul }}</h2><p class="mt-1 text-sm text-slate-600">{{ $activity?->community?->nama_community }} · {{ $activity?->lokasi }}</p></div><span class="inline-flex w-fit rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ ucfirst($application->status) }}</span></div>
                    <div class="mt-4 grid gap-3 text-sm text-slate-600 sm:grid-cols-3"><p>Mulai: <strong class="text-slate-900">{{ $activity?->tanggal_mulai?->format('d M Y, H:i') ?? 'Belum ditentukan' }}</strong></p><p>Daftar: <strong class="text-slate-900">{{ $application->created_at?->format('d M Y') }}</strong></p><p>Keputusan: <strong class="text-slate-900">{{ $application->tanggal_keputusan?->format('d M Y') ?? 'Menunggu' }}</strong></p></div>
                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-[#E2E8D8] pt-4"><a href="{{ $activity ? route('activities.show', $activity) : '#' }}" class="text-sm font-semibold text-[#2D6A4F] hover:underline">Lihat detail</a>@if($application->status === 'diterima' && $activity?->tautan_whatsapp)<a href="{{ $activity->tautan_whatsapp }}" target="_blank" rel="noopener noreferrer" class="rounded-xl bg-[#2D6A4F] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#24583f]">Gabung WhatsApp</a>@endif</div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-[#E2E8D8] bg-[#F4F6F0] px-6 py-12 text-center"><p class="font-semibold text-slate-900">Belum ada pendaftaran</p><p class="mt-1 text-sm text-slate-600">Jelajahi kegiatan yang ingin Anda ikuti.</p><a href="{{ route('home') }}" class="mt-4 inline-flex rounded-xl bg-[#2D6A4F] px-5 py-3 text-sm font-semibold text-white">Jelajahi aksi</a></div>
            @endforelse
        </div>
        <div class="mt-5">{{ $applications->links() }}</div>
    @else
        <div class="mt-5 space-y-4">
            @forelse($donations as $donation)
                @php($donationStatusClass = match($donation->status) { 'diterima' => 'border-emerald-200 bg-emerald-50 text-emerald-800', 'ditolak' => 'border-rose-200 bg-rose-50 text-rose-800', default => 'border-amber-200 bg-amber-50 text-amber-800' })
                <article class="grid gap-5 rounded-2xl border border-[#E2E8D8] p-5 sm:grid-cols-[1fr_150px]">
                    <div><div class="flex flex-wrap items-center justify-between gap-3"><div><h2 class="font-bold text-slate-900">{{ $donation->activity?->judul }}</h2><p class="mt-1 text-sm text-slate-600">{{ $donation->activity?->community?->nama_community }} · {{ $donation->created_at?->format('d M Y') }}</p></div><span class="rounded-full border px-3 py-1 text-xs font-semibold {{ $donationStatusClass }}">{{ str_replace('_', ' ', ucfirst($donation->status)) }}</span></div><p class="mt-4 text-lg font-bold text-slate-900">Rp {{ number_format($donation->amount, 0, ',', '.') }}</p>@if($donation->review_note)<p class="mt-2 text-sm text-slate-600">Catatan komunitas: {{ $donation->review_note }}</p>@endif</div>
                    @if(Route::has('user.donations.proof'))<a href="{{ route('user.donations.proof', $donation) }}" target="_blank" rel="noopener noreferrer" class="overflow-hidden rounded-xl border border-[#E2E8D8] bg-[#F4F6F0]"><img src="{{ route('user.donations.proof', $donation) }}" alt="Bukti transfer donasi" class="h-full min-h-28 w-full object-cover"></a>@endif
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-[#E2E8D8] bg-[#F4F6F0] px-6 py-12 text-center"><p class="font-semibold text-slate-900">Belum ada riwayat donasi</p><p class="mt-1 text-sm text-slate-600">Kirim bukti transfer dari halaman aktivitas yang membuka donasi.</p><a href="{{ route('home') }}" class="mt-4 inline-flex rounded-xl bg-[#2D6A4F] px-5 py-3 text-sm font-semibold text-white">Cari aktivitas</a></div>
            @endforelse
        </div>
        <div class="mt-5">{{ $donations->links() }}</div>
    @endif
</section>
@endsection
