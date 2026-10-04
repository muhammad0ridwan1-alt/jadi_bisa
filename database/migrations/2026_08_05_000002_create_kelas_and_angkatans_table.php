<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('angkatans')) {
            Schema::create('angkatans', function (Blueprint $table) {
                $table->id();
                $table->integer('nomor_angkatan')->unique(); // e.g. 29, 30, 31
                $table->string('tahun_ajaran'); // e.g. 2025/2026
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Seed default Angkatan 29 for Bogor Educare
            DB::table('angkatans')->insert([
                'nomor_angkatan' => 29,
                'tahun_ajaran' => '2025/2026',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!Schema::hasTable('kelas_list')) {
            Schema::create('kelas_list', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // e.g. 3A, AP-1, BM-1
                $table->string('jurusan')->default('Administrasi Perkantoran');
                $table->integer('angkatan')->default(29);
                $table->timestamps();
            });

            // Seed initial classes for Angkatan 29
            $initialClasses = [
                ['name' => '3A', 'jurusan' => 'Administrasi Perkantoran', 'angkatan' => 29],
                ['name' => '3B', 'jurusan' => 'Administrasi Perkantoran', 'angkatan' => 29],
                ['name' => '3C', 'jurusan' => 'Administrasi Perkantoran', 'angkatan' => 29],
                ['name' => '3D', 'jurusan' => 'Administrasi Perkantoran', 'angkatan' => 29],
                ['name' => '3E', 'jurusan' => 'Administrasi Perkantoran', 'angkatan' => 29],
                ['name' => '3F', 'jurusan' => 'Administrasi Perkantoran', 'angkatan' => 29],
                ['name' => '3G', 'jurusan' => 'Bisnis Manajemen', 'angkatan' => 29],
                ['name' => '3H', 'jurusan' => 'Bisnis Manajemen', 'angkatan' => 29],
            ];

            foreach ($initialClasses as $c) {
                DB::table('kelas_list')->insert(array_merge($c, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_list');
        Schema::dropIfExists('angkatans');
    }
};
