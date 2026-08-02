<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modul_progresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('modul_id')->constrained('moduls')->onDelete('cascade');
            $table->boolean('completed')->default(true);
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'modul_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modul_progresses');
    }
};
