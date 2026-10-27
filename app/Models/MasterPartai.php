<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPartai extends Model
{
    use HasFactory;

    protected $table = 'master_partai';

    protected $fillable = [
        'singkatan_parpol',
        'nama_lengkap_parpol'
    ];


    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function suaraPartai2024()
    {
        return $this->hasMany(
            MasterSuaraPartai2024::class,
            'id_partai'
        );
    }

    public function suaraPartai2019()
    {
        return $this->hasMany(
            MasterSuaraPartai2019::class,
            'id_partai'
        );
    }

    public function rekapSuara2024()
    {
        return $this->hasMany(
            RekapSuara2024::class,
            'id_partai'
        );
    }

    public function rekapSuara2019()
    {
        return $this->hasMany(
            RekapSuara2019::class,
            'id_partai'
        );
    }

    public function hasilCluster2024()
    {
        return $this->hasMany(
            HasilCluster2024::class,
            'id_partai'
        );
    }

    public function hasilCluster2019()
    {
        return $this->hasMany(
            HasilCluster2019::class,
            'id_partai'
        );
    }

    public function hasilPerbandingan()
    {
        return $this->hasMany(
            HasilPerbandingan::class,
            'id_partai'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getNamaAttribute()
    {
        return $this->nama_lengkap_parpol;
    }

    public function getSingkatanAttribute()
    {
        return $this->singkatan_parpol;
    }


    /*
    |--------------------------------------------------------------------------
    | LOGO PARTAI
    |--------------------------------------------------------------------------
    */

    public function getLogoUrlAttribute()
    {
        $extensions = [
            'png',
            'jpg',
            'jpeg',
            'webp',
            'avif'
        ];

        foreach ($extensions as $ext) {

            $filename = $this->singkatan_parpol . '.' . $ext;

            /*
             * Folder logo sesuai dengan struktur project:
             *
             * public/images/logo/
             */

            $path = 'images/logo/' . $filename;

            $fullPath = public_path($path);

            if (file_exists($fullPath)) {
                return asset($path);
            }
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | NOMOR URUT
    |--------------------------------------------------------------------------
    */

    public function getNomorUrutAttribute()
    {
        return $this->id;
    }
}
