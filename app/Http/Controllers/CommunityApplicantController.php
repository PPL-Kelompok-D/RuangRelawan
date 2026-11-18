<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunityApplicantController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->role === 'community', 403);

        $validated = $request->validate([
            'activity' => ['nullable', 'integer'],
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:menunggu,diterima,ditolak'],
        ]);

        $user = $request->user();
        $community = $user->community()->firstOrFail();
        $activities = Activity::query()
            ->where('community_id', $community->id)
            ->withCount('applications')
            ->orderByDesc('starts_at')
            ->get();

        $selectedActivity = null;
        if (! empty($validated['activity'])) {
            $selectedActivity = $activities->firstWhere('id', (int) $validated['activity']);
            abort_unless($selectedActivity, 404);
        }

        $applications = Application::query()
            ->with(['applicant', 'activity'])
            ->whereHas('activity', fn ($query) => $query->where('community_id', $community->id))
            ->when($selectedActivity, fn ($query) => $query->where('activity_id', $selectedActivity->id))
            ->when($validated['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($validated['q'] ?? null, function ($query, $search) {
                $query->whereHas('applicant', function ($applicantQuery) use ($search) {
                    $applicantQuery->where(function ($nameQuery) use ($search) {
                        $nameQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                        $nameQuery->orWhereHas('profilUser', fn ($profileQuery) => $profileQuery->where('no_hp', 'like', "%{$search}%"));
                    });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('community.applicants.index', compact('user', 'community', 'activities', 'selectedActivity', 'applications'));
    }
}
