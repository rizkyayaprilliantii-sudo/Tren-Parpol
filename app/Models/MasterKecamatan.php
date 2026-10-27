<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKecamatan extends Model
{
    use HasFactory;
    protected $table = 'master_kecamatan';
    protected $fillable = ['nama_kecamatan'];

    public function kelurahan()
    {
        return $this->hasMany(MasterKelurahan::class, 'id_kecamatan');
    }
}
