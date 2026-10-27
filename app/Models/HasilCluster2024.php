<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilCluster2024 extends Model
{
    use HasFactory;

    protected $table = 'hasil_cluster_2024';

    protected $fillable = [
        'id_partai',
        'total_suara',
        'cluster',
        'kategori_cluster',
        'jarak_centroid'
    ];

    public function partai()
    {
        return $this->belongsTo(MasterPartai::class, 'id_partai', 'id');
    }

    public function rekapSuara()
    {
        return $this->belongsTo(RekapSuara2024::class, 'id_partai', 'id_partai');
    }
}
