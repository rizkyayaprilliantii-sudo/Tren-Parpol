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
        Schema::create('hasil_clustering', function (Blueprint $table) {
            $table->id();

            $table->foreignId('analisis_id')
                ->constrained('analisis_clustering')
                ->cascadeOnDelete();

            $table->foreignId('id_kecamatan')
                ->constrained('master_kecamatan')
                ->cascadeOnDelete();

            $table->foreignId('id_partai')
                ->constrained('master_partai')
                ->cascadeOnDelete();

            $table->decimal('share_2019', 10, 4);

            $table->decimal('share_2024', 10, 4);

            $table->decimal('selisih_share', 10, 4);

            $table->unsignedTinyInteger('cluster');

            $table->string('kategori_cluster', 20);

            $table->decimal('jarak_centroid', 12, 6)
                ->nullable();

            $table->string('status_tren', 20);

            $table->timestamps();

            $table->unique(
                ['analisis_id', 'id_kecamatan', 'id_partai'],
                'hasil_clustering_analisis_kecamatan_partai_unique'
            );

            $table->index(['analisis_id', 'cluster']);

            $table->index(['id_kecamatan', 'id_partai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_clustering');
    }
};
