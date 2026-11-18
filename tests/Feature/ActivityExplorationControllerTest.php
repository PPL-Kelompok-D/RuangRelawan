<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityExplorationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_renders_published_database_activities_and_communities(): void
    {
        $communityUser = User::factory()->create(['role' => 'community']);
        $community = $communityUser->community()->create([
            'nama_community' => 'Komunitas Pesisir',
            'deskripsi' => 'Menjaga lingkungan pantai.',
            'status_verifikasi' => 'verified',
        ]);
        $category = Category::create(['nama_kategori' => 'Lingkungan']);
        $activity = Activity::create([
            'community_id' => $community->id,
            'kategori_id' => $category->id,
            'judul' => 'Aksi Bersih Pantai Database',
            'deskripsi' => 'Bersih pantai bersama relawan.',
            'lokasi' => 'Pantai Ancol',
            'tanggal_mulai' => now()->addDays(20),
            'kuota_relawan' => 10,
            'status_aktivitas' => 'dipublikasikan',
        ]);
        Activity::create([
            'community_id' => $community->id,
            'judul' => 'Aktivitas Belum Dipublikasikan',
            'status_aktivitas' => 'menunggu_verifikasi',
        ]);

        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Aksi Bersih Pantai Database')
            ->assertSee('Lingkungan')
            ->assertSee('Komunitas Pesisir')
            ->assertSee('0 / 10')
            ->assertDontSee('Aktivitas Belum Dipublikasikan')
            ->assertSee(route('activities.show', $activity), false)
            ->assertSee('Kuota Terbanyak');
    }

    public function test_home_shows_database_empty_states_without_demo_records(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Belum ada aktivitas tersedia')
            ->assertSee('Belum ada komunitas penyelenggara yang tersedia.')
            ->assertDontSee('12.450+')
            ->assertDontSee('Komunitas Bahari Lestari');
    }
}
