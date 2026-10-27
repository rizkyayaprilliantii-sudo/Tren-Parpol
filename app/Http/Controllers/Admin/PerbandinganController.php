<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PerbandinganController extends Controller
{
    /**
     * Menampilkan tren perolehan suara partai tingkat
     * Kota Banjarmasin berdasarkan share tahun 2019 dan 2024.
     */
    public function index()
    {
        $hasil = $this->getDataTren();

        return view('admin.perbandingan.index', [
            'data'      => $hasil['data'],
            'stats'     => $hasil['stats'],
            'total2019' => $hasil['total2019'],
            'total2024' => $hasil['total2024'],
        ]);
    }

    /**
     * Ekspor data tren ke Excel.
     */
    public function exportExcel(): StreamedResponse
    {
        $hasil = $this->getDataTren();
        $data = $hasil['data'];

        $filename = 'Tren_Share_Partai_Banjarmasin_2019_2024.xls';

        return response()->streamDownload(function () use ($data, $hasil) {
            echo "\xEF\xBB\xBF";
            echo '<table border="1">';

            echo '<tr><th colspan="9">';
            echo 'TREN PROPORSI SUARA PARTAI POLITIK';
            echo '</th></tr>';

            echo '<tr><th colspan="9">';
            echo 'KOTA BANJARMASIN - PEMILU 2019 DAN 2024';
            echo '</th></tr>';

            echo '<tr>';
            echo '<th>No.</th>';
            echo '<th>Partai Politik</th>';
            echo '<th>Suara 2019</th>';
            echo '<th>Share 2019 (%)</th>';
            echo '<th>Suara 2024</th>';
            echo '<th>Share 2024 (%)</th>';
            echo '<th>Selisih Share (pp)</th>';
            echo '<th>Perubahan Suara (%)</th>';
            echo '<th>Status Tren Share</th>';
            echo '</tr>';

            foreach ($data as $index => $item) {
                $namaPartai = $item->partai->singkatan_parpol
                    ?? $item->singkatan_parpol
                    ?? '-';

                echo '<tr>';
                echo '<td>' . ($index + 1) . '</td>';
                echo '<td>' . e($namaPartai) . '</td>';
                echo '<td>' . $item->suara_2019 . '</td>';
                echo '<td>' . $this->formatAngka($item->share_2019) . '</td>';
                echo '<td>' . $item->suara_2024 . '</td>';
                echo '<td>' . $this->formatAngka($item->share_2024) . '</td>';
                echo '<td>' . $this->formatAngka($item->selisih_share) . '</td>';
                echo '<td>' . $this->formatAngka($item->persentase_perubahan_suara) . '</td>';
                echo '<td>' . e($item->status_tren) . '</td>';
                echo '</tr>';
            }

            echo '<tr>';
            echo '<th colspan="2">Total suara seluruh partai</th>';
            echo '<th>' . $hasil['total2019'] . '</th>';
            echo '<th></th>';
            echo '<th>' . $hasil['total2024'] . '</th>';
            echo '<th></th>';
            echo '<th colspan="3"></th>';
            echo '</tr>';

            echo '</table>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    /**
     * Ekspor laporan tren ke PDF.
     */
    public function exportPdf()
    {
        $hasil = $this->getDataTren();

        $pdf = Pdf::loadView('admin.perbandingan.pdf', [
            'data'      => $hasil['data'],
            'stats'     => $hasil['stats'],
            'total2019' => $hasil['total2019'],
            'total2024' => $hasil['total2024'],
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'Tren_Share_Partai_Banjarmasin_2019_2024.pdf'
        );
    }

    /**
     * Mengambil data suara dan menghitung tren share tingkat kota.
     */
    private function getDataTren(): array
    {
        $tabel2019 = 'master_suara_partai_2019';
        $tabel2024 = 'master_suara_partai_2024';

        /*
         * Agregasi suara setiap partai.
         * Data TPS tahun 2024 dijumlahkan menjadi total tingkat kota.
         */
        $suara2019 = DB::table($tabel2019)
            ->select(
                'id_partai',
                DB::raw('SUM(jumlah_suara) AS suara_2019')
            )
            ->groupBy('id_partai');

        $suara2024 = DB::table($tabel2024)
            ->select(
                'id_partai',
                DB::raw('SUM(jumlah_suara) AS suara_2024')
            )
            ->groupBy('id_partai');

        /*
         * Penyebut menggunakan total suara seluruh partai
         * pada masing-masing tahun.
         */
        $total2019 = (int) DB::table($tabel2019)
            ->sum('jumlah_suara');

        $total2024 = (int) DB::table($tabel2024)
            ->sum('jumlah_suara');

        /*
         * Ambil seluruh partai dari tabel master.
         */
        $data = DB::table('master_partai')
            ->leftJoinSub(
                $suara2019,
                'rekap_2019',
                function ($join) {
                    $join->on(
                        'master_partai.id',
                        '=',
                        'rekap_2019.id_partai'
                    );
                }
            )
            ->leftJoinSub(
                $suara2024,
                'rekap_2024',
                function ($join) {
                    $join->on(
                        'master_partai.id',
                        '=',
                        'rekap_2024.id_partai'
                    );
                }
            )
            ->select(
                'master_partai.id as id_partai',
                'master_partai.singkatan_parpol',
                'master_partai.nama_lengkap_parpol',
                DB::raw(
                    'COALESCE(rekap_2019.suara_2019, 0) AS suara_2019'
                ),
                DB::raw(
                    'COALESCE(rekap_2024.suara_2024, 0) AS suara_2024'
                )
            )
            ->orderBy('master_partai.singkatan_parpol')
            ->get();

        /*
         * Hitung share, selisih, dan status tren.
         */
        $data = $data->map(function ($item) use (
            $total2019,
            $total2024
        ) {
            $item->suara_2019 = (int) $item->suara_2019;
            $item->suara_2024 = (int) $item->suara_2024;

            $item->share_2019 = $total2019 > 0
                ? ($item->suara_2019 / $total2019) * 100
                : null;

            $item->share_2024 = $total2024 > 0
                ? ($item->suara_2024 / $total2024) * 100
                : null;

            $item->selisih_suara =
                $item->suara_2024 - $item->suara_2019;

            $item->persentase_perubahan_suara =
                $item->suara_2019 > 0
                    ? ($item->selisih_suara / $item->suara_2019) * 100
                    : null;

            /*
             * Partai hanya dapat dibandingkan apabila memiliki
             * data suara positif pada kedua periode.
             */
            $item->dapat_dibandingkan =
                $item->suara_2019 > 0
                && $item->suara_2024 > 0
                && $item->share_2019 !== null
                && $item->share_2024 !== null;

            $item->selisih_share = $item->dapat_dibandingkan
                ? $item->share_2024 - $item->share_2019
                : null;

            if (!$item->dapat_dibandingkan) {
                $item->status_tren = 'Tidak dibandingkan';
                $item->status_tren_share = 'TIDAK DIBANDINGKAN';
            } elseif ($item->selisih_share > 0.0000001) {
                $item->status_tren = 'Naik';
                $item->status_tren_share = 'NAIK';
            } elseif ($item->selisih_share < -0.0000001) {
                $item->status_tren = 'Turun';
                $item->status_tren_share = 'TURUN';
            } else {
                $item->status_tren = 'Tetap';
                $item->status_tren_share = 'TETAP';
            }

            /*
             * Bentuk objek partai agar sesuai dengan Blade
             * yang membaca $item->partai->singkatan_parpol,
             * $item->partai->nama_lengkap_parpol, dan logo_url.
             */
            $singkatan = trim((string) $item->singkatan_parpol);

            $item->partai = (object) [
                'id' => $item->id_partai,
                'singkatan_parpol' => $singkatan,
                'nama_lengkap_parpol' => $item->nama_lengkap_parpol,
                'logo_url' => $this->getLogoUrl($singkatan),
            ];

            return $item;
        });

        /*
         * Statistik hanya menghitung partai yang dapat dibandingkan.
         */
        $dataPembanding = $data->filter(
            fn ($item) => $item->dapat_dibandingkan
        );

        $stats = [
            'naik' => $dataPembanding
                ->where('status_tren', 'Naik')
                ->count(),

            'turun' => $dataPembanding
                ->where('status_tren', 'Turun')
                ->count(),

            'tetap' => $dataPembanding
                ->where('status_tren', 'Tetap')
                ->count(),

            'tidak_dibandingkan' => $data
                ->where('status_tren', 'Tidak dibandingkan')
                ->count(),
        ];

        return [
            'data' => $data->values(),
            'stats' => $stats,
            'total2019' => $total2019,
            'total2024' => $total2024,
        ];
    }

    /**
     * Mencari logo berdasarkan singkatan partai di public/images/logo.
     */
    private function getLogoUrl(?string $singkatan): ?string
    {
        if (!$singkatan) {
            return null;
        }

        $ekstensiYangDidukung = [
            'png',
            'jpg',
            'jpeg',
            'webp',
            'avif',
        ];

        foreach ($ekstensiYangDidukung as $ekstensi) {
            $path = 'images/logo/' . $singkatan . '.' . $ekstensi;

            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        return null;
    }

    /**
     * Format angka untuk ekspor.
     */
    private function formatAngka($angka): string
    {
        if ($angka === null) {
            return '-';
        }

        return number_format((float) $angka, 4, ',', '.');
    }
}
