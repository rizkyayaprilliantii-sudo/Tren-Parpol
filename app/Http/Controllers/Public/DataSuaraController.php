<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\RekapSuara2019;
use App\Models\RekapSuara2024;
use App\Models\MasterPartai;

class DataSuaraController extends Controller
{
    public function index($tahun = 2024)
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil tahun dari query string jika tersedia
        |--------------------------------------------------------------------------
        | Contoh:
        | /data-suara?tahun=2019
        | /data-suara?tahun=2024
        */

        $tahun = (int) request()->query('tahun', $tahun);

        /*
        |--------------------------------------------------------------------------
        | Validasi tahun
        |--------------------------------------------------------------------------
        */

        if (!in_array($tahun, [2019, 2024], true)) {
            $tahun = 2024;
        }


        /*
        |--------------------------------------------------------------------------
        | Data 2019
        |--------------------------------------------------------------------------
        */

        if ($tahun === 2019) {

            $data = RekapSuara2019::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();

            $totalSuara = RekapSuara2019::sum('total_suara');

            $jumlahPartai = RekapSuara2019::count();

            $sumberData = 'KPU RI (Rekapitulasi Kecamatan)';
        }


        /*
        |--------------------------------------------------------------------------
        | Data 2024
        |--------------------------------------------------------------------------
        */ else {

            $data = RekapSuara2024::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();

            $totalSuara = RekapSuara2024::sum('total_suara');

            $jumlahPartai = RekapSuara2024::count();

            $sumberData = 'KPU RI (Rekapitulasi TPS)';
        }


        /*
        |--------------------------------------------------------------------------
        | Tentukan peringkat
        |--------------------------------------------------------------------------
        */

        $data->each(function ($item, $index) {
            $item->peringkat = $index + 1;
        });


        /*
        |--------------------------------------------------------------------------
        | Ambil master partai
        |--------------------------------------------------------------------------
        */

        $partai = MasterPartai::orderBy('id')->get();


        /*
        |--------------------------------------------------------------------------
        | Tampilkan halaman
        |--------------------------------------------------------------------------
        */

        return view('data-suara.index', compact(
            'data',
            'tahun',
            'partai',
            'totalSuara',
            'jumlahPartai',
            'sumberData'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | Fragment
    |--------------------------------------------------------------------------
    |
    | Method ini tetap disimpan kalau nanti dibutuhkan untuk AJAX atau
    | kebutuhan lain. Untuk halaman utama sekarang tidak digunakan.
    |
    */

    public function fragment($tahun)
    {
        $tahun = (int) $tahun;

        if (!in_array($tahun, [2019, 2024], true)) {
            $tahun = 2024;
        }

        if ($tahun === 2019) {

            $data = RekapSuara2019::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();

            $totalSuara = RekapSuara2019::sum('total_suara');

            $jumlahPartai = RekapSuara2019::count();

            $sumberData = 'KPU RI (Rekapitulasi Kecamatan)';
        } else {

            $data = RekapSuara2024::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();

            $totalSuara = RekapSuara2024::sum('total_suara');

            $jumlahPartai = RekapSuara2024::count();

            $sumberData = 'KPU RI (Rekapitulasi TPS)';
        }

        $data->each(function ($item, $index) {
            $item->peringkat = $index + 1;
        });

        return view('data-suara._tabel', compact(
            'data',
            'tahun',
            'totalSuara',
            'jumlahPartai',
            'sumberData'
        ));
    }
}
