<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Activity extends Model
{
    protected $fillable = [
        'community_id', 'kategori_id', 'judul', 'gambar_aktivitas', 'deskripsi', 'lokasi', 'tanggal_mulai', 'tanggal_selesai',
        'batas_pendaftaran', 'kuota_relawan', 'target_dampak', 'tautan_whatsapp', 'donasi_aktif', 'target_donasi',
        'tujuan_donasi', 'nama_bank', 'nomor_rekening', 'nama_pemilik_rekening', 'status_aktivitas',
        'title', 'description', 'location', 'starts_at',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'tanggal_mulai' => 'datetime',
            'tanggal_selesai' => 'datetime',
            'batas_pendaftaran' => 'datetime',
            'donasi_aktif' => 'boolean',
            'kuota_relawan' => 'integer',
            'target_donasi' => 'integer',
        ];
    }

    public function community(): BelongsTo
    {
        return $this->belongsTo(Community::class, 'community_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function activityVerification(): HasOne
    {
        return $this->hasOne(ActivityVerification::class, 'activity_id');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function documentations(): HasMany
    {
        return $this->hasMany(ActivityDocumentation::class, 'activity_id');
    }

    public function completion(): HasOne
    {
        return $this->hasOne(ActivityCompletion::class);
    }
}
