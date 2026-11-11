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
        Schema::table('activities', function (Blueprint $table): void {
            if (! Schema::hasColumn('activities', 'gambar_aktivitas')) {
                $table->string('gambar_aktivitas')->nullable()->after('judul');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('activities', 'gambar_aktivitas')) {
            Schema::table('activities', fn (Blueprint $table) => $table->dropColumn('gambar_aktivitas'));
        }
    }
};
