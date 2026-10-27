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
        Schema::create('analisis_clustering', function (Blueprint $table) {
            $table->id();

            $table->unsignedSmallInteger('tahun_awal');
            $table->unsignedSmallInteger('tahun_akhir');

            $table->unsignedTinyInteger('jumlah_cluster');

            $table->string('metode_scaling', 50)
                ->default('Tanpa Scaling');

            $table->unsignedInteger('jumlah_observasi');

            $table->decimal('silhouette_score', 8, 4)
                ->nullable();

            $table->string('status', 20)
                ->default('selesai');

            $table->timestamps();

            $table->index(['tahun_awal', 'tahun_akhir']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analisis_clustering');
    }
};
