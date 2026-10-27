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
        Schema::create('rekap_suara_2024', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_partai')->constrained('master_partai')->cascadeOnDelete();
            $table->integer('total_suara');
            $table->decimal('persentase_suara', 8, 2);
            $table->integer('peringkat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_suara_2024');
    }
};
