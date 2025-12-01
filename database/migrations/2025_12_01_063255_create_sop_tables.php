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
       // 1. Tabel Dokumen SOP
        Schema::create('tb_dokumen_sop', function (Blueprint $table) {
            $table->char('id_sop', 10)->primary();
            $table->string('nomor_sk', 50)->nullable()->unique(); // Nullable jika belum ada SK
            $table->string('judul_sop', 255);
            $table->enum('kategori_sop', ['SOP', 'SOP_AP'])->default('SOP');
            $table->string('file_path', 255)->nullable();

            $table->date('tgl_pengesahan')->nullable();
            $table->date('tgl_berlaku')->nullable();
            $table->date('tgl_review_berikutnya')->nullable();
            $table->date('tgl_kadaluarsa')->nullable();

            $table->enum('status', ['DALAM REVIEW', 'REVISI', 'AKTIF', 'KADALUARSA'])->default('DALAM REVIEW');

            // Foreign Keys Columns
            $table->char('id_unit_pemilik', 5);
            $table->char('created_by', 5);
            $table->char('updated_by', 5)->nullable();
            $table->char('deleted_by', 5)->nullable();

            // Timestamps & Soft Deletes
            $table->timestamps(); // create_at & updated_at
            $table->softDeletes(); // deleted_at

            // Constraints
            $table->foreign('id_unit_pemilik')->references('id_unit')->on('tb_unit_kerja');
            $table->foreign('created_by')->references('id_user')->on('tb_users');
            $table->foreign('updated_by')->references('id_user')->on('tb_users');
            $table->foreign('deleted_by')->references('id_user')->on('tb_users');
        });

        // 2. Tabel SOP Unit Terkait (Bridge)
        Schema::create('tb_sop_unit_terkait', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->char('id_sop', 10);
            $table->char('id_unit', 5);

            $table->foreign('id_sop')->references('id_sop')->on('tb_dokumen_sop')->onDelete('cascade');
            $table->foreign('id_unit')->references('id_unit')->on('tb_unit_kerja')->onDelete('cascade');
        });

        // 3. Tabel Riwayat SOP
        Schema::create('tb_riwayat_sop', function (Blueprint $table) {
            $table->integer('id_riwayat')->autoIncrement();
            $table->text('catatan')->nullable();
            $table->enum('aksi', ['PENGAJUAN', 'DISETUJUI', 'REVISI', 'UPLOAD_ULANG']); // Sesuaikan enum diagram vs sql
            $table->string('dokumen_path', 255)->nullable();
            $table->dateTime('created_at')->useCurrent();

            $table->char('id_user', 5);
            $table->char('id_sop', 10);

            $table->foreign('id_user')->references('id_user')->on('tb_users');
            $table->foreign('id_sop')->references('id_sop')->on('tb_dokumen_sop')->onDelete('cascade');
        });

        // 4. Tabel Notifikasi
        Schema::create('tb_notifikasi', function (Blueprint $table) {
            $table->integer('id_notifikasi')->autoIncrement();
            $table->string('judul', 100);
            $table->string('pesan', 255)->nullable();
            $table->boolean('is_read')->default(false);
            $table->dateTime('created_at')->useCurrent();

            $table->char('id_user', 5);
            $table->char('id_sop', 10)->nullable();

            $table->foreign('id_user')->references('id_user')->on('tb_users')->onDelete('cascade');
            $table->foreign('id_sop')->references('id_sop')->on('tb_dokumen_sop')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_notifikasi');
        Schema::dropIfExists('tb_riwayat_sop');
        Schema::dropIfExists('tb_sop_unit_terkait');
        Schema::dropIfExists('tb_dokumen_sop');
    }
};
