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
        Schema::create('hasil_perbandingan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_partai')->constrained('master_partai')->cascadeOnDelete();
            $table->integer('suara_2019');
            $table->integer('suara_2024');
            $table->integer('selisih');
            $table->decimal('persentase_perubahan', 8, 2);
            $table->string('status_tren', 20); 
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_perbandingan');
    }
};
