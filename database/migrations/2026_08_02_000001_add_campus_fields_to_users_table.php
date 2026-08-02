<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim')->nullable()->after('email');
            $table->integer('semester')->default(1)->after('jurusan');
            $table->string('phone')->nullable()->after('semester');
            $table->text('address')->nullable()->after('phone');
            $table->string('avatar')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nim', 'semester', 'phone', 'address', 'avatar']);
        });
    }
};
