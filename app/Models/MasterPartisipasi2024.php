<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPartisipasi2024 extends Model
{
    use HasFactory;
    protected $table = 'master_partisipasi_2024';
    protected $fillable = ['id_kecamatan', 'id_kelurahan', 'id_tps', 'dpt_p', 'dpt_l', 'dpt_total', 'hadir', 'suara_sah', 'tidak_sah'];

    public function tps()
    {
        return $this->belongsTo(MasterTps::class, 'id_tps');
    }
}
