<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKelurahan extends Model
{
    use HasFactory;
    protected $table = 'master_kelurahan';
    protected $fillable = ['id_kecamatan', 'nama_kelurahan'];

    public function kecamatan()
    {
        return $this->belongsTo(MasterKecamatan::class, 'id_kecamatan');
    }
}
