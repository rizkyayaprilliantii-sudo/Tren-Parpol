<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\MasterPartai;
use App\Models\MasterSuaraPartai2019;
use App\Models\MasterSuaraPartai2024;

class PerbandinganController extends Controller
{
    public function index()
    {
        $rekap2019 = MasterSuaraPartai2019::query()
            ->select('id_partai')
            ->selectRaw('SUM(jumlah_suara) AS total_2019')
            ->groupBy('id_partai')
            ->get()
            ->keyBy('id_partai');

        $rekap2024 = MasterSuaraPartai2024::query()
            ->select('id_partai')
            ->selectRaw('SUM(jumlah_suara) AS total_2024')
            ->groupBy('id_partai')
            ->get()
            ->keyBy('id_partai');

        /*
        |--------------------------------------------------------------------------
        | TREN PEROLEHAN SUARA SELURUH KOTA
        |--------------------------------------------------------------------------
        | Total kota menggunakan seluruh data pada masing-masing tahun.
        | Perhitungan ini terpisah dari perbandingan partai yang tersedia
        | pada kedua periode.
        */

        $totalSuara2019 = (int) MasterSuaraPartai2019::query()
            ->sum('jumlah_suara');

        $totalSuara2024 = (int) MasterSuaraPartai2024::query()
            ->sum('jumlah_suara');

        $selisihTotalSuara = $totalSuara2024 - $totalSuara2019;

        $persentasePerubahanTotal = $totalSuara2019 > 0
            ? round(
                ($selisihTotalSuara / $totalSuara2019) * 100,
                2
            )
            : null;

        $statusPerubahanTotal = match (true) {
            $selisihTotalSuara > 0 => 'Naik',
            $selisihTotalSuara < 0 => 'Turun',
            default => 'Tetap',
        };

        /*
        |--------------------------------------------------------------------------
        | JUMLAH PARTAI YANG MEMILIKI DATA PADA SETIAP PERIODE
        |--------------------------------------------------------------------------
        */

        $jumlahPartai2019 = $rekap2019->count();
        $jumlahPartai2024 = $rekap2024->count();

        /*
        |--------------------------------------------------------------------------
        | PERBANDINGAN PEROLEHAN SUARA PER PARTAI
        |--------------------------------------------------------------------------
        */

        $partaiIds = $rekap2019->keys()
            ->merge($rekap2024->keys())
            ->unique()
            ->values();

        $masterPartai = MasterPartai::query()
            ->whereIn('id', $partaiIds)
            ->get()
            ->keyBy('id');

        $perPartai = $partaiIds->map(function ($idPartai) use (
            $rekap2019,
            $rekap2024,
            $masterPartai
        ) {
            $ada2019 = $rekap2019->has($idPartai);
            $ada2024 = $rekap2024->has($idPartai);

            $suara2019 = $ada2019
                ? (int) $rekap2019->get($idPartai)->total_2019
                : null;

            $suara2024 = $ada2024
                ? (int) $rekap2024->get($idPartai)->total_2024
                : null;

            $dapatDibandingkan = $ada2019 && $ada2024;

            $selisih = $dapatDibandingkan
                ? $suara2024 - $suara2019
                : null;

            $persentase = $dapatDibandingkan && $suara2019 > 0
                ? round(($selisih / $suara2019) * 100, 2)
                : null;

            if (!$ada2019 && $ada2024) {
                $status = 'Baru 2024';
            } elseif ($ada2019 && !$ada2024) {
                $status = 'Tidak mengikuti 2024';
            } elseif ($selisih > 0) {
                $status = 'Naik';
            } elseif ($selisih < 0) {
                $status = 'Turun';
            } else {
                $status = 'Tetap';
            }

            return (object) [
                'partai' => $masterPartai->get($idPartai),
                'total_2019' => $suara2019,
                'total_2024' => $suara2024,
                'total_selisih' => $selisih,
                'persentase_perubahan' => $persentase,
                'dapat_dibandingkan' => $dapatDibandingkan,
                'status_tren' => $status,
            ];
        });

        $dataPembanding = $perPartai
            ->where('dapat_dibandingkan', true)
            ->values();

        $naikTertinggi = $dataPembanding
            ->where('total_selisih', '>', 0)
            ->sortByDesc('total_selisih')
            ->first();

        $turunTerbesar = $dataPembanding
            ->where('total_selisih', '<', 0)
            ->sortBy('total_selisih')
            ->first();

        $partaiBaru = $perPartai
            ->where('status_tren', 'Baru 2024')
            ->values();

        $jumlahNaik = $dataPembanding
            ->where('status_tren', 'Naik')
            ->count();

        $jumlahTurun = $dataPembanding
            ->where('status_tren', 'Turun')
            ->count();

        $jumlahTetap = $dataPembanding
            ->where('status_tren', 'Tetap')
            ->count();

        $perPartai = $perPartai
            ->sortByDesc(fn($item) => $item->total_2024 ?? -1)
            ->values();

        return view('perbandingan.index', compact(
            'perPartai',
            'naikTertinggi',
            'turunTerbesar',
            'partaiBaru',
            'jumlahNaik',
            'jumlahTurun',
            'jumlahTetap',
            'totalSuara2019',
            'totalSuara2024',
            'selisihTotalSuara',
            'persentasePerubahanTotal',
            'statusPerubahanTotal',
            'jumlahPartai2019',
            'jumlahPartai2024'
        ));
    }
}
