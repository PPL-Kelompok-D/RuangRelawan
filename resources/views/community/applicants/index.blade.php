@extends('layouts.app')

@section('title', 'Pendaftar Aktivitas')

@section('content')
<section class="max-w-[1152px] w-full mx-auto bg-white border border-[#E2E8D8] rounded-[24px] shadow-[0px_20px_25px_-5px_rgba(0,0,0,0.1),0px_8px_10px_-6px_rgba(0,0,0,0.1)] p-6 md:p-8">
    <a href="{{ route('community.my-activities') }}" class="text-sm font-semibold text-[#2D6A4F] hover:underline">← Kembali ke Aktivitas Saya</a>
    <div class="mt-5 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div><p class="text-sm font-semibold uppercase tracking-wide text-[#2D6A4F]">{{ $community->nama_community }}</p><h1 class="mt-2 text-2xl font-bold text-slate-900 md:text-3xl">Pendaftar relawan</h1><p class="mt-2 text-slate-600">{{ $activity->judul ?? $activity->title }}</p></div>
        <span class="inline-flex w-fit rounded-full border border-[#E2E8D8] bg-[#F4F6F0] px-4 py-2 text-sm font-semibold text-slate-700">{{ $activity->applications()->where('status', 'diterima')->count() }} / {{ $activity->kuota_relawan ?? '∞' }} diterima</span>
    </div>
    @if(session('success'))<div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <form method="GET" action="{{ route('community.activities.applicants.index', $activity) }}" class="mt-7 grid grid-cols-1 gap-4 rounded-2xl border border-[#E2E8D8] bg-[#F4F6F0] p-4 md:grid-cols-[1fr_220px_auto] md:items-end">
        <label><span class="mb-2 block text-sm font-semibold text-slate-700">Cari nama, email, atau nomor telepon</span><input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20"></label>
        <label><span class="mb-2 block text-sm font-semibold text-slate-700">Status</span><select name="status" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3"><option value="">Semua status</option>@foreach(['menunggu' => 'Menunggu', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $value => $label)<option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>@endforeach</select></label>
        <div class="flex gap-2"><a href="{{ route('community.activities.applicants.index', $activity) }}" class="rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700">Reset</a><button class="rounded-xl bg-[#2D6A4F] px-5 py-3 text-sm font-semibold text-white hover:bg-[#24583f]">Filter</button></div>
    </form>

    <div class="mt-7 space-y-4">
        @forelse($applications as $application)
            @php($statusClass = match($application->status) { 'diterima' => 'border-emerald-200 bg-emerald-50 text-emerald-800', 'ditolak' => 'border-rose-200 bg-rose-50 text-rose-800', default => 'border-amber-200 bg-amber-50 text-amber-800' })
            <article class="rounded-2xl border border-slate-200 p-5 md:p-6">
                <div class="flex flex-col justify-between gap-5 lg:flex-row">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-3"><div><h2 class="text-lg font-bold text-slate-900">{{ $application->applicant->name }}</h2><p class="mt-1 text-sm text-slate-600">{{ $application->applicant->email }}</p><p class="mt-1 text-sm text-slate-600">{{ $application->applicant->profilUser?->no_hp ?? 'Nomor telepon belum diisi' }} · {{ $application->applicant->profilUser?->preferensi_lokasi ?? 'Lokasi belum diisi' }}</p></div><span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ ucfirst($application->status) }}</span></div>
                        <p class="mt-4 text-xs text-slate-500">Mendaftar {{ $application->created_at?->format('d M Y H:i') }}@if($application->tanggal_keputusan) · Diputuskan {{ $application->tanggal_keputusan->format('d M Y H:i') }}@endif</p>
                        @if($application->motivation)<div class="mt-4 rounded-xl bg-[#F4F6F0] p-4"><p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Motivasi</p><p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $application->motivation }}</p></div>@endif
                    </div>
                    @if($application->status === 'menunggu')
                        <div class="flex shrink-0 flex-col gap-2 sm:flex-row lg:flex-col">
                            <form action="{{ route('community.applications.accept', $application) }}" method="POST">@csrf<button class="w-full rounded-xl bg-[#2D6A4F] px-5 py-3 text-sm font-semibold text-white hover:bg-[#24583f]">Terima relawan</button></form>
                            <form action="{{ route('community.applications.reject', $application) }}" method="POST" onsubmit="return confirm('Tolak pendaftaran relawan ini?')">@csrf<button class="w-full rounded-xl border border-rose-200 bg-white px-5 py-3 text-sm font-semibold text-rose-700 hover:bg-rose-50">Tolak</button></form>
                        </div>
                    @endif
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-[#E2E8D8] bg-[#F4F6F0] px-6 py-14 text-center"><p class="font-semibold text-slate-900">Belum ada pendaftar yang cocok</p><p class="mt-1 text-sm text-slate-600">Ubah filter atau tunggu pendaftar baru untuk aktivitas ini.</p></div>
        @endforelse
    </div>
    <div class="mt-6">{{ $applications->links() }}</div>
</section>
@endsection
