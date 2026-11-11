<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;
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

    private function communityFor(Request $request): Community
    {
        return $request->user()->community()->firstOrFail();
    }
}
