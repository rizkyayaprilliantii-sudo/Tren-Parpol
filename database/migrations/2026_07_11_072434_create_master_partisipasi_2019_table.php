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
        Schema::create('master_partisipasi_2019', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_kecamatan')->constrained('master_kecamatan')->cascadeOnDelete();
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
        Schema::dropIfExists('master_partisipasi_2019');
    }
};
