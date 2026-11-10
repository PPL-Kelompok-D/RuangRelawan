<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Category;
use App\Models\Community;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ActivityExplorationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:150'],
            'kategori_id' => ['nullable', 'integer', 'exists:kategori_aktivitas,id'],
            'urutkan' => ['nullable', 'in:terbaru,terdekat'],
        ]);

        $activities = Activity::query()
            ->with(['category', 'community'])
            ->withCount(['applications as accepted_applications_count' => fn ($query) => $query->where('status', 'diterima')])
            ->where('status_aktivitas', 'dipublikasikan')
            ->when($filters['q'] ?? null, function ($query, $keyword) {
                $query->where(function ($search) use ($keyword) {
                    $search->where('judul', 'like', "%{$keyword}%")
                        ->orWhere('deskripsi', 'like', "%{$keyword}%")
                        ->orWhere('lokasi', 'like', "%{$keyword}%");
                });
            })
            ->when($filters['kategori_id'] ?? null, fn ($query, $categoryId) => $query->where('kategori_id', $categoryId));

        if (($filters['urutkan'] ?? 'terbaru') === 'terdekat') {
            $activities->orderByRaw('tanggal_mulai IS NULL')->orderBy('tanggal_mulai');
        } else {
            $activities->latest();
        }

        $activities = $activities->limit(25)->get();
        $categories = Category::query()->orderBy('nama_kategori')->get();
        $communities = Community::query()
            ->withCount(['activities' => fn ($query) => $query->where('status_aktivitas', 'dipublikasikan')])
            ->with(['activities' => fn ($query) => $query->where('status_aktivitas', 'dipublikasikan')
                ->withCount(['applications as accepted_applications_count' => fn ($applications) => $applications->where('status', 'diterima')])])
            ->orderBy('nama_community')
            ->limit(25)
            ->get();
        $communities->each(function (Community $community): void {
            $community->setAttribute('accepted_volunteers_count', $community->activities->sum('accepted_applications_count'));
        });
        $statistics = [
            'relawan_aktif' => User::query()->where('role', 'user')->count(),
            'komunitas_terdaftar' => Community::query()->count(),
            'aksi_selesai' => Activity::query()->where('status_aktivitas', 'selesai')->count(),
            'total_donasi' => Donation::query()->where('status', 'diterima')->sum('amount'),
        ];
        $user = $request->user();
        $myOngoingActivities = collect();

        if ($user?->role === 'user') {
            $myOngoingActivities = $user->applications()
                ->whereIn('status', ['diterima', 'menunggu'])
                ->whereHas('activity', fn ($query) => $query->where('status_aktivitas', 'dipublikasikan'))
                ->with(['activity.category'])
                ->latest()
                ->get();
        }

        return view('activities.index', compact('activities', 'categories', 'communities', 'filters', 'statistics', 'myOngoingActivities', 'user'));
    }

    public function show(Request $request, int $id): View
    {
        $activity = Activity::query()
            ->with(['category', 'community.user', 'documentations', 'completion'])
            ->withCount(['applications as accepted_applications_count' => fn ($query) => $query->where('status', 'diterima')])
            ->whereIn('status_aktivitas', ['dipublikasikan', 'selesai'])
            ->findOrFail($id);

        $activity->load(['community', 'category', 'completion', 'documentations']);

        $user = $request->user();
        $application = $user?->role === 'user' && $activity->status_aktivitas === 'dipublikasikan'
            ? $activity->applications()->where('applicant_id', $user->id)->first()
            : null;
        $totalDonasi = $activity->donations()->where('status', 'diterima')->sum('amount');
        $persenDonasi = $activity->target_donasi > 0
            ? min(100, round(($totalDonasi / $activity->target_donasi) * 100))
            : 0;

        return view('activities.show', compact('activity', 'application', 'persenDonasi', 'totalDonasi', 'user'));
    }

    public function completionDocument(int $id): BinaryFileResponse
    {
        $activity = Activity::query()->with('completion')
            ->where('status_aktivitas', 'selesai')
            ->findOrFail($id);
        $path = $activity->completion?->documentation_path;
        $disk = Storage::disk('local');
        abort_unless($path && $disk->exists($path), 404, 'Dokumentasi penyelesaian tidak ditemukan.');

        return response()->file($disk->path($path));
    }
}
