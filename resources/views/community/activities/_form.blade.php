@php
    $value = fn ($field, $default = null) => old($field, data_get($activity, $field, $default));
    $dateValue = fn ($field) => old($field, data_get($activity, $field)?->format('Y-m-d\TH:i'));
    $donationEnabled = (bool) old('donasi_aktif', $activity?->donasi_aktif ?? false);
    $targetDonationRaw = (string) old('target_donasi', $activity?->target_donasi ?? 100001);
    $targetDonationDigits = preg_replace('/\D+/', '', $targetDonationRaw);
    $targetDonationValue = $targetDonationDigits === '' ? '' : 'Rp '.number_format((int) $targetDonationDigits, 0, ',', '.');
@endphp
<section class="max-w-[1152px] w-full mx-auto bg-white border border-[#E2E8D8] rounded-[24px] shadow-[0px_20px_25px_-5px_rgba(0,0,0,0.1),0px_8px_10px_-6px_rgba(0,0,0,0.1)] p-6 md:p-8">
    <div class="mb-8">
        <a href="{{ route('community.my-activities') }}" class="text-sm font-semibold text-[#2D6A4F] hover:underline">← Kembali ke Aktivitas Saya</a>
        <p class="mt-5 text-sm font-semibold uppercase tracking-wide text-[#2D6A4F]">Pengelolaan Aktivitas</p>
        <h1 class="mt-2 text-2xl font-bold text-slate-900 md:text-3xl">{{ $heading }}</h1>
        <p class="mt-2 text-slate-600">Isi informasi yang jelas. Aktivitas akan ditinjau admin sebelum dipublikasikan.</p>
    </div>

    @if($errors->any())
        <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800"><ul class="list-disc space-y-1 pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @if($method !== 'POST') @method($method) @endif

        <div>
            <h2 class="mb-4 border-b border-slate-100 pb-3 text-lg font-bold text-slate-900">Informasi kegiatan</h2>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div>
                    <label for="judul" class="mb-2 block text-sm font-semibold text-slate-700">Judul aktivitas <span class="text-rose-600">*</span></label>
                    <input id="judul" name="judul" required maxlength="255" value="{{ $value('judul') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
                </div>
                <div>
                    <label for="kategori_id" class="mb-2 block text-sm font-semibold text-slate-700">Kategori</label>
                    <select id="kategori_id" name="kategori_id" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
                        <option value="">Pilih Kategori (Opsional)</option>
                        @foreach($categories as $category)<option value="{{ $category->id }}" @selected((string) $value('kategori_id') === (string) $category->id)>{{ $category->nama_kategori }}</option>@endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label for="gambar_aktivitas" class="mb-2 block text-sm font-semibold text-slate-700">Foto aktivitas (opsional, maks. 2 MB)</label>
                    <input id="gambar_aktivitas" name="gambar_aktivitas" type="file" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm">
                    <p class="mt-1 text-xs text-slate-500">Format JPG, PNG, atau WebP. Foto lama tetap digunakan jika tidak memilih file baru.</p>
                    @if($activity?->gambar_aktivitas)
                        <img id="activity-image-preview" src="{{ asset('storage/'.$activity->gambar_aktivitas) }}" alt="Foto aktivitas saat ini" class="mt-3 h-40 w-full rounded-xl object-cover sm:w-72">
                    @else
                        <img id="activity-image-preview" alt="Pratinjau foto aktivitas" class="mt-3 hidden h-40 w-full rounded-xl object-cover sm:w-72">
                    @endif
                </div>
                <div class="md:col-span-2">
                    <label for="deskripsi" class="mb-2 block text-sm font-semibold text-slate-700">Deskripsi <span class="text-rose-600">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="5" required maxlength="10000" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">{{ $value('deskripsi') }}</textarea>
                </div>
                <div class="md:col-span-2">
                    <label for="lokasi" class="mb-2 block text-sm font-semibold text-slate-700">Lokasi <span class="text-rose-600">*</span></label>
                    <input id="lokasi" name="lokasi" required maxlength="255" value="{{ $value('lokasi') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
                </div>
                <div>
                    <label for="tanggal_mulai" class="mb-2 block text-sm font-semibold text-slate-700">Mulai <span class="text-rose-600">*</span></label>
                    <input id="tanggal_mulai" name="tanggal_mulai" type="datetime-local" required value="{{ $dateValue('tanggal_mulai') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
                </div>
                <div>
                    <label for="tanggal_selesai" class="mb-2 block text-sm font-semibold text-slate-700">Selesai <span class="text-rose-600">*</span></label>
                    <input id="tanggal_selesai" name="tanggal_selesai" type="datetime-local" required value="{{ $dateValue('tanggal_selesai') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
                </div>
                <div>
                    <label for="batas_pendaftaran" class="mb-2 block text-sm font-semibold text-slate-700">Batas pendaftaran <span class="text-rose-600">*</span></label>
                    <input id="batas_pendaftaran" name="batas_pendaftaran" type="datetime-local" required value="{{ $dateValue('batas_pendaftaran') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
                </div>
                <div>
                    <label for="kuota_relawan" class="mb-2 block text-sm font-semibold text-slate-700">Kuota relawan <span class="text-rose-600">*</span></label>
                    <input id="kuota_relawan" name="kuota_relawan" type="number" min="1" max="100000" required value="{{ $value('kuota_relawan') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
                </div>
                <div>
                    <label for="target_dampak" class="mb-2 block text-sm font-semibold text-slate-700">Target dampak <span class="text-rose-600">*</span></label>
                    <input id="target_dampak" name="target_dampak" required maxlength="500" value="{{ $value('target_dampak') }}" placeholder="Contoh: 500 bibit tertanam" class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
                </div>
                <div>
                    <label for="tautan_whatsapp" class="mb-2 block text-sm font-semibold text-slate-700">Tautan WhatsApp <span class="text-rose-600">*</span></label>
                    <input id="tautan_whatsapp" name="tautan_whatsapp" type="url" required value="{{ $value('tautan_whatsapp') }}" placeholder="https://wa.me/628..." class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:border-[#2D6A4F] focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20">
                </div>
            </div>
        </div>

        <div>
            <h2 class="mb-4 border-b border-slate-100 pb-3 text-lg font-bold text-slate-900">Penggalangan dana</h2>
            <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-[#E2E8D8] bg-[#F4F6F0] p-4">
                <input id="donasi_aktif" name="donasi_aktif" type="checkbox" value="1" @checked($donationEnabled) class="mt-1 h-4 w-4 rounded border-slate-300 text-[#2D6A4F] focus:ring-[#2D6A4F]">
                <span><span class="block font-semibold text-slate-900">Aktifkan donasi</span><span class="mt-1 block text-sm text-slate-600">Detail rekening hanya ditampilkan jika penggalangan dana diaktifkan.</span></span>
            </label>
            <div id="donation-fields" class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2 {{ $donationEnabled ? '' : 'hidden' }}">
                <div><label for="target_donasi" class="mb-2 block text-sm font-semibold text-slate-700">Target donasi</label><input id="target_donasi" name="target_donasi" type="text" inputmode="numeric" autocomplete="off" @required($donationEnabled) value="{{ $targetDonationValue }}" placeholder="Rp 100.001" class="w-full rounded-xl border border-slate-300 px-4 py-3"></div>
                <div><label for="nama_bank" class="mb-2 block text-sm font-semibold text-slate-700">Nama bank</label><input id="nama_bank" name="nama_bank" maxlength="100" @required($donationEnabled) value="{{ $value('nama_bank') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3"></div>
                <div><label for="nomor_rekening" class="mb-2 block text-sm font-semibold text-slate-700">Nomor rekening</label><input id="nomor_rekening" name="nomor_rekening" maxlength="50" @required($donationEnabled) value="{{ $value('nomor_rekening') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3"></div>
                <div><label for="nama_pemilik_rekening" class="mb-2 block text-sm font-semibold text-slate-700">Nama pemilik rekening</label><input id="nama_pemilik_rekening" name="nama_pemilik_rekening" maxlength="150" @required($donationEnabled) value="{{ $value('nama_pemilik_rekening') }}" class="w-full rounded-xl border border-slate-300 px-4 py-3"></div>
                <div class="md:col-span-2"><label for="tujuan_donasi" class="mb-2 block text-sm font-semibold text-slate-700">Tujuan donasi</label><textarea id="tujuan_donasi" name="tujuan_donasi" rows="3" maxlength="2000" @required($donationEnabled) class="w-full rounded-xl border border-slate-300 px-4 py-3">{{ $value('tujuan_donasi') }}</textarea></div>
            </div>
        </div>

        <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 pt-5 sm:flex-row">
            <a href="{{ route('community.my-activities') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-center font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
            <button type="submit" class="rounded-xl bg-[#2D6A4F] px-6 py-3 font-semibold text-white transition hover:bg-[#24583f]">{{ $buttonLabel }}</button>
        </div>
    </form>
</section>
<script>
    const donationToggle = document.getElementById('donasi_aktif');
    const donationFields = document.getElementById('donation-fields');
    const donationInputs = ['target_donasi', 'tujuan_donasi', 'nama_bank', 'nomor_rekening', 'nama_pemilik_rekening']
        .map((id) => document.getElementById(id));

    const updateDonationFields = () => {
        const isEnabled = donationToggle?.checked ?? false;
        donationFields?.classList.toggle('hidden', !isEnabled);
        donationInputs.forEach((input) => {
            if (input) input.required = isEnabled;
        });
    };

    donationToggle?.addEventListener('change', updateDonationFields);
    updateDonationFields();

    document.getElementById('target_donasi')?.addEventListener('input', (event) => {
        const digits = event.target.value.replace(/\D/g, '');
        event.target.value = digits ? `Rp ${new Intl.NumberFormat('id-ID').format(Number(digits))}` : '';
    });

    document.getElementById('gambar_aktivitas')?.addEventListener('change', (event) => {
        const [file] = event.target.files;
        const preview = document.getElementById('activity-image-preview');
        if (!file || !preview) return;

        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    });
</script>
