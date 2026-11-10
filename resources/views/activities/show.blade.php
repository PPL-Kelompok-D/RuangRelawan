@extends('layouts.app')

@section('title', $activity->judul)

@section('content')
<main class="mx-auto grid w-full max-w-[1152px] grid-cols-1 gap-8 p-4 md:p-8 lg:grid-cols-3">
    <div class="space-y-6 lg:col-span-2">
        <a href="{{ route('home') }}" class="inline-flex text-sm font-semibold text-[#2D6A4F] hover:underline">← Kembali ke aktivitas</a>
        @if($errors->any())<div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        @if(session('success'))<div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">{{ session('success') }}</div>@endif

        <div class="mb-6 h-[320px] w-full overflow-hidden rounded-[24px] border border-[#E2E8D8] shadow-sm md:h-[420px]">
            @if($activity->gambar_aktivitas)
                <img src="{{ asset('storage/'.$activity->gambar_aktivitas) }}" alt="{{ $activity->judul }}" class="h-full w-full object-cover">
            @else
                <div class="h-full w-full bg-[#E8F0E9] flex items-center justify-center text-[#2D6A4F] font-bold text-xl">{{ $activity->judul }}</div>
            @endif
        </div>

        <header class="rounded-[24px] border border-[#E2E8D8] bg-white p-5 shadow-sm md:p-7">
            <div class="flex flex-wrap items-center gap-2"><span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-[#2D6A4F]">{{ $activity->category?->nama_kategori ?? 'Aksi sosial' }}</span>@if($activity->status_aktivitas === 'selesai')<span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">Aktivitas Telah Selesai</span>@else<span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800">Terverifikasi</span>@endif</div>
            <h1 class="mt-4 text-2xl font-bold leading-tight text-slate-900 md:text-4xl">{{ $activity->judul }}</h1>
            <div class="mt-4 flex items-center gap-3">
                @if($activity->community?->logo)
                    @php($communityLogo = filter_var($activity->community->logo, FILTER_VALIDATE_URL) ? $activity->community->logo : asset('storage/'.$activity->community->logo))
                    <img src="{{ $communityLogo }}" alt="Logo {{ $activity->community->nama_community }}" class="h-11 w-11 rounded-full border border-[#E2E8D8] bg-white object-cover">
                @else
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#E8F0E9] font-bold text-[#2D6A4F]">{{ mb_substr($activity->community?->nama_community ?? 'K', 0, 1) }}</span>
                @endif
                <div><p class="text-xs text-slate-500">Diselenggarakan oleh</p><p class="font-semibold text-slate-900">{{ $activity->community?->nama_community ?? 'Komunitas' }}</p>@if($activity->community?->kota)<p class="text-sm text-slate-500">{{ $activity->community->kota }}</p>@endif</div>
            </div>
        </header>

        <article class="rounded-[24px] border border-[#E2E8D8] bg-white p-5 shadow-sm md:p-7"><h2 class="text-xl font-bold text-slate-900">Tentang aktivitas</h2><p class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-700">{{ $activity->deskripsi }}</p></article>

        <article class="rounded-[24px] border border-[#E2E8D8] bg-white p-5 shadow-sm md:p-7"><h2 class="text-xl font-bold text-slate-900">Target dampak</h2><p class="mt-3 text-sm leading-7 text-slate-700">{{ $activity->target_dampak }}</p><div class="mt-5 grid grid-cols-2 gap-3"><div class="rounded-2xl bg-[#F4F6F0] p-4"><p class="text-xs text-slate-500">Relawan diterima</p><p class="mt-1 text-lg font-bold text-slate-900">{{ $activity->accepted_applications_count ?? 0 }} orang</p></div><div class="rounded-2xl bg-[#F4F6F0] p-4"><p class="text-xs text-slate-500">Kuota relawan</p><p class="mt-1 text-lg font-bold text-slate-900">{{ $activity->kuota_relawan }} orang</p></div></div></article>

        @if($activity->status_aktivitas === 'selesai')
            <article class="rounded-[24px] border border-[#E2E8D8] bg-white p-5 shadow-sm md:p-7"><h2 class="text-xl font-bold text-slate-900">Laporan Pelaksanaan &amp; Hasil Aktual</h2><h3 class="mt-4 font-semibold text-slate-900">Ringkasan pelaksanaan</h3><p class="mt-2 whitespace-pre-line text-sm leading-7 text-slate-700">{{ $activity->completion?->ringkasan_pelaksanaan ?? 'Tidak ada ringkasan.' }}</p><h3 class="mt-5 font-semibold text-slate-900">Hasil aktual / dampak</h3><p class="mt-2 whitespace-pre-line text-sm leading-7 text-slate-700">{{ $activity->completion?->hasil_aktual ?? 'Tidak ada data hasil aktual.' }}</p></article>
        @endif
        @if($activity->documentations->isNotEmpty() || ($activity->status_aktivitas === 'selesai' && $activity->completion))
            <article class="rounded-[24px] border border-[#E2E8D8] bg-white p-5 shadow-sm md:p-7"><h2 class="text-xl font-bold text-slate-900">{{ $activity->status_aktivitas === 'selesai' ? 'Dokumentasi Kegiatan' : 'Dokumentasi aktivitas' }}</h2><div class="mt-4 grid grid-cols-2 gap-3 md:grid-cols-3">@foreach($activity->documentations as $documentation)<figure class="overflow-hidden rounded-xl border border-[#E2E8D8]"><img src="{{ asset('storage/'.$documentation->file_dokumentasi) }}" alt="{{ $documentation->keterangan ?: 'Dokumentasi '.$activity->judul }}" class="h-40 w-full object-cover">@if($documentation->keterangan)<figcaption class="p-3 text-sm text-slate-600">{{ $documentation->keterangan }}</figcaption>@endif</figure>@endforeach</div>
                @if($activity->status_aktivitas === 'selesai' && $activity->completion?->documentation_path)
                    @php($evidenceExtension = strtolower(pathinfo($activity->completion->documentation_path, PATHINFO_EXTENSION)))
                    @if(in_array($evidenceExtension, ['jpg', 'jpeg', 'png', 'webp'], true))
                        <figure class="mt-4 overflow-hidden rounded-xl border border-[#E2E8D8]"><img src="{{ route('activities.completion.document', $activity) }}" alt="Bukti penyelesaian {{ $activity->judul }}" class="max-h-[480px] w-full object-contain"><figcaption class="p-3 text-sm text-slate-600">Dokumentasi penyelesaian aktivitas</figcaption></figure>
                    @else
                        <a href="{{ route('activities.completion.document', $activity) }}" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex rounded-xl bg-[#F4F6F0] px-4 py-3 text-sm font-semibold text-[#2D6A4F]">Lihat dokumen bukti penyelesaian (PDF)</a>
                    @endif
                @endif
            </article>
        @endif
    </div>

    <aside class="space-y-5 lg:col-span-1">
        <article class="sticky top-24 rounded-[24px] border border-[#E2E8D8] bg-white p-5 shadow-sm md:p-6">
            <h2 class="text-lg font-bold text-slate-900">Informasi aktivitas</h2>
            <dl class="mt-5 space-y-4 text-sm">
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Mulai</dt><dd class="mt-1 font-medium text-slate-900">{{ $activity->tanggal_mulai?->format('d M Y, H:i') ?? 'Belum ditentukan' }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Selesai</dt><dd class="mt-1 font-medium text-slate-900">{{ $activity->tanggal_selesai?->format('d M Y, H:i') ?? 'Belum ditentukan' }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Lokasi</dt><dd class="mt-1 font-medium text-slate-900">{{ $activity->lokasi }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Batas pendaftaran</dt><dd class="mt-1 font-medium text-slate-900">{{ $activity->batas_pendaftaran?->format('d M Y, H:i') ?? 'Belum ditentukan' }}</dd></div>
            </dl>
            @if($activity->donasi_aktif && $activity->status_aktivitas !== 'selesai')
                <div class="mt-6 border-t border-slate-100 pt-5"><div class="flex items-end justify-between gap-3"><div><p class="text-xs text-slate-500">Donasi terkumpul</p><p class="mt-1 text-xl font-bold text-slate-900">Rp {{ number_format($totalDonasi, 0, ',', '.') }}</p></div><p class="text-right text-xs text-slate-500">Target<br><strong class="text-slate-900">Rp {{ number_format($activity->target_donasi ?? 0, 0, ',', '.') }}</strong></p></div><div class="mt-3 h-2.5 overflow-hidden rounded-full bg-[#E2E8D8]" role="progressbar" aria-label="Progress donasi" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $persenDonasi }}"><div class="h-full rounded-full bg-[#2D6A4F]" style="width: {{ $persenDonasi }}%"></div></div><p class="mt-1 text-right text-xs font-semibold text-[#2D6A4F]">{{ $persenDonasi }}%</p><p class="mt-3 whitespace-pre-line text-sm text-slate-600">{{ $activity->tujuan_donasi }}</p><div class="mt-3 rounded-xl bg-[#F4F6F0] p-3 text-sm"><p class="font-semibold text-slate-900">{{ $activity->nama_bank }} · {{ $activity->nomor_rekening }}</p><p class="text-slate-600">a.n. {{ $activity->nama_pemilik_rekening }}</p></div>
                    @if($user?->role === 'user' && Route::has('activities.donations.create'))<a href="{{ route('activities.donations.create', $activity) }}" class="mt-4 flex w-full justify-center rounded-xl bg-[#2D6A4F] px-4 py-3 text-sm font-semibold text-white hover:bg-[#24583f]">Donasi sekarang</a>@elseif(!$user && Route::has('login'))<a href="{{ route('login') }}" class="mt-4 flex w-full justify-center rounded-xl bg-[#2D6A4F] px-4 py-3 text-sm font-semibold text-white">Masuk untuk berdonasi</a>@endif
                </div>
            @endif
            <div id="registration" class="mt-6 border-t border-slate-100 pt-5">
                {{-- US-12: Mengakses Tautan WhatsApp Grup jika diterima --}}
                @if($application?->status === 'diterima')
                    <span class="inline-flex rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800">Pendaftaran diterima</span>
                    @if($activity->tautan_whatsapp)
                        <a href="{{ $activity->tautan_whatsapp }}" target="_blank" rel="noopener noreferrer" class="mt-3 flex w-full justify-center rounded-xl bg-[#2D6A4F] px-4 py-3 text-center text-sm font-semibold text-white hover:bg-[#24583f]">Masuk grup WhatsApp</a>
                    @else
                        <p class="mt-3 text-sm text-slate-600">Tautan grup WhatsApp belum tersedia dari penyelenggara.</p>
                    @endif
                @elseif($application)
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $application->status === 'ditolak' ? 'bg-rose-50 text-rose-800' : 'bg-amber-50 text-amber-800' }}">Pendaftaran {{ $application->status }}</span><p class="mt-3 text-sm text-slate-600">Status pendaftaran Anda akan diperbarui di halaman Kegiatan Saya.</p>
                @elseif($user?->role === 'user' && $activity->status_aktivitas === 'dipublikasikan')
                    <h2 class="font-bold text-slate-900">Tertarik bergabung?</h2><p class="mt-2 text-sm text-slate-600">Kirim pendaftaran kepada penyelenggara.</p><form action="{{ route('activities.apply', $activity) }}" method="POST" class="mt-4 space-y-3">@csrf<label for="motivation" class="block text-sm font-medium text-slate-700">Motivasi (opsional)</label><textarea id="motivation" name="motivation" rows="3" maxlength="3000" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm" placeholder="Ceritakan alasan Anda ingin bergabung"></textarea><button class="w-full rounded-xl bg-[#2D6A4F] px-4 py-3 font-semibold text-white hover:bg-[#24583f]">Daftar aktivitas</button></form>
                @elseif(!$user && $activity->status_aktivitas === 'dipublikasikan' && Route::has('login'))
                    <h2 class="font-bold text-slate-900">Bergabung sebagai relawan</h2><p class="mt-2 text-sm text-slate-600">Masuk ke akun relawan untuk mendaftar.</p><a href="{{ route('login') }}" class="mt-4 block rounded-xl bg-[#2D6A4F] px-4 py-3 text-center font-semibold text-white">Masuk</a>
                @elseif($activity->status_aktivitas === 'selesai')
                    <p class="text-sm font-semibold text-slate-600">Aktivitas ini telah selesai.</p>
                @else
                    <p class="text-sm text-slate-600">Pendaftaran aktivitas tersedia untuk akun relawan.</p>
                @endif
            </div>
        </article>
    </aside>
</main>
@endsection
