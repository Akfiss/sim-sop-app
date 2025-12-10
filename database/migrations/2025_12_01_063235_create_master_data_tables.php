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
        // 1. Tabel Direktorat
        Schema::create('tb_direktorat', function (Blueprint $table) {
            $table->char('id_direktorat', 10)->primary();
            $table->string('nama_direktorat', 100);
        });

        // 2. Tabel Unit Kerja
        Schema::create('tb_unit_kerja', function (Blueprint $table) {
            $table->char('id_unit', 10)->primary();
            $table->string('nama_unit', 50);
            $table->char('id_direktorat', 10);

            // Foreign Key
            $table->foreign('id_direktorat')
                  ->references('id_direktorat')->on('tb_direktorat')
                  ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_unit_kerja');
        Schema::dropIfExists('tb_direktorat');
    }
};
