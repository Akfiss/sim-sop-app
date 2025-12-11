<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_dokumen_sop', function (Blueprint $table) {
            // Drop unique index logic
            // Nama index biasanya: tb_dokumen_sop_nomor_sk_unique
            $table->dropUnique(['nomor_sk']);
        });
    }

    public function down(): void
    {
        Schema::table('tb_dokumen_sop', function (Blueprint $table) {
             $table->unique('nomor_sk');
        });
    }
};
