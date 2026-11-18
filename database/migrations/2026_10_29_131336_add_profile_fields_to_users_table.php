<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Profil dipisahkan ke tabel profil_users dan communities.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada perubahan skema yang perlu dibatalkan.
    }
};

