<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterPartai;
use App\Models\MasterKecamatan;
use App\Models\MasterKelurahan;
use App\Models\MasterTps;
use App\Models\MasterSuaraPartai2019;
use App\Models\MasterSuaraPartai2024;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class PimpinanDataSuaraController extends Controller
{
    private function applyFilters($query, string $tahun, Request $request)
    {
        $partai = $request->input('partai');
        $kecamatan = $request->input('kecamatan');
        $kelurahan = $request->input('kelurahan');
        $tps = $request->input('tps');
        $search = trim((string) $request->input('search', ''));

        if ($partai !== null && $partai !== '') {
            $query->where('id_partai', $partai);
        }

        if ($tahun === '2019') {
            if ($kecamatan !== null && $kecamatan !== '') {
                $query->where('id_kecamatan', $kecamatan);
            }

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('partai', function ($p) use ($search) {
                        $p->where('singkatan_parpol', 'like', "%{$search}%")
                            ->orWhere('nama_lengkap_parpol', 'like', "%{$search}%");
                    })->orWhereHas('kecamatan', function ($k) use ($search) {
                        $k->where('nama_kecamatan', 'like', "%{$search}%");
                    });
                });
            }
        } else {
            if ($kecamatan !== null && $kecamatan !== '') {
                $query->whereHas('tps', fn ($q) => $q->where('id_kecamatan', $kecamatan));
            }

            if ($kelurahan !== null && $kelurahan !== '') {
                $query->whereHas('tps', fn ($q) => $q->where('id_kelurahan', $kelurahan));
            }

            if ($tps !== null && $tps !== '') {
                $query->where('id_tps', $tps);
            }

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('partai', function ($p) use ($search) {
                        $p->where('singkatan_parpol', 'like', "%{$search}%")
                            ->orWhere('nama_lengkap_parpol', 'like', "%{$search}%");
                    })->orWhereHas('tps.kelurahan', function ($k) use ($search) {
                        $k->where('nama_kelurahan', 'like', "%{$search}%");
                    })->orWhereHas('tps.kecamatan', function ($k) use ($search) {
                        $k->where('nama_kecamatan', 'like', "%{$search}%");
                    });
                });
            }
        }

        return $query;
    }

    private function makeQuery(string $tahun, Request $request)
    {
        if ($tahun === '2019') {
            return $this->applyFilters(
                MasterSuaraPartai2019::with(['partai', 'kecamatan']),
                $tahun,
                $request
            )->orderBy('id_kecamatan')->orderBy('id_partai');
        }

        return $this->applyFilters(
            MasterSuaraPartai2024::with(['partai', 'tps.kecamatan', 'tps.kelurahan']),
            $tahun,
            $request
        )->orderBy('id_tps')->orderBy('id_partai');
    }

    public function index(Request $request)
    {
        $tahun = (string) $request->get('tahun', '2024');

        if (!in_array($tahun, ['2019', '2024'], true)) {
            $tahun = '2024';
        }

        $search = trim((string) $request->get('search', ''));
        $partaiTerpilih = $request->get('partai', '');
        $kecamatanTerpilih = $request->get('kecamatan', '');
        $kelurahanTerpilih = $request->get('kelurahan', '');
        $tpsTerpilih = $request->get('tps', '');

        $dataSuara = $this->makeQuery($tahun, $request)
            ->paginate(20)
            ->withQueryString();

        $jumlahData = (clone $this->makeQuery($tahun, $request))->count();

        $totalSuara = (clone $this->makeQuery($tahun, $request))
            ->sum('jumlah_suara');

        $daftarPartai = MasterPartai::orderBy('singkatan_parpol')->get();

        // Perbaikan: menghitung jumlah partai dari tabel master partai.
        $jumlahPartai = $daftarPartai->count();

        $daftarKecamatan = MasterKecamatan::orderBy('nama_kecamatan')->get();

        $daftarKelurahan = $tahun === '2024'
            ? MasterKelurahan::orderBy('nama_kelurahan')->get()
            : collect();

        $daftarTps = $tahun === '2024'
            ? MasterTps::with('kelurahan')->orderBy('nomor_tps')->get()
            : collect();

        return view('pimpinan.data-suara', compact(
            'tahun',
            'search',
            'dataSuara',
            'jumlahData',
            'totalSuara',
            'jumlahPartai',
            'daftarPartai',
            'daftarKecamatan',
            'daftarKelurahan',
            'daftarTps',
            'partaiTerpilih',
            'kecamatanTerpilih',
            'kelurahanTerpilih',
            'tpsTerpilih'
        ));
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $tahun = (string) $request->get('tahun', '2024');

        if (!in_array($tahun, ['2019', '2024'], true)) {
            abort(404);
        }

        $data = $this->makeQuery($tahun, $request)->get();
        $totalSuara = $data->sum('jumlah_suara');
        $filename = 'Data_Suara_Pemilu_' . $tahun . '.xls';

        return response()->streamDownload(function () use ($data, $tahun, $totalSuara) {
            echo "\xEF\xBB\xBF";
            echo '<table border="1">';

            echo '<tr><th colspan="7">DATA PEROLEHAN SUARA PARTAI POLITIK - PEMILU '
                . e($tahun) . ' KOTA BANJARMASIN</th></tr>';

            if ($tahun === '2019') {
                echo '<tr><th>No</th><th>Kecamatan</th><th>Partai Politik</th>'
                    . '<th>Nama Lengkap Partai</th><th>Jumlah Suara</th></tr>';

                foreach ($data as $i => $item) {
                    echo '<tr><td>' . ($i + 1) . '</td>'
                        . '<td>' . e($item->kecamatan->nama_kecamatan ?? '-') . '</td>'
                        . '<td>' . e($item->partai->singkatan_parpol ?? '-') . '</td>'
                        . '<td>' . e($item->partai->nama_lengkap_parpol ?? '-') . '</td>'
                        . '<td>' . $item->jumlah_suara . '</td></tr>';
                }

                echo '<tr><th colspan="4">TOTAL SUARA</th>'
                    . '<th>' . $totalSuara . '</th></tr>';
            } else {
                echo '<tr><th>No</th><th>Kecamatan</th><th>Kelurahan</th>'
                    . '<th>No. TPS</th><th>Partai Politik</th>'
                    . '<th>Nama Lengkap Partai</th><th>Jumlah Suara</th></tr>';

                foreach ($data as $i => $item) {
                    echo '<tr><td>' . ($i + 1) . '</td>'
                        . '<td>' . e($item->tps->kecamatan->nama_kecamatan ?? '-') . '</td>'
                        . '<td>' . e($item->tps->kelurahan->nama_kelurahan ?? '-') . '</td>'
                        . '<td>' . e($item->tps->nomor_tps ?? '-') . '</td>'
                        . '<td>' . e($item->partai->singkatan_parpol ?? '-') . '</td>'
                        . '<td>' . e($item->partai->nama_lengkap_parpol ?? '-') . '</td>'
                        . '<td>' . $item->jumlah_suara . '</td></tr>';
                }

                echo '<tr><th colspan="6">TOTAL SUARA</th>'
                    . '<th>' . $totalSuara . '</th></tr>';
            }

            echo '</table>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $tahun = (string) $request->get('tahun', '2024');

        if (!in_array($tahun, ['2019', '2024'], true)) {
            abort(404);
        }

        $data = $this->makeQuery($tahun, $request)->get();
        $search = trim((string) $request->get('search', ''));
        $totalSuara = $data->sum('jumlah_suara');

        $pdf = Pdf::loadView(
            'pimpinan.data-suara-pdf',
            compact('data', 'tahun', 'search', 'totalSuara')
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download('Data_Suara_Pemilu_' . $tahun . '.pdf');
    }
}
