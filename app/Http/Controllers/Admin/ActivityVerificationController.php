<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ActivityVerificationController extends Controller
{
    public function index(Request $request): View
    {
        $activities = Activity::query()
            ->with(['community', 'category'])
            ->where('status_aktivitas', 'menunggu_verifikasi')
            ->orderBy('dibuat_pada')
            ->paginate(12);

        $user = $request->user();

        return view('admin.verifications.index', compact('user', 'activities'));
    }

    public function show(Request $request, string $id): View
    {
        $activity = Activity::query()
            ->with(['community', 'category'])
            ->where('status_aktivitas', 'menunggu_verifikasi')
            ->findOrFail($id);

        $user = $request->user();

        return view('admin.verifications.show', compact('user', 'activity'));
    }

    public function approve(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'catatan' => ['nullable', 'string', 'max:5000'],
        ]);

        $this->decide(
            $request,
            $id,
            'dipublikasikan',
            'disetujui',
            $validated['catatan'] ?? null
        );

        return redirect()
            ->route('admin.verifications.index')
            ->with('success', 'Aktivitas berhasil dipublikasikan.');
    }

    public function reject(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            'catatan' => ['required', 'string', 'min:5', 'max:5000'],
        ], [
            'catatan.required' => 'Alasan penolakan wajib diisi.',
            'catatan.min' => 'Jelaskan alasan penolakan minimal 5 karakter.',
        ]);

        $this->decide(
            $request,
            $id,
            'ditolak',
            'ditolak',
            $validated['catatan']
        );

        return redirect()
            ->route('admin.verifications.index')
            ->with('success', 'Pengajuan aktivitas ditolak.');
    }

    private function decide(
        Request $request,
        string $id,
        string $activityStatus,
        string $decision,
        ?string $note
    ): void {
        DB::transaction(function () use (
            $request,
            $id,
            $activityStatus,
            $decision,
            $note
        ) {
            $activity = Activity::query()
                ->where('status_aktivitas', 'menunggu_verifikasi')
                ->lockForUpdate()
                ->findOrFail($id);

            $activity->update([
                'status_aktivitas' => $activityStatus,
            ]);

            ActivityVerification::updateOrCreate(
                ['id_aktivitas' => $activity->id_aktivitas],
                [
                    'id_admin' => $request->user()->getKey(),
                    'keputusan' => $decision,
                    'catatan' => $note,
                    'tanggal_verifikasi' => now(),
                ]
            );
        });
    }
}
