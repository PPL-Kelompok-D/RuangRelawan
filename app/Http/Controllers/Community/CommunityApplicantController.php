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
        $applications = $activity->applications()
            ->with(['applicant.profilUser', 'activity'])
            ->latest()
            ->get();
        $user = $request->user();
        $filters = ['q' => '', 'status' => ''];

        return view('community.applicants.index', compact('user', 'community', 'activity', 'applications', 'filters'));
    }

    private function communityFor(Request $request): Community
    {
        return $request->user()->community()->firstOrFail();
    }
}
