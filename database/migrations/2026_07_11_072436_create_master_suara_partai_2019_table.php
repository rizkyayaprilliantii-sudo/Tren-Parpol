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
        Schema::create('master_suara_partai_2019', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kecamatan')->constrained('master_kecamatan')->cascadeOnDelete();
            $table->foreignId('id_partai')->constrained('master_partai')->cascadeOnDelete();
            $table->integer('jumlah_suara');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_suara_partai_2019');
    }
};
