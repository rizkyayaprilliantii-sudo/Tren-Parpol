<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterKecamatan;
use App\Models\MasterKelurahan;
use App\Models\MasterPartai;
use App\Models\MasterSuaraPartai2019;
use App\Models\MasterSuaraPartai2024;
use App\Models\MasterTps;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RincianSuaraController extends Controller
{
    /**
     * Menerapkan filter dan pencarian data suara.
     */
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
                            ->orWhere(
                                'nama_lengkap_parpol',
                                'like',
                                "%{$search}%"
                            );
                    })->orWhereHas('kecamatan', function ($k) use ($search) {
                        $k->where('nama_kecamatan', 'like', "%{$search}%");
                    });
                });
            }
        } else {
            if ($kecamatan !== null && $kecamatan !== '') {
                $query->whereHas('tps', function ($q) use ($kecamatan) {
                    $q->where('id_kecamatan', $kecamatan);
                });
            }

            if ($kelurahan !== null && $kelurahan !== '') {
                $query->whereHas('tps', function ($q) use ($kelurahan) {
                    $q->where('id_kelurahan', $kelurahan);
                });
            }

            if ($tps !== null && $tps !== '') {
                $query->whereHas('tps', function ($q) use ($tps) {
                    $q->where('nomor_tps', $tps);
                });
            }

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('partai', function ($p) use ($search) {
                        $p->where('singkatan_parpol', 'like', "%{$search}%")
                            ->orWhere(
                                'nama_lengkap_parpol',
                                'like',
                                "%{$search}%"
                            );
                    })->orWhereHas(
                        'tps.kelurahan',
                        function ($k) use ($search) {
                            $k->where(
                                'nama_kelurahan',
                                'like',
                                "%{$search}%"
                            );
                        }
                    )->orWhereHas(
                        'tps.kecamatan',
                        function ($k) use ($search) {
                            $k->where(
                                'nama_kecamatan',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
                });
            }
        }

        return $query;
    }

    /**
     * Membuat query sesuai tahun dan filter aktif.
     */
    private function makeQuery(string $tahun, Request $request)
    {
        if ($tahun === '2019') {
            return $this->applyFilters(
                MasterSuaraPartai2019::with([
                    'partai',
                    'kecamatan',
                ]),
                $tahun,
                $request
            )
                ->orderBy('id_kecamatan')
                ->orderBy('id_partai');
        }

        return $this->applyFilters(
            MasterSuaraPartai2024::with([
                'partai',
                'tps.kecamatan',
                'tps.kelurahan',
            ]),
            $tahun,
            $request
        )
            ->orderBy('id_tps')
            ->orderBy('id_partai');
    }

    /**
     * Menampilkan rincian perolehan suara.
     */
    public function index(Request $request)
    {
        $tahun = (string) $request->get('tahun', '2024');

        if (!in_array($tahun, ['2019', '2024'], true)) {
            $tahun = '2024';
        }

        $search = trim((string) $request->get('search', ''));

        // ID filter yang dipilih.
        $partaiFilterId = $request->get('partai', '');
        $kecamatanFilterId = $request->get('kecamatan', '');
        $kelurahanFilterId = $request->get('kelurahan', '');
        $tpsFilter = $request->get('tps', '');

        // Daftar untuk dropdown filter.
        $partaiFilter = MasterPartai::orderBy(
            'singkatan_parpol'
        )->get();

        $kecamatanFilter = MasterKecamatan::orderBy(
            'nama_kecamatan'
        )->get();

        if ($tahun === '2024') {
            $kelurahanFilter = MasterKelurahan::query()
                ->when(
                    $kecamatanFilterId !== '',
                    function ($query) use ($kecamatanFilterId) {
                        $query->where(
                            'id_kecamatan',
                            $kecamatanFilterId
                        );
                    }
                )
                ->orderBy('nama_kelurahan')
                ->get();

            $daftarTps = MasterTps::query()
                ->when(
                    $kecamatanFilterId !== '',
                    function ($query) use ($kecamatanFilterId) {
                        $query->where(
                            'id_kecamatan',
                            $kecamatanFilterId
                        );
                    }
                )
                ->when(
                    $kelurahanFilterId !== '',
                    function ($query) use ($kelurahanFilterId) {
                        $query->where(
                            'id_kelurahan',
                            $kelurahanFilterId
                        );
                    }
                )
                ->orderBy('nomor_tps')
                ->get();
        } else {
            $kelurahanFilter = collect();
            $daftarTps = collect();
        }

        // Ambil data sesuai filter aktif.
        $query = $this->makeQuery($tahun, $request);

        $data = (clone $query)
            ->paginate(20)
            ->withQueryString();

        $jumlahData = (clone $query)->count();

        $totalSuara = (clone $query)->sum('jumlah_suara');

        return view(
            'admin.rincian-suara.index',
            compact(
                'data',
                'tahun',
                'search',
                'partaiFilter',
                'partaiFilterId',
                'kecamatanFilter',
                'kecamatanFilterId',
                'kelurahanFilter',
                'kelurahanFilterId',
                'tpsFilter',
                'daftarTps',
                'jumlahData',
                'totalSuara'
            )
        );
    }

    /**
     * Menampilkan form tambah data suara.
     */
    public function create(Request $request)
    {
        $tahun = (string) $request->get('tahun', '2024');

        if (!in_array($tahun, ['2019', '2024'], true)) {
            $tahun = '2024';
        }

        $partai = MasterPartai::orderBy('id')->get();

        if ($tahun === '2019') {
            $kecamatan = MasterKecamatan::orderBy(
                'nama_kecamatan'
            )->get();

            return view(
                'admin.rincian-suara.create',
                compact('tahun', 'partai', 'kecamatan')
            );
        }

        $tps = MasterTps::with([
            'kecamatan',
            'kelurahan',
        ])
            ->orderBy('nomor_tps')
            ->get();

        return view(
            'admin.rincian-suara.create',
            compact('tahun', 'partai', 'tps')
        );
    }

    /**
     * Menyimpan data suara baru.
     */
    public function store(Request $request)
    {
        $tahun = (string) $request->input('tahun');

        if ($tahun === '2019') {
            $validated = $request->validate([
                'tahun' => ['required', Rule::in(['2019'])],
                'id_kecamatan' => [
                    'required',
                    'integer',
                    'exists:master_kecamatan,id',
                ],
                'id_partai' => [
                    'required',
                    'integer',
                    'exists:master_partai,id',
                ],
                'jumlah_suara' => [
                    'required',
                    'integer',
                    'min:0',
                ],
            ]);

            $sudahAda = MasterSuaraPartai2019::where(
                'id_kecamatan',
                $validated['id_kecamatan']
            )
                ->where('id_partai', $validated['id_partai'])
                ->exists();

            if ($sudahAda) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'id_partai' =>
                        'Data suara untuk kecamatan dan partai tersebut sudah ada.',
                    ]);
            }

            MasterSuaraPartai2019::create([
                'id_kecamatan' => $validated['id_kecamatan'],
                'id_partai' => $validated['id_partai'],
                'jumlah_suara' => $validated['jumlah_suara'],
            ]);

            return redirect()
                ->route('admin.rincian.index', ['tahun' => '2019'])
                ->with(
                    'success',
                    'Data suara Pemilu 2019 berhasil ditambahkan.'
                );
        }

        if ($tahun !== '2024') {
            abort(404);
        }

        $validated = $request->validate([
            'tahun' => ['required', Rule::in(['2024'])],
            'id_tps' => [
                'required',
                'integer',
                'exists:master_tps,id',
            ],
            'id_partai' => [
                'required',
                'integer',
                'exists:master_partai,id',
            ],
            'jumlah_suara' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        $tps = MasterTps::findOrFail($validated['id_tps']);

        $sudahAda = MasterSuaraPartai2024::where(
            'id_tps',
            $validated['id_tps']
        )
            ->where('id_partai', $validated['id_partai'])
            ->exists();

        if ($sudahAda) {
            return back()
                ->withInput()
                ->withErrors([
                    'id_partai' =>
                    'Data suara untuk TPS dan partai tersebut sudah ada.',
                ]);
        }

        MasterSuaraPartai2024::create([
            'id_kecamatan' => $tps->id_kecamatan,
            'id_kelurahan' => $tps->id_kelurahan,
            'id_tps' => $tps->id,
            'id_partai' => $validated['id_partai'],
            'jumlah_suara' => $validated['jumlah_suara'],
        ]);

        return redirect()
            ->route('admin.rincian.index', ['tahun' => '2024'])
            ->with(
                'success',
                'Data suara Pemilu 2024 berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan form edit data suara.
     */
    public function edit(string $tahun, int $id)
    {
        if ($tahun === '2019') {
            $data = MasterSuaraPartai2019::with([
                'partai',
                'kecamatan',
            ])->findOrFail($id);

            return view(
                'admin.rincian-suara.edit',
                compact('data', 'tahun')
            );
        }

        if ($tahun === '2024') {
            $data = MasterSuaraPartai2024::with([
                'partai',
                'tps.kecamatan',
                'tps.kelurahan',
            ])->findOrFail($id);

            return view(
                'admin.rincian-suara.edit',
                compact('data', 'tahun')
            );
        }

        abort(404);
    }

    /**
     * Memperbarui jumlah suara.
     */
    public function update(
        Request $request,
        string $tahun,
        int $id
    ) {
        $validated = $request->validate([
            'jumlah_suara' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);

        if ($tahun === '2019') {
            $data = MasterSuaraPartai2019::findOrFail($id);

            $data->update([
                'jumlah_suara' => $validated['jumlah_suara'],
            ]);

            return redirect()
                ->route('admin.rincian.index', ['tahun' => '2019'])
                ->with(
                    'success',
                    'Data suara Pemilu 2019 berhasil diperbarui.'
                );
        }

        if ($tahun === '2024') {
            $data = MasterSuaraPartai2024::findOrFail($id);

            $data->update([
                'jumlah_suara' => $validated['jumlah_suara'],
            ]);

            return redirect()
                ->route('admin.rincian.index', ['tahun' => '2024'])
                ->with(
                    'success',
                    'Data suara Pemilu 2024 berhasil diperbarui.'
                );
        }

        abort(404);
    }

    /**
     * Menghapus data suara.
     */
    public function destroy(string $tahun, int $id)
    {
        if ($tahun === '2019') {
            $data = MasterSuaraPartai2019::findOrFail($id);
            $data->delete();

            return redirect()
                ->route('admin.rincian.index', ['tahun' => '2019'])
                ->with(
                    'success',
                    'Data suara Pemilu 2019 berhasil dihapus.'
                );
        }

        if ($tahun === '2024') {
            $data = MasterSuaraPartai2024::findOrFail($id);
            $data->delete();

            return redirect()
                ->route('admin.rincian.index', ['tahun' => '2024'])
                ->with(
                    'success',
                    'Data suara Pemilu 2024 berhasil dihapus.'
                );
        }

        abort(404);
    }
}
