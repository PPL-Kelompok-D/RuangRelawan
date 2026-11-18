<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_uses_role_column_and_redirects_to_admin_dashboard(): void
    {
        User::factory()->create([
            'email' => 'admin@ruangrelawan.com',
            'password' => Hash::make('Admin12345!'),
            'role' => 'admin',
        ]);

        $this->post(route('login.perform'), [
            'role' => 'admin',
            'login' => 'admin@ruangrelawan.com',
            'password' => 'Admin12345!',
        ])->assertRedirect(route('admin.dashboard'));

        $this->get(route('admin.dashboard'))->assertOk();
    }

    public function test_volunteer_login_redirects_to_volunteer_profile(): void
    {
        User::factory()->create([
            'email' => 'relawan@example.test',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $this->post(route('login.perform'), [
            'role' => 'relawan',
            'login' => 'relawan@example.test',
            'password' => 'password123',
        ])->assertRedirect(route('profile.edit'));
    }

    public function test_community_login_redirects_to_community_profile(): void
    {
        $community = User::factory()->create([
            'email' => 'komunitas@example.test',
            'password' => Hash::make('password123'),
            'role' => 'community',
        ]);
        $community->community()->create(['nama_community' => 'Komunitas Uji']);

        $this->post(route('login.perform'), [
            'role' => 'komunitas',
            'login' => 'komunitas@example.test',
            'password' => 'password123',
        ])->assertRedirect(route('community.profile.edit'));
    }

    public function test_wrong_selected_role_shows_the_registered_role_name(): void
    {
        User::factory()->create([
            'email' => 'admin@example.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->from(route('login'))->post(route('login.perform'), [
            'role' => 'relawan',
            'login' => 'admin@example.test',
            'password' => 'password123',
        ])->assertSessionHasErrors(['role' => 'Akun ini terdaftar sebagai Admin. Silakan pilih peran yang sesuai.']);
    }

    public function test_only_admin_can_access_admin_dashboard(): void
    {
        $volunteer = User::factory()->create(['role' => 'user']);
        $community = User::factory()->create(['role' => 'community']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($volunteer)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($community)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    }

    public function test_volunteer_registration_stores_the_user_role(): void
    {
        $this->post(route('register.perform'), [
            'role' => 'relawan',
            'name' => 'Relawan Baru',
            'email' => 'relawan.baru@example.test',
            'no_hp' => '081234567890',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => 'on',
        ])->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', [
            'email' => 'relawan.baru@example.test',
            'role' => 'user',
        ]);
    }

    public function test_community_registration_stores_the_community_role(): void
    {
        $this->post(route('register.perform'), [
            'role' => 'komunitas',
            'name' => 'Komunitas Baru',
            'email' => 'komunitas.baru@example.test',
            'no_hp' => '081234567891',
            'kota' => 'Bandung',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => 'on',
        ])->assertRedirect(route('community.profile.edit'));

        $this->assertDatabaseHas('users', [
            'email' => 'komunitas.baru@example.test',
            'role' => 'community',
        ]);
    }

    public function test_logout_ends_the_session_and_returns_to_login(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post(route('logout'))
            ->assertRedirect(route('login'))
            ->assertSessionHas('success', 'Anda telah berhasil keluar.');

        $this->assertGuest();
    }
}
