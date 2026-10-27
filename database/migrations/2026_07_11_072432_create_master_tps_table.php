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
        Schema::create('master_tps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kecamatan')->constrained('master_kecamatan')->cascadeOnDelete(); // ✅ TAMBAHKAN INI
            $table->foreignId('id_kelurahan')->constrained('master_kelurahan')->cascadeOnDelete();
            $table->string('nomor_tps', 20);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_tps');
    }
};
