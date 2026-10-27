<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * ID partai yang digunakan sebagai pembanding penelitian.
     */
    private const PARTAI_COMPARABLE = [
        1, 2, 3, 4, 5, 6, 8, 9, 10, 11, 12, 13, 14, 15,
    ];

    /**
     * DASHBOARD ADMIN
     */
    public function admin()
    {
        $countPartai = $this->countTable('master_partai');
        $countKecamatan = $this->countTable('master_kecamatan');
        $countKelurahan = $this->countTable('master_kelurahan');
        $countTps = $this->countTable('master_tps');

        $countUsers = $this->countTable('users');
        $roleAdmin = $this->countUsersByRole('admin');
        $rolePimpinan = $this->countUsersByRole('pimpinan');

        $totalSuara2019 = $this->getTotalSuara('master_suara_partai_2019');
        $totalSuara2024 = $this->getTotalSuara('master_suara_partai_2024');

        $selisihTotalSuara = $totalSuara2024 - $totalSuara2019;

        $persentasePerubahan = $totalSuara2019 > 0
            ? round(($selisihTotalSuara / $totalSuara2019) * 100, 2)
            : 0;

        $statusPerubahan = match (true) {
            $selisihTotalSuara > 0 => 'Meningkat',
            $selisihTotalSuara < 0 => 'Menurun',
            default => 'Tetap',
        };

        /*
         * Tren utama dihitung berdasarkan perubahan proporsi suara.
         */
        $trenData = $this->buildTrendData();
        $trenPartai = $trenData['trenPartai'];

        $jumlahPartaiNaik = $trenPartai
            ->where('selisih_share', '>', 0)
            ->count();

        $jumlahPartaiTurun = $trenPartai
            ->where('selisih_share', '<', 0)
            ->count();

        $jumlahPartaiTetap = $trenPartai
            ->where('selisih_share', '=', 0)
            ->count();

        $partaiKenaikanTertinggi = $trenPartai
            ->where('selisih_share', '>', 0)
            ->sortByDesc('selisih_share')
            ->first();

        $partaiPenurunanTertinggi = $trenPartai
            ->where('selisih_share', '<', 0)
            ->sortBy('selisih_share')
            ->first();

        /*
         * Delapan partai dengan perubahan proporsi terbesar,
         * baik meningkat maupun menurun.
         */
        $topTrenPartai = $trenPartai
            ->sortByDesc(fn ($item) => abs($item['selisih_share']))
            ->take(8)
            ->values();

        $nilaiMaksimumTren = max(
            0.0001,
            (float) ($topTrenPartai->max(
                fn ($item) => abs($item['selisih_share'])
            ) ?? 0)
        );

        /*
         * Ringkasan hasil K-Means terbaru.
         */
        $kmeans = $this->getLatestKMeansSummary();

        $analisisKMeans = $kmeans['analisis'];
        $clusterKMeans = $kmeans['cluster'];
        $totalClusterKMeans = $kmeans['total'];
        $metrikKMeans = $kmeans['metrik'];

        $cluster2019 = $clusterKMeans;
        $cluster2024 = $clusterKMeans;
        $totalCluster2019 = $totalClusterKMeans;
        $totalCluster2024 = $totalClusterKMeans;

        /*
         * Cakupan data.
         */
        $rawData2019 = $this->countTable('master_suara_partai_2019');
        $rawData2024 = $this->countTable('master_suara_partai_2024');

        $jumlahRekap2019 = $rawData2019;
        $jumlahRekap2024 = $rawData2024;
        $jumlahPerbandingan = $totalClusterKMeans;

        return view('admin.dashboard', compact(
            'countPartai',
            'countKecamatan',
            'countKelurahan',
            'countTps',
            'countUsers',
            'roleAdmin',
            'rolePimpinan',
            'totalSuara2019',
            'totalSuara2024',
            'selisihTotalSuara',
            'persentasePerubahan',
            'statusPerubahan',
            'jumlahPartaiNaik',
            'jumlahPartaiTurun',
            'jumlahPartaiTetap',
            'partaiKenaikanTertinggi',
            'partaiPenurunanTertinggi',
            'topTrenPartai',
            'nilaiMaksimumTren',
            'analisisKMeans',
            'clusterKMeans',
            'totalClusterKMeans',
            'metrikKMeans',
            'cluster2019',
            'cluster2024',
            'totalCluster2019',
            'totalCluster2024',
            'rawData2019',
            'rawData2024',
            'jumlahRekap2019',
            'jumlahRekap2024',
            'jumlahPerbandingan',
            'trenPartai'
        ));
    }

    /**
     * DASHBOARD PIMPINAN
     */
    public function pimpinan()
    {
        $countPartai = $this->countTable('master_partai');
        $jumlahKecamatan = $this->countTable('master_kecamatan');
        $jumlahKelurahan = $this->countTable('master_kelurahan');
        $jumlahTps = $this->countTable('master_tps');

        $totalSuara2019 = $this->getTotalSuara('master_suara_partai_2019');
        $totalSuara2024 = $this->getTotalSuara('master_suara_partai_2024');

        $selisihTotalSuara = $totalSuara2024 - $totalSuara2019;

        $persentasePerubahan = $totalSuara2019 > 0
            ? round(($selisihTotalSuara / $totalSuara2019) * 100, 2)
            : 0;

        /*
         * Logika tren sama dengan Dashboard Admin.
         */
        $trenData = $this->buildTrendData();
        $trenPartai = $trenData['trenPartai'];

        $jumlahPartai = $trenPartai->count();

        $jumlahMeningkat = $trenPartai
            ->where('selisih_share', '>', 0)
            ->count();

        $jumlahMenurun = $trenPartai
            ->where('selisih_share', '<', 0)
            ->count();

        $jumlahTetap = $trenPartai
            ->where('selisih_share', '=', 0)
            ->count();

        $partaiKenaikanTertinggi = $trenPartai
            ->where('selisih_share', '>', 0)
            ->sortByDesc('selisih_share')
            ->first();

        $partaiPenurunanTertinggi = $trenPartai
            ->where('selisih_share', '<', 0)
            ->sortBy('selisih_share')
            ->first();

        $trenPartaiChart = $trenPartai
            ->sortByDesc(fn ($item) => abs($item['selisih_share']))
            ->take(6)
            ->values();

        $nilaiMaksimumTren = max(
            0.0001,
            (float) ($trenPartaiChart->max(
                fn ($item) => abs($item['selisih_share'])
            ) ?? 0)
        );

        /*
         * Ringkasan K-Means terbaru.
         */
        $kmeans = $this->getLatestKMeansSummary();

        $analisisKMeans = $kmeans['analisis'];
        $clusterKMeans = $kmeans['cluster'];
        $totalClusterKMeans = $kmeans['total'];
        $metrikKMeans = $kmeans['metrik'];

        // Alias kompatibilitas dengan Blade lama.
        $cluster2019 = $clusterKMeans;
        $cluster2024 = $clusterKMeans;
        $totalCluster2019 = $totalClusterKMeans;
        $totalCluster2024 = $totalClusterKMeans;

        $rawData2019 = $this->countTable('master_suara_partai_2019');
        $rawData2024 = $this->countTable('master_suara_partai_2024');

        $jumlahRekap2019 = $rawData2019;
        $jumlahRekap2024 = $rawData2024;
        $jumlahPerbandingan = $totalClusterKMeans;

        return view('pimpinan.dashboard', compact(
            'countPartai',
            'jumlahKecamatan',
            'jumlahKelurahan',
            'jumlahTps',
            'totalSuara2019',
            'totalSuara2024',
            'selisihTotalSuara',
            'persentasePerubahan',
            'jumlahPartai',
            'jumlahMeningkat',
            'jumlahMenurun',
            'jumlahTetap',
            'partaiKenaikanTertinggi',
            'partaiPenurunanTertinggi',
            'trenPartai',
            'trenPartaiChart',
            'nilaiMaksimumTren',
            'analisisKMeans',
            'clusterKMeans',
            'totalClusterKMeans',
            'metrikKMeans',
            'cluster2019',
            'cluster2024',
            'totalCluster2019',
            'totalCluster2024',
            'rawData2019',
            'rawData2024',
            'jumlahRekap2019',
            'jumlahRekap2024',
            'jumlahPerbandingan'
        ));
    }

    /**
     * MEMBANGUN DATA TREN PROPORSI SUARA
     *
     * Proporsi = suara partai / total suara seluruh partai pada tahun itu x 100.
     * Selisih proporsi = proporsi 2024 - proporsi 2019.
     * Selisih proporsi dinyatakan dalam poin persentase (pp).
     */
    private function buildTrendData(): array
    {
        $rekap2019 = $this->getMasterSuaraPerPartai(
            'master_suara_partai_2019'
        );

        $rekap2024 = $this->getMasterSuaraPerPartai(
            'master_suara_partai_2024'
        );

        $totalSuara2019 = $this->getTotalSuara(
            'master_suara_partai_2019'
        );

        $totalSuara2024 = $this->getTotalSuara(
            'master_suara_partai_2024'
        );

        $partaiMap = $this->getPartaiMap();

        $idsPembanding = collect(self::PARTAI_COMPARABLE)
            ->map(fn ($id) => (string) $id)
            ->filter(fn ($id) =>
                $rekap2019->has($id)
                && $rekap2024->has($id)
                && $partaiMap->has($id)
            )
            ->values();

        $trenPartai = $idsPembanding
            ->map(function ($idPartai) use (
                $rekap2019,
                $rekap2024,
                $partaiMap,
                $totalSuara2019,
                $totalSuara2024
            ) {
                $data2019 = $rekap2019->get((string) $idPartai);
                $data2024 = $rekap2024->get((string) $idPartai);
                $partai = $partaiMap->get((string) $idPartai, []);

                $suara2019 = (int) ($data2019['total_suara'] ?? 0);
                $suara2024 = (int) ($data2024['total_suara'] ?? 0);

                $share2019 = $totalSuara2019 > 0
                    ? ($suara2019 / $totalSuara2019) * 100
                    : null;

                $share2024 = $totalSuara2024 > 0
                    ? ($suara2024 / $totalSuara2024) * 100
                    : null;

                if ($share2019 === null || $share2024 === null) {
                    return null;
                }

                $selisihShare = $share2024 - $share2019;
                $selisihSuara = $suara2024 - $suara2019;

                $persentasePerubahanSuara = $suara2019 > 0
                    ? round(($selisihSuara / $suara2019) * 100, 2)
                    : null;

                $statusTren = match (true) {
                    $selisihShare > 0.000001 => 'Naik',
                    $selisihShare < -0.000001 => 'Turun',
                    default => 'Tetap',
                };

                return [
                    'id_partai' => (string) $idPartai,
                    'singkatan' => $partai['singkatan'] ?? 'Partai ' . $idPartai,
                    'nama_lengkap' => $partai['nama_lengkap']
                        ?? ($partai['singkatan'] ?? 'Partai ' . $idPartai),

                    // Jumlah suara tetap tersedia sebagai informasi pendukung.
                    'suara_2019' => $suara2019,
                    'suara_2024' => $suara2024,
                    'selisih_suara' => $selisihSuara,
                    'persentase_perubahan_suara' => $persentasePerubahanSuara,

                    // Data utama untuk analisis tren.
                    'share_2019' => round($share2019, 4),
                    'share_2024' => round($share2024, 4),
                    'selisih_share' => round($selisihShare, 4),
                    'status_tren_share' => $statusTren,

                    /*
                     * Alias lama tetap disediakan agar sebagian Blade
                     * yang belum diperbarui tidak langsung kehilangan data.
                     * Untuk tren, gunakan selisih_share.
                     */
                    'selisih' => $selisihSuara,
                    'persentase' => $persentasePerubahanSuara,
                    'status' => $selisihSuara > 0
                        ? 'Naik'
                        : ($selisihSuara < 0 ? 'Turun' : 'Tetap'),
                ];
            })
            ->filter()
            ->values();

        return [
            'trenPartai' => $trenPartai,
            'totalSuara2019' => $totalSuara2019,
            'totalSuara2024' => $totalSuara2024,
        ];
    }

    /**
     * TOTAL SUARA DARI TABEL SUMBER.
     */
    private function getTotalSuara(string $table): int
    {
        if (!Schema::hasTable($table)) {
            return 0;
        }

        $column = $this->findColumn(
            $table,
            ['jumlah_suara', 'total_suara']
        );

        if (!$column) {
            return 0;
        }

        return (int) DB::table($table)->sum($column);
    }

    /**
     * REKAP JUMLAH SUARA PER PARTAI.
     */
    private function getMasterSuaraPerPartai(string $table): Collection
    {
        if (!Schema::hasTable($table)) {
            return collect();
        }

        $idColumn = $this->findColumn(
            $table,
            ['id_partai', 'partai_id']
        );

        $suaraColumn = $this->findColumn(
            $table,
            ['jumlah_suara', 'total_suara']
        );

        if (!$idColumn || !$suaraColumn) {
            return collect();
        }

        return DB::table($table)
            ->select(
                $idColumn,
                DB::raw("SUM(`{$suaraColumn}`) AS total_suara")
            )
            ->whereIn($idColumn, self::PARTAI_COMPARABLE)
            ->groupBy($idColumn)
            ->get()
            ->mapWithKeys(function ($item) use ($idColumn) {
                $id = (string) $item->{$idColumn};

                return [
                    $id => [
                        'id_partai' => $id,
                        'total_suara' => (int) $item->total_suara,
                    ],
                ];
            });
    }

    /**
     * PETA DATA PARTAI.
     */
    private function getPartaiMap(): Collection
    {
        $table = 'master_partai';

        if (!Schema::hasTable($table)) {
            return collect();
        }

        $idColumn = $this->findColumn(
            $table,
            ['id', 'id_partai']
        );

        $singkatanColumn = $this->findColumn(
            $table,
            [
                'singkatan_parpol',
                'singkatan',
                'nama_singkat',
                'kode_partai',
            ]
        );

        $namaColumn = $this->findColumn(
            $table,
            [
                'nama_lengkap_parpol',
                'nama_lengkap',
                'nama_partai',
                'nama',
            ]
        );

        if (!$idColumn) {
            return collect();
        }

        $columns = [$idColumn];

        if ($singkatanColumn) {
            $columns[] = $singkatanColumn;
        }

        if ($namaColumn) {
            $columns[] = $namaColumn;
        }

        return DB::table($table)
            ->select($columns)
            ->get()
            ->mapWithKeys(function ($item) use (
                $idColumn,
                $singkatanColumn,
                $namaColumn
            ) {
                $id = (string) $item->{$idColumn};

                return [
                    $id => [
                        'singkatan' => $singkatanColumn
                            ? $item->{$singkatanColumn}
                            : null,
                        'nama_lengkap' => $namaColumn
                            ? $item->{$namaColumn}
                            : null,
                    ],
                ];
            });
    }

    /**
     * RINGKASAN K-MEANS TERBARU.
     */
    private function getLatestKMeansSummary(): array
    {
        $summary = [
            'tinggi' => 0,
            'rendah' => 0,
        ];

        $emptyResult = [
            'analisis' => null,
            'cluster' => $summary,
            'total' => 0,
            'metrik' => [
                'k' => null,
                'jumlah_data' => 0,
                'silhouette' => null,
                'davies_bouldin' => null,
                'calinski_harabasz' => null,
            ],
        ];

        if (
            !Schema::hasTable('analisis_clustering')
            || !Schema::hasTable('hasil_clustering')
        ) {
            return $emptyResult;
        }

        $analisisIdColumn = $this->findColumn(
            'analisis_clustering',
            ['id', 'analisis_id']
        );

        $hasilAnalisisIdColumn = $this->findColumn(
            'hasil_clustering',
            ['analisis_id']
        );

        $kategoriColumn = $this->findColumn(
            'hasil_clustering',
            [
                'kategori_cluster',
                'kategori',
                'label_cluster',
                'nama_cluster',
            ]
        );

        if (!$analisisIdColumn || !$hasilAnalisisIdColumn || !$kategoriColumn) {
            return $emptyResult;
        }

        $analisisTerakhir = DB::table('analisis_clustering')
            ->orderByDesc($analisisIdColumn)
            ->first();

        if (!$analisisTerakhir) {
            return $emptyResult;
        }

        $analisisId = $analisisTerakhir->{$analisisIdColumn};

        $kategoriList = DB::table('hasil_clustering')
            ->where($hasilAnalisisIdColumn, $analisisId)
            ->pluck($kategoriColumn);

        foreach ($kategoriList as $kategori) {
            $kategoriNormal = strtolower(trim((string) $kategori));

            if (str_contains($kategoriNormal, 'tinggi')) {
                $summary['tinggi']++;
            } elseif (str_contains($kategoriNormal, 'rendah')) {
                $summary['rendah']++;
            }
        }

        $totalHasil = $kategoriList->count();

        $kColumn = $this->findColumn(
            'analisis_clustering',
            ['k', 'jumlah_cluster', 'n_clusters', 'k_cluster']
        );

        $jumlahDataColumn = $this->findColumn(
            'analisis_clustering',
            [
                'jumlah_data',
                'jumlah_observasi',
                'total_data',
                'n_observasi',
            ]
        );

        $silhouetteColumn = $this->findColumn(
            'analisis_clustering',
            [
                'silhouette_score',
                'silhouette',
                'nilai_silhouette',
            ]
        );

        $dbiColumn = $this->findColumn(
            'analisis_clustering',
            [
                'davies_bouldin_score',
                'davies_bouldin',
                'dbi',
                'nilai_dbi',
            ]
        );

        $chColumn = $this->findColumn(
            'analisis_clustering',
            [
                'calinski_harabasz_score',
                'calinski_harabasz',
                'ch_score',
                'nilai_ch',
            ]
        );

        $metrik = [
            'k' => $kColumn
                ? $analisisTerakhir->{$kColumn}
                : null,

            'jumlah_data' => $jumlahDataColumn
                ? (int) $analisisTerakhir->{$jumlahDataColumn}
                : $totalHasil,

            'silhouette' => $silhouetteColumn
                ? $analisisTerakhir->{$silhouetteColumn}
                : null,

            'davies_bouldin' => $dbiColumn
                ? $analisisTerakhir->{$dbiColumn}
                : null,

            'calinski_harabasz' => $chColumn
                ? $analisisTerakhir->{$chColumn}
                : null,
        ];

        return [
            'analisis' => $analisisTerakhir,
            'cluster' => $summary,
            'total' => $totalHasil,
            'metrik' => $metrik,
        ];
    }

    /**
     * HITUNG BARIS TABEL.
     */
    private function countTable(string $table): int
    {
        if (!Schema::hasTable($table)) {
            return 0;
        }

        return (int) DB::table($table)->count();
    }

    /**
     * HITUNG PENGGUNA BERDASARKAN ROLE.
     */
    private function countUsersByRole(string $role): int
    {
        if (
            !Schema::hasTable('users')
            || !Schema::hasColumn('users', 'role')
        ) {
            return 0;
        }

        return (int) DB::table('users')
            ->where('role', $role)
            ->count();
    }

    /**
     * CARI NAMA KOLOM YANG TERSEDIA.
     */
    private function findColumn(
        string $table,
        array $candidates
    ): ?string {
        if (!Schema::hasTable($table)) {
            return null;
        }

        foreach ($candidates as $column) {
            if (Schema::hasColumn($table, $column)) {
                return $column;
            }
        }

        return null;
    }
}
