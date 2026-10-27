@extends('layouts.pimpinan')

@section('title', 'Dashboard Pimpinan | Tren Parpol')

@section('page-title', 'Dashboard Analisis Pemilu')

@push('styles')

<style>

    :root {

        --navy: #1B2A41;

        --navy-soft: #2C4160;

        --maroon: #7A2E2E;

        --gold: #C9A24B;

        --gold-soft: #E3C878;

        --cream: #F4F1E8;

        --ink-soft: #5B6472;

        --green: #2F855A;

        --line: rgba(27, 42, 65, .22);

        --shadow: 0 1px 2px rgba(27, 42, 65, .08), 0 8px 20px rgba(27, 42, 65, .09);

    }

    .dashboard-wrap { width: 100%; max-width: 1500px; margin: 0 auto; }

    .dashboard-hero {

        position: relative; overflow: hidden; padding: 22px 28px;

        background: var(--navy); color: var(--cream);

        border-radius: 16px; border: 0;
        box-shadow: none;

    }

    .dashboard-hero::before {

        content: ''; position: absolute; right: -60px; top: -70px;

        width: 200px; height: 200px; border-radius: 50%;

        border: 26px solid rgba(201, 162, 75, .16); pointer-events: none;

    }

    .hero-inner {

        position: relative; z-index: 2; display: flex; align-items: center;

        justify-content: space-between; gap: 24px;

    }

    .hero-title { margin: 0; font-size: 32px; font-weight: 700; line-height: 1.2; color: var(--cream); }

    .hero-description {

        margin: 10px 0 0; max-width: none; font-size: 15px;

        font-weight: 400; line-height: 1.6; color: rgba(244, 241, 232, .75);

        white-space: normal;

    }

    .hero-guide {

        position: relative; z-index: 2; display: grid;

        grid-template-columns: repeat(4, minmax(0, 1fr));

        margin: 18px 0 0; padding: 16px 0 0; list-style: none;

        border-top: 1.5px solid var(--gold);

    }

    .hero-guide li {

        min-height: 86px; padding: 0 22px; font-size: 12px;

        line-height: 1.55; color: rgba(244, 241, 232, .75);

        border-right: 1.5px solid var(--gold);

    }

    .hero-guide li:first-child { padding-left: 0; }

    .hero-guide li:last-child { padding-right: 0; border-right: 0; }

    .hero-guide strong { display: block; margin-bottom: 4px; font-size: 12.5px; color: var(--gold-soft); }

    .dashboard-section { margin-top: 26px; }

    .section-heading { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }

    .section-heading::before {

        content: ''; width: 5px; align-self: stretch; border-radius: 4px;

        background: linear-gradient(var(--gold), var(--maroon));

    }

    .section-title { margin: 0; font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700; color: var(--navy); }

    .section-description { margin: 3px 0 0; font-size: 13px; color: var(--ink-soft); }

    .panel {

        overflow: hidden; background: #fff; border-radius: 14px;

        border: 1.5px solid var(--navy); box-shadow: var(--shadow);

    }

    .panel-header {

        display: flex; align-items: flex-start; justify-content: space-between;

        gap: 15px; padding: 16px 20px 13px;

        background: linear-gradient(180deg, #F4F1E8, #FBF9F3);

        border-bottom: 1.5px solid var(--navy);

    }

    .panel-title { margin: 0; font-family: 'Fraunces', serif; font-size: 17px; font-weight: 700; color: var(--navy); }

    .panel-description { margin: 3px 0 0; font-size: 12px; line-height: 1.5; color: var(--ink-soft); }

    .panel-badge {

        flex-shrink: 0; padding: 5px 10px; border-radius: 999px;

        background: var(--navy); font-family: 'JetBrains Mono', monospace;

        font-size: 10px; font-weight: 700; color: var(--gold-soft);

    }

    .panel-body { padding: 18px 20px; }

    .kpi-strip { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); }

    .kpi { padding: 20px 22px; border-right: 1.5px solid var(--navy); border-top: 4px solid transparent; }

    .kpi:last-child { border-right: 0; }

    .kpi.k-navy { border-top-color: var(--navy); }

    .kpi.k-maroon { border-top-color: var(--maroon); }

    .kpi.k-gold { border-top-color: var(--gold); }

    .kpi.k-up { border-top-color: var(--green); }

    .kpi.k-down { border-top-color: var(--maroon); }

    .kpi-label { font-size: 12px; font-weight: 700; color: var(--ink-soft); }

    .kpi-value {

        margin-top: 6px; font-family: 'Fraunces', serif; font-size: 29px;

        font-weight: 700; line-height: 1.05; color: var(--navy);

    }

    .kpi-value.up { color: var(--green); }

    .kpi-value.down { color: var(--maroon); }

    .kpi-note { margin-top: 6px; font-size: 11px; line-height: 1.45; color: var(--ink-soft); }

    .compare-bars { padding: 4px 22px 20px; border-top: 1.5px solid var(--navy); background: #FBF9F3; }

    .compare-bars-title { padding-top: 14px; font-size: 12px; font-weight: 700; color: var(--ink-soft); }

    .cbar-row {

        display: grid; grid-template-columns: 48px minmax(0, 1fr) 120px;

        align-items: center; gap: 12px; margin-top: 9px;

    }

    .cbar-year { font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 800; color: var(--navy); }

    .cbar-track { height: 14px; border-radius: 999px; background: #E7E4DB; overflow: hidden; }

    .cbar-fill { height: 100%; border-radius: 999px; }

    .cbar-fill.f2019 { background: var(--navy); }

    .cbar-fill.f2024 { background: var(--maroon); }

    .cbar-val { text-align: right; font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 700; color: var(--navy); }

    .main-grid {

        display: grid; grid-template-columns: minmax(0, 1.6fr) minmax(300px, .8fr);

        gap: 20px; align-items: start;

    }

    .side-stack { display: grid; gap: 20px; }

    .legend { display: flex; flex-wrap: wrap; gap: 14px; font-size: 11px; font-weight: 600; color: var(--ink-soft); }

    .legend span::before {

        content: ''; display: inline-block; width: 10px; height: 10px;

        margin-right: 6px; border-radius: 3px; vertical-align: -1px;

        background: var(--c, var(--navy));

    }

    .recap-wrap { overflow-x: auto; }

    .recap-table { width: 100%; border-collapse: collapse; min-width: 640px; }

    .recap-table th {

        padding: 10px; text-align: left; font-size: 11px; font-weight: 700;

        color: var(--ink-soft); border-bottom: 2px solid var(--navy); white-space: nowrap;

    }

    .recap-table th.num, .recap-table td.num { text-align: right; }

    .recap-table td {

        padding: 11px 10px; border-bottom: 1px solid rgba(27, 42, 65, .22);

        font-size: 12px; color: var(--navy); vertical-align: middle;

    }

    .recap-table tbody tr:hover { background: rgba(201, 162, 75, .12); }

    .recap-table tr:last-child td { border-bottom: 0; }

    .party-name { font-weight: 700; font-size: 13px; }

    .party-full-name { margin-top: 2px; font-size: 10px; color: var(--ink-soft); }

    .bar-pair { min-width: 210px; }

    .bar-line { display: grid; grid-template-columns: minmax(0, 1fr) 70px; align-items: center; gap: 8px; }

    .bar-line + .bar-line { margin-top: 4px; }

    .bar-track { height: 9px; border-radius: 999px; background: #E7E4DB; overflow: hidden; }

    .bar-track span { display: block; height: 100%; border-radius: 999px; }

    .b2019 { background: var(--navy); }

    .b2024 { background: var(--maroon); }

    .bar-num { text-align: right; font-family: 'JetBrains Mono', monospace; font-size: 10.5px; font-weight: 700; }

    .delta { font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 700; white-space: nowrap; }

    .delta.up { color: var(--green); }

    .delta.down { color: var(--maroon); }

    .delta.flat { color: var(--ink-soft); }

    .stack-bar {

        display: flex; height: 22px; overflow: hidden; border-radius: 999px;

        background: #E7E4DB; border: 1px solid var(--navy);

    }

    .stack-bar span { display: block; height: 100%; }

    .stack-bar span + span { border-left: 2px solid var(--navy); }

    .stat-list { margin-top: 14px; display: grid; gap: 0; }

    .stat-row {

        display: flex; align-items: center; justify-content: space-between;

        gap: 10px; padding: 10px 0; border-bottom: 1px solid var(--navy);

        font-size: 13px; color: var(--navy);

    }

    .stat-row:last-child { border-bottom: 0; }

    .stat-row .lbl::before {

        content: ''; display: inline-block; width: 10px; height: 10px;

        margin-right: 8px; border-radius: 3px; background: var(--c);

    }

    .stat-row .val { font-family: 'Fraunces', serif; font-size: 22px; font-weight: 700; }

    .stat-row .pct { margin-left: 6px; font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--ink-soft); }

    .coverage-list {

        margin: 0; padding: 0; list-style: none; display: grid;

        grid-template-columns: repeat(5, minmax(0, 1fr));

    }

    .coverage-list li {

        display: flex; flex-direction: column; justify-content: center; gap: 5px;

        min-height: 76px; padding: 12px 18px; border-right: 1.5px solid var(--navy);

        font-size: 12px; color: var(--ink-soft);

    }

    .coverage-list li:last-child { border-right: 0; }

    .coverage-list strong {

        font-family: 'Fraunces', serif; font-size: 24px; line-height: 1; color: var(--navy);

    }

    .cluster-grid {

        display: grid; grid-template-columns: repeat(2, minmax(0, 1fr));

        gap: 24px; padding: 18px 20px;

    }

    .cluster-metrics {

        display: grid; grid-template-columns: repeat(3, minmax(0, 1fr));

        gap: 0; margin: 0; padding: 0; list-style: none;

        border-top: 1px solid rgba(27, 42, 65, .22);

    }

    .cluster-metrics li {

        display: flex; flex-direction: column; justify-content: center; gap: 5px;

        min-height: 76px; padding: 12px 18px; border-right: 1.5px solid var(--navy);

        font-size: 12px; color: var(--ink-soft);

    }

    .cluster-metrics li:last-child { border-right: 0; }

    .cluster-metrics strong {

        font-family: 'Fraunces', serif; font-size: 24px; line-height: 1; color: var(--navy);

    }

    .cluster-period-title {

        display: flex; align-items: baseline; justify-content: space-between;

        font-family: 'Fraunces', serif; font-size: 16px; font-weight: 700; color: var(--navy);

    }

    .cluster-period-total { font-family: 'JetBrains Mono', monospace; font-size: 10px; font-weight: 700; color: var(--ink-soft); }

    .cluster-period { padding-top: 10px; border-top: 4px solid var(--navy); }

    .cluster-period.p2024 { border-top-color: var(--maroon); }

    .cluster-bars { margin-top: 12px; display: grid; gap: 10px; }

    .cluster-line {

        display: grid; grid-template-columns: 78px minmax(0, 1fr) 54px;

        align-items: center; gap: 10px; padding-bottom: 8px;

        border-bottom: 1px solid var(--navy); font-size: 12px;

    }

    .cluster-line:last-child { border-bottom: 0; }

    .cluster-name { font-weight: 700; color: var(--navy); }

    .cluster-track { height: 12px; border-radius: 999px; background: #E7E4DB; overflow: hidden; }

    .cluster-track span { display: block; height: 100%; border-radius: 999px; }

    .cluster-count { text-align: right; font-family: 'JetBrains Mono', monospace; font-weight: 800; color: var(--navy); }

    .cluster-count small { font-weight: 600; font-size: 10px; color: var(--ink-soft); }

    .ck0 { background: var(--navy); }

    .ck1 { background: var(--gold); }

    .ck2 { background: var(--maroon); }

    .ck3 { background: var(--green); }

    .empty-note { font-size: 12px; color: var(--ink-soft); }

    .foot-note { margin-top: 22px; font-size: 11px; line-height: 1.6; color: var(--ink-soft); }

    @media (max-width: 1200px) {

        .kpi-strip { grid-template-columns: repeat(2, minmax(0, 1fr)); }

        .kpi:nth-child(2) { border-right: 0; }

        .kpi:nth-child(-n+2) { border-bottom: 1.5px solid var(--navy); }

        .main-grid { grid-template-columns: 1fr; }

    }

    @media (max-width: 1000px) {

        .hero-guide { grid-template-columns: repeat(2, minmax(0, 1fr)); }

        .hero-guide li:nth-child(2) { border-right: 0; }

        .hero-guide li:nth-child(3), .hero-guide li:nth-child(4) {

            margin-top: 12px; padding-top: 12px; border-top: 1.5px solid var(--gold);

        }

    }

    @media (max-width: 800px) {

        .hero-inner { flex-direction: column; align-items: flex-start; }

        .cluster-grid { grid-template-columns: 1fr; }

        .coverage-list { grid-template-columns: repeat(2, minmax(0, 1fr)); }

        .cluster-metrics { grid-template-columns: 1fr; }

        .cluster-metrics li { border-right: 0; border-bottom: 1.5px solid var(--navy); }

        .cluster-metrics li:last-child { border-bottom: 0; }

        .coverage-list li:nth-child(2n) { border-right: 0; }

        .coverage-list li { border-bottom: 1.5px solid var(--navy); }

        .coverage-list li:last-child { border-bottom: 0; }

    }

    @media (max-width: 600px) {

        .dashboard-hero { padding: 20px; }

        .hero-title { font-size: 27px; }

        .hero-description { white-space: normal; font-size: 14px; }

        .hero-guide { grid-template-columns: 1fr; }

        .hero-guide li, .hero-guide li:nth-child(2), .hero-guide li:nth-child(3), .hero-guide li:nth-child(4) {

            margin-top: 0; padding: 12px 0; border-right: 0; border-top: 1.5px solid var(--gold);

        }

        .hero-guide li:first-child { padding-top: 0; border-top: 0; }

        .kpi-strip { grid-template-columns: 1fr; }

        .kpi { border-right: 0; border-bottom: 1.5px solid var(--navy); }

        .kpi:last-child { border-bottom: 0; }

        .cbar-row { grid-template-columns: 40px minmax(0, 1fr); }

        .cbar-val { grid-column: 2; text-align: left; }

        .coverage-list { grid-template-columns: 1fr; }

        .coverage-list li { border-right: 0; }

    }

</style>

@endpush

@section('content')

@php
    $persen = (float) ($persentasePerubahan ?? 0);
    $total2019 = (int) ($totalSuara2019 ?? 0);
    $total2024 = (int) ($totalSuara2024 ?? 0);
    $selisihTotal = (int) ($selisihTotalSuara ?? ($total2024 - $total2019));
    $maxTotal = max(1, $total2019, $total2024);

    $naik = (int) ($jumlahMeningkat ?? 0);
    $turun = (int) ($jumlahMenurun ?? 0);
    $tetap = (int) ($jumlahPartaiTetap ?? 0);
    $totalTren = $naik + $turun + $tetap;
    $pctTren = fn ($n) => $totalTren > 0 ? ($n / $totalTren) * 100 : 0;

    $rekapDashboard = collect($trenPartai ?? [])
        ->sortByDesc(fn ($item) => (int) ($item['suara_2024'] ?? 0))
        ->take(6)
        ->values();

    $maxSuara = max(
        1,
        (int) ($rekapDashboard->map(
            fn ($item) => max(
                (int) ($item['suara_2019'] ?? 0),
                (int) ($item['suara_2024'] ?? 0)
            )
        )->max() ?? 0)
    );

    $jumlahPartaiPembanding = collect($trenPartai ?? [])->count();
    $jumlahKecamatanDashboard = (int) ($countKecamatan ?? $jumlahKecamatan ?? 0);
    $jumlahKelurahanDashboard = (int) ($countKelurahan ?? $jumlahKelurahan ?? 0);
    $jumlahTpsDashboard = (int) ($countTps ?? $jumlahTps ?? 0);
@endphp

<div class="dashboard-wrap">

    {{-- HEADER --}}

    <section class="dashboard-hero">

        <div class="hero-inner">

            <div>

                <h1 class="hero-title">Dashboard Analisis Pemilu</h1>

                <p class="hero-description">

                    Halaman ini menyajikan gambaran umum data dan hasil analisis perolehan suara partai politik pada Pemilu Legislatif 2019 dan 2024 di Kota Banjarmasin.

                </p>

            </div>

        </div>

        <ul class="hero-guide">

            <li>

                <strong>Ringkasan Utama</strong>

                Total suara 2019 dan 2024 serta perubahan perolehan suara.

            </li>

            <li>

                <strong>Cakupan Data</strong>

                Periode pemilu, jumlah partai pembanding, wilayah, dan TPS yang tersedia.

            </li>

            <li>

                <strong>Perolehan Suara dan Tren</strong>

                Perbandingan suara partai serta arah perubahan dari 2019 ke 2024.

            </li>

            <li>

                <strong>K-Means Clustering</strong>

                Pengelompokan observasi berdasarkan karakteristik perolehan suara.

            </li>

        </ul>

    </section>

    {{-- RINGKASAN UTAMA --}}

    <section class="dashboard-section">

        <div class="panel">

            <div class="kpi-strip">

                <div class="kpi k-navy">

                    <div class="kpi-label">Total Suara 2019</div>

                    <div class="kpi-value">{{ number_format($total2019, 0, ',', '.') }}</div>

                    <div class="kpi-note">Seluruh partai, Pemilu 2019</div>

                </div>

                <div class="kpi k-maroon">

                    <div class="kpi-label">Total Suara 2024</div>

                    <div class="kpi-value">{{ number_format($total2024, 0, ',', '.') }}</div>

                    <div class="kpi-note">Seluruh partai, Pemilu 2024</div>

                </div>

                <div class="kpi {{ $persen >= 0 ? 'k-up' : 'k-down' }}">

                    <div class="kpi-label">Perubahan Total</div>

                    <div class="kpi-value {{ $persen >= 0 ? 'up' : 'down' }}">

                        {{ $persen > 0 ? '+' : '' }}{{ number_format($persen, 2, ',', '.') }}%

                    </div>

                    <div class="kpi-note">

                        Selisih {{ $selisihTotal > 0 ? '+' : ($selisihTotal < 0 ? '−' : '') }}{{ number_format(abs($selisihTotal), 0, ',', '.') }} suara

                    </div>

                </div>

                <div class="kpi k-gold">

                    <div class="kpi-label">Partai Pembanding</div>

                    <div class="kpi-value">{{ number_format($jumlahPartaiPembanding, 0, ',', '.') }}</div>

                    <div class="kpi-note">Memiliki data pada Pemilu 2019 dan 2024</div>

                </div>

            </div>

            <div class="compare-bars">

                <div class="compare-bars-title">Perbandingan total suara </div>

                <div class="cbar-row">

                    <span class="cbar-year">2019</span>

                    <div class="cbar-track">

                        <div class="cbar-fill f2019" style="width: {{ ($total2019 / $maxTotal) * 100 }}%"></div>

                    </div>

                    <span class="cbar-val">{{ number_format($total2019, 0, ',', '.') }}</span>

                </div>

                <div class="cbar-row">

                    <span class="cbar-year">2024</span>

                    <div class="cbar-track">

                        <div class="cbar-fill f2024" style="width: {{ ($total2024 / $maxTotal) * 100 }}%"></div>

                    </div>

                    <span class="cbar-val">{{ number_format($total2024, 0, ',', '.') }}</span>

                </div>

            </div>

        </div>

    </section>

    {{-- CAKUPAN DATA --}}

    <section class="dashboard-section">

        <div class="panel">

            <div class="panel-header">

                <div>

                    <h3 class="panel-title">Cakupan Data</h3>

                    <p class="panel-description">Wilayah dan periode yang digunakan.</p>

                </div>

            </div>

            <div class="panel-body" style="padding: 8px 0;">

                <ul class="coverage-list">

                    <li><span>Periode Pemilu (2019 dan 2024)</span><strong>2</strong></li>

                    <li>

                        <span>Partai Pembanding (2019 &amp; 2024)</span>

                        <strong>{{ number_format($jumlahPartaiPembanding, 0, ',', '.') }}</strong>

                    </li>

                    <li>

                        <span>Kecamatan</span>

                        <strong>{{ number_format($jumlahKecamatanDashboard, 0, ',', '.') }}</strong>

                    </li>

                    <li>

                        <span>Kelurahan</span>

                        <strong>{{ number_format($jumlahKelurahanDashboard, 0, ',', '.') }}</strong>

                    </li>

                    <li>

                        <span>TPS (data Pemilu 2024)</span>

                        <strong>{{ number_format($jumlahTpsDashboard, 0, ',', '.') }}</strong>

                    </li>

                </ul>

            </div>

        </div>

    </section>

    {{-- PEROLEHAN SUARA PARTAI + TREN --}}

    <section class="dashboard-section">

        <div class="section-heading">

            <div>

                <h2 class="section-title">Perolehan Suara dan Tren</h2>

                <p class="section-description">Perbandingan jumlah suara dan perubahan proporsi suara partai dari 2019 ke 2024.</p>

            </div>

        </div>

        <div class="main-grid">

            <div class="panel">

                <div class="panel-header">

                    <div>

                        <h3 class="panel-title">Enam Partai Pembanding dengan Suara Terbanyak 2024</h3>

                        <p class="panel-description">

                            Enam partai pembanding dengan perolehan suara 2024 tertinggi. Tren utama diukur dari perubahan proporsi suara tingkat kota.

                        </p>

                    </div>

                    <span class="panel-badge">2019 vs 2024</span>

                </div>

                <div class="panel-body">

                    <div class="legend" style="margin-bottom: 8px;">

                        <span style="--c: var(--navy)">2019</span>

                        <span style="--c: var(--maroon)">2024</span>

                    </div>

                    <div class="recap-wrap">

                        <table class="recap-table">

                            <thead>

                                <tr>

                                    <th>Partai</th>

                                    <th>Perolehan suara</th>

                                    <th class="num">Selisih suara</th>

                                    <th class="num">Perubahan proporsi</th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($rekapDashboard as $item)

                                    @php

                                        $s19 = (int) ($item['suara_2019'] ?? 0);

                                        $s24 = (int) ($item['suara_2024'] ?? 0);

                                        $selisih = $s24 - $s19;
                                        $selisihShare = (float) ($item['selisih_share'] ?? 0);
                                        $dirSuara = $selisih > 0 ? 'up' : ($selisih < 0 ? 'down' : 'flat');
                                        $dirShare = $selisihShare > 0.000001 ? 'up' : ($selisihShare < -0.000001 ? 'down' : 'flat');

                                    @endphp

                                    <tr>

                                        <td>

                                            <div class="party-name">

                                                {{ $item['singkatan'] ?? $item['nama_lengkap'] ?? $item['partai'] ?? '-' }}

                                            </div>

                                            @if(!empty($item['nama_lengkap']))

                                                <div class="party-full-name">{{ $item['nama_lengkap'] }}</div>

                                            @endif

                                        </td>

                                        <td class="bar-pair">

                                            <div class="bar-line">

                                                <div class="bar-track">

                                                    <span class="b2019" style="width: {{ ($s19 / $maxSuara) * 100 }}%"></span>

                                                </div>

                                                <span class="bar-num">{{ number_format($s19, 0, ',', '.') }}</span>

                                            </div>

                                            <div class="bar-line">

                                                <div class="bar-track">

                                                    <span class="b2024" style="width: {{ ($s24 / $maxSuara) * 100 }}%"></span>

                                                </div>

                                                <span class="bar-num">{{ number_format($s24, 0, ',', '.') }}</span>

                                            </div>

                                        </td>

                                        <td class="num">

                                            <span class="delta {{ $dirSuara }}">
                                                {{ $selisih > 0 ? '+' : ($selisih < 0 ? '−' : '') }}{{ number_format(abs($selisih), 0, ',', '.') }}
                                            </span>

                                        </td>

                                        <td class="num">

                                            <span class="delta {{ $dirShare }}">
                                                {{ $selisihShare > 0 ? '+' : ($selisihShare < 0 ? '−' : '') }}{{ number_format(abs($selisihShare), 2, ',', '.') }} pp
                                            </span>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4">Data rekapitulasi belum tersedia.</td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <div class="side-stack">

                <div class="panel">

                    <div class="panel-header">

                        <div>

                            <h3 class="panel-title">Arah Perubahan Tren</h3>

                            <p class="panel-description">

                                Jumlah partai pembanding yang meningkat, menurun, atau tetap dari 2019 ke 2024.

                            </p>

                        </div>

                        <span class="panel-badge">Tren</span>

                    </div>

                    <div class="panel-body">

                        @if($totalTren > 0)

                            <div class="stack-bar" role="img" aria-label="Distribusi tren partai pembanding">

                                <span style="width: {{ $pctTren($naik) }}%; background: var(--green)"></span>

                                <span style="width: {{ $pctTren($turun) }}%; background: var(--maroon)"></span>

                                <span style="width: {{ $pctTren($tetap) }}%; background: var(--navy-soft)"></span>

                            </div>

                        @endif

                        <div class="stat-list">

                            <div class="stat-row" style="--c: var(--green)">

                                <span class="lbl">Meningkat</span>

                                <span>

                                    <span class="val">{{ $naik }}</span>

                                    <span class="pct">{{ number_format($pctTren($naik), 0) }}%</span>

                                </span>

                            </div>

                            <div class="stat-row" style="--c: var(--maroon)">

                                <span class="lbl">Menurun</span>

                                <span>

                                    <span class="val">{{ $turun }}</span>

                                    <span class="pct">{{ number_format($pctTren($turun), 0) }}%</span>

                                </span>

                            </div>

                            <div class="stat-row" style="--c: var(--navy-soft)">

                                <span class="lbl">Tetap</span>

                                <span>

                                    <span class="val">{{ $tetap }}</span>

                                    <span class="pct">{{ number_format($pctTren($tetap), 0) }}%</span>

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- K-MEANS CLUSTERING TERBARU --}}
    @php
        $metrikKlaster = is_array($metrikKMeans ?? null) ? $metrikKMeans : [];
        $nilaiK = $metrikKlaster['k'] ?? null;
        $nilaiSilhouette = $metrikKlaster['silhouette'] ?? null;
        $totalObservasiKMeans = (int) ($totalClusterKMeans ?? $metrikKlaster['jumlah_data'] ?? 0);
        $adaHasilKMeans = !empty($analisisKMeans) && $totalObservasiKMeans > 0;
    @endphp

    @if ($adaHasilKMeans)
        <section class="dashboard-section">
            <div class="section-heading">
                <div>
                    <h2 class="section-title">Hasil K-Means Clustering</h2>
                    <p class="section-description">Ringkasan analisis clustering terbaru berdasarkan data yang tersimpan.</p>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <div>
                        <h3 class="panel-title">Ringkasan Hasil Clustering</h3>
                        <p class="panel-description">Setiap observasi merupakan kombinasi partai politik dan kecamatan.</p>
                    </div>
                    @if (is_numeric($nilaiK))
                        <span class="panel-badge">K = {{ (int) $nilaiK }}</span>
                    @endif
                </div>

                <ul class="cluster-metrics">
                    <li>
                        <span>Total observasi hasil clustering</span>
                        <strong>{{ number_format($totalObservasiKMeans, 0, ',', '.') }}</strong>
                    </li>
                    <li>
                        <span>Jumlah klaster model</span>
                        <strong>{{ is_numeric($nilaiK) ? (int) $nilaiK : '—' }}</strong>
                    </li>
                    <li>
                        <span>Silhouette Score</span>
                        <strong style="font-size: 20px;">
                            {{ is_numeric($nilaiSilhouette) ? number_format((float) $nilaiSilhouette, 4, ',', '.') : '—' }}
                        </strong>
                    </li>
                </ul>
            </div>
        </section>
    @else
        <section class="dashboard-section">
            <div class="section-heading">
                <div>
                    <h2 class="section-title">Hasil K-Means Clustering</h2>
                    <p class="section-description">Ringkasan hasil analisis clustering terbaru.</p>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <p class="empty-note">Belum ada hasil clustering yang dapat ditampilkan.</p>
                </div>
            </div>
        </section>
    @endif

    <p class="foot-note">Detail masing-masing analisis tersedia pada halaman terkait.</p>

</div>

@endsection
