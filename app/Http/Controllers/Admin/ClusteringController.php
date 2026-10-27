<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterPartai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Throwable;

class ClusteringController extends Controller
{
    /**
     * ============================================================
     * HALAMAN UTAMA CLUSTERING
     * ============================================================
     *
     * Menampilkan hasil analisis clustering terbaru.
     *
     * K-Means menggunakan:
     * - share_2019
     * - share_2024
     * - K = 2
     *
     * Hasil disimpan pada:
     * - analisis_clustering
     * - hasil_clustering
     */
    public function index(Request $request)
    {
        $analisisTerakhir = DB::table('analisis_clustering')
            ->orderByDesc('id')
            ->first();

        $data = collect();

        $stats = [
            'tinggi' => 0,
            'rendah' => 0,
        ];

        if ($analisisTerakhir) {
            $data = DB::table('hasil_clustering')
                ->join(
                    'master_kecamatan',
                    'hasil_clustering.id_kecamatan',
                    '=',
                    'master_kecamatan.id'
                )
                ->join(
                    'master_partai',
                    'hasil_clustering.id_partai',
                    '=',
                    'master_partai.id'
                )
                ->where(
                    'hasil_clustering.analisis_id',
                    $analisisTerakhir->id
                )
                ->select(
                    'hasil_clustering.*',
                    'master_kecamatan.nama_kecamatan',
                    'master_partai.nama_lengkap_parpol',
                    'master_partai.singkatan_parpol'
                )
                ->orderByRaw("
                    CASE
                        WHEN hasil_clustering.kategori_cluster = 'Tinggi' THEN 1
                        WHEN hasil_clustering.kategori_cluster = 'Rendah' THEN 2
                        ELSE 3
                    END
                ")
                ->orderBy('master_kecamatan.nama_kecamatan')
                ->orderBy('master_partai.id')
                ->get();

            /*
             * ========================================================
             * HUBUNGKAN DATA HASIL DENGAN MODEL MASTER PARTAI
             * ========================================================
             *
             * $data berasal dari DB::table(), sehingga setiap item
             * berupa stdClass dan tidak memiliki relasi Eloquent.
             *
             * Model MasterPartai memiliki accessor:
             *     getLogoUrlAttribute()
             *
             * Karena itu kita ambil model MasterPartai berdasarkan
             * id_partai, kemudian memasukkannya ke masing-masing
             * item sebagai property "partai".
             *
             * Dengan demikian Blade dapat menggunakan:
             *
             *     $item->partai->logo_url
             *
             * tanpa menganggap logo_url sebagai kolom database.
             */
            $partaiIds = $data
                ->pluck('id_partai')
                ->filter()
                ->unique()
                ->values();

            if ($partaiIds->isNotEmpty()) {
                $partaiMap = MasterPartai::query()
                    ->whereIn('id', $partaiIds)
                    ->get()
                    ->keyBy('id');

                $data->each(function ($item) use ($partaiMap) {
                    $item->partai = $partaiMap->get(
                        (int) $item->id_partai
                    );
                });
            }

            $stats = [
                'tinggi' => $data
                    ->where('kategori_cluster', 'Tinggi')
                    ->count(),

                'rendah' => $data
                    ->where('kategori_cluster', 'Rendah')
                    ->count(),
            ];
        }

        $tahun = '2019-2024';

        return view(
            'admin.clustering.index',
            compact(
                'data',
                'tahun',
                'stats',
                'analisisTerakhir'
            )
        );
    }


    /**
     * ============================================================
     * JALANKAN K-MEANS
     * ============================================================
     *
     * MODEL FINAL:
     *
     * Unit analisis:
     * kecamatan × partai
     *
     * Fitur:
     * share_2019
     * share_2024
     *
     * K:
     * 2
     *
     * Scaling:
     * Tidak ada
     */
    public function jalankan(Request $request)
    {
        set_time_limit(600);
        ini_set('memory_limit', '1024M');

        /*
         * K sudah ditetapkan berdasarkan hasil validasi.
         * Tidak menerima K bebas dari form.
         */
        $k = 2;

        try {

            /*
             * ========================================================
             * 1. AMBIL DATA 2019
             * ========================================================
             *
             * Data 2019 sudah berada pada level kecamatan × partai.
             */
            $data2019 = DB::table(
                'master_suara_partai_2019'
            )
                ->select(
                    'id_kecamatan',
                    'id_partai',
                    DB::raw(
                        'SUM(jumlah_suara) AS suara_2019'
                    )
                )
                ->groupBy(
                    'id_kecamatan',
                    'id_partai'
                )
                ->get();

            if ($data2019->isEmpty()) {
                return back()->with(
                    'error',
                    'Data suara 2019 belum tersedia di database.'
                );
            }


            /*
             * ========================================================
             * 2. AMBIL DATA 2024
             * ========================================================
             *
             * Data 2024 berasal dari TPS.
             *
             * SUM otomatis:
             *
             * TPS → kecamatan × partai
             */
            $data2024 = DB::table(
                'master_suara_partai_2024'
            )
                ->select(
                    'id_kecamatan',
                    'id_partai',
                    DB::raw(
                        'SUM(jumlah_suara) AS suara_2024'
                    )
                )
                ->groupBy(
                    'id_kecamatan',
                    'id_partai'
                )
                ->get();

            if ($data2024->isEmpty()) {
                return back()->with(
                    'error',
                    'Data suara 2024 belum tersedia di database.'
                );
            }


            /*
             * ========================================================
             * 3. TOTAL SUARA PER KECAMATAN
             * ========================================================
             *
             * Denominator share:
             *
             * seluruh partai pada kecamatan dan tahun tersebut.
             */
            $total2019 = DB::table(
                'master_suara_partai_2019'
            )
                ->select(
                    'id_kecamatan',
                    DB::raw(
                        'SUM(jumlah_suara) AS total_suara_2019'
                    )
                )
                ->groupBy('id_kecamatan')
                ->get()
                ->keyBy('id_kecamatan');


            $total2024 = DB::table(
                'master_suara_partai_2024'
            )
                ->select(
                    'id_kecamatan',
                    DB::raw(
                        'SUM(jumlah_suara) AS total_suara_2024'
                    )
                )
                ->groupBy('id_kecamatan')
                ->get()
                ->keyBy('id_kecamatan');


            /*
             * ========================================================
             * 4. PARTAI COMPARABLE
             * ========================================================
             *
             * Hanya partai yang tersedia pada 2019 dan 2024.
             *
             * Total = 14 partai.
             */
            $partaiComparable = [
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
             * ========================================================
             * 5. INDEX DATA 2019 DAN 2024
             * ========================================================
             */
            $index2019 = [];

            foreach ($data2019 as $row) {
                $key =
                    (int) $row->id_kecamatan
                    . '_'
                    . (int) $row->id_partai;

                $index2019[$key] =
                    (int) $row->suara_2019;
            }


            $index2024 = [];

            foreach ($data2024 as $row) {
                $key =
                    (int) $row->id_kecamatan
                    . '_'
                    . (int) $row->id_partai;

                $index2024[$key] =
                    (int) $row->suara_2024;
            }


            /*
             * ========================================================
             * 6. BENTUK DATASET 70 OBSERVASI
             * ========================================================
             */
            $dataset = [];

            $idKecamatanList = [
                1,
                2,
                3,
                4,
                5,
            ];


            foreach ($idKecamatanList as $idKecamatan) {

                foreach (
                    $partaiComparable
                    as $idPartai
                ) {

                    $key =
                        $idKecamatan
                        . '_'
                        . $idPartai;


                    /*
                     * Wajib ada pada kedua tahun.
                     */
                    if (
                        !array_key_exists(
                            $key,
                            $index2019
                        ) ||
                        !array_key_exists(
                            $key,
                            $index2024
                        )
                    ) {
                        continue;
                    }


                    $suara2019 =
                        $index2019[$key];

                    $suara2024 =
                        $index2024[$key];


                    $totalKecamatan2019 =
                        (int) (
                            $total2019[$idKecamatan]
                            ->total_suara_2019
                            ?? 0
                        );


                    $totalKecamatan2024 =
                        (int) (
                            $total2024[$idKecamatan]
                            ->total_suara_2024
                            ?? 0
                        );


                    if (
                        $totalKecamatan2019 <= 0 ||
                        $totalKecamatan2024 <= 0
                    ) {
                        throw new \RuntimeException(
                            "Total suara kecamatan {$idKecamatan} "
                                . "tidak valid."
                        );
                    }


                    $share2019 =
                        (
                            $suara2019
                            / $totalKecamatan2019
                        ) * 100;


                    $share2024 =
                        (
                            $suara2024
                            / $totalKecamatan2024
                        ) * 100;


                    $selisihShare =
                        $share2024
                        - $share2019;


                    $dataset[] = [
                        'id_kecamatan' =>
                        $idKecamatan,

                        'id_partai' =>
                        $idPartai,

                        'share_2019' =>
                        round(
                            $share2019,
                            4
                        ),

                        'share_2024' =>
                        round(
                            $share2024,
                            4
                        ),

                        'selisih_share' =>
                        round(
                            $selisihShare,
                            4
                        ),
                    ];
                }
            }


            /*
             * ========================================================
             * 7. VALIDASI DATASET
             * ========================================================
             *
             * Harus:
             *
             * 5 kecamatan × 14 partai = 70.
             */
            $jumlahObservasi = count(
                $dataset
            );


            if ($jumlahObservasi !== 70) {
                throw new \RuntimeException(
                    "Dataset clustering tidak valid. "
                        . "Diharapkan 70 observasi, "
                        . "tetapi terbentuk {$jumlahObservasi} observasi."
                );
            }


            /*
             * Pastikan tidak ada duplicate:
             * kecamatan × partai
             */
            $uniqueKeys = [];

            foreach ($dataset as $row) {

                $key =
                    $row['id_kecamatan']
                    . '_'
                    . $row['id_partai'];


                if (
                    isset($uniqueKeys[$key])
                ) {
                    throw new \RuntimeException(
                        "Duplicate observasi ditemukan: {$key}."
                    );
                }


                $uniqueKeys[$key] = true;
            }


            /*
             * ========================================================
             * 8. PYTHON
             * ========================================================
             */
            $pythonPath = base_path(
                '.venv/Scripts/python.exe'
            );


            $scriptPath = base_path(
                'kmeans_engine.py'
            );


            if (!file_exists($pythonPath)) {
                return back()->with(
                    'error',
                    'Python virtual environment tidak ditemukan di: '
                        . $pythonPath
                );
            }


            if (!file_exists($scriptPath)) {
                return back()->with(
                    'error',
                    'File kmeans_engine.py tidak ditemukan.'
                );
            }


            $process = new Process([
                $pythonPath,
                $scriptPath,
                (string) $k,
            ]);


            $process->setInput(
                json_encode(
                    $dataset,
                    JSON_THROW_ON_ERROR
                )
            );


            $process->setTimeout(600);

            $process->mustRun();


            $output = json_decode(
                trim(
                    $process->getOutput()
                ),
                true,
                512,
                JSON_THROW_ON_ERROR
            );


            if (
                isset($output['error'])
            ) {
                throw new \RuntimeException(
                    $output['error']
                );
            }


            $silhouetteScore =
                (float) (
                    $output['silhouette_score']
                    ?? 0
                );


            $results =
                $output['results']
                ?? [];


            if (
                empty($results)
            ) {
                throw new \RuntimeException(
                    'Python tidak menghasilkan data clustering.'
                );
            }


            /*
             * Harus tetap 70 hasil.
             */
            if (
                count($results) !== 70
            ) {
                throw new \RuntimeException(
                    'Jumlah hasil clustering dari Python '
                        . 'tidak sesuai. Diharapkan 70 hasil, '
                        . 'diterima '
                        . count($results)
                        . '.'
                );
            }


            /*
             * ========================================================
             * 9. SIMPAN ANALISIS + 70 HASIL
             * ========================================================
             */
            DB::transaction(
                function () use (
                    $k,
                    $jumlahObservasi,
                    $silhouetteScore,
                    $results
                ) {

                    /*
                     * Simpan satu record proses analisis.
                     */
                    $analisisId =
                        DB::table(
                            'analisis_clustering'
                        )->insertGetId([

                            'tahun_awal' =>
                            2019,

                            'tahun_akhir' =>
                            2024,

                            'jumlah_cluster' =>
                            $k,

                            'metode_scaling' =>
                            'Tanpa Scaling',

                            'jumlah_observasi' =>
                            $jumlahObservasi,

                            'silhouette_score' =>
                            round(
                                $silhouetteScore,
                                4
                            ),

                            'status' =>
                            'selesai',

                            'created_at' =>
                            now(),

                            'updated_at' =>
                            now(),
                        ]);


                    /*
                     * Simpan 70 hasil.
                     */
                    $insertHasil = [];


                    foreach (
                        $results
                        as $result
                    ) {

                        $idKecamatan =
                            (int) (
                                $result['id_kecamatan']
                                ?? 0
                            );


                        $idPartai =
                            (int) (
                                $result['id_partai']
                                ?? 0
                            );


                        if (
                            $idKecamatan <= 0 ||
                            $idPartai <= 0
                        ) {
                            throw new \RuntimeException(
                                'ID kecamatan atau partai '
                                    . 'dari Python tidak valid.'
                            );
                        }


                        $share2019 =
                            (float) (
                                $result['share_2019']
                                ?? 0
                            );


                        $share2024 =
                            (float) (
                                $result['share_2024']
                                ?? 0
                            );


                        $selisihShare =
                            (float) (
                                $result['selisih_share']
                                ?? (
                                    $share2024
                                    - $share2019
                                )
                            );


                        if (
                            $selisihShare > 0
                        ) {

                            $statusTren =
                                'Naik';
                        } elseif (
                            $selisihShare < 0
                        ) {

                            $statusTren =
                                'Turun';
                        } else {

                            $statusTren =
                                'Tetap';
                        }


                        $insertHasil[] = [

                            'analisis_id' =>
                            $analisisId,

                            'id_kecamatan' =>
                            $idKecamatan,

                            'id_partai' =>
                            $idPartai,

                            'share_2019' =>
                            round(
                                $share2019,
                                4
                            ),

                            'share_2024' =>
                            round(
                                $share2024,
                                4
                            ),

                            'selisih_share' =>
                            round(
                                $selisihShare,
                                4
                            ),

                            'cluster' =>
                            (int) (
                                $result['cluster']
                                ?? 0
                            ),

                            'kategori_cluster' =>
                            $result['kategori_cluster']
                                ?? 'Lainnya',

                            'jarak_centroid' =>
                            isset(
                                $result['jarak_centroid']
                            )
                                ? round(
                                    (float) $result['jarak_centroid'],
                                    6
                                )
                                : null,

                            'status_tren' =>
                            $statusTren,

                            'created_at' =>
                            now(),

                            'updated_at' =>
                            now(),
                        ];
                    }


                    foreach (
                        array_chunk(
                            $insertHasil,
                            500
                        ) as $chunk
                    ) {

                        DB::table(
                            'hasil_clustering'
                        )->insert(
                            $chunk
                        );
                    }
                }
            );


            /*
             * ========================================================
             * 10. ACTIVITY LOG
             * ========================================================
             */
            DB::table('activity_logs')->insert([

                'user_id' =>
                auth()->id(),

                'activity' =>
                'Menjalankan Clustering',

                'description' =>
                'Menjalankan K-Means clustering '
                    . 'perbandingan Pemilu 2019 dan 2024 '
                    . 'dengan K=2, '
                    . '70 observasi kecamatan-partai, '
                    . 'fitur share_2019 dan share_2024, '
                    . 'tanpa scaling. '
                    . 'Silhouette Score: '
                    . $silhouetteScore
                    . '.',

                'created_at' =>
                now(),

                'updated_at' =>
                now(),
            ]);


            return redirect()
                ->route(
                    'admin.clustering.index'
                )
                ->with([

                    'success' =>
                    'Proses K-Means K=2 '
                        . 'berhasil dijalankan '
                        . 'untuk 70 observasi.',

                    'ssi' =>
                    $silhouetteScore,

                    'k_terakhir' =>
                    $k,

                    'tahun_proses' =>
                    '2019-2024',
                ]);
        } catch (
            ProcessFailedException $e
        ) {

            return back()->with(
                'error',
                'Gagal menjalankan Python. Detail: '
                    . $e->getMessage()
            );
        } catch (
            Throwable $e
        ) {

            return back()->with(
                'error',
                'Terjadi kesalahan saat clustering. Detail: '
                    . $e->getMessage()
            );
        }
    }
}
