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
        Schema::create('master_partisipasi_2024', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kecamatan')->constrained('master_kecamatan')->cascadeOnDelete(); // ✅ TAMBAHKAN INI
            $table->foreignId('id_kelurahan')->constrained('master_kelurahan')->cascadeOnDelete(); // ✅ TAMBAHKAN INI
            $table->foreignId('id_tps')->constrained('master_tps')->cascadeOnDelete();
            $table->integer('dpt_l');
            $table->integer('dpt_p');
            $table->integer('dpt_total');
            $table->integer('hadir');
            $table->integer('suara_sah');
            $table->integer('tidak_sah');
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_partisipasi_2024');
    }
};
