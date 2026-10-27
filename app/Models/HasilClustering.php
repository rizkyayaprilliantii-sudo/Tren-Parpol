<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilClustering extends Model
{
    use HasFactory;

    protected $table = 'hasil_clustering';

    protected $fillable = [
        'analisis_id',
        'id_kecamatan',
        'id_partai',
        'share_2019',
        'share_2024',
        'selisih_share',
        'cluster',
        'kategori_cluster',
        'jarak_centroid',
        'status_tren',
    ];

    protected $casts = [
        'analisis_id' => 'integer',
        'id_kecamatan' => 'integer',
        'id_partai' => 'integer',
        'share_2019' => 'float',
        'share_2024' => 'float',
        'selisih_share' => 'float',
        'cluster' => 'integer',
        'jarak_centroid' => 'float',
    ];

    /**
     * Relasi ke analisis clustering.
     */
    public function analisis(): BelongsTo
    {
        return $this->belongsTo(
            AnalisisClustering::class,
            'analisis_id'
        );
    }

    /**
     * Relasi ke kecamatan.
     */
    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(
            MasterKecamatan::class,
            'id_kecamatan'
        );
    }

    /**
     * Relasi ke partai politik.
     */
    public function partai(): BelongsTo
    {
        return $this->belongsTo(
            MasterPartai::class,
            'id_partai'
        );
    }
}
