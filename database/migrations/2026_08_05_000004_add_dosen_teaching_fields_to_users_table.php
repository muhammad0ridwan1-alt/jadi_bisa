<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'kode_dosen')) {
                $table->string('kode_dosen', 20)->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'mata_kuliah_diampu')) {
                $table->string('mata_kuliah_diampu')->nullable()->after('kode_dosen');
            }
            if (!Schema::hasColumn('users', 'sesi_per_kelas')) {
                $table->integer('sesi_per_kelas')->nullable()->default(1)->after('mata_kuliah_diampu');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kode_dosen', 'mata_kuliah_diampu', 'sesi_per_kelas']);
        });
    }
};
