@extends('layouts.pimpinan')

@section('title', 'Data Suara | Pimpinan')

@section('page-title', 'Data Suara')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">

    {{-- HEADER DATA SUARA --}}
    <section class="relative overflow-hidden rounded-xl border-2 border-maroon bg-navy px-6 py-7 shadow-sm sm:px-8">

        {{-- ELEMEN DEKORATIF --}}
        <div
            class="pointer-events-none absolute -right-[60px] -top-[70px] h-[200px] w-[200px] rounded-full border-[26px] border-[rgba(201,162,75,0.16)]"
        ></div>

        <div class="relative z-10">
            <h2 class="font-display text-2xl font-bold tracking-tight text-cream sm:text-3xl">
                Data Suara
            </h2>

            <p class="mt-3 max-w-3xl text-sm leading-relaxed text-cream/80">
                Menampilkan data perolehan suara partai politik berdasarkan periode Pemilu Legislatif 2019 dan 2024.
            </p>
        </div>
    </section>

    {{-- FILTER DATA --}}
    <section class="overflow-hidden rounded-xl border-2 border-navy bg-white shadow-sm">

        <div class="border-b-2 border-navy bg-navy px-6 py-5">
            <h3 class="text-base font-bold text-cream">
                Filter Data
            </h3>

            <p class="mt-1 text-xs text-cream/70">
                Saring data berdasarkan partai dan wilayah, lalu gunakan pencarian jika diperlukan.
            </p>
        </div>

        <div class="p-6">
            <form method="GET" action="{{ route('pimpinan.data-suara.index') }}">

                <input type="hidden" name="tahun" value="{{ $tahun }}">

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                    {{-- PARTAI --}}
                    <div>
                        <label for="filter_partai" class="mb-2 block text-sm font-semibold text-navy">
                            Partai Politik
                        </label>

                        <select
                            id="filter_partai"
                            name="partai"
                            class="w-full rounded-lg border-2 border-navy bg-white px-4 py-2.5 text-sm text-navy outline-none focus:border-gold"
                        >
                            <option value="">Semua Partai</option>

                            @foreach ($daftarPartai as $partai)
                                <option
                                    value="{{ $partai->id }}"
                                    {{ (string) $partaiTerpilih === (string) $partai->id ? 'selected' : '' }}
                                >
                                    {{ $partai->singkatan_parpol }} - {{ $partai->nama_lengkap_parpol }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- KECAMATAN --}}
                    <div>
                        <label for="filter_kecamatan" class="mb-2 block text-sm font-semibold text-navy">
                            Kecamatan
                        </label>

                        <select
                            id="filter_kecamatan"
                            name="kecamatan"
                            class="w-full rounded-lg border-2 border-navy bg-white px-4 py-2.5 text-sm text-navy outline-none focus:border-gold"
                        >
                            <option value="">Semua Kecamatan</option>

                            @foreach ($daftarKecamatan as $kecamatan)
                                <option
                                    value="{{ $kecamatan->id }}"
                                    {{ (string) $kecamatanTerpilih === (string) $kecamatan->id ? 'selected' : '' }}
                                >
                                    {{ $kecamatan->nama_kecamatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- KELURAHAN DAN TPS KHUSUS 2024 --}}
                    @if ($tahun === '2024')

                        <div>
                            <label for="filter_kelurahan" class="mb-2 block text-sm font-semibold text-navy">
                                Kelurahan
                            </label>

                            <select
                                id="filter_kelurahan"
                                name="kelurahan"
                                class="w-full rounded-lg border-2 border-navy bg-white px-4 py-2.5 text-sm text-navy outline-none focus:border-gold"
                            >
                                <option value="">Semua Kelurahan</option>

                                @foreach ($daftarKelurahan as $kelurahan)
                                    <option
                                        value="{{ $kelurahan->id }}"
                                        {{ (string) $kelurahanTerpilih === (string) $kelurahan->id ? 'selected' : '' }}
                                    >
                                        {{ $kelurahan->nama_kelurahan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="filter_tps" class="mb-2 block text-sm font-semibold text-navy">
                                Nomor TPS
                            </label>

                            <select
                                id="filter_tps"
                                name="tps"
                                class="w-full rounded-lg border-2 border-navy bg-white px-4 py-2.5 text-sm text-navy outline-none focus:border-gold"
                            >
                                <option value="">Semua TPS</option>

                                @foreach ($daftarTps as $tps)
                                    <option
                                        value="{{ $tps->id }}"
                                        {{ (string) $tpsTerpilih === (string) $tps->id ? 'selected' : '' }}
                                    >
                                        TPS {{ str_pad($tps->nomor_tps, 3, '0', STR_PAD_LEFT) }}
                                        - {{ $tps->kelurahan->nama_kelurahan ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    @endif

                    {{-- PENCARIAN --}}
                    <div class="{{ $tahun === '2019' ? 'sm:col-span-2' : '' }}">
                        <label for="search" class="mb-2 block text-sm font-semibold text-navy">
                            Pencarian
                        </label>

                        <input
                            id="search"
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari nama partai atau wilayah..."
                            class="w-full rounded-lg border-2 border-navy bg-white px-4 py-2.5 text-sm text-navy placeholder:text-slate/60 outline-none focus:border-gold"
                        >
                    </div>

                </div>

                {{-- TOMBOL FILTER --}}
                <div class="mt-5 flex flex-wrap gap-3">

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg border-2 border-navy bg-navy px-5 py-2.5 text-sm font-bold text-cream transition-colors hover:border-gold hover:bg-gold hover:text-navy"
                    >
                        Terapkan Filter
                    </button>

                    <a
                        href="{{ route('pimpinan.data-suara.index', ['tahun' => $tahun]) }}"
                        class="inline-flex items-center justify-center rounded-lg border-2 border-navy bg-white px-5 py-2.5 text-sm font-bold text-navy transition-colors hover:bg-navy hover:text-cream"
                    >
                        Reset
                    </a>

                </div>
            </form>
        </div>
    </section>

    {{-- PERALIHAN PERIODE KIRI DAN DOWNLOAD KANAN --}}
    <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">

        {{-- TOMBOL PERALIHAN PERIODE --}}
        <div
            class="inline-flex rounded-xl border-2 border-[#4B5563] bg-white p-1.5 shadow-sm"
            aria-label="Pilih periode Pemilu"
        >
            <a
                href="{{ route('pimpinan.data-suara.index', ['tahun' => '2019']) }}"
                class="rounded-lg px-6 py-2.5 text-sm font-bold transition-colors {{ (string) $tahun === '2019' ? 'bg-navy text-white' : 'bg-white text-navy hover:bg-slate-50' }}"
                @if ((string) $tahun === '2019') aria-current="page" @endif
            >
                Pileg 2019
            </a>

            <a
                href="{{ route('pimpinan.data-suara.index', ['tahun' => '2024']) }}"
                class="rounded-lg px-6 py-2.5 text-sm font-bold transition-colors {{ (string) $tahun === '2024' ? 'bg-navy text-white' : 'bg-white text-navy hover:bg-slate-50' }}"
                @if ((string) $tahun === '2024') aria-current="page" @endif
            >
                Pileg 2024
            </a>
        </div>

        {{-- TOMBOL DOWNLOAD --}}
        <div class="relative flex-shrink-0" x-data="{ open: false }">

            <button
                type="button"
                @click="open = !open"
                @click.outside="open = false"
                :aria-expanded="open.toString()"
                aria-controls="menuDownloadDataSuara"
                class="inline-flex items-center justify-center gap-3 rounded-lg border-2 border-navy bg-navy px-5 py-3 text-sm font-bold text-white shadow-sm transition-colors hover:bg-[#263a59] focus:outline-none focus:ring-2 focus:ring-navy focus:ring-offset-2"
            >
                {{-- ICON DOWNLOAD --}}
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 3v12m0 0 4-4m-4 4-4-4M5 21h14"
                    />
                </svg>

                Download

                {{-- ICON CHEVRON --}}
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-3.5 w-3.5 transition-transform"
                    :class="{ 'rotate-180': open }"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m6 9 6 6 6-6"
                    />
                </svg>
            </button>

            {{-- MENU DOWNLOAD --}}
            <div
                id="menuDownloadDataSuara"
                x-show="open"
                x-transition
                class="absolute right-0 z-30 mt-2 w-48 overflow-hidden rounded-lg border-2 border-navy bg-white shadow-lg"
                style="display: none;"
            >

                <div class="border-b border-slate-200 px-4 py-2.5">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate">
                        Pilih Format
                    </p>
                </div>

                {{-- DOWNLOAD EXCEL --}}
                <a
                    href="{{ route('pimpinan.data-suara.export.excel', [
                        'tahun' => $tahun,
                        'search' => $search,
                        'partai' => $partaiTerpilih,
                        'kecamatan' => $kecamatanTerpilih,
                        'kelurahan' => $kelurahanTerpilih,
                        'tps' => $tpsTerpilih
                    ]) }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-navy transition-colors hover:bg-cream"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 4h16v16H4z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8 8l8 8M16 8l-8 8"
                        />
                    </svg>

                    Download Excel
                </a>

                {{-- DOWNLOAD PDF --}}
                <a
                    href="{{ route('pimpinan.data-suara.export.pdf', [
                        'tahun' => $tahun,
                        'search' => $search,
                        'partai' => $partaiTerpilih,
                        'kecamatan' => $kecamatanTerpilih,
                        'kelurahan' => $kelurahanTerpilih,
                        'tps' => $tpsTerpilih
                    ]) }}"
                    class="flex items-center gap-3 border-t border-slate-200 px-4 py-3 text-sm font-semibold text-navy transition-colors hover:bg-cream"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 text-maroon"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 3h9l3 3v15H6V3Z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M14 3v4h4"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 13h6M9 17h4"
                        />
                    </svg>

                    Download PDF
                </a>

            </div>
        </div>

    </div>

    {{-- DATA TABLE --}}
    <section class="overflow-hidden rounded-xl border-2 border-[#4B5563] bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left">

                <thead class="border-b border-slate-300 bg-navy text-cream">
                    <tr class="font-mono text-xs uppercase tracking-wider">
                        <th class="whitespace-nowrap px-6 py-4 font-bold">
                            No.
                        </th>

                        <th class="px-6 py-4 font-bold">
                            Partai Politik
                        </th>

                        <th class="px-6 py-4 font-bold">
                            Wilayah
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-right font-bold">
                            Jumlah Suara
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($dataSuara as $index => $item)
                        <tr class="transition-colors hover:bg-cream/60">

                            {{-- NO --}}
                            <td class="whitespace-nowrap px-6 py-4 font-mono text-xs text-slate">
                                {{ $dataSuara->firstItem() + $index }}
                            </td>

                            {{-- PARTAI --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white p-1.5">
                                        @if ($item->partai && $item->partai->logo_url)
                                            <img
                                                src="{{ $item->partai->logo_url }}"
                                                alt="Logo {{ $item->partai->singkatan_parpol }}"
                                                class="h-full w-full object-contain"
                                            >
                                        @else
                                            <div class="flex h-full w-full items-center justify-center rounded-md bg-cream font-mono text-[10px] font-bold text-navy">
                                                {{ $item->partai->singkatan_parpol ?? '-' }}
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        <p class="text-sm font-bold text-navy">
                                            {{ $item->partai->singkatan_parpol ?? '-' }}
                                        </p>

                                        <p class="mt-1 text-xs text-slate">
                                            {{ $item->partai->nama_lengkap_parpol ?? '-' }}
                                        </p>
                                    </div>

                                </div>
                            </td>

                            {{-- WILAYAH --}}
                            <td class="px-6 py-4 text-sm text-slate">
                                @if ($tahun === '2019')
                                    <p>
                                        {{ $item->kecamatan->nama_kecamatan ?? '-' }}
                                    </p>
                                @else
                                    <p>
                                        {{ $item->tps->kecamatan->nama_kecamatan ?? '-' }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate">
                                        {{ $item->tps->kelurahan->nama_kelurahan ?? '-' }}
                                        · TPS {{ $item->tps->nomor_tps ?? '-' }}
                                    </p>
                                @endif
                            </td>

                            {{-- JUMLAH SUARA --}}
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <span class="font-mono text-sm font-bold text-navy">
                                    {{ number_format($item->jumlah_suara, 0, ',', '.') }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-14 text-center">
                                <p class="font-display text-lg font-bold text-navy">
                                    Data Tidak Ditemukan
                                </p>

                                <p class="mt-1 text-sm text-slate">
                                    Coba ubah kata pencarian atau periode data.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                {{-- TOTAL SUARA --}}
                @if ($dataSuara->count() > 0)
                    <tfoot class="border-t-2 border-navy bg-cream">
                        <tr>
                            <th
                                colspan="3"
                                class="px-6 py-4 text-right text-sm font-bold uppercase tracking-wide text-navy"
                            >
                                Total Suara {{ $tahun }}
                            </th>

                            <th class="whitespace-nowrap px-6 py-4 text-right font-mono text-base font-black text-navy">
                                {{ number_format($totalSuara, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                @endif

            </table>
        </div>
    </section>

    {{-- PAGINATION --}}
    @if ($dataSuara->hasPages())
        <div class="pt-1">
            {{ $dataSuara->links() }}
        </div>
    @endif

    {{-- INFORMASI --}}
    <section class="rounded-xl border-2 border-[#4B5563] bg-white px-5 py-4 shadow-sm">
        <div class="flex items-start gap-3">
            <span class="mt-1 h-2 w-2 flex-shrink-0 rounded-full bg-gold"></span>

            <div>
                <p class="text-sm font-bold text-navy">
                    Informasi
                </p>

                <p class="mt-1 text-xs leading-relaxed text-slate">
                    Data pada halaman ini diambil langsung dari database sistem.
                    Pimpinan memiliki akses untuk melihat dan menelusuri data
                    tanpa hak untuk menambah, mengubah, atau menghapus data.
                </p>
            </div>
        </div>
    </section>

</div>
@endsection
