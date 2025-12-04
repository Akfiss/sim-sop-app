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
        Schema::table('tb_dokumen_sop', function (Blueprint $table) {
            $table->boolean('is_all_units')->default(false)->after('kategori_sop');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tb_dokumen_sop', function (Blueprint $table) {
            $table->dropColumn('is_all_units');
        });
    }
};
