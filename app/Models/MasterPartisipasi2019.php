<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPartisipasi2019 extends Model
{
    use HasFactory;
    protected $table = 'master_partisipasi_2019';
    protected $fillable = ['id_kecamatan', 'dpt_p', 'dpt_l', 'dpt_total', 'hadir', 'suara_sah', 'tidak_sah'];

    public function kecamatan()
    {
        return $this->belongsTo(MasterKecamatan::class, 'id_kecamatan');
    }

    public function getPersenAttribute()
    {
        $totalPerKec = $this->where('id_kecamatan', $this->id_kecamatan)->sum('jumlah_suara');
        return $totalPerKec > 0 ? ($this->jumlah_suara / $totalPerKec) * 100 : 0;
    }
}
