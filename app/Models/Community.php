<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Community extends Model
{
    protected $fillable = [
        'user_id',
        'nama_community',
        'deskripsi',
        'alamat',
        'kota',
        'kontak',
        'logo',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}