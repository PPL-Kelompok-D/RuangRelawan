<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_profile_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Rian Ramadhan Kusuma',
            'email' => 'rian.ramadhan@email.com',
            'peran' => 'user',
        ]);
        $user->profilUser()->create([
            'no_hp' => '+62 812-3456-7890',
            'jenis_kelamin' => 'Laki-laki',
        ]);

        $response = $this->actingAs($user)->get('/profile');

        $response->assertOk()
            ->assertSee('Profil Saya')
            ->assertSee('Rian Ramadhan Kusuma')
            ->assertSee('rian.ramadhan@email.com');
    }

    public function test_user_can_update_their_profile_and_related_profile_record(): void
    {
        $user = User::factory()->create(['email' => 'rian.ramadhan@email.com', 'peran' => 'user']);
        $profile = $user->profilUser()->create();

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'Rian Ramadhan Kusuma S.Kom',
            'no_hp' => '+62 812-9999-8888',
            'tanggal_lahir' => '1997-10-05',
            'jenis_kelamin' => 'Laki-laki',
            'preferensi_lokasi' => 'Kota Bandung, Jawa Barat',
            'alamat' => 'Jl. Ir. H. Juanda No. 100',
        ]);

        $response->assertRedirect('/profile')->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Rian Ramadhan Kusuma S.Kom']);
        $this->assertDatabaseHas('profil_users', [
            'id' => $profile->id,
            'no_hp' => '+62 812-9999-8888',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Ir. H. Juanda No. 100',
        ]);
    }

    public function test_profile_update_requires_a_valid_account_name(): void
    {
        $user = User::factory()->create(['name' => 'Nama Awal', 'peran' => 'user']);
        $user->profilUser()->create(['no_hp' => '08123456789']);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => '',
            'no_hp' => 'nomor-invalid',
            'tanggal_lahir' => now()->addDay()->toDateString(),
            'jenis_kelamin' => 'InvalidGender',
        ]);

        $response->assertSessionHasErrors(['name', 'no_hp', 'tanggal_lahir', 'jenis_kelamin']);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'Nama Awal']);
        $this->assertDatabaseHas('profil_users', ['user_id' => $user->id, 'no_hp' => '08123456789']);
    }
}
