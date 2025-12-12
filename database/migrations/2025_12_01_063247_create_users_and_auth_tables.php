<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Password Resets
        Schema::create('tb_password_resets', function (Blueprint $table) {
            $table->string('email', 255)->primary();
            $table->string('token', 255);
            $table->dateTime('created_at')->useCurrent();
        });

        // 2. Tabel Users
        Schema::create('tb_users', function (Blueprint $table) {
            $table->bigIncrements('id_user');
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('nama_lengkap', 255);
            $table->enum('role', ['PENGUSUL', 'VERIFIKATOR', 'DIREKSI', 'SUPER ADMIN']);
            $table->boolean('is_active')->default(true);
            $table->char('id_direktorat', 5)->nullable();
            
            // Langsung tambahkan remember_token di sini
            $table->rememberToken(); 

            // Foreign Key
            $table->foreign('id_direktorat')
                  ->references('id_direktorat')->on('tb_direktorat')
                  ->onUpdate('cascade')->onDelete('set null');
        });

        // 3. Tabel Unit User (Bridge)
        Schema::create('tb_unit_user', function (Blueprint $table) {
            $table->integer('id_unit_user')->autoIncrement();
            $table->unsignedBigInteger('id_user');
            $table->char('id_unit', 10);

            $table->foreign('id_user')->references('id_user')->on('tb_users')->onDelete('cascade');
            $table->foreign('id_unit')->references('id_unit')->on('tb_unit_kerja')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_unit_user');
        Schema::dropIfExists('tb_users');
        Schema::dropIfExists('tb_password_resets');
    }
};