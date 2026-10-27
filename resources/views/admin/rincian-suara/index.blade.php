@extends('layouts.sidebar')







@section('title', 'Data Suara')



@section('page-title', 'Data Suara')







@section('content')







<div class="mx-auto max-w-7xl space-y-8">







    {{-- ==========================================================



        PENGANTAR HALAMAN



    =========================================================== --}}







    <section class="relative overflow-hidden rounded-2xl border-2 border-navy bg-navy px-6 py-6 shadow-sm sm:px-8">







        <div class="relative z-10 max-w-3xl">







            <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">



                Data Perolehan Suara Partai Politik



            </h1>







            <p class="mt-3 text-sm leading-6 text-white/85 sm:whitespace-nowrap sm:text-base">



                Kelola dan telusuri data perolehan suara partai politik di Kota Banjarmasin berdasarkan hasil Pemilu Legislatif 2019 dan 2024.



            </p>







            <div class="mt-5 flex items-start gap-3 rounded-xl border-2 border-gold bg-navy p-4 shadow-sm">







                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-gold bg-navy text-gold-soft">



                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">



                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.75A2.75 2.75 0 016.75 4h10.5A2.75 2.75 0 0120 6.75v10.5A2.75 2.75 0 0117.25 20H6.75A2.75 2.75 0 014 17.25V6.75zM8 8h8M8 12h8M8 16h4" />



                    </svg>



                </div>







                <div>



                    <h2 class="text-sm font-bold text-white">



                        Data terstruktur dan mudah dikelola



                    </h2>







                    <p class="mt-1 text-sm leading-5 text-white/80">



                        Pilih tahun pemilu untuk melihat, memperbarui, dan mengunduh data suara.



                    </p>



                </div>







            </div>







        </div>







        <div class="pointer-events-none absolute -right-10 -top-16 h-48 w-48 rounded-full border-[24px] border-gold/10"></div>



        <div class="pointer-events-none absolute -bottom-24 right-24 h-40 w-40 rounded-full bg-white/[0.04] blur-2xl"></div>







    </section>











    {{-- ==========================================================



        KONTROL DATA



    =========================================================== --}}







    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">







        {{-- ==========================================================



            PILIHAN TAHUN



        =========================================================== --}}







        <div class="inline-flex w-fit shrink-0 rounded-xl border-2 border-[#4B5563] bg-white p-1">







            <a



                href="{{ route('admin.rincian.index', [



                    'tahun' => '2019',



                    'search' => $search



                ]) }}"



                class="rounded-lg px-5 py-2 text-sm font-bold transition-all



                {{ $tahun == '2019'



                    ? 'bg-navy text-white shadow-sm'



                    : 'text-slate-500 hover:bg-slate-50 hover:text-navy' }}"



            >



                Pileg 2019



            </a>







            <a



                href="{{ route('admin.rincian.index', [



                    'tahun' => '2024',



                    'search' => $search



                ]) }}"



                class="rounded-lg px-5 py-2 text-sm font-bold transition-all



                {{ $tahun == '2024'



                    ? 'bg-navy text-white shadow-sm'



                    : 'text-slate-500 hover:bg-slate-50 hover:text-navy' }}"



            >



                Pileg 2024



            </a>







        </div>











        {{-- ==========================================================



            AKSI



        =========================================================== --}}







        <div class="flex flex-wrap items-center gap-2">







            {{-- ======================================================



                TAMBAH DATA



            ======================================================= --}}







            <a



                href="{{ route('admin.rincian.create', ['tahun' => $tahun]) }}"



                class="inline-flex items-center gap-2 rounded-lg border-2 border-navy bg-navy px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:border-gold hover:bg-gold hover:text-navy"



            >







                <svg



                    class="h-4 w-4"



                    fill="none"



                    stroke="currentColor"



                    viewBox="0 0 24 24"



                >



                    <path



                        stroke-linecap="round"



                        stroke-linejoin="round"



                        stroke-width="2"



                        d="M12 4.5v15m7.5-7.5h-15"



                    />



                </svg>







                Tambah Data







            </a>











            {{-- ======================================================



                DOWNLOAD



            ======================================================= --}}







            <div class="relative">







                <button



                    type="button"



                    id="downloadButton"



                    onclick="toggleDownloadMenu()"



                    class="inline-flex items-center gap-2 rounded-lg border-2 border-navy bg-navy px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:border-gold hover:bg-gold hover:text-navy"



                >







                    <svg



                        class="h-4 w-4"



                        fill="none"



                        stroke="currentColor"



                        viewBox="0 0 24 24"



                    >



                        <path



                            stroke-linecap="round"



                            stroke-linejoin="round"



                            stroke-width="2"



                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"



                        />



                    </svg>







                    Download







                    <svg



                        class="h-4 w-4 transition-transform duration-200"



                        id="downloadArrow"



                        fill="none"



                        stroke="currentColor"



                        viewBox="0 0 24 24"



                    >



                        <path



                            stroke-linecap="round"



                            stroke-linejoin="round"



                            stroke-width="2"



                            d="M19 9l-7 7-7-7"



                        />



                    </svg>







                </button>











                {{-- MENU DOWNLOAD --}}







                <div



                    id="downloadMenu"



                    class="hidden absolute right-0 z-50 mt-2 w-48 overflow-hidden rounded-xl border-2 border-slate-200 bg-white shadow-lg"



                >







                    <div class="border-b border-slate-200 px-4 py-3">







                        <p class="text-xs font-semibold uppercase tracking-wide text-slate">



                            Pilih Format



                        </p>







                    </div>











                    {{-- EXCEL --}}







                    <a



                        href="{{ route('admin.rekap.export.excel', $tahun) }}"



                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-navy transition-colors hover:bg-slate-50"



                    >







                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-50 text-green-600">







                            <svg



                                class="h-4 w-4"



                                fill="none"



                                stroke="currentColor"



                                viewBox="0 0 24 24"



                            >



                                <path



                                    stroke-linecap="round"



                                    stroke-linejoin="round"



                                    stroke-width="2"



                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"



                                />



                            </svg>







                        </span>







                        <span>



                            Download Excel



                        </span>







                    </a>











                    {{-- PDF --}}







                    <a



                        href="{{ route('admin.rekap.export.pdf', $tahun) }}"



                        class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-navy transition-colors hover:bg-slate-50"



                    >







                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-600">







                            <svg



                                class="h-4 w-4"



                                fill="none"



                                stroke="currentColor"



                                viewBox="0 0 24 24"



                            >



                                <path



                                    stroke-linecap="round"



                                    stroke-linejoin="round"



                                    stroke-width="2"



                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"



                                />



                            </svg>







                        </span>







                        <span>



                            Download PDF



                        </span>







                    </a>







                </div>







            </div>







        </div>







    </div>











    {{-- ==========================================================



        TABEL DATA



    =========================================================== --}}







        {{-- FILTER DAN PENCARIAN --}}
    <section class="rounded-xl border-2 border-navy bg-white p-5 shadow-sm">
        <form method="GET" action="{{ route('admin.rincian.index') }}" class="space-y-4">
            <input type="hidden" name="tahun" value="{{ $tahun }}">

            {{-- BARIS PERTAMA: FILTER UTAMA --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div>
                    <label for="filter_partai" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-600">Partai Politik</label>
                    <select id="filter_partai" name="partai" class="w-full rounded-lg border-2 border-navy bg-white px-3 py-2.5 text-sm text-navy focus:border-gold focus:outline-none">
                        <option value="">Semua Partai</option>
                        @foreach($partaiFilter as $partaiItem)
                            <option value="{{ $partaiItem->id }}" {{ (string) $partaiFilterId === (string) $partaiItem->id ? 'selected' : '' }}>{{ $partaiItem->singkatan_parpol }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="filter_kecamatan" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-600">Kecamatan</label>
                    <select id="filter_kecamatan" name="kecamatan" class="w-full rounded-lg border-2 border-navy bg-white px-3 py-2.5 text-sm text-navy focus:border-gold focus:outline-none">
                        <option value="">Semua Kecamatan</option>
                        @foreach($kecamatanFilter as $kecamatanItem)
                            <option value="{{ $kecamatanItem->id }}" {{ (string) $kecamatanFilterId === (string) $kecamatanItem->id ? 'selected' : '' }}>{{ $kecamatanItem->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>

                @if($tahun == '2024')
                    <div>
                        <label for="filter_kelurahan" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-600">Kelurahan</label>
                        <select id="filter_kelurahan" name="kelurahan" class="w-full rounded-lg border-2 border-navy bg-white px-3 py-2.5 text-sm text-navy focus:border-gold focus:outline-none">
                            <option value="">Semua Kelurahan</option>
                            @foreach($kelurahanFilter as $kelurahanItem)
                                <option value="{{ $kelurahanItem->id }}" {{ (string) $kelurahanFilterId === (string) $kelurahanItem->id ? 'selected' : '' }}>{{ $kelurahanItem->nama_kelurahan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="filter_tps" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-600">No. TPS</label>
                        <input id="filter_tps" type="number" min="1" name="tps" value="{{ $tpsFilter }}" placeholder="Contoh: 12" class="w-full rounded-lg border-2 border-navy bg-white px-3 py-2.5 text-sm text-navy focus:border-gold focus:outline-none">
                    </div>
                @else
                    <div class="hidden xl:block"></div>
                    <div class="hidden xl:block"></div>
                @endif
            </div>

            {{-- BARIS KEDUA: PENCARIAN DI KIRI, TOMBOL DI KANAN --}}
            <div class="grid grid-cols-1 items-end gap-4 lg:grid-cols-[minmax(0,1fr)_auto]">
                <div>
                    <label for="search" class="mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-600">Cari Data</label>
                    <input id="search" type="text" name="search" value="{{ $search }}" placeholder="Cari nama partai atau wilayah..." class="w-full rounded-lg border-2 border-navy px-3 py-2.5 text-sm text-navy outline-none transition focus:border-gold focus:ring-2 focus:ring-navy/10">
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="submit" class="inline-flex min-h-[42px] items-center justify-center rounded-lg border-2 border-navy bg-navy px-5 py-2.5 text-sm font-bold text-white transition hover:border-gold hover:bg-gold hover:text-navy">Terapkan Filter</button>
                    <a href="{{ route('admin.rincian.index', ['tahun' => $tahun]) }}" class="inline-flex min-h-[42px] items-center justify-center rounded-lg border-2 border-navy bg-white px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:border-gold hover:text-navy">Reset</a>
                </div>
            </div>
        </form>
    </section>

    {{-- ==========================================================

        TABEL DATA

    =========================================================== --}}





<div class="overflow-hidden rounded-xl border-2 border-[#4B5563] bg-white shadow-sm">







        <div class="overflow-x-auto">







            <table class="w-full text-left text-sm">







                <thead class="border-b border-slate-300 bg-navy text-cream">







                    <tr>







                        <th class="px-6 py-4 font-mono text-[10px] font-bold uppercase tracking-[0.12em]">



                            No



                        </th>







                        @if($tahun == '2024')







                            <th class="px-6 py-4 font-mono text-[10px] font-bold uppercase tracking-[0.12em]">



                                Wilayah (Kecamatan / Kelurahan)



                            </th>







                            <th class="px-6 py-4 font-mono text-[10px] font-bold uppercase tracking-[0.12em]">



                                No. TPS



                            </th>







                        @else







                            <th class="px-6 py-4 font-mono text-[10px] font-bold uppercase tracking-[0.12em]">



                                Kecamatan



                            </th>







                        @endif







                        <th class="px-6 py-4 font-mono text-[10px] font-bold uppercase tracking-[0.12em]">



                            Partai Politik



                        </th>







                        <th class="px-6 py-4 text-right font-mono text-[10px] font-bold uppercase tracking-[0.12em]">



                            Jumlah Suara



                        </th>







                        <th class="px-6 py-4 text-center font-mono text-[10px] font-bold uppercase tracking-[0.12em]">



                            Aksi



                        </th>







                    </tr>







                </thead>











                <tbody class="divide-y divide-slate-200">







                    @forelse($data as $index => $item)







                        <tr class="transition-colors hover:bg-slate-50">







                            {{-- NO --}}







                            <td class="px-6 py-4 font-mono text-xs text-slate">



                                {{ $data->firstItem() + $index }}



                            </td>











                            {{-- WILAYAH 2024 --}}







                            @if($tahun == '2024')







                                <td class="px-6 py-4">







                                    <div class="font-medium text-navy">



                                        {{ $item->tps->kecamatan->nama_kecamatan ?? '-' }}



                                    </div>







                                    <div class="mt-0.5 text-xs text-slate">



                                        {{ $item->tps->kelurahan->nama_kelurahan ?? '-' }}



                                    </div>







                                </td>







                                <td class="px-6 py-4 font-mono text-xs text-navy">



                                    TPS



                                    {{ str_pad($item->tps->nomor_tps ?? 0, 3, '0', STR_PAD_LEFT) }}



                                </td>







                            @else







                                {{-- WILAYAH 2019 --}}







                                <td class="px-6 py-4 font-medium text-navy">



                                    {{ $item->kecamatan->nama_kecamatan ?? '-' }}



                                </td>







                            @endif











                            {{-- PARTAI --}}







                            <td class="px-6 py-4">







                                <div class="flex items-center gap-3">







                                    @if($item->partai->logo_url)







                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-300 bg-white">







                                            <img



                                                src="{{ $item->partai->logo_url }}"



                                                class="h-6 w-6 object-contain"



                                                alt="{{ $item->partai->singkatan_parpol }}"



                                            >







                                        </div>







                                    @endif







                                    <div>







                                        <div class="font-bold text-navy">



                                            {{ $item->partai->singkatan_parpol }}



                                        </div>







                                    </div>







                                </div>







                            </td>











                            {{-- JUMLAH SUARA --}}







                            <td class="px-6 py-4 text-right font-mono font-bold text-navy">



                                {{ number_format($item->jumlah_suara) }}



                            </td>











                            {{-- AKSI --}}







                            <td class="px-6 py-4">







                                <div class="flex items-center justify-center gap-1">







                                    {{-- EDIT --}}







                                    <a



                                        href="{{ route('admin.rincian.edit', [



                                            'tahun' => $tahun,



                                            'id' => $item->id



                                        ]) }}"



                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate transition-colors hover:bg-gold/10 hover:text-gold"



                                        title="Edit Data"



                                    >







                                        <svg



                                            xmlns="http\\://www\\.w3.org/2000/svg"



                                            class="h-4 w-4"



                                            fill="none"



                                            stroke="currentColor"



                                            viewBox="0 0 24 24"



                                        >



                                            <path



                                                stroke-linecap="round"



                                                stroke-linejoin="round"



                                                stroke-width="2"



                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"



                                            ></path>



                                        </svg>







                                    </a>











                                    {{-- DELETE --}}







                                    <form



                                        action="{{ route('admin.rincian.destroy', [



                                            'tahun' => $tahun,



                                            'id' => $item->id



                                        ]) }}"



                                        method="POST"



                                        class="inline-flex items-center"



                                        onsubmit="return confirm('Apakah kamu yakin ingin menghapus data suara ini? Data yang sudah dihapus tidak dapat dikembalikan.');"



                                    >







                                        @csrf



                                        @method('DELETE')







                                        <button



                                            type="submit"



                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-slate transition-colors hover:bg-red-50 hover:text-red-600"



                                            title="Hapus Data"



                                        >







                                            <svg



                                                class="h-4 w-4"



                                                fill="none"



                                                stroke="currentColor"



                                                viewBox="0 0 24 24"



                                                stroke-width="1.8"



                                                stroke-linecap="round"



                                                stroke-linejoin="round"



                                            >



                                                <path d="M4 7h16"></path>



                                                <path d="M10 11v6"></path>



                                                <path d="M14 11v6"></path>



                                                <path d="M5 7l1 14h12l1-14"></path>



                                                <path d="M9 7V4h6v3"></path>



                                                <path d="M8 7h8"></path>



                                            </svg>







                                        </button>







                                    </form>







                                </div>







                            </td>







                        </tr>







                    @empty







                        {{-- EMPTY STATE --}}







                        <tr>







                            <td



                                colspan="{{ $tahun == '2024' ? 6 : 5 }}"



                                class="px-6 py-12 text-center text-slate"



                            >







                                <div class="mb-2 text-4xl">



                                    📭



                                </div>







                                <p class="font-mono text-xs">



                                    Tidak ada rincian data yang ditemukan.



                                </p>







                            </td>







                        </tr>







                    @endforelse







                </tbody>







            </table>







        </div>











        {{-- PAGINATION --}}







        <div class="flex flex-col gap-4 border-t border-slate-300 bg-cream/60 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">







            <div class="text-sm text-slate">







                Menampilkan







                <span class="font-bold text-navy">



                    {{ $data->firstItem() }}



                </span>







                sampai







                <span class="font-bold text-navy">



                    {{ $data->lastItem() }}



                </span>







                dari







                <span class="font-bold text-navy">



                    {{ $data->total() }}



                </span>







                entitas







            </div>







            <div>



                {{ $data->links() }}



            </div>







        </div>







    </div>







</div>











{{-- ==========================================================



    SCRIPT DOWNLOAD



\\=========================================================== --}}







<script>



    function toggleDownloadMenu() {



        const menu = document.getElementById('downloadMenu');



        const arrow = document.getElementById('downloadArrow');







        if (!menu || !arrow) {



            return;



        }







        menu.classList.toggle('hidden');



        arrow.classList.toggle('rotate-180');



    }







    document.addEventListener('click', function (event) {



        const button = document.getElementById('downloadButton');



        const menu = document.getElementById('downloadMenu');







        if (!button || !menu) {



            return;



        }







        if (!button.contains(event.target) && !menu.contains(event.target)) {



            menu.classList.add('hidden');







            document



                .getElementById('downloadArrow')



                ?.classList.remove('rotate-180');



        }



    });



</script>







@endsection