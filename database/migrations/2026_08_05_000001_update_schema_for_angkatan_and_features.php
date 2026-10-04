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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'angkatan')) {
                    $table->integer('angkatan')->default(29)->after('kelas');
                }
                if (!Schema::hasColumn('users', 'tahun_ajaran')) {
                    $table->string('tahun_ajaran')->default('2025/2026')->after('angkatan');
                }
            });
        }

        if (Schema::hasTable('mata_kuliahs')) {
            Schema::table('mata_kuliahs', function (Blueprint $table) {
                if (!Schema::hasColumn('mata_kuliahs', 'angkatan')) {
                    $table->integer('angkatan')->default(29)->after('cawu');
                }
                if (!Schema::hasColumn('mata_kuliahs', 'tahun_ajaran')) {
                    $table->string('tahun_ajaran')->default('2025/2026')->after('angkatan');
                }
            });
        }

        if (Schema::hasTable('jadwals')) {
            Schema::table('jadwals', function (Blueprint $table) {
                if (!Schema::hasColumn('jadwals', 'angkatan')) {
                    $table->integer('angkatan')->default(29)->after('kelas');
                }
                if (!Schema::hasColumn('jadwals', 'tahun_ajaran')) {
                    $table->string('tahun_ajaran')->default('2025/2026')->after('angkatan');
                }
            });
        }

        if (Schema::hasTable('tugases')) {
            Schema::table('tugases', function (Blueprint $table) {
                if (!Schema::hasColumn('tugases', 'angkatan')) {
                    $table->integer('angkatan')->default(29)->after('deadline');
                }
                if (!Schema::hasColumn('tugases', 'tahun_ajaran')) {
                    $table->string('tahun_ajaran')->default('2025/2026')->after('angkatan');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['angkatan', 'tahun_ajaran']);
        });
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->dropColumn(['angkatan', 'tahun_ajaran']);
        });
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropColumn(['angkatan', 'tahun_ajaran']);
        });
        Schema::table('tugases', function (Blueprint $table) {
            $table->dropColumn(['angkatan', 'tahun_ajaran']);
        });
    }
};
