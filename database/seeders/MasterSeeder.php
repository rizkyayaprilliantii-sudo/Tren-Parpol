<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterPemilu;
use App\Models\MasterKecamatan;
use App\Models\MasterPartai;

class MasterSeeder extends Seeder
{
    public function run(): void
    {
        MasterPemilu::updateOrCreate(
            ['tahun' => 2019],
            ['deskripsi' => 'Pemilu Legislatif 2019']
        );
        MasterPemilu::updateOrCreate(
            ['tahun' => 2024],
            ['deskripsi' => 'Pemilu Legislatif 2024']
        );

        $kecamatanData = [
            'Banjarmasin Barat',
            'Banjarmasin Selatan',
            'Banjarmasin Timur',
            'Banjarmasin Tengah',
            'Banjarmasin Utara',
        ];
        foreach ($kecamatanData as $nama) {
            MasterKecamatan::updateOrCreate(['nama_kecamatan' => $nama]);
        }

        $partaiData = [
            ['singkatan' => 'PKB',          'nama_lengkap_parpol' => 'Partai Kebangkitan Bangsa'],
            ['singkatan' => 'GERINDRA',     'nama_lengkap_parpol' => 'Partai Gerakan Indonesia Raya'],
            ['singkatan' => 'PDIP',         'nama_lengkap_parpol' => 'Partai Demokrasi Indonesia Perjuangan'],
            ['singkatan' => 'GOLKAR',       'nama_lengkap_parpol' => 'Partai Golongan Karya'],
            ['singkatan' => 'NASDEM',       'nama_lengkap_parpol' => 'Partai Nasional Demokrat'],
            ['singkatan' => 'GARUDA',       'nama_lengkap_parpol' => 'Partai Garuda Indonesia'],
            ['singkatan' => 'BERKARYA',     'nama_lengkap_parpol' => 'Partai Berkarya'],
            ['singkatan' => 'PKS',          'nama_lengkap_parpol' => 'Partai Keadilan Sejahtera'],
            ['singkatan' => 'PERINDO',      'nama_lengkap_parpol' => 'Partai Persatuan Indonesia'],
            ['singkatan' => 'PPP',          'nama_lengkap_parpol' => 'Partai Persatuan Pembangunan'],
            ['singkatan' => 'PSI',          'nama_lengkap_parpol' => 'Partai Solidaritas Indonesia'],
            ['singkatan' => 'PAN',          'nama_lengkap_parpol' => 'Partai Amanat Nasional'],
            ['singkatan' => 'HANURA',       'nama_lengkap_parpol' => 'Partai Hati Nurani Rakyat'],
            ['singkatan' => 'DEMOKRAT',     'nama_lengkap_parpol' => 'Partai Demokrat'],
            ['singkatan' => 'PBB',          'nama_lengkap_parpol' => 'Partai Bulan Bintang'],
            ['singkatan' => 'PKPI',         'nama_lengkap_parpol' => 'Partai Keadilan dan Persatuan Indonesia'],
            ['singkatan' => 'PARTAI BURUH', 'nama_lengkap_parpol' => 'Partai Buruh'],
            ['singkatan' => 'GELORA',       'nama_lengkap_parpol' => 'Partai Gelora Indonesia'],
            ['singkatan' => 'PKN',          'nama_lengkap_parpol' => 'Partai Kebangkitan Nusantara'],
            ['singkatan' => 'PARTAI UMMAT', 'nama_lengkap_parpol' => 'Partai Ummat'],
        ];

        foreach ($partaiData as $p) {
            MasterPartai::updateOrCreate(
                ['singkatan' => $p['singkatan']], 
                $p                                
            );
        }
    }
}
