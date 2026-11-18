<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    protected $fillable = [
        'activity_id',
        'applicant_id',
        'motivation',
        'status',
        'tanggal_keputusan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_keputusan' => 'datetime',
        ];
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }
}
