<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Category;
use App\Models\Community;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function myActivities(Request $request): View
    {
        $community = $this->communityFor($request);
        $activities = $community->activities()
            ->with(['category', 'activityVerification', 'completion'])
            ->withCount(['applications', 'donations', 'documentations'])
            ->latest()
            ->paginate(10);
        $user = $request->user();

        return view('community.my_activities', compact('user', 'community', 'activities'));
    }

    public function create(Request $request): View
    {
        $this->communityFor($request);
        $categories = Category::all();
        $user = $request->user();

        return view('community.activities.create', compact('user', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $community = $this->communityFor($request);
        $data = $this->validatedActivity($request);
        $data['community_id'] = $community->id;
        $data['status_aktivitas'] = 'menunggu_verifikasi';
        if ($request->hasFile('gambar_aktivitas')) {
            $data['gambar_aktivitas'] = $request->file('gambar_aktivitas')->store('activities', 'public');
        }
        $data = $this->withLegacyFields($data);

        Activity::create($data);

        return redirect()->route('community.my-activities')
            ->with('success', 'Aktivitas berhasil dikirim dan menunggu verifikasi admin.');
    }

    public function edit(Request $request, int $id): View
    {
        $community = $this->communityFor($request);
        $activity = $this->ownedActivity($community, $id);
        $this->ensureEditable($activity);
        $categories = Category::all();
        $user = $request->user();

        return view('community.activities.edit', compact('user', 'activity', 'categories'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $community = $this->communityFor($request);
        $activity = $this->ownedActivity($community, $id);
        $this->ensureEditable($activity);

        $data = $this->validatedActivity($request);
        $data['status_aktivitas'] = $activity->status_aktivitas === 'ditolak'
            ? 'menunggu_verifikasi'
            : $activity->status_aktivitas;
        $oldImagePath = $activity->gambar_aktivitas;
        if ($request->hasFile('gambar_aktivitas')) {
            $data['gambar_aktivitas'] = $request->file('gambar_aktivitas')->store('activities', 'public');
        } else {
            unset($data['gambar_aktivitas']);
        }

        $activity->update($this->withLegacyFields($data));
        if ($request->hasFile('gambar_aktivitas') && $oldImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()->route('community.my-activities')
            ->with('success', 'Perubahan tersimpan. Aktivitas menunggu verifikasi admin kembali jika sebelumnya ditolak.');
    }

    private function communityFor(Request $request): Community
    {
        return $request->user()->community()->firstOrFail();
    }

    private function ownedActivity(Community $community, int $id): Activity
    {
        return $community->activities()->with('activityVerification')->findOrFail($id);
    }

    private function ensureEditable(Activity $activity): void
    {
        if (! in_array($activity->status_aktivitas, ['menunggu_verifikasi', 'ditolak'], true)) {
            throw ValidationException::withMessages([
                'activity' => 'Aktivitas yang sudah dipublikasikan atau selesai tidak dapat diubah.',
            ]);
        }
    }

    private function validatedActivity(Request $request): array
    {
        $donationEnabled = $request->boolean('donasi_aktif');
        $targetDonationDigits = preg_replace('/\D+/', '', (string) $request->input('target_donasi'));
        $request->merge([
            'target_donasi' => $targetDonationDigits === '' ? null : $targetDonationDigits,
        ]);

        $donationFieldRequirement = $donationEnabled ? 'required' : 'nullable';

        $validated = $request->validate([
            'kategori_id' => ['nullable', 'integer', 'exists:kategori_aktivitas,id'],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string', 'max:10000'],
            'lokasi' => ['required', 'string', 'max:255'],
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'batas_pendaftaran' => ['required', 'date', 'before:tanggal_mulai'],
            'kuota_relawan' => ['required', 'integer', 'min:1', 'max:100000'],
            'target_dampak' => ['required', 'string', 'max:500'],
            'tautan_whatsapp' => ['required', 'url', 'regex:/^https:\/\/(wa\.me|api\.whatsapp\.com|chat\.whatsapp\.com)\/.+$/'],
            'gambar_aktivitas' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'donasi_aktif' => ['sometimes', 'boolean'],
            'target_donasi' => [$donationEnabled ? 'required' : 'nullable', 'integer', 'min:1', 'max:999999999999'],
            'tujuan_donasi' => [$donationEnabled ? 'required' : 'nullable', 'string', 'max:2000'],
            'nama_bank' => [$donationEnabled ? 'required' : 'nullable', 'string', 'max:100'],
            'nomor_rekening' => [$donationEnabled ? 'required' : 'nullable', 'string', 'max:50'],
            'nama_pemilik_rekening' => [$donationEnabled ? 'required' : 'nullable', 'string', 'max:150'],
        ]);

        $validated['donasi_aktif'] = $request->boolean('donasi_aktif');
        if (! $validated['donasi_aktif']) {
            foreach (['target_donasi', 'tujuan_donasi', 'nama_bank', 'nomor_rekening', 'nama_pemilik_rekening'] as $field) {
                $validated[$field] = null;
            }
        }

        return $validated;
    }

    private function withLegacyFields(array $data): array
    {
        $data['title'] = $data['judul'];
        $data['description'] = $data['deskripsi'];
        $data['location'] = $data['lokasi'];
        $data['starts_at'] = $data['tanggal_mulai'];

        return $data;
    }
}
