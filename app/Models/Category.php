<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'kategori_aktivitas';

    protected $fillable = ['nama_kategori', 'deskripsi'];

    public function activities()
    {
        return $this->hasMany(Activity::class, 'kategori_id');
    }
}
