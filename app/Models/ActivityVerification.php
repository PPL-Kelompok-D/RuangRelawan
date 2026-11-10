<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityVerification extends Model
{
    protected $table = 'verifikasi_aktivitas';

    protected $fillable = ['activity_id', 'admin_id', 'keputusan', 'catatan', 'tanggal_verifikasi'];

    protected function casts(): array
    {
        return ['tanggal_verifikasi' => 'datetime'];
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
