<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function store(Request $request, int $id): RedirectResponse
    {
        abort_unless($request->user()->role === 'user', 403);
        $validated = $request->validate([
            'motivation' => ['nullable', 'string', 'max:3000'],
        ]);

        DB::transaction(function () use ($request, $id, $validated) {
            $activity = Activity::query()
                ->where('status_aktivitas', 'dipublikasikan')
                ->lockForUpdate()
                ->findOrFail($id);

            if ($activity->batas_pendaftaran && now()->gt($activity->batas_pendaftaran)) {
                throw ValidationException::withMessages(['application' => 'Batas pendaftaran aktivitas ini sudah lewat.']);
            }

            $duplicate = Application::query()
                ->where('activity_id', $activity->id)
                ->where('applicant_id', $request->user()->id)
                ->exists();
            if ($duplicate) {
                throw ValidationException::withMessages(['application' => 'Anda sudah mendaftar pada aktivitas ini.']);
            }

            $acceptedCount = $activity->applications()->where('status', 'diterima')->count();
            if ($activity->kuota_relawan !== null && $acceptedCount >= $activity->kuota_relawan) {
                throw ValidationException::withMessages(['application' => 'Kuota relawan untuk aktivitas ini sudah penuh.']);
            }

            $activity->applications()->create([
                'applicant_id' => $request->user()->id,
                'motivation' => $validated['motivation'] ?? null,
                'status' => 'menunggu',
            ]);
        });

        return redirect()->route('user.my-activities')->with('success', 'Pendaftaran Anda berhasil dikirim.');
    }

    public function myActivities(Request $request): View
    {
        abort_unless($request->user()->role === 'user', 403);
        $applications = $request->user()->applications()
            ->with(['activity.community', 'activity.category'])
            ->latest()
            ->paginate(8, ['*'], 'applications_page')
            ->withQueryString();
        $donations = $request->user()->donations()
            ->with(['activity.community'])
            ->latest()
            ->paginate(8, ['*'], 'donations_page')
            ->withQueryString();
        $tab = $request->query('tab', 'applications');
        if (! in_array($tab, ['applications', 'donations'], true)) {
            $tab = 'applications';
        }
        $user = $request->user();

        return view('user.my_activities', compact('applications', 'donations', 'tab', 'user'));
    }
}
