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
        Schema::create('hasil_cluster_2019', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_partai')->constrained('master_partai')->cascadeOnDelete();
            $table->integer('total_suara');
            $table->integer('cluster'); 
            $table->string('kategori_cluster', 20); // 
            $table->decimal('nilai_centroid', 12, 2)->nullable();
            $table->decimal('jarak_centroid', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_cluster_2019');
    }
};
