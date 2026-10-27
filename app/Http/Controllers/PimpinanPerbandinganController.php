<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PimpinanPerbandinganController extends Controller
{
    /**
     * Menampilkan tren perolehan suara tingkat Kota Banjarmasin.
     *
     * Share suara = suara partai / total seluruh suara pada tahun terkait
     *               x 100%.
     *
     * Selisih share dihitung dalam poin persentase (pp).
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $hasil = $this->getComparisonData($search);

        return view('pimpinan.perbandingan', [
            'search'             => $search,
            'dataPerbandingan'   => $hasil['dataPerbandingan'],
            'total2019'          => $hasil['total2019'],
            'total2024'          => $hasil['total2024'],
            'selisihTotal'       => $hasil['selisihTotal'],
            'persentaseTotal'    => $hasil['persentaseTotal'],
            'jumlahMeningkat'    => $hasil['jumlahMeningkat'],
            'jumlahMenurun'      => $hasil['jumlahMenurun'],
            'jumlahTetap'        => $hasil['jumlahTetap'],
            'kenaikanTerbesar'   => $hasil['kenaikanTerbesar'],
            'penurunanTerbesar'  => $hasil['penurunanTerbesar'],
        ]);
    }

    /**
     * Ekspor perbandingan tren ke Excel.
     */
    public function exportExcel(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $hasil = $this->getComparisonData($search);
        $data = $hasil['dataPerbandingan'];

        $filename = 'tren-share-suara-pemilu-2019-2024.xls';

        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Tren Share Suara Pemilu 2019 dan 2024</title>
        </head>
        <body>
            <h2>Analisis Tren Perolehan Suara Partai Politik</h2>
            <h3>Kota Banjarmasin, Pemilu Legislatif 2019 dan 2024</h3>

            <table border="1">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Partai Politik</th>
                        <th>Nama Lengkap</th>
                        <th>Suara 2019</th>
                        <th>Share 2019 (%)</th>
                        <th>Suara 2024</th>
                        <th>Share 2024 (%)</th>
                        <th>Selisih Share (pp)</th>
                        <th>Status Tren Share</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($data as $index => $item) {
            $html .= '<tr>
                <td>' . ($index + 1) . '</td>
                <td>' . e($item['partai']) . '</td>
                <td>' . e($item['nama_lengkap']) . '</td>
                <td>' . $item['suara_2019'] . '</td>
                <td>' . $this->formatExcelNumber($item['share_2019']) . '</td>
                <td>' . $item['suara_2024'] . '</td>
                <td>' . $this->formatExcelNumber($item['share_2024']) . '</td>
                <td>' . $this->formatExcelNumber($item['selisih_share']) . '</td>
                <td>' . e($item['status_tren_share']) . '</td>
            </tr>';
        }

        $html .= '</tbody>
            </table>

            <br>

            <table border="1">
                <tr>
                    <th>Total seluruh suara 2019</th>
                    <td>' . $hasil['total2019'] . '</td>
                </tr>
                <tr>
                    <th>Total seluruh suara 2024</th>
                    <td>' . $hasil['total2024'] . '</td>
                </tr>
            </table>

            <p>
                Catatan: share suara dihitung berdasarkan total seluruh suara
                pada tingkat Kota Banjarmasin untuk masing-masing tahun.
                Selisih share dinyatakan dalam poin persentase (pp).
            </p>
        </body>
        </html>';

        return response($html, 200, [
            'Content-Type' =>
            'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' =>
            'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Ekspor laporan tren ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $hasil = $this->getComparisonData($search);

        $pdf = Pdf::loadView('pimpinan.exports.perbandingan-pdf', [
            'search'             => $search,
            'dataPerbandingan'   => $hasil['dataPerbandingan'],
            'total2019'          => $hasil['total2019'],
            'total2024'          => $hasil['total2024'],
            'selisihTotal'       => $hasil['selisihTotal'],
            'persentaseTotal'    => $hasil['persentaseTotal'],
            'jumlahMeningkat'    => $hasil['jumlahMeningkat'],
            'jumlahMenurun'      => $hasil['jumlahMenurun'],
            'jumlahTetap'        => $hasil['jumlahTetap'],
            'kenaikanTerbesar'   => $hasil['kenaikanTerbesar'],
            'penurunanTerbesar'  => $hasil['penurunanTerbesar'],
        ])->setPaper('a4', 'landscape');

        return $pdf->download('tren-share-suara-pemilu-2019-2024.pdf');
    }

    /**
     * Mengambil dan mengolah data tren share suara.
     */
    private function getComparisonData(string $search = ''): array
    {
        /*
         * ID 14 partai yang dibandingkan dalam penelitian.
         * Penyebut share tetap menggunakan total suara seluruh partai
         * di setiap tahun, bukan hanya 14 partai ini.
         */
        $partaiDibandingkan = [
            1,
            2,
            3,
            4,
            5,
            6,
            8,
            9,
            10,
            11,
            12,
            13,
            14,
            15,
        ];

        /*
         * Rekap suara per partai untuk tahun 2019.
         */
        $suara2019 = DB::table('master_suara_partai_2019')
            ->select('id_partai')
            ->selectRaw('SUM(COALESCE(jumlah_suara, 0)) AS suara_2019')
            ->groupBy('id_partai');

        /*
         * Rekap suara per partai untuk tahun 2024.
         * Data tingkat TPS dijumlahkan menjadi total partai.
         */
        $suara2024 = DB::table('master_suara_partai_2024')
            ->select('id_partai')
            ->selectRaw('SUM(COALESCE(jumlah_suara, 0)) AS suara_2024')
            ->groupBy('id_partai');

        /*
         * Penyebut share: total suara seluruh partai untuk masing-masing
         * tahun, tidak dipengaruhi filter pencarian atau daftar 14 partai.
         */
        $total2019 = (int) DB::table('master_suara_partai_2019')
            ->sum('jumlah_suara');

        $total2024 = (int) DB::table('master_suara_partai_2024')
            ->sum('jumlah_suara');

        /*
         * Ambil master partai beserta total suara kedua tahun.
         */
        $query = DB::table('master_partai')
            ->leftJoinSub($suara2019, 'rekap_2019', function ($join) {
                $join->on('master_partai.id', '=', 'rekap_2019.id_partai');
            })
            ->leftJoinSub($suara2024, 'rekap_2024', function ($join) {
                $join->on('master_partai.id', '=', 'rekap_2024.id_partai');
            })
            ->whereIn('master_partai.id', $partaiDibandingkan)
            ->select([
                'master_partai.id',
                'master_partai.singkatan_parpol',
                'master_partai.nama_lengkap_parpol',
            ])
            ->selectRaw('COALESCE(rekap_2019.suara_2019, 0) AS suara_2019')
            ->selectRaw('COALESCE(rekap_2024.suara_2024, 0) AS suara_2024')
            ->orderBy('master_partai.singkatan_parpol');

        /*
         * Pencarian berdasarkan singkatan atau nama lengkap partai.
         */
        if ($search !== '') {
            $query->where(function ($subQuery) use ($search) {
                $subQuery
                    ->where(
                        'master_partai.singkatan_parpol',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'master_partai.nama_lengkap_parpol',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        $rows = $query->get();

        $dataPerbandingan = [];

        foreach ($rows as $row) {
            $suara19 = (int) $row->suara_2019;
            $suara24 = (int) $row->suara_2024;

            /*
             * Share suara masing-masing tahun.
             */
            $share19 = $total2019 > 0
                ? ($suara19 / $total2019) * 100
                : null;

            $share24 = $total2024 > 0
                ? ($suara24 / $total2024) * 100
                : null;

            /*
             * Selisih share dinyatakan dalam poin persentase.
             */
            $dapatDibandingkan =
                $suara19 > 0 &&
                $suara24 > 0 &&
                $share19 !== null &&
                $share24 !== null;

            $selisihShare = $dapatDibandingkan
                ? $share24 - $share19
                : null;

            /*
             * Status tren share.
             */
            if (!$dapatDibandingkan) {
                $statusTrenShare = 'TIDAK DIBANDINGKAN';
                $statusLama = 'Tidak dibandingkan';
            } elseif ($selisihShare > 0.0000001) {
                $statusTrenShare = 'NAIK';
                $statusLama = 'Meningkat';
            } elseif ($selisihShare < -0.0000001) {
                $statusTrenShare = 'TURUN';
                $statusLama = 'Menurun';
            } else {
                $statusTrenShare = 'TETAP';
                $statusLama = 'Tetap';
            }

            /*
             * Perubahan jumlah suara disimpan sebagai informasi tambahan.
             * Ukuran utama analisis tren tetap share suara.
             */
            $selisihSuara = $suara24 - $suara19;

            $persentaseSuara = $suara19 > 0
                ? ($selisihSuara / $suara19) * 100
                : null;

            $singkatan = (string) $row->singkatan_parpol;

            $dataPerbandingan[] = [
                'id_partai'       => $row->id,
                'partai'          => $singkatan,
                'nama_lengkap'    => $row->nama_lengkap_parpol,
                'logo_url'        => $this->getLogoUrl($singkatan),

                'suara_2019'      => $suara19,
                'suara_2024'      => $suara24,

                'share_2019'      => $share19,
                'share_2024'      => $share24,
                'selisih_share'   => $selisihShare,

                'status_tren_share' => $statusTrenShare,
                'dapat_dibandingkan' => $dapatDibandingkan,

                'selisih'         => $selisihSuara,
                'persentase'      => $persentaseSuara,
                'status'          => $statusLama,
            ];
        }

        /*
         * Statistik dihitung dari partai yang memiliki suara positif
         * pada kedua tahun dan dapat dibandingkan.
         */
        $dataComparable = array_values(array_filter(
            $dataPerbandingan,
            fn($item) => $item['dapat_dibandingkan']
        ));

        $jumlahMeningkat = count(array_filter(
            $dataComparable,
            fn($item) => $item['status_tren_share'] === 'NAIK'
        ));

        $jumlahMenurun = count(array_filter(
            $dataComparable,
            fn($item) => $item['status_tren_share'] === 'TURUN'
        ));

        $jumlahTetap = count(array_filter(
            $dataComparable,
            fn($item) => $item['status_tren_share'] === 'TETAP'
        ));

        $kenaikanTerbesar = null;
        $penurunanTerbesar = null;

        if (!empty($dataComparable)) {
            $kenaikanTerbesar = collect($dataComparable)
                ->filter(fn($item) => $item['selisih_share'] > 0)
                ->sortByDesc('selisih_share')
                ->first();

            $penurunanTerbesar = collect($dataComparable)
                ->filter(fn($item) => $item['selisih_share'] < 0)
                ->sortBy('selisih_share')
                ->first();
        }

        $selisihTotal = $total2024 - $total2019;

        $persentaseTotal = $total2019 > 0
            ? ($selisihTotal / $total2019) * 100
            : null;

        return [
            'dataPerbandingan'  => $dataPerbandingan,

            // Total kota, bukan total 14 partai yang ditampilkan.
            'total2019'         => $total2019,
            'total2024'         => $total2024,
            'selisihTotal'      => $selisihTotal,
            'persentaseTotal'   => $persentaseTotal,

            'jumlahMeningkat'   => $jumlahMeningkat,
            'jumlahMenurun'     => $jumlahMenurun,
            'jumlahTetap'       => $jumlahTetap,

            'kenaikanTerbesar'  => $kenaikanTerbesar,
            'penurunanTerbesar' => $penurunanTerbesar,
        ];
    }

    /**
     * Mengambil URL logo partai dari public/images/logo.
     */
    private function getLogoUrl(string $singkatan): ?string
    {
        $singkatan = trim($singkatan);

        if ($singkatan === '') {
            return null;
        }

        $extensions = ['png', 'jpg', 'jpeg', 'webp', 'avif'];

        foreach ($extensions as $extension) {
            $relativePath = 'images/logo/' . $singkatan . '.' . $extension;
            $absolutePath = public_path($relativePath);

            if (file_exists($absolutePath)) {
                return asset($relativePath);
            }
        }

        return null;
    }

    /**
     * Memformat angka untuk ekspor.
     */
    private function formatExcelNumber($value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        return number_format((float) $value, 4, '.', '');
    }
}
