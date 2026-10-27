<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AnalisisClustering;
use App\Models\HasilClustering;
use App\Models\MasterPartai;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BerandaController extends Controller
{
    /**
     * Menampilkan beranda publik dengan data terbaru.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | REKAP SUARA PER PARTAI
        |--------------------------------------------------------------------------
        | Data 2024 dijumlahkan dari seluruh TPS.
        | Total suara setiap tahun digunakan sebagai pembagi pangsa suara.
        */

        $rekap2019 = DB::table('master_suara_partai_2019')
            ->select('id_partai')
            ->selectRaw('SUM(jumlah_suara) AS total_suara')
            ->groupBy('id_partai')
            ->get()
            ->keyBy('id_partai');

        $rekap2024 = DB::table('master_suara_partai_2024')
            ->select('id_partai')
            ->selectRaw('SUM(jumlah_suara) AS total_suara')
            ->groupBy('id_partai')
            ->get()
            ->keyBy('id_partai');

        $idsPartai2019 = $rekap2019->keys();
        $idsPartai2024 = $rekap2024->keys();

        /*
        |--------------------------------------------------------------------------
        | TOTAL SUARA SELURUH PARTAI
        |--------------------------------------------------------------------------
        | Menggunakan jumlah suara seluruh partai pada masing-masing tahun,
        | bukan rata-rata persentase suara per kecamatan.
        */

        $totalSuara2019 = (int) $rekap2019->sum('total_suara');
        $totalSuara2024 = (int) $rekap2024->sum('total_suara');

        /*
        |--------------------------------------------------------------------------
        | PARTAI YANG DAPAT DIBANDINGKAN
        |--------------------------------------------------------------------------
        | Hanya partai yang memiliki data pada kedua periode yang digunakan
        | untuk menghitung tren kenaikan dan penurunan.
        */

        $idsPartaiPembanding = $idsPartai2019
            ->intersect($idsPartai2024)
            ->values();

        $partaiMap = MasterPartai::query()
            ->whereIn(
                'id',
                $idsPartai2019
                    ->merge($idsPartai2024)
                    ->unique()
                    ->values()
            )
            ->get()
            ->keyBy('id');

        /*
        |--------------------------------------------------------------------------
        | ANALISIS CLUSTERING TERBARU
        |--------------------------------------------------------------------------
        */

        $analisisTerbaru = AnalisisClustering::query()
            ->latest('id')
            ->first();

        $jumlahHasilClustering = 0;

        if ($analisisTerbaru) {
            $jumlahHasilClustering = HasilClustering::query()
                ->where('analisis_id', $analisisTerbaru->id)
                ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | STATISTIK BERANDA
        |--------------------------------------------------------------------------
        */

        $stats = [
            'partai_2019' => $idsPartai2019->count(),
            'partai_2024' => $idsPartai2024->count(),
            'cluster' => 2,
            'periode' => 2,
            'dapat_dibandingkan' => $idsPartaiPembanding->count(),
        ];

        /*
        |--------------------------------------------------------------------------
        | RINGKASAN TREN PANGSA SUARA
        |--------------------------------------------------------------------------
        | Pangsa suara = suara partai / total suara seluruh partai x 100.
        | Perubahan pangsa suara dinyatakan dalam poin persentase (pp).
        |
        | Selisih jumlah suara dan persentase perubahan jumlah suara tetap
        | disediakan untuk kompatibilitas dengan Blade yang lama.
        */

        $dataTren = $idsPartaiPembanding
            ->map(function ($idPartai) use (
                $rekap2019,
                $rekap2024,
                $partaiMap,
                $totalSuara2019,
                $totalSuara2024
            ) {
                $data2019 = $rekap2019->get($idPartai);
                $data2024 = $rekap2024->get($idPartai);

                if (!$data2019 || !$data2024) {
                    return null;
                }

                $partai = $partaiMap->get($idPartai);

                if (!$partai) {
                    return null;
                }

                $suara2019 = (int) $data2019->total_suara;
                $suara2024 = (int) $data2024->total_suara;

                /*
                 * Tren tidak dihitung jika total suara salah satu periode
                 * tidak valid atau tidak tersedia.
                 */
                if ($totalSuara2019 <= 0 || $totalSuara2024 <= 0) {
                    return null;
                }

                $share2019 = ($suara2019 / $totalSuara2019) * 100;
                $share2024 = ($suara2024 / $totalSuara2024) * 100;

                $selisihShare = $share2024 - $share2019;

                // Perubahan jumlah suara, dipertahankan untuk kompatibilitas.
                $selisihSuara = $suara2024 - $suara2019;

                $persentasePerubahanSuara = $suara2019 > 0
                    ? round(($selisihSuara / $suara2019) * 100, 2)
                    : null;

                if ($selisihShare > 0.000001) {
                    $statusTrenShare = 'NAIK';
                } elseif ($selisihShare < -0.000001) {
                    $statusTrenShare = 'TURUN';
                } else {
                    $statusTrenShare = 'TETAP';
                }

                return (object) [
                    'partai' => $partai,

                    // Data jumlah suara.
                    'suara_2019' => $suara2019,
                    'suara_2024' => $suara2024,
                    'selisih' => $selisihSuara,
                    'persentase_perubahan' => $persentasePerubahanSuara,

                    // Data pangsa suara.
                    'share_2019' => round($share2019, 4),
                    'share_2024' => round($share2024, 4),
                    'selisih_share' => round($selisihShare, 4),
                    'status_tren_share' => $statusTrenShare,
                ];
            })
            ->filter()
            ->values();

        /*
        |--------------------------------------------------------------------------
        | KENAIKAN TERTINGGI
        |--------------------------------------------------------------------------
        | Diurutkan berdasarkan kenaikan pangsa suara terbesar,
        | bukan berdasarkan kenaikan jumlah suara.
        */

        $naikTertinggi = $dataTren
            ->filter(function ($item) {
                return $item->selisih_share > 0;
            })
            ->sortByDesc('selisih_share')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | PENURUNAN TERDALAM
        |--------------------------------------------------------------------------
        | Diurutkan berdasarkan penurunan pangsa suara terbesar.
        */

        $turunTerdalam = $dataTren
            ->filter(function ($item) {
                return $item->selisih_share < 0;
            })
            ->sortBy('selisih_share')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | TIGA PEROLEHAN SUARA TERTINGGI
        |--------------------------------------------------------------------------
        | Bagian perbandingan tetap menggunakan jumlah suara.
        | Pangsa suara ditambahkan sebagai informasi pendukung.
        */

        $topPartai2019 = $this->buatPeringkatPartai(
            $rekap2019,
            $partaiMap,
            $totalSuara2019
        );

        $topPartai2024 = $this->buatPeringkatPartai(
            $rekap2024,
            $partaiMap,
            $totalSuara2024
        );

        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE BERANDA
        |--------------------------------------------------------------------------
        */

        return view('beranda.index', compact(
            'stats',
            'naikTertinggi',
            'turunTerdalam',
            'topPartai2019',
            'topPartai2024',
            'analisisTerbaru',
            'jumlahHasilClustering'
        ));
    }

    /**
     * Membuat peringkat tiga partai dengan total suara tertinggi.
     *
     * Pemeringkatan tetap berdasarkan jumlah suara.
     * Pangsa suara dihitung sebagai informasi tambahan.
     */
    private function buatPeringkatPartai(
        Collection $rekap,
        Collection $partaiMap,
        int $totalSuara
    ): Collection {
        return $rekap
            ->map(function ($item, $idPartai) use (
                $partaiMap,
                $totalSuara
            ) {
                $partai = $partaiMap->get($idPartai);

                if (!$partai) {
                    return null;
                }

                $jumlahSuara = (int) $item->total_suara;

                $share = $totalSuara > 0
                    ? ($jumlahSuara / $totalSuara) * 100
                    : null;

                return (object) [
                    'partai' => $partai,
                    'total_suara' => $jumlahSuara,
                    'share' => $share !== null
                        ? round($share, 4)
                        : null,
                ];
            })
            ->filter()
            ->sortByDesc('total_suara')
            ->take(3)
            ->values();
    }
}
