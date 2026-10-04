<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('modul_progresses', function (Blueprint $table) {
            if (!Schema::hasColumn('modul_progresses', 'is_completed')) {
                $table->boolean('is_completed')->default(false)->after('modul_id');
            }
            if (!Schema::hasColumn('modul_progresses', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('is_completed');
            }
        });
    }

    public function down(): void
    {
        Schema::table('modul_progresses', function (Blueprint $table) {
            if (Schema::hasColumn('modul_progresses', 'is_completed')) {
                $table->dropColumn('is_completed');
            }
            if (Schema::hasColumn('modul_progresses', 'completed_at')) {
                $table->dropColumn('completed_at');
            }
        });
    }
};
