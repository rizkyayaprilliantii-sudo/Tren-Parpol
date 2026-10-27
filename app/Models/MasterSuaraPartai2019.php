<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSuaraPartai2019 extends Model
{
    use HasFactory;

    protected $table = 'master_suara_partai_2019';
    protected $fillable = ['id_kecamatan', 'id_partai', 'jumlah_suara'];


    public function kecamatan()
    {
        return $this->belongsTo(MasterKecamatan::class, 'id_kecamatan');
    }

    public function partai()
    {
        return $this->belongsTo(MasterPartai::class, 'id_partai');
    }
}
