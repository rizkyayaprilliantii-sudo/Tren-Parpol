<?php

namespace App\Http\Controllers;

use App\Models\AnalisisClustering;
use App\Models\HasilClustering;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PimpinanClusteringController extends Controller
{
    /**
     * Menampilkan hasil clustering terbaru untuk role Pimpinan.
     *
     * Model utama:
     * - Periode: 2019-2024
     * - K = 2
     * - Unit analisis: kecamatan x partai
     * - Fitur: share_2019 dan share_2024
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        /*
        |--------------------------------------------------------------------------
        | ANALISIS TERBARU
        |--------------------------------------------------------------------------
        */

        $analisis = AnalisisClustering::query()
            ->latest('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | HASIL CLUSTERING
        |--------------------------------------------------------------------------
        */

        $hasilQuery = HasilClustering::query()
            ->with([
                'kecamatan',
                'partai',
            ]);

        if ($analisis) {
            $hasilQuery->where(
                'analisis_id',
                $analisis->id
            );
        } else {
            $hasilQuery->whereRaw('1 = 0');
        }

        /*
        |--------------------------------------------------------------------------
        | PENCARIAN
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {
            $hasilQuery->where(function ($query) use ($search) {

                $query
                    ->whereHas('partai', function ($partaiQuery) use ($search) {

                        $partaiQuery
                            ->where(
                                'singkatan_parpol',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'nama_lengkap_parpol',
                                'like',
                                '%' . $search . '%'
                            );
                    })
                    ->orWhereHas('kecamatan', function ($kecamatanQuery) use ($search) {

                        $kecamatanQuery->where(
                            'nama_kecamatan',
                            'like',
                            '%' . $search . '%'
                        );
                    })
                    ->orWhere(
                        'kategori_cluster',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'status_tren',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | URUTAN
        |--------------------------------------------------------------------------
        */

        $hasilCluster = $hasilQuery
            ->orderByRaw(
                "CASE
                    WHEN kategori_cluster = 'Tinggi' THEN 1
                    WHEN kategori_cluster = 'Rendah' THEN 2
                    ELSE 3
                END"
            )
            ->orderByDesc('share_2024')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SEMUA HASIL UNTUK RINGKASAN
        |--------------------------------------------------------------------------
        */

        if ($analisis) {
            $semuaHasil = HasilClustering::query()
                ->where(
                    'analisis_id',
                    $analisis->id
                )
                ->get();
        } else {
            $semuaHasil = collect();
        }

        /*
        |--------------------------------------------------------------------------
        | RINGKASAN KLASTER
        |--------------------------------------------------------------------------
        */

        $ringkasanKlaster = [
            'Tinggi' => [
                'jumlah' => 0,
                'rata_share_2019' => 0,
                'rata_share_2024' => 0,
                'rata_selisih' => 0,
                'cluster' => null,
            ],

            'Rendah' => [
                'jumlah' => 0,
                'rata_share_2019' => 0,
                'rata_share_2024' => 0,
                'rata_selisih' => 0,
                'cluster' => null,
            ],
        ];

        foreach ($ringkasanKlaster as $kategori => $nilai) {

            $rows = $semuaHasil->where(
                'kategori_cluster',
                $kategori
            );

            $ringkasanKlaster[$kategori] = [
                'jumlah' => $rows->count(),

                'rata_share_2019' => $rows->avg(
                    'share_2019'
                ) ?? 0,

                'rata_share_2024' => $rows->avg(
                    'share_2024'
                ) ?? 0,

                'rata_selisih' => $rows->avg(
                    'selisih_share'
                ) ?? 0,

                'cluster' => $rows->first()?->cluster,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $jumlahData = $analisis
            ? (int) $analisis->jumlah_observasi
            : $semuaHasil->count();

        $jumlahKecamatan = $semuaHasil
            ->pluck('id_kecamatan')
            ->filter()
            ->unique()
            ->count();

        $jumlahPartaiComparable = $semuaHasil
            ->pluck('id_partai')
            ->filter()
            ->unique()
            ->count();

        $jumlahCluster = $analisis
            ? (int) $analisis->jumlah_cluster
            : 0;

        /*
        |--------------------------------------------------------------------------
        | RECORD SUMBER
        |--------------------------------------------------------------------------
        */

        $jumlahRecord2019 = DB::table(
            'master_suara_partai_2019'
        )->count();

        $jumlahRecord2024 = DB::table(
            'master_suara_partai_2024'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view('pimpinan.clustering', [
            'analisis' => $analisis,
            'hasilCluster' => $hasilCluster,
            'semuaHasil' => $semuaHasil,
            'ringkasanKlaster' => $ringkasanKlaster,
            'search' => $search,
            'jumlahData' => $jumlahData,
            'jumlahKecamatan' => $jumlahKecamatan,
            'jumlahPartaiComparable' => $jumlahPartaiComparable,
            'jumlahCluster' => $jumlahCluster,
            'jumlahRecord2019' => $jumlahRecord2019,
            'jumlahRecord2024' => $jumlahRecord2024,
        ]);
    }

    /**
     * Export hasil clustering ke Excel/CSV.
     */
    public function exportExcel(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $analisis = AnalisisClustering::query()
            ->latest('id')
            ->first();

        if (!$analisis) {
            abort(
                404,
                'Hasil clustering belum tersedia.'
            );
        }

        $query = HasilClustering::query()
            ->with([
                'kecamatan',
                'partai',
            ])
            ->where(
                'analisis_id',
                $analisis->id
            );

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {
            $query->where(function ($query) use ($search) {

                $query
                    ->whereHas('partai', function ($partaiQuery) use ($search) {

                        $partaiQuery
                            ->where(
                                'singkatan_parpol',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'nama_lengkap_parpol',
                                'like',
                                '%' . $search . '%'
                            );
                    })
                    ->orWhereHas('kecamatan', function ($kecamatanQuery) use ($search) {

                        $kecamatanQuery->where(
                            'nama_kecamatan',
                            'like',
                            '%' . $search . '%'
                        );
                    })
                    ->orWhere(
                        'kategori_cluster',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'status_tren',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        $hasil = $query
            ->orderByRaw(
                "CASE
                    WHEN kategori_cluster = 'Tinggi' THEN 1
                    WHEN kategori_cluster = 'Rendah' THEN 2
                    ELSE 3
                END"
            )
            ->orderByDesc('share_2024')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | CSV / EXCEL
        |--------------------------------------------------------------------------
        */

        return response()->streamDownload(
            function () use ($hasil, $analisis) {

                $handle = fopen(
                    'php://output',
                    'w'
                );

                /*
                |--------------------------------------------------------------------------
                | UTF-8 BOM
                |--------------------------------------------------------------------------
                */

                fprintf(
                    $handle,
                    chr(0xEF) .
                        chr(0xBB) .
                        chr(0xBF)
                );

                /*
                |--------------------------------------------------------------------------
                | INFORMASI
                |--------------------------------------------------------------------------
                */

                fputcsv(
                    $handle,
                    [
                        'Hasil Pengelompokan Perolehan Suara Partai Politik'
                    ],
                    ';'
                );

                fputcsv(
                    $handle,
                    [
                        'Periode',
                        '2019-2024'
                    ],
                    ';'
                );

                fputcsv(
                    $handle,
                    [
                        'Jumlah Cluster',
                        $analisis->jumlah_cluster
                    ],
                    ';'
                );

                fputcsv(
                    $handle,
                    [
                        'Jumlah Data',
                        $analisis->jumlah_observasi
                    ],
                    ';'
                );

                fputcsv(
                    $handle,
                    [],
                    ';'
                );

                /*
                |--------------------------------------------------------------------------
                | HEADER DATA
                |--------------------------------------------------------------------------
                */

                fputcsv(
                    $handle,
                    [
                        'No',
                        'Kecamatan',
                        'Partai Politik',
                        'Nama Lengkap Partai',
                        'Share 2019 (%)',
                        'Share 2024 (%)',
                        'Perubahan Share (%)',
                        'Klaster',
                        'Kode Cluster',
                        'Jarak ke Pusat Klaster',
                        'Tren',
                    ],
                    ';'
                );

                /*
                |--------------------------------------------------------------------------
                | DATA
                |--------------------------------------------------------------------------
                */

                foreach ($hasil as $index => $item) {

                    fputcsv(
                        $handle,
                        [
                            $index + 1,

                            $item->kecamatan?->nama_kecamatan
                                ?? '-',

                            $item->partai?->singkatan_parpol
                                ?? '-',

                            $item->partai?->nama_lengkap_parpol
                                ?? '-',

                            number_format(
                                (float) $item->share_2019,
                                4,
                                '.',
                                ''
                            ),

                            number_format(
                                (float) $item->share_2024,
                                4,
                                '.',
                                ''
                            ),

                            number_format(
                                (float) $item->selisih_share,
                                4,
                                '.',
                                ''
                            ),

                            $item->kategori_cluster
                                ?? '-',

                            $item->cluster !== null
                                ? 'C' . $item->cluster
                                : '-',

                            $item->jarak_centroid !== null
                                ? number_format(
                                    (float) $item->jarak_centroid,
                                    6,
                                    '.',
                                    ''
                                )
                                : '-',

                            $item->status_tren
                                ?? 'Tetap',
                        ],
                        ';'
                    );
                }

                fclose($handle);
            },
            'hasil-clustering-pemilu-2019-2024.csv',
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]
        );
    }

    /**
     * Export hasil clustering ke PDF.
     *
     * Tidak membutuhkan Blade export terpisah.
     */
    public function exportPdf(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $analisis = AnalisisClustering::query()
            ->latest('id')
            ->first();

        if (!$analisis) {
            abort(
                404,
                'Hasil clustering belum tersedia.'
            );
        }

        $query = HasilClustering::query()
            ->with([
                'kecamatan',
                'partai',
            ])
            ->where(
                'analisis_id',
                $analisis->id
            );

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {
            $query->where(function ($query) use ($search) {

                $query
                    ->whereHas('partai', function ($partaiQuery) use ($search) {

                        $partaiQuery
                            ->where(
                                'singkatan_parpol',
                                'like',
                                '%' . $search . '%'
                            )
                            ->orWhere(
                                'nama_lengkap_parpol',
                                'like',
                                '%' . $search . '%'
                            );
                    })
                    ->orWhereHas('kecamatan', function ($kecamatanQuery) use ($search) {

                        $kecamatanQuery->where(
                            'nama_kecamatan',
                            'like',
                            '%' . $search . '%'
                        );
                    })
                    ->orWhere(
                        'kategori_cluster',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'status_tren',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        $hasil = $query
            ->orderByRaw(
                "CASE
                    WHEN kategori_cluster = 'Tinggi' THEN 1
                    WHEN kategori_cluster = 'Rendah' THEN 2
                    ELSE 3
                END"
            )
            ->orderByDesc('share_2024')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | RECORD SUMBER
        |--------------------------------------------------------------------------
        */

        $jumlahRecord2019 = DB::table(
            'master_suara_partai_2019'
        )->count();

        $jumlahRecord2024 = DB::table(
            'master_suara_partai_2024'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | DATA RINGKAS KLASTER
        |--------------------------------------------------------------------------
        */

        $tinggi = $hasil->where(
            'kategori_cluster',
            'Tinggi'
        );

        $rendah = $hasil->where(
            'kategori_cluster',
            'Rendah'
        );

        /*
        |--------------------------------------------------------------------------
        | HTML PDF
        |--------------------------------------------------------------------------
        */

        $html = '
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">

<style>

@page {
    margin: 28px;
}

body {
    font-family: DejaVu Sans, sans-serif;
    color: #1B2A41;
    font-size: 9px;
    line-height: 1.4;
}

.header {
    border-bottom: 3px solid #7A2E2E;
    padding-bottom: 12px;
    margin-bottom: 15px;
}

.title {
    font-size: 18px;
    font-weight: bold;
    color: #1B2A41;
}

.subtitle {
    margin-top: 4px;
    font-size: 11px;
    color: #7A2E2E;
}

.description {
    margin-top: 6px;
    color: #5B6472;
}

.info {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
}

.info td {
    border: 1px solid #D7D9DD;
    padding: 5px 7px;
}

.info .label {
    width: 18%;
    background: #F4F1E8;
    font-weight: bold;
}

.summary {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
}

.summary td {
    width: 50%;
    border: 1px solid #D7D9DD;
    padding: 7px;
}

.summary-title {
    font-weight: bold;
    color: #7A2E2E;
}

table.data {
    width: 100%;
    border-collapse: collapse;
}

table.data th {
    background: #1B2A41;
    color: #F4F1E8;
    border: 1px solid #1B2A41;
    padding: 7px 5px;
    font-size: 8px;
    text-align: center;
}

table.data td {
    border: 1px solid #D7D9DD;
    padding: 6px 5px;
}

.center {
    text-align: center;
}

.right {
    text-align: right;
}

.tinggi {
    color: #7A2E2E;
    font-weight: bold;
}

.rendah {
    color: #1B2A41;
    font-weight: bold;
}

.naik {
    color: #15803D;
    font-weight: bold;
}

.turun {
    color: #7A2E2E;
    font-weight: bold;
}

.tetap {
    color: #5B6472;
    font-weight: bold;
}

.footer {
    margin-top: 15px;
    padding-top: 8px;
    border-top: 1px solid #D7D9DD;
    color: #5B6472;
    font-size: 8px;
}

</style>

</head>

<body>

<div class="header">

    <div class="title">
        Hasil Pengelompokan Perolehan Suara Partai Politik
    </div>

    <div class="subtitle">
        Pemilu Legislatif 2019–2024 Kota Banjarmasin
    </div>

    <div class="description">
        Hasil analisis menggunakan algoritma K-Means Clustering
        dengan K = ' . $analisis->jumlah_cluster . '
        dan ' . $analisis->jumlah_observasi . '
        data perbandingan.
    </div>

</div>

<table class="info">

<tr>

    <td class="label">
        Periode
    </td>

    <td>
        2019–2024
    </td>

    <td class="label">
        Jumlah Cluster
    </td>

    <td>
        ' . $analisis->jumlah_cluster . '
    </td>

</tr>

<tr>

    <td class="label">
        Data Analisis
    </td>

    <td>
        ' . number_format(
            (int) $analisis->jumlah_observasi,
            0,
            ',',
            '.'
        ) . '
    </td>

    <td class="label">
        Silhouette Score
    </td>

    <td>
        ' . number_format(
            (float) $analisis->silhouette_score,
            4,
            ',',
            '.'
        ) . '
    </td>

</tr>

<tr>

    <td class="label">
        Data Sumber 2019
    </td>

    <td>
        ' . number_format(
            $jumlahRecord2019,
            0,
            ',',
            '.'
        ) . ' record
    </td>

    <td class="label">
        Data Sumber 2024
    </td>

    <td>
        ' . number_format(
            $jumlahRecord2024,
            0,
            ',',
            '.'
        ) . ' record
    </td>

</tr>

</table>

<table class="summary">

<tr>

    <td>

        <div class="summary-title">
            Klaster Tinggi
        </div>

        <div>
            ' . $tinggi->count() . '
            data perbandingan
        </div>

        <div>
            Rata-rata share 2019:
            ' . number_format(
            (float) ($tinggi->avg('share_2019') ?? 0),
            2,
            ',',
            '.'
        ) . '%
        </div>

        <div>
            Rata-rata share 2024:
            ' . number_format(
            (float) ($tinggi->avg('share_2024') ?? 0),
            2,
            ',',
            '.'
        ) . '%
        </div>

    </td>

    <td>

        <div class="summary-title">
            Klaster Rendah
        </div>

        <div>
            ' . $rendah->count() . '
            data perbandingan
        </div>

        <div>
            Rata-rata share 2019:
            ' . number_format(
            (float) ($rendah->avg('share_2019') ?? 0),
            2,
            ',',
            '.'
        ) . '%
        </div>

        <div>
            Rata-rata share 2024:
            ' . number_format(
            (float) ($rendah->avg('share_2024') ?? 0),
            2,
            ',',
            '.'
        ) . '%
        </div>

    </td>

</tr>

</table>

<table class="data">

<thead>

<tr>
    <th>No.</th>
    <th>Kecamatan</th>
    <th>Partai</th>
    <th>Share 2019</th>
    <th>Share 2024</th>
    <th>Perubahan</th>
    <th>Klaster</th>
    <th>Jarak Pusat</th>
    <th>Tren</th>
</tr>

</thead>

<tbody>
';

        foreach ($hasil as $index => $item) {

            $kategoriClass = match ($item->kategori_cluster) {
                'Tinggi' => 'tinggi',
                'Rendah' => 'rendah',
                default => '',
            };

            $trenClass = match ($item->status_tren) {
                'Naik' => 'naik',
                'Turun' => 'turun',
                default => 'tetap',
            };

            $html .= '

<tr>

<td class="center">
    ' . ($index + 1) . '
</td>

<td>
    ' . e(
                $item->kecamatan?->nama_kecamatan ?? '-'
            ) . '
</td>

<td>
    ' . e(
                $item->partai?->singkatan_parpol ?? '-'
            ) . '
</td>

<td class="right">
    ' . number_format(
                (float) $item->share_2019,
                2,
                ',',
                '.'
            ) . '%
</td>

<td class="right">
    ' . number_format(
                (float) $item->share_2024,
                2,
                ',',
                '.'
            ) . '%
</td>

<td class="right">
    ' . (
                (float) $item->selisih_share > 0
                ? '+'
                : ''
            ) . number_format(
                (float) $item->selisih_share,
                2,
                ',',
                '.'
            ) . '%
</td>

<td class="center ' . $kategoriClass . '">
    ' . e(
                $item->kategori_cluster ?? '-'
            ) . '
</td>

<td class="right">
    ' . (
                $item->jarak_centroid !== null
                ? number_format(
                    (float) $item->jarak_centroid,
                    4,
                    ',',
                    '.'
                )
                : '-'
            ) . '
</td>

<td class="center ' . $trenClass . '">
    ' . e(
                $item->status_tren ?? 'Tetap'
            ) . '
</td>

</tr>

';
        }

        $html .= '

</tbody>

</table>

<div class="footer">

    Share suara merupakan proporsi suara partai terhadap
    total suara partai dalam kecamatan pada tahun yang bersangkutan.
    Perubahan share dihitung dari share 2024 dikurangi share 2019.
    Data 2024 diagregasikan dari tingkat TPS ke tingkat kecamatan
    agar dapat dibandingkan dengan data 2019.

</div>

</body>

</html>
';

        /*
        |--------------------------------------------------------------------------
        | GENERATE PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadHTML($html);

        $pdf->setPaper(
            'a4',
            'landscape'
        );

        return $pdf->download(
            'hasil-clustering-pemilu-2019-2024.pdf'
        );
    }
}
