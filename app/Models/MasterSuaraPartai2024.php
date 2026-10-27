<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSuaraPartai2024 extends Model
{
    use HasFactory;

    protected $table = 'master_suara_partai_2024';
    protected $fillable = ['id_kecamatan', 'id_kelurahan', 'id_tps', 'id_partai', 'jumlah_suara'];

    public function tps()
    {
        return $this->belongsTo(MasterTps::class, 'id_tps');
    }

    public function partai()
    {
        return $this->belongsTo(MasterPartai::class, 'id_partai');
    }

    public function kelurahan()
    {
        return $this->belongsTo(MasterKelurahan::class, 'id_kelurahan');
    }

    public function kecamatan()
    {
        return $this->belongsTo(MasterKecamatan::class, 'id_kecamatan');
    }

    public function getPersenAttribute()
    {
        $totalPerTps = $this->where('id_tps', $this->id_tps)->sum('jumlah_suara');
        return $totalPerTps > 0 ? ($this->jumlah_suara / $totalPerTps) * 100 : 0;
    }
}
