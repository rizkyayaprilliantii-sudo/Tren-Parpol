<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTps extends Model
{
    use HasFactory;

    protected $table = 'master_tps';
    protected $fillable = ['id_kecamatan', 'id_kelurahan', 'nomor_tps'];

    public function kecamatan()
    {
        return $this->belongsTo(MasterKecamatan::class, 'id_kecamatan');
    }

    public function kelurahan()
    {
        return $this->belongsTo(MasterKelurahan::class, 'id_kelurahan');
    }

    public function suaraPartai2024()
    {
        return $this->hasMany(MasterSuaraPartai2024::class, 'id_tps');
    }
}
