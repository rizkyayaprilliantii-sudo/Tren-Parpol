<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnalisisClustering extends Model
{
    use HasFactory;

    protected $table = 'analisis_clustering';

    protected $fillable = [
        'tahun_awal',
        'tahun_akhir',
        'jumlah_cluster',
        'metode_scaling',
        'jumlah_observasi',
        'silhouette_score',
        'status',
    ];

    protected $casts = [
        'tahun_awal' => 'integer',
        'tahun_akhir' => 'integer',
        'jumlah_cluster' => 'integer',
        'jumlah_observasi' => 'integer',
        'silhouette_score' => 'float',
    ];

    /**
     * Relasi ke hasil clustering.
     */
    public function hasilClustering(): HasMany
    {
        return $this->hasMany(
            HasilClustering::class,
            'analisis_id'
        );
    }
}
