<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapSuara2024 extends Model
{
    use HasFactory;

    protected $table = 'rekap_suara_2024';
    protected $fillable = ['id_partai', 'total_suara', 'persentase_suara', 'peringkat'];

    public function partai()
    {
        return $this->belongsTo(MasterPartai::class, 'id_partai');
    }
    public function getTotalAttribute()
    {
        return $this->total_suara;
    }
}
