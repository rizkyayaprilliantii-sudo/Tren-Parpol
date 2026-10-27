@extends('layouts.pimpinan')

@section('title', 'Rekapitulasi | Pimpinan')

@section('page-title', 'Rekapitulasi Perolehan Suara')

@section('content')

<div class="max-w-[1500px] mx-auto space-y-6">

    {{-- ==========================================================
        INFORMASI HALAMAN
    =========================================================== --}}

    <section class="relative overflow-hidden rounded-2xl border-2 border-navy bg-navy px-6 py-6 shadow-sm sm:px-8">

        <div class="relative z-10 max-w-full">

            <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                Informasi Rekapitulasi
            </h1>

            <p class="mt-3 text-sm leading-6 text-white/85 sm:text-base">
                Halaman ini menyajikan ringkasan perolehan suara setiap partai politik pada Pemilu Legislatif {{ $tahun }} di Kota Banjarmasin, termasuk total suara sah, jumlah partai, perolehan tertinggi, rata-rata suara, serta persentase perolehan suara.
            </p>

        </div>

        <div class="pointer-events-none absolute -right-10 -top-16 h-48 w-48 rounded-full border-[24px] border-gold/10"></div>

    </section>


    {{-- ==========================================================
        FILTER TAHUN + DOWNLOAD
    =========================================================== --}}

    <div class="flex items-center justify-between gap-4 pt-4">

        {{-- TOGGLE TAHUN --}}

        <div class="inline-flex rounded-xl border-2 border-[#4B5563] bg-white p-1.5 shadow-sm">

            <a
                href="{{ route('pimpinan.rekap.index', ['tahun' => '2019']) }}"
                class="rounded-lg px-6 py-2 text-sm font-bold transition-all
                    {{ $tahun === '2019'
                        ? 'bg-navy text-white shadow-sm'
                        : 'text-slate-500 hover:bg-slate-50 hover:text-navy' }}"
            >
                Pileg 2019
            </a>

            <a
                href="{{ route('pimpinan.rekap.index', ['tahun' => '2024']) }}"
                class="rounded-lg px-6 py-2 text-sm font-bold transition-all
                    {{ $tahun === '2024'
                        ? 'bg-navy text-white shadow-sm'
                        : 'text-slate-500 hover:bg-slate-50 hover:text-navy' }}"
            >
                Pileg 2024
            </a>

        </div>


        {{-- DOWNLOAD --}}

        <div class="relative">

            <button
                type="button"
                id="downloadButton"
                onclick="toggleDownloadMenu()"
                class="flex items-center gap-2 rounded-lg border-2 border-navy bg-navy px-4 py-2 text-sm font-medium text-white shadow-sm transition-all hover:border-gold hover:bg-gold hover:text-navy"
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


            {{-- DOWNLOAD MENU --}}

            <div
                id="downloadMenu"
                class="absolute right-0 z-50 mt-2 hidden w-56 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-lg"
            >

                <div class="border-b border-slate-200 px-5 py-3">

                    <p class="text-xs font-bold uppercase tracking-wide text-slate">
                        Pilih Format
                    </p>

                </div>


                {{-- EXCEL --}}

                <a
                    href="{{ route('pimpinan.rekap.export.excel', [
                        'tahun' => $tahun
                    ]) }}"
                    class="flex items-center gap-3 px-5 py-4 text-sm text-navy transition-colors hover:bg-slate-50"
                >

                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-green-50 text-green-600">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M6 3h8l4 4v14H6V3z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M14 3v5h5"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M9 13l3 3m0-3l-3 3"
                            />

                        </svg>

                    </span>

                    <span class="font-medium">
                        Download Excel
                    </span>

                </a>


                {{-- PDF --}}

                <a
                    href="{{ route('pimpinan.rekap.export.pdf', [
                        'tahun' => $tahun
                    ]) }}"
                    class="flex items-center gap-3 px-5 py-4 text-sm text-navy transition-colors hover:bg-slate-50"
                >

                    <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M6 3h8l4 4v14H6V3z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M14 3v5h5"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M9 14h6M9 17h4"
                            />

                        </svg>

                    </span>

                    <span class="font-medium">
                        Download PDF
                    </span>

                </a>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        RINGKASAN
    =========================================================== --}}

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">

        {{-- TOTAL SUARA SAH --}}

        <article class="rounded-xl border-2 border-[#4B5563] bg-white p-6 shadow-sm">

            <p class="text-sm font-semibold text-slate">
                Total Suara Sah
            </p>

            <p class="mt-3 font-mono text-4xl font-black leading-none text-navy">
                {{ number_format($totalSuara, 0, ',', '.') }}
            </p>

            <div class="mt-4 h-1.5 w-12 bg-maroon"></div>

            <p class="mt-3 text-xs text-slate">
                Total perolehan suara seluruh partai
            </p>

        </article>


        {{-- JUMLAH PARTAI --}}

        <article class="rounded-xl border-2 border-[#4B5563] bg-white p-6 shadow-sm">

            <p class="text-sm font-semibold text-slate">
                Jumlah Partai
            </p>

            <p class="mt-3 font-mono text-4xl font-black leading-none text-navy">
                {{ number_format($jumlahPartai, 0, ',', '.') }}
            </p>

            <div class="mt-4 h-1.5 w-12 bg-gold"></div>

            <p class="mt-3 text-xs text-slate">
                Partai yang tercatat pada periode ini
            </p>

        </article>


        {{-- PEROLEHAN TERTINGGI --}}

        <article class="rounded-xl border-2 border-[#4B5563] bg-white p-6 shadow-sm">

            <p class="text-sm font-semibold text-slate">
                Perolehan Tertinggi
            </p>

            @if ($partaiTertinggi)

                <p class="mt-3 font-display text-2xl font-black leading-none text-navy">
                    {{ $partaiTertinggi->partai->singkatan_parpol ?? '-' }}
                </p>

                <p class="mt-2 font-mono text-sm font-bold text-maroon">
                    {{ number_format($partaiTertinggi->total_suara, 0, ',', '.') }}
                    suara
                </p>

            @else

                <p class="mt-3 font-display text-2xl font-black leading-none text-navy">
                    -
                </p>

                <p class="mt-2 text-xs text-slate">
                    Belum ada data
                </p>

            @endif

            <div class="mt-4 h-1.5 w-12 bg-maroon"></div>

        </article>


        {{-- RATA-RATA --}}

        <article class="rounded-xl border-2 border-[#4B5563] bg-white p-6 shadow-sm">

            <p class="text-sm font-semibold text-slate">
                Rata-rata Suara
            </p>

            <p class="mt-3 font-mono text-4xl font-black leading-none text-navy">
                {{ number_format($rataRataSuara, 0, ',', '.') }}
            </p>

            <div class="mt-4 h-1.5 w-12 bg-gold"></div>

            <p class="mt-3 text-xs text-slate">
                Rata-rata per partai politik
            </p>

        </article>

    </section>


    {{-- ==========================================================
        TABEL REKAPITULASI
    =========================================================== --}}

    <section class="overflow-hidden rounded-xl border-2 border-[#4B5563] bg-white shadow-sm">

        <div class="overflow-x-auto">

            <table class="min-w-full text-left">

                <thead class="border-b border-slate-300 bg-navy text-cream">

                    <tr class="font-mono text-xs uppercase tracking-wider">

                        <th class="whitespace-nowrap px-6 py-4 font-bold">
                            Peringkat
                        </th>

                        <th class="px-6 py-4 font-bold">
                            Partai Politik
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-right font-bold">
                            Total Suara
                        </th>

                        <th class="whitespace-nowrap px-6 py-4 text-right font-bold">
                            Persentase
                        </th>

                        <th class="px-6 py-4 text-center font-bold">
                            Proporsi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100 bg-white">

                    @forelse ($data as $index => $item)

                        @php
                            $persentase = $item->persentase ?? 0;
                        @endphp

                        <tr class="transition-colors hover:bg-cream/60">

                            <td class="whitespace-nowrap px-6 py-4">

                                @if ($index === 0)

                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-gold/15 text-lg">
                                        🥇
                                    </span>

                                @elseif ($index === 1)

                                    <span class="text-xl">
                                        🥈
                                    </span>

                                @elseif ($index === 2)

                                    <span class="text-xl">
                                        🥉
                                    </span>

                                @else

                                    <span class="font-mono text-sm font-semibold text-slate">
                                        #{{ $index + 1 }}
                                    </span>

                                @endif

                            </td>


                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    @if ($item->partai && $item->partai->logo_url)

                                        <img
                                            src="{{ $item->partai->logo_url }}"
                                            class="h-9 w-9 rounded-lg border border-slate-300 bg-white object-contain p-0.5"
                                            alt="{{ $item->partai->singkatan_parpol ?? 'Logo Partai' }}"
                                        >

                                    @endif

                                    <div>

                                        <div class="font-bold text-navy">
                                            {{ $item->partai->singkatan_parpol ?? '-' }}
                                        </div>

                                        <div class="max-w-[260px] truncate text-xs text-slate">
                                            {{ $item->partai->nama_lengkap_parpol ?? '-' }}
                                        </div>

                                    </div>

                                </div>

                            </td>


                            <td class="whitespace-nowrap px-6 py-4 text-right">

                                <span class="font-mono text-base font-bold text-navy">
                                    {{ number_format($item->total_suara, 0, ',', '.') }}
                                </span>

                            </td>


                            <td class="whitespace-nowrap px-6 py-4 text-right">

                                <span class="font-mono text-sm font-bold text-navy">
                                    {{ number_format($persentase, 2, ',', '.') }}%
                                </span>

                            </td>


                            <td class="min-w-[220px] px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="h-2.5 flex-1 overflow-hidden rounded-full bg-slate-100">

                                        <div
                                            class="h-full rounded-full bg-gradient-to-r from-gold to-yellow-500"
                                            style="width: {{ min($persentase, 100) }}%;"
                                        ></div>

                                    </div>

                                    <span class="w-12 text-right font-mono text-xs font-semibold text-slate">
                                        {{ number_format($persentase, 1, ',', '.') }}%
                                    </span>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-14 text-center"
                            >

                                <p class="font-display text-lg font-bold text-navy">
                                    Data Rekapitulasi Belum Tersedia
                                </p>

                                <p class="mt-1 text-sm text-slate">
                                    Belum terdapat data perolehan suara untuk Pemilu {{ $tahun }}.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>


                @if ($data->isNotEmpty())

                    <tfoot class="border-t-2 border-[#4B5563] bg-cream/50">

                        <tr>

                            <td
                                colspan="2"
                                class="px-6 py-4 text-sm font-bold text-navy"
                            >
                                Total Suara Sah
                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right">

                                <span class="font-mono text-sm font-black text-navy">
                                    {{ number_format($totalSuara, 0, ',', '.') }}
                                </span>

                            </td>

                            <td class="whitespace-nowrap px-6 py-4 text-right">

                                <span class="font-mono text-sm font-black text-navy">
                                    100,00%
                                </span>

                            </td>

                            <td class="px-6 py-4">

                                <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100">

                                    <div class="h-full w-full rounded-full bg-gold"></div>

                                </div>

                            </td>

                        </tr>

                    </tfoot>

                @endif

            </table>

        </div>

    </section>


    {{-- ==========================================================
        INFORMASI
    =========================================================== --}}

    <section class="rounded-xl border-2 border-[#4B5563] bg-white px-5 py-4 shadow-sm">

        <div class="flex items-start gap-3">

            <span class="mt-1 h-2 w-2 flex-shrink-0 rounded-full bg-gold"></span>

            <div>

                <p class="text-sm font-bold text-navy">
                    Informasi Rekapitulasi
                </p>

                <p class="mt-1 text-xs leading-relaxed text-slate">
                    Rekapitulasi menampilkan total perolehan suara setiap partai
                    politik pada Pemilu Legislatif {{ $tahun }} di Kota Banjarmasin.
                    Persentase menunjukkan proporsi suara masing-masing partai
                    terhadap total suara sah.
                </p>

            </div>

        </div>

    </section>

</div>


{{-- ==========================================================
    SCRIPT DOWNLOAD DROPDOWN
========================================================== --}}

<script>

    function toggleDownloadMenu() {

        const menu = document.getElementById('downloadMenu');
        const arrow = document.getElementById('downloadArrow');

        menu.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180');

    }


    document.addEventListener('click', function (event) {

        const button = document.getElementById('downloadButton');
        const menu = document.getElementById('downloadMenu');

        if (
            !button.contains(event.target) &&
            !menu.contains(event.target)
        ) {

            menu.classList.add('hidden');

            document
                .getElementById('downloadArrow')
                .classList.remove('rotate-180');

        }

    });

</script>

@endsection