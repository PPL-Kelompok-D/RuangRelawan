<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('applications', 'tanggal_keputusan')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->timestamp('tanggal_keputusan')->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('applications', 'tanggal_keputusan')) {
            Schema::table('applications', fn (Blueprint $table) => $table->dropColumn('tanggal_keputusan'));
        }
    }
};
