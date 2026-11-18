<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $table = 'aktivitas';

    protected $primaryKey = 'id_aktivitas';

    public $incrementing = false;

    protected $keyType = 'string';

    const CREATED_AT = 'dibuat_pada';
    const UPDATED_AT = 'diperbarui_pada';

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'batas_pendaftaran' => 'datetime',
        'donasi_aktif' => 'boolean',
    ];

    public function community(): BelongsTo
    {
        return $this->belongsTo(
            Community::class,
            'id_community',
            'id_community'
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'id_kategori',
            'id_kategori'
        );
    }
}
