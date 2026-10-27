@extends('layouts.sidebar')

@section('title', 'Rekapitulasi Suara')

@section('page-title', 'Rekapitulasi Suara')

@section('content')

<div class="mx-auto max-w-7xl space-y-8">

    {{-- ==========================================================
        PENGANTAR HALAMAN
    =========================================================== --}}

    <section class="relative overflow-hidden rounded-2xl border-2 border-navy bg-navy px-6 py-6 shadow-sm sm:px-8">

        <div class="relative z-10 w-full">

            <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                Rekapitulasi Perolehan Suara Partai Politik
            </h1>

            <p class="mt-3 text-sm leading-6 text-white/85 sm:whitespace-nowrap sm:text-base">
                Halaman ini menyajikan rekapitulasi perolehan suara sah partai politik berdasarkan tahun pemilu yang dipilih.
            </p>

            <div class="mt-5 flex items-start gap-3 rounded-xl border-2 border-gold bg-navy p-4 shadow-sm">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border-2 border-gold bg-navy text-gold-soft">

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
                            d="M4 6.75A2.75 2.75 0 016.75 4h10.5A2.75 2.75 0 0120 6.75v10.5A2.75 2.75 0 0117.25 20H6.75A2.75 2.75 0 014 17.25V6.75zM8 8h8M8 12h8M8 16h4"
                        />
                    </svg>

                </div>

                <div>

                    <h2 class="text-sm font-bold text-white">
                        Rekap data berdasarkan perolehan suara
                    </h2>

                    <p class="mt-1 text-sm leading-5 text-white/80">
                        Data diurutkan berdasarkan jumlah suara sah dari perolehan tertinggi hingga terendah untuk menampilkan peringkat masing-masing partai politik.
                    </p>

                </div>

            </div>

        </div>

        {{-- DEKORASI BULAT --}}

        <div class="pointer-events-none absolute -right-10 -top-16 h-48 w-48 rounded-full border-[24px] border-gold/10"></div>

        <div class="pointer-events-none absolute -bottom-24 right-24 h-40 w-40 rounded-full bg-white/[0.04] blur-2xl"></div>

    </section>


    {{-- ==========================================================
        PILIHAN TAHUN + DOWNLOAD
    =========================================================== --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        {{-- ==========================================================
            PILIHAN TAHUN
        =========================================================== --}}

        <div class="inline-flex w-fit shrink-0 rounded-xl border-2 border-[#4B5563] bg-white p-1">

            <a
                href="{{ route('admin.rekap.index', ['tahun' => '2019']) }}"
                class="rounded-lg px-5 py-2 text-sm font-bold transition-all
                {{ $tahun == '2019'
                    ? 'bg-navy text-white shadow-sm'
                    : 'text-slate-500 hover:bg-slate-50 hover:text-navy' }}"
            >
                Pileg 2019
            </a>

            <a
                href="{{ route('admin.rekap.index', ['tahun' => '2024']) }}"
                class="rounded-lg px-5 py-2 text-sm font-bold transition-all
                {{ $tahun == '2024'
                    ? 'bg-navy text-white shadow-sm'
                    : 'text-slate-500 hover:bg-slate-50 hover:text-navy' }}"
            >
                Pileg 2024
            </a>

        </div>


        {{-- ==========================================================
            DOWNLOAD
        =========================================================== --}}

        <div class="flex flex-wrap items-center gap-2">

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
                    class="absolute right-0 z-50 mt-2 hidden w-48 overflow-hidden rounded-xl border-2 border-slate-200 bg-white shadow-lg"
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
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293 0.707V19a2 2 0 01-2 2z"
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
        STATISTIK RINGKAS
    =========================================================== --}}

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

        {{-- TOTAL SUARA SAH --}}

        <div class="relative overflow-hidden rounded-xl border-2 border-[#4B5563] bg-white p-5 shadow-sm">

            <div class="relative z-10">

                <p class="text-xs font-mono font-semibold uppercase tracking-wide text-slate">
                    Total Suara Sah
                </p>

                <p class="mt-3 text-2xl font-black text-navy sm:text-3xl">
                    {{ number_format($totalSuara) }}
                </p>

                <p class="mt-2 text-xs text-slate">
                    Akumulasi suara seluruh partai politik
                </p>

            </div>

            <div class="pointer-events-none absolute -right-8 -top-8 h-20 w-20 rounded-full border-[12px] border-gold/10"></div>

        </div>


        {{-- JUMLAH PARTAI --}}

        <div class="relative overflow-hidden rounded-xl border-2 border-[#4B5563] bg-white p-5 shadow-sm">

            <div class="relative z-10">

                <p class="text-xs font-mono font-semibold uppercase tracking-wide text-slate">
                    Jumlah Partai Politik
                </p>

                <p class="mt-3 text-2xl font-black text-navy sm:text-3xl">
                    {{ $data->count() }}
                </p>

                <p class="mt-2 text-xs text-slate">
                    Partai politik dalam rekapitulasi tahun {{ $tahun }}
                </p>

            </div>

            <div class="pointer-events-none absolute -right-8 -top-8 h-20 w-20 rounded-full border-[12px] border-gold/10"></div>

        </div>

    </div>


    {{-- ==========================================================
        TABEL PERINGKAT PARTAI POLITIK
    =========================================================== --}}

    <div class="overflow-hidden rounded-xl border-2 border-[#4B5563] bg-white shadow-sm">

        <div class="flex flex-col gap-2 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">

            <div>

                <h3 class="text-base font-bold text-navy">
                    Peringkat Perolehan Suara
                </h3>

                <p class="mt-1 text-xs text-slate">
                    Urutan partai politik berdasarkan jumlah suara sah.
                </p>

            </div>

            <span class="text-xs font-mono text-slate">
                Pemilu {{ $tahun }}
            </span>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-left text-sm">

                <thead class="border-b border-slate-300 bg-navy font-mono text-xs uppercase tracking-wider text-cream">

                    <tr>

                        <th class="px-6 py-4">
                            Peringkat
                        </th>

                        <th class="px-6 py-4">
                            Partai Politik
                        </th>

                        <th class="px-6 py-4 text-right">
                            Total Suara
                        </th>

                        <th class="px-6 py-4 text-right">
                            Persentase
                        </th>

                        <th class="px-6 py-4 text-center">
                            Proporsi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-200">

                    @forelse($data as $index => $item)

                        @php

                            $persen = $totalSuara > 0
                                ? ($item->total_suara / $totalSuara) * 100
                                : 0;

                            $medals = ['', '🥈', '🥉'];

                        @endphp


                        <tr class="transition-colors hover:bg-slate-50">

                            {{-- PERINGKAT --}}

                            <td class="px-6 py-4 font-mono text-slate">

                                @if($index < 3)

                                    @if($index === 0)

                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-gold/15 text-lg">
                                            🥇
                                        </span>

                                    @else

                                        <span class="text-xl">
                                            {{ $medals[$index] }}
                                        </span>

                                    @endif

                                @else

                                    <span class="text-sm font-semibold">
                                        #{{ $index + 1 }}
                                    </span>

                                @endif

                            </td>


                            {{-- PARTAI --}}

                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    @if($item->partai->logo_url)

                                        <img
                                            src="{{ $item->partai->logo_url }}"
                                            class="h-9 w-9 rounded-lg border border-slate-300 bg-white object-contain p-0.5"
                                            alt="{{ $item->partai->singkatan_parpol }}"
                                        >

                                    @endif

                                    <div>

                                        <div class="font-bold text-navy">
                                            {{ $item->partai->singkatan_parpol }}
                                        </div>

                                        <div class="max-w-[220px] truncate text-xs text-slate">
                                            {{ $item->partai->nama_lengkap_parpol }}
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- TOTAL SUARA --}}

                            <td class="px-6 py-4 text-right font-mono text-base font-bold text-navy">
                                {{ number_format($item->total_suara) }}
                            </td>


                            {{-- PERSENTASE --}}

                            <td class="px-6 py-4 text-right font-mono font-bold text-navy">
                                {{ number_format($persen, 2) }}%
                            </td>


                            {{-- PROPORSI --}}

                            <td class="w-48 px-6 py-4">

                                <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100">

                                    <div
                                        class="h-2.5 rounded-full bg-gradient-to-r from-gold to-yellow-500"
                                        style="width: {{ min($persen * 1.5, 100) }}%"
                                    ></div>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-12 text-center text-slate"
                            >

                                <div class="mb-2 text-4xl">
                                    📭
                                </div>

                                <p class="font-mono">
                                    Belum ada data rekapitulasi untuk tahun {{ $tahun }}.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ==========================================================
    SCRIPT DROPDOWN DOWNLOAD
=========================================================== --}}

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

        if (
            button &&
            menu &&
            !button.contains(event.target) &&
            !menu.contains(event.target)
        ) {

            menu.classList.add('hidden');

            document
                .getElementById('downloadArrow')
                ?.classList.remove('rotate-180');

        }

    });

</script>

@endsection