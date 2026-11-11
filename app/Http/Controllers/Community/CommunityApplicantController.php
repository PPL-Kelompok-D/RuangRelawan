<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Community;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CommunityApplicantController extends Controller
{
    public function index(Request $request, int $id): View
    {
        $community = $this->communityFor($request);
        $activity = $community->activities()->findOrFail($id);
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:menunggu,diterima,ditolak'],
        ]);

        $applications = $activity->applications()
            ->with(['applicant.profilUser', 'activity'])
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['q'] ?? null, function ($query, $keyword) {
                $query->whereHas('applicant', function ($applicantQuery) use ($keyword) {
                    $applicantQuery->where(function ($nameQuery) use ($keyword) {
                        $nameQuery->where('name', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%")
                            ->orWhereHas('profilUser', fn ($profile) => $profile->where('no_hp', 'like', "%{$keyword}%"));
                    });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();
        $user = $request->user();

        return view('community.applicants.index', compact('user', 'community', 'activity', 'applications', 'filters'));
    }

    public function accept(Request $request, int $id): RedirectResponse
    {
        $application = $this->decide($request, $id, 'diterima');

        return redirect()->route('community.activities.applicants.index', $application->activity_id)
            ->with('success', 'Pendaftar berhasil diterima.');
    }

    public function reject(Request $request, int $id): RedirectResponse
    {
        $application = $this->decide($request, $id, 'ditolak');

        return redirect()->route('community.activities.applicants.index', $application->activity_id)
            ->with('success', 'Pendaftar berhasil ditolak.');
    }

    private function decide(Request $request, int $id, string $decision): Application
    {
        $community = $this->communityFor($request);
        $application = Application::query()
            ->whereHas('activity', fn ($query) => $query->where('community_id', $community->id))
            ->findOrFail($id);

        if ($application->status !== 'menunggu') {
            throw ValidationException::withMessages(['application' => 'Keputusan hanya dapat dibuat untuk pendaftaran yang masih menunggu.']);
        }

        $application->update([
            'status' => $decision,
            'tanggal_keputusan' => now(),
        ]);

        return $application;
    }

    private function communityFor(Request $request): Community
    {
        return $request->user()->community()->firstOrFail();
    }
}
