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
        ]);

        $applications = $activity->applications()
            ->with(['applicant.profilUser', 'activity'])
            ->when($filters['q'] ?? null, function ($query, $keyword) {
                $query->whereHas('applicant', function ($applicantQuery) use ($keyword) {
                    $applicantQuery->where(function ($nameQuery) use ($keyword) {
                        $nameQuery->where('name', 'like', "%{$keyword}%")
                            ->orWhere('email', 'like', "%{$keyword}%");
                    });
                });
            })
            ->latest()
            ->get();
        $user = $request->user();
        $filters['status'] = '';

        return view('community.applicants.index', compact('user', 'community', 'activity', 'applications', 'filters'));
    }

    private function communityFor(Request $request): Community
    {
        return $request->user()->community()->firstOrFail();
    }
}
