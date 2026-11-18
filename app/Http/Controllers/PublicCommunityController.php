<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicCommunityController extends Controller
{
    public function show(Request $request, int $id): View
    {
        $community = Community::query()->findOrFail($id);
        $community->load([
            'documentations' => fn ($query) => $query->latest(),
            'activities' => fn ($query) => $query->with(['category', 'completion', 'documentations'])->latest(),
        ]);
        $activeActivities = $community->activities->where('status_aktivitas', 'dipublikasikan')->values();
        $completedActivities = $community->activities->where('status_aktivitas', 'selesai')->values();
        $user = $request->user();

        return view('communities.show', compact('community', 'activeActivities', 'completedActivities', 'user'));
    }
}
