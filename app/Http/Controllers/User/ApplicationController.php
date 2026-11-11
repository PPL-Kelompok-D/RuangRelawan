<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    /**
     * Mendaftarkan relawan pada aktivitas.
     */
    public function store(Request $request, int $id): RedirectResponse
    {
        abort_unless($request->user()?->role === 'user', 403);

        $validated = $request->validate([
            'motivation' => ['nullable', 'string', 'max:3000'],
        ]);

        DB::transaction(function () use ($request, $id, $validated) {

            // Ambil aktivitas langsung dari tabel database.
            $activity = DB::table('activities')
                ->where('id', $id)
                ->where('status_aktivitas', 'dipublikasikan')
                ->lockForUpdate()
                ->first();

            if (!$activity) {
                throw ValidationException::withMessages([
                    'application' => 'Aktivitas tidak ditemukan atau belum dipublikasikan.',
                ]);
            }

            // Periksa batas pendaftaran.
            if (
                $activity->batas_pendaftaran &&
                now()->gt($activity->batas_pendaftaran)
            ) {
                throw ValidationException::withMessages([
                    'application' => 'Batas pendaftaran aktivitas ini sudah lewat.',
                ]);
            }

            // Cegah pendaftaran ganda.
            $duplicate = DB::table('applications')
                ->where('activity_id', $id)
                ->where('applicant_id', $request->user()->id)
                ->exists();

            if ($duplicate) {
                throw ValidationException::withMessages([
                    'application' => 'Anda sudah mendaftar pada aktivitas ini.',
                ]);
            }

            // Hitung jumlah pendaftar yang sudah diterima.
            $acceptedCount = DB::table('applications')
                ->where('activity_id', $id)
                ->where('status', 'diterima')
                ->count();

            // Periksa kuota relawan.
            if (
                $activity->kuota_relawan !== null &&
                $acceptedCount >= $activity->kuota_relawan
            ) {
                throw ValidationException::withMessages([
                    'application' => 'Kuota relawan untuk aktivitas ini sudah penuh.',
                ]);
            }

            // Simpan pendaftaran.
            DB::table('applications')->insert([
                'activity_id' => $id,
                'applicant_id' => $request->user()->id,
                'motivation' => $validated['motivation'] ?? null,
                'status' => 'menunggu',
                'tanggal_keputusan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()
            ->route('user.my-activities')
            ->with('success', 'Pendaftaran Anda berhasil dikirim.');
    }

    /**
     * Menampilkan daftar aktivitas yang didaftarkan relawan.
     */
    public function myActivities(Request $request): View
    {
        abort_unless($request->user()?->role === 'user', 403);

        $applications = $request->user()
            ->applications()
            ->latest()
            ->paginate(8, ['*'], 'applications_page')
            ->withQueryString();

        $tab = 'applications';
        $user = $request->user();

        return view('user.my_activities', compact(
            'applications',
            'tab',
            'user'
        ));
    }
}
