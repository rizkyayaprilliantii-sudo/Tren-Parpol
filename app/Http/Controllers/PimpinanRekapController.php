<?php

namespace App\Http\Controllers;

use App\Models\RekapSuara2019;
use App\Models\RekapSuara2024;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class PimpinanRekapController extends Controller
{
    /**
     * Menampilkan halaman Rekapitulasi untuk Pimpinan.
     *
     * Fokus halaman:
     * - Membaca hasil rekap perolehan suara partai
     * - Melihat peringkat partai
     * - Melihat persentase perolehan suara
     * - Melihat ringkasan statistik
     *
     * Tidak terdapat proses validasi, sinkronisasi,
     * edit, maupun pengelolaan data.
     */
    public function index(Request $request)
    {
        $tahun = (string) $request->get('tahun', '2024');

        if (!in_array($tahun, ['2019', '2024'], true)) {
            $tahun = '2024';
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA REKAP
        |--------------------------------------------------------------------------
        */

        if ($tahun === '2019') {
            $data = RekapSuara2019::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();
        } else {
            $data = RekapSuara2024::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL SUARA SAH
        |--------------------------------------------------------------------------
        */

        $totalSuara = (int) $data->sum('total_suara');


        /*
        |--------------------------------------------------------------------------
        | JUMLAH PARTAI
        |--------------------------------------------------------------------------
        */

        $jumlahPartai = $data->count();


        /*
        |--------------------------------------------------------------------------
        | PARTAI DENGAN PEROLEHAN TERTINGGI
        |--------------------------------------------------------------------------
        */

        $partaiTertinggi = $data->first();


        /*
        |--------------------------------------------------------------------------
        | RATA-RATA SUARA
        |--------------------------------------------------------------------------
        */

        $rataRataSuara = $jumlahPartai > 0
            ? round($totalSuara / $jumlahPartai, 0)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | TAMBAHKAN PERSENTASE
        |--------------------------------------------------------------------------
        |
        | Persentase dihitung dari total suara sah seluruh partai.
        |
        */

        $data = $data->map(function ($item) use ($totalSuara) {

            $persentase = $totalSuara > 0
                ? round(
                    ($item->total_suara / $totalSuara) * 100,
                    2
                )
                : 0;

            $item->persentase = $persentase;

            return $item;
        });


        /*
        |--------------------------------------------------------------------------
        | KIRIM KE VIEW
        |--------------------------------------------------------------------------
        */

        return view('pimpinan.rekap', compact(
            'tahun',
            'data',
            'totalSuara',
            'jumlahPartai',
            'partaiTertinggi',
            'rataRataSuara'
        ));
    }


    /**
     * Export Rekapitulasi Pimpinan ke Excel.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $tahun = (string) $request->get('tahun', '2024');

        if (!in_array($tahun, ['2019', '2024'], true)) {
            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA
        |--------------------------------------------------------------------------
        */

        if ($tahun === '2019') {
            $data = RekapSuara2019::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();
        } else {
            $data = RekapSuara2024::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        $totalSuara = (int) $data->sum('total_suara');


        /*
        |--------------------------------------------------------------------------
        | PERSENTASE
        |--------------------------------------------------------------------------
        */

        $data = $data->map(function ($item) use ($totalSuara) {

            $item->persentase = $totalSuara > 0
                ? round(
                    ($item->total_suara / $totalSuara) * 100,
                    2
                )
                : 0;

            return $item;
        });


        /*
        |--------------------------------------------------------------------------
        | NAMA FILE
        |--------------------------------------------------------------------------
        */

        $filename = 'Rekapitulasi_Perolehan_Suara_' . $tahun . '.xls';


        /*
        |--------------------------------------------------------------------------
        | GENERATE EXCEL
        |--------------------------------------------------------------------------
        */

        return response()->streamDownload(
            function () use ($data, $tahun, $totalSuara) {

                echo "\xEF\xBB\xBF";

                echo '<table border="1">';


                /*
                |--------------------------------------------------------------------------
                | JUDUL
                |--------------------------------------------------------------------------
                */

                echo '<tr>';
                echo '<th colspan="5">';
                echo 'REKAPITULASI PEROLEHAN SUARA PARTAI POLITIK';
                echo '</th>';
                echo '</tr>';

                echo '<tr>';
                echo '<th colspan="5">';
                echo 'PEMILU LEGISLATIF ' . $tahun . ' KOTA BANJARMASIN';
                echo '</th>';
                echo '</tr>';


                /*
                |--------------------------------------------------------------------------
                | HEADER
                |--------------------------------------------------------------------------
                */

                echo '<tr>';

                echo '<th>Peringkat</th>';
                echo '<th>Partai Politik</th>';
                echo '<th>Nama Lengkap Partai</th>';
                echo '<th>Total Suara</th>';
                echo '<th>Persentase</th>';

                echo '</tr>';


                /*
                |--------------------------------------------------------------------------
                | DATA
                |--------------------------------------------------------------------------
                */

                foreach ($data as $index => $item) {

                    echo '<tr>';

                    echo '<td>';
                    echo $index + 1;
                    echo '</td>';

                    echo '<td>';
                    echo e(
                        $item->partai->singkatan_parpol ?? '-'
                    );
                    echo '</td>';

                    echo '<td>';
                    echo e(
                        $item->partai->nama_lengkap_parpol ?? '-'
                    );
                    echo '</td>';

                    echo '<td>';
                    echo $item->total_suara;
                    echo '</td>';

                    echo '<td>';
                    echo number_format(
                        $item->persentase,
                        2,
                        ',',
                        '.'
                    );
                    echo '%';
                    echo '</td>';

                    echo '</tr>';
                }


                /*
                |--------------------------------------------------------------------------
                | TOTAL
                |--------------------------------------------------------------------------
                */

                echo '<tr>';

                echo '<th colspan="3">';
                echo 'TOTAL SUARA SAH';
                echo '</th>';

                echo '<th>';
                echo $totalSuara;
                echo '</th>';

                echo '<th>';
                echo '100%';
                echo '</th>';

                echo '</tr>';

                echo '</table>';
            },
            $filename,
            [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            ]
        );
    }


    /**
     * Export Rekapitulasi Pimpinan ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $tahun = (string) $request->get('tahun', '2024');

        if (!in_array($tahun, ['2019', '2024'], true)) {
            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA
        |--------------------------------------------------------------------------
        */

        if ($tahun === '2019') {
            $data = RekapSuara2019::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();
        } else {
            $data = RekapSuara2024::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        $totalSuara = (int) $data->sum('total_suara');


        /*
        |--------------------------------------------------------------------------
        | PERSENTASE
        |--------------------------------------------------------------------------
        */

        $data = $data->map(function ($item) use ($totalSuara) {

            $item->persentase = $totalSuara > 0
                ? round(
                    ($item->total_suara / $totalSuara) * 100,
                    2
                )
                : 0;

            return $item;
        });


        /*
        |--------------------------------------------------------------------------
        | GENERATE PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'pimpinan.rekap-pdf',
            compact(
                'data',
                'tahun',
                'totalSuara'
            )
        );


        /*
        |--------------------------------------------------------------------------
        | ORIENTASI
        |--------------------------------------------------------------------------
        */

        $pdf->setPaper('a4', 'landscape');


        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD
        |--------------------------------------------------------------------------
        */

        return $pdf->download(
            'Rekapitulasi_Perolehan_Suara_' . $tahun . '.pdf'
        );
    }
}
