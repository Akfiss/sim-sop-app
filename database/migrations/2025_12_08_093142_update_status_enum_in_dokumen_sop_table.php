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
        // Menggunakan raw statement karena mengubah enum di Laravel kadang tricky
        // Pastikan urutan enum sesuai dengan yang Anda butuhkan
        DB::statement("ALTER TABLE tb_dokumen_sop MODIFY COLUMN status ENUM('DRAFT', 'DALAM REVIEW', 'REVISI', 'AKTIF', 'KADALUARSA') NOT NULL DEFAULT 'DRAFT'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_dokumen_sop', function (Blueprint $table) {
            //
        });
    }
};
