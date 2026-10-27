<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilCluster2019 extends Model
{
    use HasFactory;

    protected $table = 'hasil_cluster_2019';

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
        return $this->belongsTo(RekapSuara2019::class, 'id_partai', 'id_partai');
    }
}
