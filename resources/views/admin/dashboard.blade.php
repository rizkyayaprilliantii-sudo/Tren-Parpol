@extends('layouts.sidebar')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard')

@section('content')

@php
    $card = 'overflow-hidden rounded-xl border-2 border-navy bg-white shadow-sm';
    $cardLabel = 'border-b-2 border-navy bg-navy px-5 py-3 text-sm font-bold text-cream';

    $fmt = fn ($n) => number_format($n, 0, ',', '.');
    $sgn = fn ($n) => $n > 0 ? '+' : ($n < 0 ? '−' : '');

    /*
    |--------------------------------------------------------------------------
    | Data tren perolehan suara
    |--------------------------------------------------------------------------
    */

    $tren = collect($topTrenPartai ?? []);
    $divData = $tren->sortByDesc('selisih_share')->values();

    $maxAbs = max(
        0.0001,
        (float) ($divData->map(fn ($p) => abs((float) ($p['selisih_share'] ?? 0)))->max() ?? 0)
    );

    $dumbData = $tren
        ->sortByDesc(fn ($p) => abs((float) ($p['selisih_share'] ?? 0)))
        ->take(6)
        ->values();

    $maxDumb = max(
        1,
        (int) ($dumbData->map(
            fn ($p) => max((int) ($p['suara_2019'] ?? 0), (int) ($p['suara_2024'] ?? 0))
        )->max() ?? 0)
    );

    $totalTren = ($jumlahPartaiNaik ?? 0)
        + ($jumlahPartaiTurun ?? 0)
        + ($jumlahPartaiTetap ?? 0);

    $komposisi = [
        ['Meningkat', $jumlahPartaiNaik ?? 0, 'bg-green-700 text-white'],
        ['Menurun', $jumlahPartaiTurun ?? 0, 'bg-maroon text-white'],
        ['Tetap', $jumlahPartaiTetap ?? 0, 'bg-navy text-cream'],
    ];
@endphp

<div class="space-y-10 pb-6">

    {{-- HERO DASHBOARD --}}
    <section class="relative overflow-hidden rounded-2xl border-2 border-gold bg-navy p-6 text-cream sm:p-8 lg:p-10">

        <div class="relative z-10 max-w-5xl">

            <p class="text-sm font-bold tracking-wide text-gold-soft">
                PEMILU LEGISLATIF KOTA BANJARMASIN
            </p>

            <h2 class="mt-2 max-w-4xl text-3xl font-bold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
                Analisis Tren Perolehan Suara Partai Politik
            </h2>

            <p class="mt-5 max-w-4xl text-sm leading-7 text-cream/80 sm:text-base">
                Ringkasan perolehan suara Pemilu Legislatif 2019 dan 2024 di Kota Banjarmasin, meliputi rekapitulasi suara, tren perubahan proporsi suara partai politik, serta ringkasan hasil K-Means Clustering.
            </p>

        </div>

        {{-- Dekorasi --}}
        <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full border-[30px] border-gold/10"></div>

        <div class="pointer-events-none absolute -bottom-28 right-20 h-56 w-56 rounded-full bg-gold/[0.06] blur-2xl"></div>

    </section>

    {{-- RINGKASAN DATA SUARA --}}
    <section>

        <div class="mb-4">

            <h3 class="text-xl font-bold text-navy">
                Ringkasan Data Suara
            </h3>

            <p class="mt-1 text-sm leading-6 text-slate">
                Rekap jumlah suara seluruh partai pada Pemilu Legislatif 2019 dan 2024 di Kota Banjarmasin.
            </p>

        </div>

        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">

            @foreach ([
                [
                    'Total Suara 2019',
                    $fmt($totalSuara2019 ?? 0),
                    'text-navy',
                    'Jumlah suara seluruh partai pada 2019'
                ],
                [
                    'Total Suara 2024',
                    $fmt($totalSuara2024 ?? 0),
                    'text-navy',
                    'Jumlah suara seluruh partai pada 2024'
                ],
                [
                    'Selisih Suara',
                    $sgn($selisihTotalSuara ?? 0) . $fmt(abs($selisihTotalSuara ?? 0)),
                    ($selisihTotalSuara ?? 0) > 0
                        ? 'text-green-700'
                        : (($selisihTotalSuara ?? 0) < 0 ? 'text-maroon' : 'text-navy'),
                    'Total suara 2024 dikurangi total suara 2019'
                ],
                [
                    'Perubahan Total Suara',
                    $sgn($persentasePerubahan ?? 0) . number_format(abs($persentasePerubahan ?? 0), 2, ',', '.') . '%',
                    ($persentasePerubahan ?? 0) > 0
                        ? 'text-green-700'
                        : (($persentasePerubahan ?? 0) < 0 ? 'text-maroon' : 'text-navy'),
                    $statusPerubahan ?? 'Belum tersedia'
                ],
            ] as [$label, $value, $color, $note])

                <article class="{{ $card }}">

                    <p class="{{ $cardLabel }}">
                        {{ $label }}
                    </p>

                    <div class="p-5">

                        <p class="break-words text-2xl font-bold tabular-nums sm:text-3xl {{ $color }}">
                            {{ $value }}
                        </p>

                        <p class="mt-2 text-xs leading-5 text-slate">
                            {{ $note }}
                        </p>

                    </div>

                </article>

            @endforeach

        </div>

    </section>

    {{-- ANALISIS TREN PEROLEHAN SUARA --}}
    <section>

        <div class="mb-4">

            <h3 class="text-xl font-bold text-navy">
                Analisis Tren Proporsi Suara
            </h3>

            <p class="mt-1 text-sm leading-6 text-slate">
                Perubahan proporsi suara setiap partai terhadap total suara tingkat Kota Banjarmasin dari 2019 ke 2024. Selisih ditampilkan dalam poin persentase (pp).
            </p>

        </div>

        <div class="grid gap-5 xl:grid-cols-[minmax(0,5fr)_minmax(0,7fr)]">

            {{-- KOMPOSISI TREN --}}
            <div class="{{ $card }}">

                <p class="{{ $cardLabel }}">
                    Komposisi Tren Partai
                </p>

                <div class="p-5">

                    @if ($totalTren > 0)

                        <div class="flex h-12 w-full overflow-hidden rounded-lg border-2 border-navy">

                            @foreach ($komposisi as [$label, $jumlah, $warna])

                                @if ($jumlah > 0)

                                    <div
                                        class="flex items-center justify-center text-sm font-bold {{ $warna }}"
                                        style="width: {{ $jumlah / $totalTren * 100 }}%"
                                        title="{{ $label }}: {{ $jumlah }} partai"
                                    >
                                        {{ $jumlah }}
                                    </div>

                                @endif

                            @endforeach

                        </div>

                        <dl class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

                            @foreach ($komposisi as [$label, $jumlah, $warna])

                                <div class="border-l-4 border-navy pl-3">

                                    <dt class="font-bold text-navy">
                                        {{ $label }}
                                    </dt>

                                    <dd class="mt-1 text-sm text-slate">
                                        {{ $jumlah }} partai
                                        ({{ number_format($jumlah / $totalTren * 100, 1, ',', '.') }}%)
                                    </dd>

                                </div>

                            @endforeach

                        </dl>

                    @else

                        <p class="py-8 text-center text-sm font-semibold text-slate">
                            Data perbandingan partai belum tersedia.
                        </p>

                    @endif

                </div>

            </div>

            {{-- PERUBAHAN PROPORSI --}}
            <div class="{{ $card }}">

                <p class="{{ $cardLabel }}">
                    Perubahan Proporsi per Partai
                </p>

                <div class="p-5">

                    @forelse ($divData as $p)

                        @php
                            $selisihShare = (float) ($p['selisih_share'] ?? 0);
                            $w = abs($selisihShare) / $maxAbs * 50;
                            $naik = $selisihShare >= 0;
                        @endphp

                        <div class="grid grid-cols-[5.5rem_minmax(0,1fr)_6rem] items-center gap-3 py-2">

                            <p
                                class="truncate text-sm font-bold text-navy"
                                title="{{ $p['nama_lengkap'] ?? $p['singkatan'] ?? 'Partai' }}"
                            >
                                {{ $p['singkatan'] ?? '-' }}
                            </p>

                            <div class="relative h-6 bg-cream/60">

                                <div class="absolute inset-y-0 left-1/2 w-0.5 bg-navy"></div>

                                <div
                                    class="absolute inset-y-0.5 {{ $naik ? 'left-1/2 bg-green-700' : 'right-1/2 bg-maroon' }}"
                                    style="width: {{ min(50, max(0.8, $w)) }}%"
                                ></div>

                            </div>

                            <p class="text-right text-sm font-bold tabular-nums {{ $naik ? 'text-green-700' : 'text-maroon' }}">
                                {{ $sgn($selisihShare) }}{{ number_format(abs($selisihShare), 2, ',', '.') }} pp
                            </p>

                        </div>

                    @empty

                        <p class="py-8 text-center text-sm font-semibold text-slate">
                            Data tren proporsi belum tersedia.
                        </p>

                    @endforelse

                    @if ($divData->isNotEmpty())

                        <p class="mt-3 border-t-2 border-navy/10 pt-3 text-xs leading-5 text-slate">
                            Batang ke kiri menunjukkan penurunan proporsi suara, sedangkan batang ke kanan menunjukkan kenaikan proporsi suara.
                        </p>

                    @endif

                </div>

            </div>

        </div>

    </section>

    {{-- PERBANDINGAN SUARA PARTAI --}}
    <section class="{{ $card }}">

        <div class="flex flex-col gap-3 border-b-2 border-navy bg-navy px-5 py-3 text-cream sm:flex-row sm:items-center sm:justify-between">

            <div>

                <h3 class="text-lg font-bold">
                    Perbandingan Suara Partai 2019 dan 2024
                </h3>

                <p class="mt-1 text-sm leading-6 text-cream/75">
                    Enam partai dengan perubahan proporsi terbesar. Jumlah suara ditampilkan sebagai informasi pendukung.
                </p>

            </div>

            <span class="inline-flex w-fit flex-shrink-0 items-center rounded-lg border border-gold/50 bg-white/5 px-3 py-1.5 font-mono text-xs font-bold text-gold-soft">
                2019 → 2024
            </span>

        </div>

        <div class="p-3 sm:p-4">

            <div class="mb-2 flex flex-wrap items-center gap-x-5 gap-y-1 text-xs font-bold text-navy">

                <span class="inline-flex items-center gap-2">
                    <span class="h-3.5 w-3.5 rounded-full border-2 border-navy bg-white"></span>
                    Suara 2019
                </span>

                <span class="inline-flex items-center gap-2">
                    <span class="h-3.5 w-3.5 rounded-full border-2 border-navy bg-gold"></span>
                    Suara 2024
                </span>

            </div>

            @forelse ($dumbData as $p)

                @php
                    $suara2019 = (int) ($p['suara_2019'] ?? 0);
                    $suara2024 = (int) ($p['suara_2024'] ?? 0);
                    $a = $suara2019 / $maxDumb * 100;
                    $b = $suara2024 / $maxDumb * 100;
                    $selisihShare = (float) ($p['selisih_share'] ?? 0);
                @endphp

                <div class="grid grid-cols-[minmax(5.5rem,8.5rem)_minmax(0,1fr)_5.5rem] items-center gap-3 border-t border-navy/10 py-2 first:border-t-0 first:pt-0 sm:grid-cols-[8.5rem_minmax(0,1fr)_6rem] sm:gap-4">

                    <div class="min-w-0">

                        <p
                            class="truncate font-bold text-navy"
                            title="{{ $p['nama_lengkap'] ?? '' }}"
                        >
                            {{ $p['singkatan'] ?? '-' }}
                        </p>

                        <p class="mt-0.5 text-[11px] leading-4 tabular-nums text-slate">
                            {{ $fmt($suara2019) }} → {{ $fmt($suara2024) }}
                        </p>

                    </div>

                    <div class="relative mx-1 h-6 sm:mx-2">

                        <div class="absolute inset-x-0 top-1/2 h-px bg-navy/25"></div>

                        <div
                            class="absolute top-1/2 h-1 -translate-y-1/2 bg-navy"
                            style="left: {{ min($a, $b) }}%; width: {{ abs($a - $b) }}%"
                        ></div>

                        <span
                            class="absolute top-1/2 h-4 w-4 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-navy bg-white"
                            style="left: {{ $a }}%"
                            title="2019: {{ $fmt($suara2019) }}"
                        ></span>

                        <span
                            class="absolute top-1/2 h-4 w-4 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-navy bg-gold"
                            style="left: {{ $b }}%"
                            title="2024: {{ $fmt($suara2024) }}"
                        ></span>

                    </div>

                    <p class="text-right text-xs font-bold tabular-nums sm:text-sm {{ $selisihShare > 0 ? 'text-green-700' : ($selisihShare < 0 ? 'text-maroon' : 'text-slate') }}">
                        {{ $sgn($selisihShare) }}{{ number_format(abs($selisihShare), 2, ',', '.') }} pp
                    </p>

                </div>

            @empty

                <div class="py-10 text-center">

                    <p class="text-sm font-semibold text-slate">
                        Data perbandingan suara belum tersedia.
                    </p>

                    <p class="mt-1 text-xs text-slate">
                        Pastikan data rekapitulasi tahun 2019 dan 2024 tersedia.
                    </p>

                </div>

            @endforelse

        </div>

    </section>

    {{-- RINGKASAN K-MEANS CLUSTERING --}}
    @php
        $metrikKMeans = is_array($metrikKMeans ?? null) ? $metrikKMeans : [];
        $nilaiK = $metrikKMeans['k'] ?? null;
        $nilaiSilhouette = $metrikKMeans['silhouette'] ?? null;
        $totalObservasiKMeans = 0;
        $adaHasilKMeans = false;

        if (
            \Illuminate\Support\Facades\Schema::hasTable('analisis_clustering') &&
            \Illuminate\Support\Facades\Schema::hasTable('hasil_clustering') &&
            \Illuminate\Support\Facades\Schema::hasColumn('analisis_clustering', 'id') &&
            \Illuminate\Support\Facades\Schema::hasColumn('hasil_clustering', 'analisis_id')
        ) {
            $analisisTerakhirDashboard = \Illuminate\Support\Facades\DB::table('analisis_clustering')
                ->orderByDesc('id')
                ->first();

            if ($analisisTerakhirDashboard) {
                $totalObservasiKMeans = \Illuminate\Support\Facades\DB::table('hasil_clustering')
                    ->where('analisis_id', $analisisTerakhirDashboard->id)
                    ->count();

                $adaHasilKMeans = $totalObservasiKMeans > 0;
            }
        }
    @endphp

    @if ($adaHasilKMeans)

        <section>

            <div class="mb-4">

                <h3 class="text-xl font-bold text-navy">
                    Ringkasan K-Means Clustering
                </h3>

                <p class="mt-1 text-sm leading-6 text-slate">
                    Ringkasan hasil analisis clustering terbaru. Rincian anggota setiap klaster tersedia pada halaman Clustering.
                </p>

            </div>

            <div class="{{ $card }}">

                <div class="border-b-2 border-navy bg-navy px-5 py-3 text-cream sm:flex sm:items-center sm:justify-between sm:gap-4">

                    <div>

                        <p class="font-mono text-[11px] font-bold uppercase tracking-[0.14em] text-gold-soft">
                            Hasil Analisis Terbaru
                        </p>

                        <h4 class="mt-1 text-lg font-bold text-white">
                            K-Means Clustering
                        </h4>

                    </div>

                    @if (is_numeric($nilaiK))

                        <span class="mt-2 inline-flex w-fit items-center rounded-lg border border-gold/60 bg-gold px-3 py-1.5 text-xs font-bold text-navy sm:mt-0">
                            K = {{ (int) $nilaiK }}
                        </span>

                    @endif

                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2">

                    <div class="flex items-center justify-between gap-4 border-b border-navy/10 px-5 py-4 sm:border-b-0 sm:border-r">

                        <div>

                            <p class="text-sm font-semibold text-slate">
                                Total Observasi
                            </p>

                            <p class="mt-0.5 text-xs text-slate">
                                Pasangan kecamatan dan partai
                            </p>

                        </div>

                        <p class="text-2xl font-bold tabular-nums text-navy">
                            {{ $fmt($totalObservasiKMeans) }}
                        </p>

                    </div>

                    @if (is_numeric($nilaiSilhouette))

                        <div class="flex items-center justify-between gap-4 px-5 py-4">

                            <div>

                                <p class="text-sm font-semibold text-slate">
                                    Silhouette Score
                                </p>

                                <p class="mt-0.5 text-xs text-slate">
                                    Evaluasi hasil pengelompokan
                                </p>

                            </div>

                            <p class="text-2xl font-bold tabular-nums text-navy">
                                {{ number_format((float) $nilaiSilhouette, 4, ',', '.') }}
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </section>

    @endif

    {{-- CAKUPAN DATA --}}
    <section>

        <div class="mb-4">

            <h3 class="text-xl font-bold text-navy">
                Cakupan Data Analisis
            </h3>

            <p class="mt-1 text-sm leading-6 text-slate">
                Jumlah data partai dan wilayah yang tersedia dalam basis data.
            </p>

        </div>

        <div class="{{ $card }}">

            <dl class="grid grid-cols-2 xl:grid-cols-4">

                @foreach ([
                    ['Partai Politik', $countPartai ?? 0, 'Data pada master partai'],
                    ['Kecamatan', $countKecamatan ?? 0, 'Wilayah Kota Banjarmasin'],
                    ['Kelurahan', $countKelurahan ?? 0, 'Data wilayah tersedia'],
                    ['TPS', $countTps ?? 0, 'Tempat pemungutan suara'],
                ] as $i => [$label, $value, $note])

                    <div class="p-5
                        {{ $i % 2 === 1 ? 'border-l-2 border-navy/15 xl:border-l-2' : '' }}
                        {{ $i >= 2 ? 'border-t-2 border-navy/15 xl:border-t-0' : '' }}
                        {{ $i === 2 ? 'xl:border-l-0' : '' }}
                    ">

                        <dt class="text-sm font-bold text-slate">
                            {{ $label }}
                        </dt>

                        <dd class="mt-1 text-3xl font-bold tabular-nums text-navy">
                            {{ $fmt($value) }}
                        </dd>

                        <p class="mt-1 text-xs leading-5 text-slate">
                            {{ $note }}
                        </p>

                    </div>

                @endforeach

            </dl>

        </div>

    </section>

</div>

@endsection