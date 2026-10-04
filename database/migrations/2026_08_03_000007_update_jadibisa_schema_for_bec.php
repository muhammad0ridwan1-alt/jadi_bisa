<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update users table for kelas & cawu
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'kelas')) {
                $table->string('kelas')->nullable()->after('jurusan');
            }
            if (!Schema::hasColumn('users', 'cawu')) {
                $table->integer('cawu')->default(3)->after('kelas');
            }
        });

        // 2. Adjust mata_kuliahs table
        Schema::table('mata_kuliahs', function (Blueprint $table) {
            if (!Schema::hasColumn('mata_kuliahs', 'cawu')) {
                $table->integer('cawu')->default(3)->after('jurusan');
            }
        });

        // 3. Update jadwals table
        Schema::table('jadwals', function (Blueprint $table) {
            if (!Schema::hasColumn('jadwals', 'kelas')) {
                $table->string('kelas')->nullable()->after('mata_kuliah_id');
            }
            if (!Schema::hasColumn('jadwals', 'dosen_id')) {
                $table->foreignId('dosen_id')->nullable()->after('kelas')->constrained('users')->onDelete('set null');
            }
        });

        // 4. Create bst_scores table for BST Typing Leaderboard
        if (!Schema::hasTable('bst_scores')) {
            Schema::create('bst_scores', function (Blueprint $table) {
                $table->id();
                $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
                $table->integer('cpm');
                $table->integer('wpm');
                $table->float('accuracy');
                $table->integer('cawu')->default(3);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bst_scores');
        
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropForeign(['dosen_id']);
            $table->dropColumn(['kelas', 'dosen_id']);
        });

        Schema::table('mata_kuliahs', function (Blueprint $table) {
            $table->dropColumn('cawu');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['kelas', 'cawu']);
        });
    }
};
