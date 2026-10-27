@extends('layouts.app')

@section('title', 'Data Perolehan Suara')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">

    {{-- =========================================================
         HEADER
    ========================================================= --}}
    <div class="relative bg-white border-2 border-navy rounded-xl shadow-[8px_8px_0_0_#1B2A41] overflow-hidden mb-12">

        {{-- aksen warna kiri --}}
        <div class="absolute top-0 left-0 w-2 h-full bg-gold"></div>

        <div class="pl-6 pr-6 py-7 md:pl-10 md:pr-10 md:py-9">

            <p class="font-mono text-xs tracking-widest text-gold uppercase mb-2">
                DATA PEROLEHAN SUARA
            </p>

            <h1 class="font-display text-3xl md:text-4xl text-ink">
                Rekapitulasi Suara Partai Politik
            </h1>

            <p class="text-slate mt-3 leading-relaxed max-w-none">               
            Total perolehan suara setiap partai politik pada Pemilu Legislatif di Kota Banjarmasin.
            </p>

        </div>

    </div>


    {{-- =========================================================
         TOGGLE TAHUN
    ========================================================= --}}
    <div class="mb-10 flex flex-wrap items-center gap-3">

        <div class="inline-flex border-2 border-navy rounded-sm overflow-hidden bg-cream shadow-[4px_4px_0_0_#1B2A41]">

            {{-- PILEG 2019 --}}
            <a
                href="{{ url('/data-suara') }}?tahun=2019"
                class="px-6 py-3 text-sm font-mono font-bold tracking-wide transition-colors
                    {{ $tahun == 2019
                        ? 'bg-navy text-cream'
                        : 'bg-cream text-navy hover:bg-navy/10' }}"
            >
                2019
            </a>

            {{-- PILEG 2024 --}}
            <a
                href="{{ url('/data-suara') }}?tahun=2024"
                class="px-6 py-3 text-sm font-mono font-bold tracking-wide border-l-2 border-navy transition-colors
                    {{ $tahun == 2024
                        ? 'bg-navy text-cream'
                        : 'bg-cream text-navy hover:bg-navy/10' }}"
            >
                2024
            </a>

        </div>

        <span class="text-xs text-slate font-mono">
            *Klik untuk beralih periode
        </span>

    </div>


    {{-- =========================================================
         CARD RINGKASAN (gaya sama dgn section STATISTIK di beranda)
    ========================================================= --}}
    @php
        $summaryItems = [
            [
                'val'   => number_format($totalSuara, 0, ',', '.'),
                'label' => 'Total Suara',
                'sub'   => 'Suara sah',
                'bar'   => 'bg-navy',
            ],
            [
                'val'   => $jumlahPartai,
                'label' => 'Jumlah Partai',
                'sub'   => 'Peserta pemilu',
                'bar'   => 'bg-gold',
            ],
            [
                'val'   => $tahun,
                'label' => 'Periode',
                'sub'   => 'Pileg',
                'bar'   => 'bg-maroon',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 bg-cream border-2 border-navy shadow-[8px_8px_0_0_#1B2A41] mb-10">

        @foreach($summaryItems as $s)

            <div class="p-6 md:p-8 border-navy
                        {{ !$loop->last ? 'border-b-2 md:border-b-0 md:border-r-2' : '' }}">

                <div class="font-mono text-4xl md:text-5xl font-black text-navy leading-none">
                    {{ $s['val'] }}
                </div>

                <div class="mt-4 h-1.5 w-12 {{ $s['bar'] }}"></div>

                <div class="mt-3 text-base md:text-lg font-extrabold text-navy">
                    {{ $s['label'] }}
                </div>

                <div class="text-xs md:text-sm font-medium text-slate">
                    {{ $s['sub'] }}
                </div>

            </div>

        @endforeach

    </div>


    {{-- COUNTER --}}
    <div class="text-right text-xs text-slate font-mono mb-4">
        Menampilkan {{ $data->count() }} partai
    </div>


    {{-- TABEL --}}
    <div id="tabel-container">

        @include('data-suara._tabel', [
            'data' => $data,
            'tahun' => $tahun,
            'totalSuara' => $totalSuara,
            'jumlahPartai' => $jumlahPartai
        ])

    </div>

</section>
@endsection