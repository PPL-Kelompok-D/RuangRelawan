<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityDocumentation extends Model
{
    protected $table = 'dokumentasi_aktivitas';

    protected $fillable = ['activity_id', 'community_id', 'file_dokumentasi', 'keterangan'];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}
