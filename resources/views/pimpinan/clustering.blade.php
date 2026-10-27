@extends('layouts.pimpinan')

@section('title', 'Hasil Clustering | Pimpinan')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<div class="space-y-8">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <section class="relative rounded-2xl border-2 border-maroon bg-navy">

        <div class="pointer-events-none absolute inset-0 overflow-hidden rounded-2xl">
            <div class="absolute -right-20 -top-24 h-64 w-64 rounded-full border-[30px] border-gold/10"></div>
            <div class="absolute -bottom-28 right-40 h-48 w-48 rounded-full bg-white/[0.035] blur-2xl"></div>
        </div>

        <div class="relative z-20 px-6 py-7 md:px-8 md:py-8">

            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

                <div class="max-w-3xl">

                    <div class="flex items-center gap-4">

                        <div>

                            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-gold-soft">
                                Analisis K-Means Clustering
                            </p>

                            <h1 class="mt-1 text-2xl font-bold tracking-tight text-white md:text-3xl">
                                Hasil Pengelompokan Perolehan Suara
                            </h1>

                        </div>

                    </div>

                    <p class="mt-4 text-sm leading-relaxed text-white/75 md:text-[15px]">
                        Ringkasan hasil pengelompokan proporsi perolehan suara partai politik
                        pada Pemilu Legislatif 2019 dan 2024 di Kota Banjarmasin.
                    </p>

                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-stretch">

                    <div class="min-w-[150px] rounded-xl border border-white/15 bg-white/[0.06] px-4 py-3">

                        <p class="text-[11px] font-bold uppercase tracking-wider text-gold-soft">
                            Periode Analisis
                        </p>

                        <p class="mt-1 text-lg font-bold text-white">
                            2019 – 2024
                        </p>

                    </div>

                    <div class="min-w-[110px] rounded-xl border border-white/15 bg-white/[0.06] px-4 py-3">

                        <p class="text-[11px] font-bold uppercase tracking-wider text-gold-soft">
                            K-Means
                        </p>

                        <p class="mt-1 text-lg font-bold text-white">
                            K = {{ $jumlahCluster }}
                        </p>

                    </div>

                    <div x-data="{ open: false }" class="relative">

                        <button
                            type="button"
                            @click="open = !open"
                            class="inline-flex h-full w-full items-center justify-center gap-1.5 rounded-lg bg-gold px-3.5 py-2 text-xs font-bold text-navy shadow-md transition hover:bg-gold-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-gold-soft focus-visible:ring-offset-2 focus-visible:ring-offset-navy sm:w-auto"
                        >

                            <i class="fas fa-download"></i>

                            <span>Download</span>

                            <i
                                class="fas fa-chevron-down text-xs transition-transform"
                                :class="{ 'rotate-180': open }"
                            ></i>

                        </button>

                        <div
                            x-show="open"
                            x-cloak
                            @click.outside="open = false"
                            x-transition
                            class="absolute right-0 top-full z-50 mt-2 w-56 overflow-hidden rounded-xl border-2 border-navy bg-white shadow-xl"
                        >

                            <a
                                href="{{ route('pimpinan.clustering.export.excel', request()->query()) }}"
                                class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-navy transition hover:bg-cream"
                            >

                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-600 text-white">
                                    <i class="fas fa-file-excel text-xs"></i>
                                </span>

                                <span>Export Excel</span>

                            </a>

                            <a
                                href="{{ route('pimpinan.clustering.export.pdf', request()->query()) }}"
                                class="flex items-center gap-3 border-t border-navy/10 px-4 py-3 text-sm font-semibold text-navy transition hover:bg-cream"
                            >

                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-maroon text-white">
                                    <i class="fas fa-file-pdf text-xs"></i>
                                </span>

                                <span>Export PDF</span>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        RINGKASAN ANALISIS
    ========================================================== --}}

    <section>

        <div class="mb-4 flex items-center gap-3">

            <span class="h-9 w-1.5 rounded-full bg-maroon"></span>

            <div>

                <h2 class="text-lg font-bold text-navy">
                    Ringkasan Analisis
                </h2>

                <p class="text-sm text-slate">
                    Gambaran singkat hasil analisis yang digunakan sebagai bahan evaluasi.
                </p>

            </div>

        </div>

        @php

            $ringkasan = [

                [
                    'label' => 'Data Perbandingan',
                    'nilai' => number_format($jumlahData, 0, ',', '.'),
                    'ket'   => 'data kecamatan × partai',
                    'icon'  => 'fa-database',
                    'bar'   => 'bg-navy',
                    'tile'  => 'bg-navy/10 text-navy',
                ],

                [
                    'label' => 'Jumlah Cluster',
                    'nilai' => $jumlahCluster,
                    'ket'   => 'kelompok hasil K-Means',
                    'icon'  => 'fa-layer-group',
                    'bar'   => 'bg-maroon',
                    'tile'  => 'bg-maroon/10 text-maroon',
                ],

                [
                    'label' => 'Kecamatan',
                    'nilai' => $jumlahKecamatan,
                    'ket'   => 'wilayah analisis',
                    'icon'  => 'fa-map-marker-alt',
                    'bar'   => 'bg-gold',
                    'tile'  => 'bg-gold/20 text-navy',
                ],

                [
                    'label' => 'Partai Comparable',
                    'nilai' => $jumlahPartaiComparable,
                    'ket'   => 'memiliki data pada 2019 dan 2024. Partai yang hanya muncul pada salah satu tahun tidak dimasukkan agar share 2019 dan share 2024 dapat dibandingkan secara langsung.',
                    'icon'  => 'fa-landmark',
                    'bar'   => 'bg-slate',
                    'tile'  => 'bg-slate/15 text-slate',
                ],

            ];

        @endphp

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            @foreach($ringkasan as $k)

                <div class="relative overflow-hidden rounded-2xl border-2 border-navy/25 bg-white p-5 shadow-[6px_6px_0_0_rgba(27,42,65,0.08)] transition-all hover:-translate-y-0.5 hover:shadow-[6px_8px_0_0_rgba(27,42,65,0.12)]">

                    <span class="absolute inset-x-0 top-0 h-1.5 {{ $k['bar'] }}"></span>

                    <div class="flex items-start justify-between gap-4">

                        <div>

                            <p class="text-xs font-semibold text-slate">
                                {{ $k['label'] }}
                            </p>

                            <p class="mt-2 text-3xl font-extrabold leading-none text-navy">
                                {{ $k['nilai'] }}
                            </p>

                            <p class="mt-2 text-xs leading-relaxed text-slate">
                                {{ $k['ket'] }}
                            </p>

                        </div>

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $k['tile'] }}">
                            <i class="fas {{ $k['icon'] }}"></i>
                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </section>


    {{-- =========================================================
        PROFIL KLASTER
    ========================================================== --}}

    <section>

        <div class="mb-4 flex items-center gap-3">

            <span class="h-9 w-1.5 rounded-full bg-maroon"></span>

            <div>

                <h2 class="text-lg font-bold text-navy">
                    Profil Klaster
                </h2>

                <p class="text-sm text-slate">
                    Karakteristik klaster berdasarkan rata-rata share suara 2019 dan 2024.
                </p>

            </div>

        </div>

        @php

            $profil = [

                'Tinggi' => [
                    'icon' => 'fa-arrow-up',
                    'head' => 'bg-navy',
                    'bar'  => 'bg-navy',
                ],

                'Rendah' => [
                    'icon' => 'fa-arrow-down',
                    'head' => 'bg-maroon',
                    'bar'  => 'bg-maroon',
                ],

            ];

        @endphp

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">

            @foreach($profil as $nama => $p)

                @php

                    $d    = $ringkasanKlaster[$nama] ?? [];
                    $r19  = (float) ($d['rata_share_2019'] ?? 0);
                    $r24  = (float) ($d['rata_share_2024'] ?? 0);
                    $sel  = (float) ($d['rata_selisih'] ?? 0);
                    $maks = max($r19, $r24, 0.01);
                    $w19  = round($r19 / $maks * 100);
                    $w24  = round($r24 / $maks * 100);

                    $selClass = $sel > 0
                        ? 'text-green-700'
                        : ($sel < 0 ? 'text-red-700' : 'text-slate');

                    $selIcon = $sel > 0
                        ? 'fa-arrow-up'
                        : ($sel < 0 ? 'fa-arrow-down' : 'fa-minus');

                @endphp

                <div class="overflow-hidden rounded-2xl border-2 border-navy bg-white shadow-[6px_6px_0_0_rgba(27,42,65,0.10)]">

                    <div class="relative overflow-hidden {{ $p['head'] }} px-5 py-4">

                        <div class="relative z-10 flex items-center justify-between gap-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gold text-navy shadow-md shadow-black/20">
                                    <i class="fas {{ $p['icon'] }}"></i>
                                </div>

                                <div>

                                    <p class="text-xs font-bold uppercase tracking-wider text-gold-soft">
                                        Cluster {{ $d['cluster'] ?? '-' }}
                                    </p>

                                    <h3 class="text-lg font-bold text-white">
                                        {{ $nama }}
                                    </h3>

                                </div>

                            </div>

                            <span class="rounded-full bg-gold px-3 py-1.5 text-xs font-extrabold text-navy shadow-sm">
                                {{ $d['jumlah'] ?? 0 }} data
                            </span>

                        </div>

                        <div class="absolute inset-x-0 bottom-0 h-1 bg-gold"></div>

                    </div>

                    <div class="space-y-4 bg-cream/50 p-5">

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">

                            <div class="rounded-xl border-2 border-t-4 border-navy border-t-navy bg-white p-4 shadow-sm">

                                <p class="text-xs font-semibold text-slate">
                                    Rata-rata 2019
                                </p>

                                <p class="mt-1 text-xl font-extrabold text-navy">
                                    {{ number_format($r19, 2, ',', '.') }}%
                                </p>

                            </div>

                            <div class="rounded-xl border-2 border-t-4 border-navy border-t-maroon bg-white p-4 shadow-sm">

                                <p class="text-xs font-semibold text-slate">
                                    Rata-rata 2024
                                </p>

                                <p class="mt-1 text-xl font-extrabold text-navy">
                                    {{ number_format($r24, 2, ',', '.') }}%
                                </p>

                            </div>

                            <div class="rounded-xl border-2 border-t-4 border-navy {{ $sel > 0 ? 'border-t-green-700' : ($sel < 0 ? 'border-t-red-700' : 'border-t-slate-600') }} bg-white p-4 shadow-sm">

                                <p class="text-xs font-semibold text-slate">
                                    Perubahan
                                </p>

                                <p class="mt-1 flex items-center gap-1.5 text-xl font-extrabold {{ $selClass }}">

                                    <i class="fas {{ $selIcon }} text-sm"></i>

                                    {{ number_format(abs($sel), 2, ',', '.') }}%

                                </p>

                            </div>

                        </div>

                        <div class="rounded-xl border-2 border-navy bg-white p-4 shadow-sm">

                            <p class="mb-3 text-xs font-semibold text-slate">
                                Perbandingan rata-rata share
                            </p>

                            <div class="space-y-2.5">

                                <div class="flex items-center gap-3">

                                    <span class="w-10 font-mono text-[11px] font-bold text-slate">
                                        2019
                                    </span>

                                    <div class="h-2.5 flex-1 overflow-hidden rounded-full bg-navy/10">

                                        <div
                                            class="h-full rounded-full bg-gold"
                                            style="width: {{ $w19 }}%"
                                        ></div>

                                    </div>

                                </div>

                                <div class="flex items-center gap-3">

                                    <span class="w-10 font-mono text-[11px] font-bold text-slate">
                                        2024
                                    </span>

                                    <div class="h-2.5 flex-1 overflow-hidden rounded-full bg-navy/10">

                                        <div
                                            class="h-full rounded-full {{ $p['bar'] }}"
                                            style="width: {{ $w24 }}%"
                                        ></div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </section>


    {{-- =========================================================
        INFORMASI ANALISIS
    ========================================================== --}}

    @php

        $skor = $analisis ? (float) $analisis->silhouette_score : null;

        if ($skor === null) {

            $levelLabel = null;
            $levelBadge = '';

        } elseif ($skor >= 0.7) {

            $levelLabel = 'Sangat kuat';
            $levelBadge = 'bg-green-100 text-green-700';

        } elseif ($skor >= 0.5) {

            $levelLabel = 'Cukup baik';
            $levelBadge = 'bg-gold/25 text-navy';

        } else {

            $levelLabel = 'Lemah';
            $levelBadge = 'bg-red-100 text-red-700';

        }

    @endphp

    <section class="overflow-hidden rounded-2xl border-2 border-navy bg-white shadow-[6px_6px_0_0_rgba(27,42,65,0.10)]">

        <div class="relative overflow-hidden bg-navy px-6 py-5">

            <div class="relative z-10">

                <h2 class="text-lg font-bold leading-tight text-white">
                    Informasi Analisis
                </h2>

                <p class="text-xs text-cream/65">
                    Sumber data, karakteristik analisis, dan evaluasi model.
                </p>

            </div>

            <div class="pointer-events-none absolute -right-8 -top-12 h-32 w-32 rounded-full border-[16px] border-gold/10"></div>

            <div class="absolute inset-x-0 bottom-0 h-1 bg-maroon"></div>

        </div>

        <div class="space-y-4 bg-cream/50 p-5 sm:p-6">

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">

                <div class="rounded-xl border-2 border-t-4 border-navy/20 border-t-gold bg-gradient-to-b from-white to-cream p-5 shadow-[4px_4px_0_0_rgba(27,42,65,0.08)]">

                    <div class="mb-4 border-b border-navy/10 pb-3">

                        <h3 class="font-bold text-navy">
                            Sumber Data
                        </h3>

                    </div>

                    <div class="space-y-3">

                        <div class="flex items-center justify-between gap-3">

                            <span class="text-sm text-slate">
                                Data 2019
                            </span>

                            <span class="font-bold text-navy">
                                {{ number_format($jumlahRecord2019, 0, ',', '.') }} record
                            </span>

                        </div>

                        <div class="h-px bg-navy/10"></div>

                        <div class="flex items-center justify-between gap-3">

                            <span class="text-sm text-slate">
                                Data 2024
                            </span>

                            <span class="font-bold text-navy">
                                {{ number_format($jumlahRecord2024, 0, ',', '.') }} record
                            </span>

                        </div>

                    </div>

                </div>

                <div class="rounded-xl border-2 border-t-4 border-navy/20 border-t-navy bg-gradient-to-b from-white to-cream p-5 shadow-[4px_4px_0_0_rgba(27,42,65,0.08)]">

                    <div class="mb-4 border-b border-navy/10 pb-3">

                        <h3 class="font-bold text-navy">
                            Karakteristik Analisis
                        </h3>

                    </div>

                    <div class="space-y-3">

                        <div>

                            <p class="text-xs font-semibold text-slate">
                                Unit analisis
                            </p>

                            <p class="mt-0.5 text-sm font-bold text-navy">
                                Kecamatan × Partai Politik
                            </p>

                        </div>

                        <div class="h-px bg-navy/10"></div>

                        <div>

                            <p class="text-xs font-semibold text-slate">
                                Fitur K-Means
                            </p>

                            <p class="mt-0.5 text-sm font-bold text-navy">
                                Share 2019 dan Share 2024
                            </p>

                        </div>

                        <div class="h-px bg-navy/10"></div>

                        <div>

                            <p class="text-xs font-semibold text-slate">
                                Normalisasi
                            </p>

                            <p class="mt-0.5 text-sm font-bold text-navy">
                                Tidak menggunakan scaling
                            </p>

                        </div>

                    </div>

                </div>

                <div class="rounded-xl border-2 border-t-4 border-navy/20 border-t-maroon bg-gradient-to-b from-white to-cream p-5 shadow-[4px_4px_0_0_rgba(27,42,65,0.08)]">

                    <div class="mb-4 border-b border-navy/10 pb-3">

                        <h3 class="font-bold text-navy">
                            Evaluasi Model
                        </h3>

                    </div>

                    <div class="relative overflow-hidden rounded-xl bg-navy p-4 shadow-md">

                        <div class="flex items-start justify-between gap-2">

                            <p class="text-xs font-semibold text-cream/70">
                                Silhouette Score
                            </p>

                            @if($levelLabel)

                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold {{ $levelBadge }}">
                                    {{ $levelLabel }}
                                </span>

                            @endif

                        </div>

                        <p class="mt-1 text-3xl font-extrabold text-gold-soft">
                            {{ $analisis ? number_format($skor, 4, ',', '.') : '-' }}
                        </p>

                        <div class="pointer-events-none absolute -bottom-6 -right-6 h-20 w-20 rounded-full border-[10px] border-gold/15"></div>

                    </div>

                    <p class="mt-3 text-xs leading-relaxed text-slate">
                        Nilai evaluasi internal untuk mengukur kualitas pemisahan
                        dan kekompakan hasil clustering.
                    </p>

                </div>

            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

                <div class="rounded-xl border-2 border-l-4 border-navy/20 border-l-gold bg-gradient-to-r from-white to-cream p-5 shadow-[4px_4px_0_0_rgba(27,42,65,0.08)]">

                    <div>

                        <h3 class="font-bold text-navy">
                            Penyesuaian Data
                        </h3>

                        <p class="mt-1 text-sm leading-relaxed text-slate">
                            Data suara tahun 2019 tersedia pada tingkat kecamatan,
                            sedangkan data tahun 2024 tersedia pada tingkat TPS.
                            Agar kedua tahun dapat dibandingkan pada unit wilayah
                            yang sama, data 2024 diagregasikan dari TPS ke kecamatan.
                        </p>

                    </div>

                </div>

                <div class="rounded-xl border-2 border-l-4 border-navy/20 border-l-maroon bg-gradient-to-r from-white to-cream p-5 shadow-[4px_4px_0_0_rgba(27,42,65,0.08)]">

                    <div>

                        <h3 class="font-bold text-navy">
                            Dasar Pemilihan Partai
                        </h3>

                        <p class="mt-1 text-sm leading-relaxed text-slate">
                            Analisis menggunakan partai politik yang memiliki data
                            pada kedua tahun pemilu. Dengan demikian, share suara
                            2019 dan share suara 2024 dapat dibandingkan secara
                            langsung untuk unit analisis yang sama.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        PENCARIAN
    ========================================================== --}}

    <section class="rounded-2xl border-2 border-navy bg-cream/70 p-4 shadow-[6px_6px_0_0_rgba(27,42,65,0.08)] sm:p-5">

        <form
            method="GET"
            action="{{ route('pimpinan.clustering.index') }}"
            class="flex flex-col gap-3 lg:flex-row"
        >

            <div class="relative flex-1">

                <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate">
                    <i class="fas fa-search"></i>
                </span>

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari partai, kecamatan, klaster, atau tren..."
                    class="w-full rounded-xl border-2 border-navy/20 bg-white py-3 pl-11 pr-4 text-sm text-navy shadow-sm placeholder:text-slate/70 focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40"
                >

            </div>

            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-navy px-6 py-3 text-sm font-bold text-white shadow-lg shadow-navy/20 transition hover:-translate-y-0.5 hover:bg-gold hover:text-navy focus:outline-none focus-visible:ring-2 focus-visible:ring-gold focus-visible:ring-offset-2"
            >

                <i class="fas fa-search"></i>

                Cari

            </button>

            @if($search !== '')

                <a
                    href="{{ route('pimpinan.clustering.index') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-navy/20 bg-white px-5 py-3 text-sm font-bold text-navy transition hover:border-navy hover:bg-white"
                >

                    <i class="fas fa-times"></i>

                    Reset

                </a>

            @endif

        </form>

    </section>


    {{-- =========================================================
        DETAIL HASIL
    ========================================================== --}}

    <section class="overflow-hidden rounded-2xl border-2 border-navy bg-white shadow-none">

        <div class="relative overflow-hidden bg-navy px-6 py-5">

            <div class="relative z-10 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">

                <div>

                    <h2 class="text-lg font-bold leading-tight text-white">
                        Detail Hasil Clustering
                    </h2>

                    <p class="mt-0.5 text-xs text-cream/65">

                        Menampilkan {{ $hasilCluster->count() }} data

                        @if($search !== '')

                            untuk pencarian
                            "<span class="font-semibold text-gold-soft">{{ $search }}</span>"

                        @endif

                    </p>

                </div>

                <span class="text-xs font-semibold text-cream/70">
                    {{ $hasilCluster->count() }} data ditampilkan
                </span>

            </div>

            <div class="absolute inset-x-0 bottom-0 h-1 bg-maroon"></div>

        </div>

        @if($hasilCluster->isEmpty())

            <div class="bg-cream/40 p-10 text-center">

                <h3 class="mt-4 text-lg font-bold text-navy">
                    Data tidak ditemukan
                </h3>

                <p class="mt-1 text-sm text-slate">
                    Tidak terdapat hasil yang sesuai dengan kata kunci pencarian.
                </p>

                @if($search !== '')

                    <a
                        href="{{ route('pimpinan.clustering.index') }}"
                        class="mt-5 inline-flex items-center gap-2 rounded-xl bg-navy px-5 py-3 text-sm font-bold text-white transition hover:bg-gold hover:text-navy"
                    >

                        <i class="fas fa-redo"></i>

                        Tampilkan Semua Data

                    </a>

                @endif

            </div>

        @else

            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead>

                        <tr class="border-b-2 border-navy bg-cream text-navy">

                            <th class="whitespace-nowrap px-4 py-4 text-center text-xs font-bold uppercase tracking-wider">
                                No
                            </th>

                            <th class="whitespace-nowrap px-4 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                Kecamatan
                            </th>

                            <th class="whitespace-nowrap px-4 py-4 text-left text-xs font-bold uppercase tracking-wider">
                                Partai Politik
                            </th>

                            <th class="whitespace-nowrap px-4 py-4 text-right text-xs font-bold uppercase tracking-wider">
                                Share 2019
                            </th>

                            <th class="whitespace-nowrap px-4 py-4 text-right text-xs font-bold uppercase tracking-wider">
                                Share 2024
                            </th>

                            <th class="whitespace-nowrap px-4 py-4 text-right text-xs font-bold uppercase tracking-wider">
                                Perubahan Share
                            </th>

                            <th class="whitespace-nowrap px-4 py-4 text-center text-xs font-bold uppercase tracking-wider">
                                Klaster
                            </th>

                            <th class="whitespace-nowrap px-4 py-4 text-right text-xs font-bold uppercase tracking-wider">
                                Jarak ke Pusat
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-navy/10">

                        @foreach($hasilCluster as $index => $item)

                            @php

                                $kategori = $item->kategori_cluster;

                                $kategoriTinggi = $kategori === 'Tinggi';

                                $partai    = $item->partai;
                                $kecamatan = $item->kecamatan;

                            @endphp

                            <tr class="bg-white transition-colors hover:bg-cream/40">

                                {{-- No --}}

                                <td class="whitespace-nowrap px-4 py-4 text-center">
                                    {{ $index + 1 }}
                                </td>


                                {{-- Kecamatan --}}

                                <td class="whitespace-nowrap px-4 py-4">

                                    <div class="font-semibold text-navy">
                                        {{ $kecamatan?->nama_kecamatan ?? '-' }}
                                    </div>

                                </td>


                                {{-- Partai --}}

                                <td class="min-w-[220px] px-4 py-4">

                                    <div class="flex items-center gap-3">

                                        @if($partai?->logo_url)

                                            <img
                                                src="{{ $partai->logo_url }}"
                                                alt="Logo {{ $partai->singkatan_parpol }}"
                                                class="h-10 w-10 shrink-0 object-contain"
                                            >

                                        @else

                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-navy/5 text-navy">
                                                <i class="fas fa-flag text-xs"></i>
                                            </div>

                                        @endif

                                        <div>

                                            <p class="font-bold text-navy">
                                                {{ $partai?->singkatan_parpol ?? '-' }}
                                            </p>

                                            <p class="mt-0.5 text-xs text-slate">
                                                {{ $partai?->nama_lengkap_parpol ?? '-' }}
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                {{-- Share 2019 --}}

                                <td class="whitespace-nowrap px-4 py-4 text-right">

                                    <span class="font-semibold text-navy">
                                        {{ number_format((float) $item->share_2019, 2, ',', '.') }}%
                                    </span>

                                </td>


                                {{-- Share 2024 --}}

                                <td class="whitespace-nowrap px-4 py-4 text-right">

                                    <span class="font-semibold text-navy">
                                        {{ number_format((float) $item->share_2024, 2, ',', '.') }}%
                                    </span>

                                </td>


                                {{-- Perubahan --}}

                                <td class="whitespace-nowrap px-4 py-4 text-right">

                                    @if((float) $item->selisih_share > 0)

                                        <span class="font-bold text-green-700">
                                            +{{ number_format((float) $item->selisih_share, 2, ',', '.') }}%
                                        </span>

                                    @elseif((float) $item->selisih_share < 0)

                                        <span class="font-bold text-maroon">
                                            -{{ number_format(abs((float) $item->selisih_share), 2, ',', '.') }}%
                                        </span>

                                    @else

                                        <span class="font-bold text-slate">
                                            0,00%
                                        </span>

                                    @endif

                                </td>


                                {{-- Klaster --}}

                                <td class="whitespace-nowrap px-4 py-4 text-center">

                                    <span class="inline-flex items-center gap-1.5 rounded-lg border-2 px-3 py-1 text-xs font-bold shadow-sm {{ $kategoriTinggi ? 'border-green-700 bg-green-600 text-white' : ($kategori === 'Rendah' ? 'border-red-700 bg-red-600 text-white' : 'border-navy bg-slate-100 text-slate-800') }}">

                                        <span class="text-[9px] leading-none">
                                            {{ $kategoriTinggi ? '▲' : ($kategori === 'Rendah' ? '▼' : '●') }}
                                        </span>

                                        {{ $kategori ?? '-' }}

                                    </span>

                                </td>


                                {{-- Jarak --}}

                                <td class="whitespace-nowrap px-4 py-4 text-right">

                                    <span class="font-mono text-xs font-semibold text-slate">

                                        {{ $item->jarak_centroid !== null
                                            ? number_format((float) $item->jarak_centroid, 4, ',', '.')
                                            : '-' }}

                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @endif

    </section>


    {{-- =========================================================
        CATATAN INTERPRETASI
    ========================================================== --}}

    <section class="relative overflow-hidden rounded-2xl border-2 border-navy bg-navy shadow-[8px_8px_0_0_rgba(27,42,65,0.16)]">

        <div class="absolute left-0 top-0 h-full w-1.5 bg-maroon"></div>

        <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full border-[26px] border-gold/10"></div>

        <div class="relative z-10 p-6 md:p-7">

            <div class="flex items-start gap-4">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gold text-navy shadow-lg shadow-black/20">
                    <i class="fas fa-lightbulb"></i>
                </div>

                <div class="min-w-0">

                    <h2 class="text-lg font-bold text-white">
                        Catatan Interpretasi
                    </h2>

                    <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">

                        <div class="rounded-xl border border-white/15 bg-white/[0.05] p-4 text-sm leading-relaxed text-white/80">

                            <span class="font-bold text-gold-soft">
                                Share suara
                            </span>

                            menunjukkan proporsi perolehan suara suatu partai terhadap
                            total suara partai pada kecamatan dan tahun pemilu terkait.

                        </div>

                        <div class="rounded-xl border border-white/15 bg-white/[0.05] p-4 text-sm leading-relaxed text-white/80">

                            <span class="font-bold text-gold-soft">
                                Perubahan share
                            </span>

                            merupakan selisih antara share 2024 dan share 2019.
                            Nilai positif menunjukkan peningkatan proporsi, sedangkan
                            nilai negatif menunjukkan penurunan proporsi.

                        </div>

                        <div class="rounded-xl border border-white/15 bg-white/[0.05] p-4 text-sm leading-relaxed text-white/80">

                            <span class="font-bold text-gold-soft">
                                Klaster Tinggi dan Rendah
                            </span>

                            merupakan hasil pengelompokan berdasarkan kemiripan
                            karakteristik share suara 2019 dan 2024, bukan batas
                            mutlak jumlah suara tertentu.

                        </div>

                        <div class="rounded-xl border border-white/15 bg-white/[0.05] p-4 text-sm leading-relaxed text-white/80">

                            Hasil clustering digunakan untuk melihat pola kemiripan
                            perolehan suara antar kombinasi kecamatan dan partai,
                            sedangkan perubahan share digunakan sebagai informasi
                            tambahan untuk membaca arah tren.

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>

@endsection