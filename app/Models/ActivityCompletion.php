<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityCompletion extends Model
{
    protected $table = 'penyelesaian_aktivitas';

    protected $fillable = ['activity_id', 'ringkasan_pelaksanaan', 'hasil_aktual', 'documentation_path'];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}
