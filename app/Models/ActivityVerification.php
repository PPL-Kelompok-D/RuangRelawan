<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ActivityVerification extends Model
{
    protected $table = 'verifikasi_aktivitas';

    protected $primaryKey = 'id_verifikasi';

    public $incrementing = false;

    protected $keyType = 'string';

    const CREATED_AT = null;
    const UPDATED_AT = null;

    protected $fillable = [
        'id_aktivitas',
        'id_admin',
        'keputusan',
        'catatan',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($verification) {
            if (empty($verification->id_verifikasi)) {
                $verification->id_verifikasi = (string) Str::uuid();
            }
        });
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(
            Activity::class,
            'id_aktivitas',
            'id_aktivitas'
        );
    }
}
