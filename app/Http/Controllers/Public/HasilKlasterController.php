<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\HasilClustering;
use App\Models\AnalisisClustering;
use Illuminate\Support\Facades\DB;

class HasilKlasterController extends Controller
{
    /**
     * Menampilkan hasil clustering terbaru
     * untuk perbandingan Pemilu 2019 dan 2024.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Ambil analisis clustering terbaru
        |--------------------------------------------------------------------------
        */

        $analisis = AnalisisClustering::latest('id')->first();

        /*
        |--------------------------------------------------------------------------
        | Jika belum ada hasil clustering
        |--------------------------------------------------------------------------
        */

        if (!$analisis) {
            return view('hasil-klaster.index', [
                'clusters' => collect(),
                'tahun' => '2019-2024',
                'analisis' => null,
                'ringkasanKlaster' => [],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil hasil clustering
        |--------------------------------------------------------------------------
        |
        | CATATAN:
        | logo_url BUKAN kolom database.
        |
        | Logo partai disediakan melalui accessor pada model MasterPartai.
        | Karena itu kita tidak boleh menulis:
        |
        | master_partai.logo_url
        |
        | di dalam select().
        |
        | Relasi "partai" akan digunakan agar Blade dapat mengakses:
        |
        | $cluster->partai->logo_url
        |
        |--------------------------------------------------------------------------
        */

        $clusters = HasilClustering::query()
            ->with([
                'partai',
            ])
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
                $analisis->id
            )
            ->select([
                'hasil_clustering.id',
                'hasil_clustering.id_kecamatan',
                'hasil_clustering.id_partai',
                'hasil_clustering.share_2019',
                'hasil_clustering.share_2024',
                'hasil_clustering.selisih_share',
                'hasil_clustering.cluster',
                'hasil_clustering.kategori_cluster',
                'hasil_clustering.jarak_centroid',
                'hasil_clustering.status_tren',

                'master_kecamatan.nama_kecamatan',

                'master_partai.nama_lengkap_parpol',
                'master_partai.singkatan_parpol',
            ])
            ->orderByRaw("
                CASE
                    WHEN hasil_clustering.kategori_cluster = 'Tinggi' THEN 1
                    WHEN hasil_clustering.kategori_cluster = 'Rendah' THEN 2
                    ELSE 3
                END
            ")
            ->orderBy('master_kecamatan.id')
            ->orderBy('master_partai.id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ringkasan setiap klaster
        |--------------------------------------------------------------------------
        |
        | Karena fitur K-Means terdiri dari:
        |
        | 1. share_2019
        | 2. share_2024
        |
        | maka karakteristik klaster ditampilkan menggunakan rata-rata
        | kedua variabel tersebut.
        |
        */

        $ringkasanKlaster = $clusters
            ->groupBy('kategori_cluster')
            ->map(function ($items, $kategori) {

                return [
                    'kategori' => $kategori,
                    'jumlah' => $items->count(),

                    'share_2019' => $items->avg(function ($item) {
                        return (float) $item->share_2019;
                    }),

                    'share_2024' => $items->avg(function ($item) {
                        return (float) $item->share_2024;
                    }),

                    'selisih_share' => $items->avg(function ($item) {
                        return (float) $item->selisih_share;
                    }),
                ];
            })
            ->sortBy(function ($item) {
                return match ($item['kategori']) {
                    'Tinggi' => 1,
                    'Rendah' => 2,
                    default => 3,
                };
            })
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Pastikan urutan ringkasan konsisten
        |--------------------------------------------------------------------------
        */

        $urutanKategori = [
            'Tinggi',
            'Rendah',
        ];

        usort($ringkasanKlaster, function ($a, $b) use ($urutanKategori) {

            $posisiA = array_search(
                $a['kategori'],
                $urutanKategori,
                true
            );

            $posisiB = array_search(
                $b['kategori'],
                $urutanKategori,
                true
            );

            $posisiA = $posisiA === false ? 999 : $posisiA;
            $posisiB = $posisiB === false ? 999 : $posisiB;

            return $posisiA <=> $posisiB;
        });

        /*
        |--------------------------------------------------------------------------
        | Kirim data ke view
        |--------------------------------------------------------------------------
        */

        return view('hasil-klaster.index', [
            'clusters' => $clusters,
            'tahun' => '2019-2024',
            'analisis' => $analisis,
            'ringkasanKlaster' => $ringkasanKlaster,
        ]);
    }
}
