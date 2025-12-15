<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Dokumen SOP
        Schema::create('tb_dokumen_sop', function (Blueprint $table) {
            $table->char('id_sop', 10)->primary();
            
            // Nomor SK tidak unique karena mungkin draft awal belum ada nomor
            $table->string('nomor_sk', 50)->nullable(); 
            
            $table->string('judul_sop', 255);
            $table->enum('kategori_sop', ['SOP', 'SOP_AP'])->default('SOP');
            
            // Kolom ini tetap dipertahankan
            $table->boolean('is_all_units')->default(false); 
            $table->string('file_path', 255)->nullable();

            $table->date('tgl_pengesahan')->nullable();
            $table->date('tgl_berlaku')->nullable();
            $table->date('tgl_review_berikutnya')->nullable();
            $table->date('tgl_kadaluarsa')->nullable();

            // --- PERUBAHAN UTAMA DI SINI ---
            // Status hanya AKTIF dan KADALUARSA
            // Default langsung AKTIF saat diupload
            $table->enum('status', ['AKTIF', 'KADALUARSA'])->default('AKTIF');

            // Foreign Keys
            $table->char('id_unit_pemilik', 10); // Pastikan panjangnya sama dengan tb_unit_kerja (10 char)
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Constraints
            $table->foreign('id_unit_pemilik')->references('id_unit')->on('tb_unit_kerja');
            $table->foreign('created_by')->references('id_user')->on('tb_users');
            $table->foreign('updated_by')->references('id_user')->on('tb_users');
            $table->foreign('deleted_by')->references('id_user')->on('tb_users');
        });

        // 2. Tabel SOP Unit Terkait (Tetap Sama)
        Schema::create('tb_sop_unit_terkait', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->char('id_sop', 10);
            $table->char('id_unit', 10);

            $table->foreign('id_sop')->references('id_sop')->on('tb_dokumen_sop')->onDelete('cascade');
            $table->foreign('id_unit')->references('id_unit')->on('tb_unit_kerja')->onDelete('cascade');
        });

        // 3. Tabel Riwayat SOP (Sesuaikan Enum)
        Schema::create('tb_riwayat_sop', function (Blueprint $table) {
            $table->integer('id_riwayat')->autoIncrement();
            $table->text('catatan')->nullable();
            
            // Enum status di riwayat juga disesuaikan
            $table->enum('status_sop', ['AKTIF', 'KADALUARSA']);
            
            $table->string('dokumen_path', 255)->nullable();
            $table->timestamps(); 

            $table->unsignedBigInteger('id_user');
            $table->char('id_sop', 10);

            $table->foreign('id_user')->references('id_user')->on('tb_users');
            $table->foreign('id_sop')->references('id_sop')->on('tb_dokumen_sop')->onDelete('cascade');
        });

        // 4. Tabel Notifikasi (Tetap Sama)
        Schema::create('tb_notifikasi', function (Blueprint $table) {
            $table->integer('id_notifikasi')->autoIncrement();
            $table->string('judul', 100);
            $table->string('pesan', 255)->nullable();
            $table->boolean('is_read')->default(false);
            $table->json('data')->nullable(); 
            $table->dateTime('created_at')->useCurrent();
            $table->unsignedBigInteger('id_user');
            $table->char('id_sop', 10)->nullable();

            $table->foreign('id_user')->references('id_user')->on('tb_users')->onDelete('cascade');
            $table->foreign('id_sop')->references('id_sop')->on('tb_dokumen_sop')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_notifikasi');
        Schema::dropIfExists('tb_riwayat_sop');
        Schema::dropIfExists('tb_sop_unit_terkait');
        Schema::dropIfExists('tb_dokumen_sop');
    }
};