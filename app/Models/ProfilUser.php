<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfilUser extends Model
{
    protected $table = 'profil_users';

    protected $fillable = [
        'user_id', 'foto_profil', 'no_hp', 'jenis_kelamin', 'tanggal_lahir', 'alamat', 'preferensi_lokasi',
    ];

    protected function casts(): array
    {
        return ['tanggal_lahir' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

