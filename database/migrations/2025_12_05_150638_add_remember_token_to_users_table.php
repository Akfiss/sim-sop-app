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
        Schema::table('tb_users', function (Blueprint $table) {
        // Tambahkan kolom remember_token (varchar 100 nullable)
        $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_users', function (Blueprint $table) {
        $table->dropRememberToken();
        });
    }
};
