<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'kategori_aktivitas';

    protected $fillable = ['nama_kategori', 'deskripsi'];

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'kategori_id');
    }
}
