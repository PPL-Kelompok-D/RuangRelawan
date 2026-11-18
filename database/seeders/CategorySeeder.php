<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Lingkungan' => 'Inisiatif pelestarian alam, penanaman pohon, dan pembersihan sampah',
            'Pendidikan' => 'Pengajaran, pelatihan keterampilan, dan literasi anak',
            'Sosial & Kemanusiaan' => 'Bantuan sosial, panti asuhan, dan pemberdayaan masyarakat',
            'Kesehatan' => 'Layanan kesehatan gratis, donor darah, dan penyuluhan',
            'Tanggap Bencana' => 'Bantuan darurat dan pemulihan pascabencana',
        ];

        foreach ($categories as $name => $description) {
            Category::updateOrCreate(
                ['nama_kategori' => $name],
                ['deskripsi' => $description],
            );
        }
    }
}
