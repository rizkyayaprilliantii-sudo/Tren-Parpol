@extends('layouts.app')

@section('title', 'Hasil Pengelompokan Partai Politik')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-20">

    {{-- =========================================================
         HEADER
    ========================================================= --}}
    <div class="relative bg-white border-2 border-navy rounded-xl shadow-[8px_8px_0_0_#1B2A41] overflow-hidden mb-10">

        {{-- aksen warna kiri --}}
        <div class="absolute top-0 left-0 w-2 h-full bg-gold"></div>

        <div class="pl-6 pr-6 py-7 md:pl-10 md:pr-10 md:py-9">

            <p class="font-mono text-xs tracking-widest text-gold uppercase mb-2">
                HASIL K-MEANS CLUSTERING
            </p>

            <h1 class="font-display text-3xl md:text-4xl text-ink">
                Hasil Pengelompokan Partai Politik
            </h1>

            <p class="text-slate mt-3 leading-relaxed max-w-none">
                Hasil pengelompokan data perolehan suara partai politik
                berdasarkan kemiripan
                <strong class="text-navy">share suara (proporsi suara)</strong>
                pada Pemilu Legislatif 2019 dan 2024 menggunakan metode
                <strong class="text-navy">K-Means Clustering</strong>.
            </p>

        </div>

    </div>


    {{-- =========================================================
         PERIODE ANALISIS
    ========================================================= --}}
    <div class="mb-10 flex flex-wrap items-center gap-3">

        <div class="inline-flex border-2 border-navy rounded-sm overflow-hidden bg-cream shadow-[4px_4px_0_0_#1B2A41]">

            <span
                class="px-6 py-3 text-sm font-mono font-bold tracking-wide bg-navy text-cream"
            >
                2019
            </span>

            <span
                class="px-6 py-3 text-sm font-mono font-bold tracking-wide border-l-2 border-navy bg-gold text-navy"
            >
                2024
            </span>

        </div>

        <span class="text-xs text-slate font-mono">
            Perbandingan share suara (proporsi suara) 2019 dan 2024
        </span>

    </div>


    {{-- =========================================================
         HASIL CLUSTERING
    ========================================================= --}}
    @include('hasil-klaster._klaster', [
        'clusters' => $clusters,
        'tahun' => $tahun,
        'analisis' => $analisis,
        'ringkasanKlaster' => $ringkasanKlaster,
    ])

</section>
@endsection