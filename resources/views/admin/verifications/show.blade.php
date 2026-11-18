
@extends('layouts.app')

@section('title', 'Tinjau Aktivitas')

@section('content')
<section class="max-w-[1152px] w-full mx-auto bg-white border border-[#E2E8D8] rounded-[24px] shadow-[0px_20px_25px_-5px_rgba(0,0,0,0.1),0px_8px_10px_-6px_rgba(0,0,0,0.1)] p-6 md:p-8">
    <a href="{{ route('admin.verifications.index') }}" class="text-sm font-semibold text-[#2D6A4F] hover:underline">← Kembali ke antrean</a>

    <div class="mt-5 flex flex-col justify-between gap-4 border-b border-slate-100 pb-6 sm:flex-row sm:items-start">
        <div>
            <span class="inline-flex rounded-full border border-amber-200 bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-800">Menunggu verifikasi</span>
            <h1 class="mt-3 text-2xl font-bold text-slate-900 md:text-3xl">{{ $activity->judul ?? $activity->title }}</h1>
            <p class="mt-2 text-slate-600">{{ $activity->category?->nama_kategori ?? 'Tanpa kategori' }} · diajukan {{ $activity->dibuat_pada?->format('d M Y H:i') }}</p>
        </div>
    </div>

    @if($errors->any())
        <div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mt-7 grid grid-cols-1 gap-6 lg:grid-cols-[1fr_320px]">
        <div class="space-y-6">
            <article class="rounded-2xl border border-slate-200 p-5 md:p-6">
                <h2 class="text-lg font-bold text-slate-900">Informasi aktivitas</h2>
                <p class="mt-4 whitespace-pre-line leading-7 text-slate-700">{{ $activity->deskripsi ?? $activity->description }}</p>

                <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Lokasi</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-900">{{ $activity->lokasi ?? $activity->location }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Kuota relawan</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-900">{{ $activity->kuota_relawan ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Mulai</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-900">{{ $activity->tanggal_mulai?->format('d M Y H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Selesai</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-900">{{ $activity->tanggal_selesai?->format('d M Y H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Batas pendaftaran</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-900">{{ $activity->batas_pendaftaran?->format('d M Y H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Target dampak</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-900">{{ $activity->target_dampak ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Kontak WhatsApp</dt>
                        <dd class="mt-1 text-sm font-medium">
                            <a href="{{ $activity->tautan_whatsapp }}" target="_blank" rel="noopener noreferrer" class="text-[#2D6A4F] underline">{{ $activity->tautan_whatsapp }}</a>
                        </dd>
                    </div>
                </dl>
            </article>

            <article class="rounded-2xl border border-slate-200 p-5 md:p-6">
                <h2 class="text-lg font-bold text-slate-900">Penggalangan dana</h2>

                @if($activity->donasi_aktif)
                    <p class="mt-3 text-sm text-slate-700">Target: <strong>Rp {{ number_format($activity->target_donasi, 0, ',', '.') }}</strong></p>
                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $activity->tujuan_donasi }}</p>

                    <dl class="mt-4 grid grid-cols-1 gap-3 text-sm sm:grid-cols-3">
                        <div>
                            <dt class="text-xs text-slate-500">Bank</dt>
                            <dd class="font-semibold text-slate-900">{{ $activity->nama_bank }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Rekening</dt>
                            <dd class="font-semibold text-slate-900">{{ $activity->nomor_rekening }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Atas nama</dt>
                            <dd class="font-semibold text-slate-900">{{ $activity->nama_pemilik_rekening }}</dd>
                        </div>
                    </dl>
                @else
                    <p class="mt-3 text-sm text-slate-600">Penggalangan dana tidak diaktifkan.</p>
                @endif
            </article>
        </div>

        <aside class="space-y-5">
            <article class="rounded-2xl border border-[#E2E8D8] bg-[#F4F6F0] p-5">
                <h2 class="font-bold text-slate-900">Penyelenggara</h2>
                <p class="mt-3 font-semibold text-slate-900">{{ $activity->community?->nama_community }}</p>
                <p class="mt-1 text-sm text-slate-600">{{ $activity->community?->kota }}</p>
                <p class="mt-3 text-sm text-slate-700">{{ $activity->community?->deskripsi }}</p>
                <p class="mt-3 text-sm text-slate-600">{{ $activity->community?->kontak }}</p>
            </article>

            <article class="rounded-2xl border border-slate-200 p-5">
                <h2 class="font-bold text-slate-900">Keputusan admin</h2>

                <form action="{{ route('admin.verifications.approve', $activity) }}" method="POST" class="mt-4 space-y-3">
                    @csrf
                    <label for="approve-note" class="block text-sm font-medium text-slate-700">Catatan (opsional)</label>
                    <textarea id="approve-note" name="catatan" rows="3" maxlength="5000" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">{{ old('catatan') }}</textarea>
                    <button type="submit" class="w-full rounded-xl bg-[#2D6A4F] px-4 py-3 font-semibold text-white hover:bg-[#24583f]">Setujui dan publikasikan</button>
                </form>

                <div class="my-5 border-t border-slate-100"></div>

                <form action="{{ route('admin.verifications.reject', $activity) }}" method="POST" class="space-y-3">
                    @csrf
                    <label for="reject-note" class="block text-sm font-medium text-slate-700">Alasan penolakan <span class="text-rose-600">*</span></label>
                    <textarea id="reject-note" name="catatan" rows="4" minlength="5" maxlength="5000" required class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm focus:border-rose-500 focus:outline-none focus:ring-2 focus:ring-rose-500/20" placeholder="Jelaskan bagian yang perlu diperbaiki">{{ old('catatan') }}</textarea>
                    <button type="submit" class="w-full rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 font-semibold text-rose-700 hover:bg-rose-100">Tolak pengajuan</button>
                </form>
            </article>
        </aside>
    </div>
</section>
@endsection
