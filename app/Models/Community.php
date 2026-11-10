<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Community extends Model
{
    protected $fillable = [
        'user_id', 'nama_community', 'deskripsi', 'logo', 'alamat', 'kota', 'kontak', 'status_verifikasi',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'community_id');
    }

    public function documentations(): HasMany
    {
        return $this->hasMany(ActivityDocumentation::class, 'community_id');
    }
}
