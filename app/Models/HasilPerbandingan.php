<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilPerbandingan extends Model
{
    use HasFactory;
    protected $table = 'hasil_perbandingan';
    protected $fillable = ['id_partai', 'suara_2019', 'suara_2024', 'selisih', 'persentase_perubahan', 'status_tren'];

    public function partai()
    {
        return $this->belongsTo(MasterPartai::class, 'id_partai');
    }
}
