<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            if (! Schema::hasColumn('activities', 'kategori_id')) {
                $table->foreignId('kategori_id')->nullable()->constrained('kategori_aktivitas')->nullOnDelete();
            }
            if (! Schema::hasColumn('activities', 'judul')) $table->string('judul')->nullable();
            if (! Schema::hasColumn('activities', 'deskripsi')) $table->text('deskripsi')->nullable();
            if (! Schema::hasColumn('activities', 'lokasi')) $table->string('lokasi')->nullable();
            if (! Schema::hasColumn('activities', 'tanggal_mulai')) $table->dateTime('tanggal_mulai')->nullable();
            if (! Schema::hasColumn('activities', 'tanggal_selesai')) $table->dateTime('tanggal_selesai')->nullable();
            if (! Schema::hasColumn('activities', 'batas_pendaftaran')) $table->dateTime('batas_pendaftaran')->nullable();
            if (! Schema::hasColumn('activities', 'kuota_relawan')) $table->unsignedInteger('kuota_relawan')->nullable();
            if (! Schema::hasColumn('activities', 'target_dampak')) $table->string('target_dampak')->nullable();
            if (! Schema::hasColumn('activities', 'tautan_whatsapp')) $table->string('tautan_whatsapp')->nullable();
            if (! Schema::hasColumn('activities', 'donasi_aktif')) $table->boolean('donasi_aktif')->default(false);
            if (! Schema::hasColumn('activities', 'target_donasi')) $table->unsignedBigInteger('target_donasi')->nullable();
            if (! Schema::hasColumn('activities', 'tujuan_donasi')) $table->text('tujuan_donasi')->nullable();
            if (! Schema::hasColumn('activities', 'nama_bank')) $table->string('nama_bank')->nullable();
            if (! Schema::hasColumn('activities', 'nomor_rekening')) $table->string('nomor_rekening')->nullable();
            if (! Schema::hasColumn('activities', 'nama_pemilik_rekening')) $table->string('nama_pemilik_rekening')->nullable();
            if (! Schema::hasColumn('activities', 'status_aktivitas')) {
                $table->enum('status_aktivitas', ['menunggu_verifikasi', 'dipublikasikan', 'ditolak', 'selesai'])
                    ->default('menunggu_verifikasi');
            }
        });

        DB::table('activities')->orderBy('id')->get()->each(function ($activity) {
            DB::table('activities')->where('id', $activity->id)->update([
                'judul' => $activity->judul ?? $activity->title,
                'deskripsi' => $activity->deskripsi ?? $activity->description,
                'lokasi' => $activity->lokasi ?? $activity->location,
                'tanggal_mulai' => $activity->tanggal_mulai ?? $activity->starts_at,
            ]);
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['community_id']);
        });

        foreach (DB::table('activities')->select('id', 'community_id')->orderBy('id')->get() as $activity) {
            $community = DB::table('communities')->where('user_id', $activity->community_id)->first();
            if (! $community) {
                $user = DB::table('users')->where('id', $activity->community_id)->first();
                $communityId = DB::table('communities')->insertGetId([
                    'user_id' => $activity->community_id,
                    'nama_community' => $user?->name ?? 'Komunitas',
                    'status_verifikasi' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $communityId = $community->id;
            }

            DB::table('activities')->where('id', $activity->id)->update(['community_id' => $communityId]);
        }

        Schema::table('activities', function (Blueprint $table) {
            $table->foreign('community_id')->references('id')->on('communities')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // Workflow data and community ownership are intentionally not collapsed back into user accounts.
    }
};
