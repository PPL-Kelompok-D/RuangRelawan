<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommunityApplicantTest extends TestCase
{
    use RefreshDatabase;

    public function test_community_can_search_and_filter_its_applicants(): void
    {
        $community = User::factory()->create(['peran' => 'community']);
        $community->community()->create(['nama_community' => 'Komunitas Demo']);
        $activity = Activity::create([
            'community_id' => $community->community->id,
            'title' => 'Aksi Bersih Sungai',
        ]);
        $nadia = User::factory()->create(['name' => 'Nadia Putri', 'email' => 'nadia@example.test', 'peran' => 'user']);
        $nadia->profilUser()->create(['no_hp' => '081234567890']);
        $budi = User::factory()->create(['name' => 'Budi Santoso', 'email' => 'budi@example.test', 'peran' => 'user']);
        $budi->profilUser()->create();

        Application::create([
            'activity_id' => $activity->id,
            'applicant_id' => $nadia->id,
            'status' => 'menunggu',
        ]);
        Application::create([
            'activity_id' => $activity->id,
            'applicant_id' => $budi->id,
            'status' => 'diterima',
        ]);

        $searchResponse = $this->actingAs($community)->get(route('community.activities.applicants.index', [$activity->id, 'q' => 'Nadia']));
        $searchResponse->assertOk()->assertSee('Nadia Putri')->assertDontSee('Budi Santoso');

        $filterResponse = $this->actingAs($community)->get(route('community.activities.applicants.index', [$activity->id, 'status' => 'diterima']));
        $filterResponse->assertOk()->assertSee('Budi Santoso')->assertDontSee('Nadia Putri');
    }

    public function test_community_cannot_view_applicants_from_another_community_activity(): void
    {
        $community = User::factory()->create(['peran' => 'community']);
        $community->community()->create(['nama_community' => 'Komunitas Saya']);
        $otherCommunity = User::factory()->create(['peran' => 'community']);
        $otherCommunity->community()->create(['nama_community' => 'Komunitas Lain']);
        $otherActivity = Activity::create([
            'community_id' => $otherCommunity->community->id,
            'title' => 'Aktivitas Privat Komunitas Lain',
        ]);

        $this->actingAs($community)
            ->get(route('community.activities.applicants.index', $otherActivity->id))
            ->assertNotFound();
    }

    public function test_non_community_user_cannot_open_applicant_management(): void
    {
        $volunteer = User::factory()->create(['peran' => 'user']);
        $volunteer->profilUser()->create();

        $this->actingAs($volunteer)
            ->get(route('community.activities.applicants.index', 1))
            ->assertForbidden();
    }
}
