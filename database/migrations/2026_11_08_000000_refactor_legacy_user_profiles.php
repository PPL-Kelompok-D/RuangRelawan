<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $hasRoleColumn = Schema::hasColumn('users', 'role');
        $hasLegacyPeranColumn = Schema::hasColumn('users', 'peran');

        if (! $hasRoleColumn) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('user');
            });

            if ($hasLegacyPeranColumn) {
                DB::table('users')->select('id', 'peran')->orderBy('id')->each(function ($account): void {
                    $role = match ($account->peran) {
                        'komunitas', 'community' => 'community',
                        'admin' => 'admin',
                        default => 'user',
                    };
                    DB::table('users')->where('id', $account->id)->update(['role' => $role]);
                });
            }
        }

        $legacy = collect([
            'role', 'volunteer_id', 'is_verified', 'avatar', 'title', 'action_hours', 'actions_count',
            'certificates_count', 'full_name', 'nickname', 'birth_date', 'gender', 'phone', 'city', 'address',
        ])->filter(fn ($column) => Schema::hasColumn('users', $column))->all();

        if (in_array('role', $legacy, true)) {
            foreach (DB::table('users')->select('id', 'role')->get() as $account) {
                $role = match ($account->role) {
                    'komunitas', 'community' => 'community',
                    'admin' => 'admin',
                    default => 'user',
                };
                DB::table('users')->where('id', $account->id)->update(['role' => $role]);
            }
        }

        foreach (DB::table('users')->get() as $account) {
            $role = $account->role ?? $account->peran ?? 'user';
            if ($role === 'community' && Schema::hasTable('communities')) {
                DB::table('communities')->updateOrInsert(
                    ['user_id' => $account->id],
                    [
                        'nama_community' => $account->full_name ?? $account->name,
                        'kota' => $account->city ?? null,
                        'kontak' => $account->phone ?? null,
                        'logo' => $account->avatar ?? null,
                        'alamat' => $account->address ?? null,
                        'status_verifikasi' => ! empty($account->is_verified) ? 'verified' : 'pending',
                        'created_at' => now(), 'updated_at' => now(),
                    ]
                );
            } elseif ($role === 'user' && Schema::hasTable('profil_users')) {
                DB::table('profil_users')->updateOrInsert(
                    ['user_id' => $account->id],
                    [
                        'foto_profil' => $account->avatar ?? null,
                        'no_hp' => $account->phone ?? null,
                        'jenis_kelamin' => $account->gender ?? null,
                        'tanggal_lahir' => $account->birth_date ?? null,
                        'alamat' => $account->address ?? null,
                        'preferensi_lokasi' => $account->city ?? null,
                        'created_at' => now(), 'updated_at' => now(),
                    ]
                );
            }
        }

        // Keep existing user columns intact; deployments may still rely on them.
    }

    public function down(): void
    {
        // Refactor migrates profile data out of users; restoring the denormalized schema is intentionally not automatic.
    }
};

