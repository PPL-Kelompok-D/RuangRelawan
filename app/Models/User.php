<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'peran'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function profilUser()
    {
        return $this->hasOne(ProfilUser::class);
    }

    public function community()
    {
        return $this->hasOne(Community::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'applicant_id');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    /** Read the legacy Indonesian attribute from the canonical database column. */
    public function getPeranAttribute(): ?string
    {
        return $this->attributes['role'] ?? null;
    }

    /** Write legacy assignments to the canonical database column. */
    public function setPeranAttribute(?string $peran): void
    {
        $this->attributes['role'] = $this->normalizeRole($peran);
    }

    public function setRoleAttribute(?string $role): void
    {
        $this->attributes['role'] = $this->normalizeRole($role);
    }

    private function normalizeRole(?string $role): ?string
    {
        return match ($role) {
            'relawan' => 'user',
            'komunitas' => 'community',
            default => $role,
        };
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function community(): HasOne
    {
        return $this->hasOne(Community::class);
    }

    public function profilUser(): HasOne
    {
        return $this->hasOne(ProfilUser::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'applicant_id');
    }
}
