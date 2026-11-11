<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Application;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ApplicantDemoSeeder extends Seeder
{
    public function run(): void
    {
        $community = User::updateOrCreate(
            ['email' => 'komunitas.demo@ruangrelawan.test'],
            [
                'name' => 'Komunitas Demo',
                'password' => Hash::make('password123'),
                'role' => 'community',
            ]
        );

        $communityProfile = $community->community()->updateOrCreate([], [
            'nama_community' => 'Komunitas Demo',
            'kota' => 'Bandung',
            'kontak' => '081234567890',
        ]);

        $activity = Activity::firstOrCreate(
            ['community_id' => $communityProfile->id, 'judul' => 'Aksi Bersih Sungai Cikapundung'],
            [
                'kategori_id' => Category::where('nama_kategori', 'Lingkungan')->value('id'),
                'title' => 'Aksi Bersih Sungai Cikapundung',
                'deskripsi' => 'Kegiatan bersih sungai bersama relawan.',
                'lokasi' => 'Bandung',
                'status_aktivitas' => 'dipublikasikan',
                'description' => 'Kegiatan bersih sungai bersama relawan.',
                'location' => 'Bandung',
                'starts_at' => now()->addWeek(),
                'tanggal_mulai' => now()->addWeek(),
                'tanggal_selesai' => now()->addWeek()->addHours(3),
                'batas_pendaftaran' => now()->addDays(5),
                'kuota_relawan' => 30,
                'target_dampak' => 'Membersihkan 2 km bantaran sungai',
                'tautan_whatsapp' => 'https://wa.me/6281234567890',
            ]
        );

        $applicant = User::updateOrCreate(
            ['email' => 'relawan.demo@ruangrelawan.test'],
            [
                'name' => 'Nadia Putri',
                'password' => Hash::make('password123'),
                'role' => 'user',
            ]
        );
        $applicant->profilUser()->updateOrCreate([], [
            'jenis_kelamin' => 'Perempuan',
            'no_hp' => '+62 812-0000-0001',
            'preferensi_lokasi' => 'Bandung',
        ]);

        Application::updateOrCreate(
            ['activity_id' => $activity->id, 'applicant_id' => $applicant->id],
            [
                'motivation' => 'Saya ingin ikut menjaga kebersihan sungai dan lingkungan sekitar.',
                'status' => 'menunggu',
            ]
        );
    }
}
