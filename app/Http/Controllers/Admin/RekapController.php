<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekapSuara2019;
use App\Models\RekapSuara2024;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', '2024');

        if ($tahun == '2019') {

            $data = RekapSuara2019::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();

            $totalSuara = RekapSuara2019::sum('total_suara');
        } else {

            $data = RekapSuara2024::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();

            $totalSuara = RekapSuara2024::sum('total_suara');
        }

        return view(
            'admin.rekap.index',
            compact('data', 'tahun', 'totalSuara')
        );
    }

    public function sinkronisasi($tahun)
    {
        return back()->with(
            'success',
            "Data Rekapitulasi Tahun {$tahun} berhasil disinkronisasi ulang dari Data Mentah!"
        );
    }

    public function exportExcel($tahun): StreamedResponse
    {
        if (!in_array($tahun, ['2019', '2024'])) {
            abort(404);
        }

        if ($tahun == '2019') {

            $data = RekapSuara2019::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();
        } else {

            $data = RekapSuara2024::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();
        }

        $totalSuara = $data->sum('total_suara');

        $filename = 'Rekapitulasi_Suara_Pemilu_' . $tahun . '.xls';

        return response()->streamDownload(function () use (
            $data,
            $tahun,
            $totalSuara
        ) {

            echo "\xEF\xBB\xBF";

            echo '<table border="1">';

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

            echo '<tr>';
            echo '<th>No</th>';
            echo '<th>Partai Politik</th>';
            echo '<th>Nama Lengkap Partai</th>';
            echo '<th>Total Suara</th>';
            echo '<th>Persentase</th>';
            echo '</tr>';

            foreach ($data as $index => $item) {

                $persen = $totalSuara > 0
                    ? ($item->total_suara / $totalSuara) * 100
                    : 0;

                echo '<tr>';

                echo '<td>' . ($index + 1) . '</td>';

                echo '<td>'
                    . e($item->partai->singkatan_parpol ?? '-')
                    . '</td>';

                echo '<td>'
                    . e($item->partai->nama_lengkap_parpol ?? '-')
                    . '</td>';

                echo '<td>'
                    . $item->total_suara
                    . '</td>';

                echo '<td>'
                    . number_format($persen, 2, ',', '.')
                    . '%'
                    . '</td>';

                echo '</tr>';
            }

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
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    public function exportPdf($tahun)
    {
        if (!in_array($tahun, ['2019', '2024'])) {
            abort(404);
        }

        if ($tahun == '2019') {

            $data = RekapSuara2019::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();
        } else {

            $data = RekapSuara2024::with('partai')
                ->orderBy('total_suara', 'desc')
                ->get();
        }

        $totalSuara = $data->sum('total_suara');

        $pdf = Pdf::loadView('admin.rekap.pdf', [
            'data' => $data,
            'tahun' => $tahun,
            'totalSuara' => $totalSuara,
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'Rekapitulasi_Suara_Pemilu_' . $tahun . '.pdf'
        );
    }
}
