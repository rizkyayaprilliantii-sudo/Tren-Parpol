@extends('layouts.app')

@section('title', 'Tren Perolehan Suara 2019–2024')

@section('content')

<section class="max-w-7xl mx-auto px-6 py-20">

    {{-- =========================================================

         HEADER

    ========================================================= --}}

    <div class="relative bg-white border-2 border-navy rounded-xl shadow-[8px_8px_0_0_#1B2A41] overflow-hidden mb-10">

        <div class="absolute top-0 left-0 w-2 h-full bg-gold"></div>

        <div class="pl-6 pr-6 py-7 md:pl-10 md:pr-10 md:py-9">

            <p class="font-mono text-xs tracking-widest text-gold uppercase mb-2">

                ANALISIS TREN

            </p>

            <h1 class="font-display text-3xl md:text-4xl text-ink">

                Tren Perolehan Suara 2019–2024

            </h1>

            <p class="text-slate mt-3 leading-relaxed max-w-none">

                Analisis perubahan jumlah suara yang diperoleh partai politik pada

                Pemilu Legislatif 2019 dan 2024 di Kota Banjarmasin. Pembahasan ini

                berfokus pada kenaikan, penurunan, dan perubahan perolehan suara

                setiap partai antarperiode.

            </p>

            <div class="flex flex-wrap gap-2 mt-5">

                <span class="inline-flex items-center rounded-md bg-navy text-white px-3 py-1.5 text-xs font-semibold">

                    Periode 2019–2024

                </span>

                <span class="inline-flex items-center rounded-md bg-cream text-navy border border-gold px-3 py-1.5 text-xs font-semibold">

                    Tren Perolehan Suara

                </span>

            </div>

        </div>

    </div>

    @php
        /*
         * Tren tingkat kota dihitung dari jumlah suara tiap parpol yang
         * sudah diagregasikan dari lima kecamatan, lalu dinyatakan sebagai
         * proporsi terhadap total suara kota pada tahun yang sama.
         */
        $trenKota = collect($perPartai)->map(function ($item) use ($totalSuara2019, $totalSuara2024) {
            $data = clone $item;

            $data->share_2019 = ($data->total_2019 !== null && $totalSuara2019 > 0)
                ? ($data->total_2019 / $totalSuara2019) * 100
                : null;

            $data->share_2024 = ($data->total_2024 !== null && $totalSuara2024 > 0)
                ? ($data->total_2024 / $totalSuara2024) * 100
                : null;

            $data->selisih_share = ($data->dapat_dibandingkan
                && $data->share_2019 !== null
                && $data->share_2024 !== null)
                ? $data->share_2024 - $data->share_2019
                : null;

            $data->status_tren_share = $data->selisih_share === null
                ? 'Tidak dibandingkan'
                : ($data->selisih_share > 0.0000001
                    ? 'Naik'
                    : ($data->selisih_share < -0.0000001 ? 'Turun' : 'Tetap'));

            return $data;
        });

        $parpolPembanding = $trenKota->filter(fn ($item) =>
            $item->dapat_dibandingkan
            && $item->share_2019 !== null
            && $item->share_2024 !== null
            && $item->selisih_share !== null
        );

        $rataShare2019 = $parpolPembanding->avg('share_2019');
        $rataShare2024 = $parpolPembanding->avg('share_2024');
        $rataSelisihShare = $parpolPembanding->avg('selisih_share');

        $naikTertinggiShare = $parpolPembanding->sortByDesc('selisih_share')->first();
        $turunTerbesarShare = $parpolPembanding->sortBy('selisih_share')->first();

        $jumlahNaikShare = $parpolPembanding->where('status_tren_share', 'Naik')->count();
        $jumlahTurunShare = $parpolPembanding->where('status_tren_share', 'Turun')->count();
        $jumlahTetapShare = $parpolPembanding->where('status_tren_share', 'Tetap')->count();

        $nilaiMaksimumShare = $trenKota
            ->flatMap(fn ($item) => [$item->share_2019 ?? 0, $item->share_2024 ?? 0])
            ->max() ?? 0;
    @endphp

    {{-- =========================================================
         RINGKASAN TREN PROPORSI SUARA TINGKAT KOTA
    ========================================================= --}}

    <div class="bg-white border-2 border-navy rounded-xl shadow-[6px_6px_0_0_#1B2A41] overflow-hidden mb-10">
        <div class="bg-navy text-white px-6 py-5">
            <p class="font-mono text-xs tracking-widest text-gold uppercase mb-1">
                RINGKASAN TINGKAT KOTA
            </p>
            <h2 class="font-display text-2xl">
                Tren Proporsi Suara Parpol Kota Banjarmasin
            </h2>
            <p class="text-sm text-white/75 mt-2 leading-relaxed">
                Perbandingan proporsi suara masing-masing partai politik pada Pemilu Legislatif
                2019 dan 2024, setelah data dari lima kecamatan digabungkan pada tingkat kota.
            </p>
        </div>

        <div class="p-6">
            <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-5">
                <article class="rounded-xl border-2 border-slate/20 bg-cream/50 p-5">
                    <p class="text-xs uppercase tracking-wider font-bold text-slate">
                        Rata-rata Share 2019
                    </p>
                    <p class="font-display text-2xl md:text-3xl text-ink mt-3">
                        {{ $rataShare2019 !== null ? number_format($rataShare2019, 2, ',', '.') . '%' : '-' }}
                    </p>
                    <p class="text-xs text-slate mt-2">
                        Rata-rata proporsi 14 parpol pembanding
                    </p>
                </article>

                <article class="rounded-xl border-2 border-navy bg-white p-5">
                    <p class="text-xs uppercase tracking-wider font-bold text-navy">
                        Rata-rata Share 2024
                    </p>
                    <p class="font-display text-2xl md:text-3xl text-ink mt-3">
                        {{ $rataShare2024 !== null ? number_format($rataShare2024, 2, ',', '.') . '%' : '-' }}
                    </p>
                    <p class="text-xs text-slate mt-2">
                        Rata-rata proporsi 14 parpol pembanding
                    </p>
                </article>

                <article class="rounded-xl border-2 {{ ($rataSelisihShare ?? 0) > 0 ? 'border-green-700' : (($rataSelisihShare ?? 0) < 0 ? 'border-maroon' : 'border-navy') }} bg-white p-5">
                    <p class="text-xs uppercase tracking-wider font-bold text-slate">
                        Rata-rata Perubahan Share
                    </p>
                    <p class="font-display text-2xl md:text-3xl mt-3 {{ ($rataSelisihShare ?? 0) > 0 ? 'text-green-700' : (($rataSelisihShare ?? 0) < 0 ? 'text-maroon' : 'text-navy') }}">
                        @if($rataSelisihShare !== null)
                            {{ $rataSelisihShare > 0 ? '+' : '' }}{{ number_format($rataSelisihShare, 2, ',', '.') }} pp
                        @else
                            -
                        @endif
                    </p>
                    <p class="text-xs text-slate mt-2">
                        Share 2024 dikurangi share 2019
                    </p>
                </article>

                <article class="rounded-xl border-2 border-gold bg-cream/50 p-5">
                    <p class="text-xs uppercase tracking-wider font-bold text-navy">
                        Parpol Pembanding
                    </p>
                    <p class="font-display text-2xl md:text-3xl text-ink mt-3">
                        {{ $parpolPembanding->count() }}
                    </p>
                    <p class="text-xs text-slate mt-2">
                        Parpol dengan data pada kedua periode
                    </p>
                </article>
            </div>

            <div class="grid sm:grid-cols-3 gap-4 mt-5">
                <div class="rounded-lg border border-green-700/30 bg-white p-4">
                    <p class="text-xs uppercase tracking-wider font-bold text-green-800">Share Meningkat</p>
                    <p class="font-display text-2xl text-green-700 mt-2">{{ $jumlahNaikShare }} <span class="text-sm font-semibold">parpol</span></p>
                </div>
                <div class="rounded-lg border border-maroon/30 bg-white p-4">
                    <p class="text-xs uppercase tracking-wider font-bold text-maroon">Share Menurun</p>
                    <p class="font-display text-2xl text-maroon mt-2">{{ $jumlahTurunShare }} <span class="text-sm font-semibold">parpol</span></p>
                </div>
                <div class="rounded-lg border border-navy/20 bg-white p-4">
                    <p class="text-xs uppercase tracking-wider font-bold text-navy">Share Tetap</p>
                    <p class="font-display text-2xl text-navy mt-2">{{ $jumlahTetapShare }} <span class="text-sm font-semibold">parpol</span></p>
                </div>
            </div>

            <div class="mt-5 rounded-lg border-l-4 border-gold bg-cream/60 p-4">
                <p class="text-sm text-slate leading-relaxed">
                    <strong class="text-navy">Catatan:</strong>
                    share kota dihitung dari jumlah suara setiap parpol yang digabung dari lima kecamatan,
                    dibagi total suara kota pada tahun yang sama. Rata-rata share pada ringkasan ini hanya
                    dihitung dari parpol yang memiliki data pembanding pada 2019 dan 2024. Perubahan share
                    dinyatakan dalam poin persentase (pp), bukan persentase perubahan relatif.
                </p>
            </div>
        </div>
    </div>

    {{-- =========================================================
         HIGHLIGHT PERUBAHAN SHARE
    ========================================================= --}}

    <div class="grid md:grid-cols-3 gap-6 items-stretch mb-10">
        <article class="flex flex-col overflow-hidden rounded-xl border-2 border-green-800 bg-gradient-to-b from-green-50 via-white to-green-100/60">
            <header class="bg-gradient-to-r from-green-800 to-green-600 text-white px-5 py-3">
                <span class="text-xs font-bold tracking-wide">KENAIKAN SHARE TERBESAR</span>
            </header>
            <div class="flex flex-col flex-1 p-5">
                @if($naikTertinggiShare && $naikTertinggiShare->selisih_share > 0)
                    <div class="font-display text-xl text-ink">{{ optional($naikTertinggiShare->partai)->singkatan ?? '-' }}</div>
                    <div class="text-xs text-slate mt-1">{{ optional($naikTertinggiShare->partai)->nama_lengkap_parpol ?? '-' }}</div>
                    <div class="font-mono text-2xl font-black text-green-700 mt-4">
                        +{{ number_format($naikTertinggiShare->selisih_share, 2, ',', '.') }} <span class="text-sm font-semibold">pp</span>
                    </div>
                    <div class="text-xs text-slate font-mono mt-2">
                        {{ number_format($naikTertinggiShare->share_2019, 2, ',', '.') }}% → {{ number_format($naikTertinggiShare->share_2024, 2, ',', '.') }}%
                    </div>
                @else
                    <p class="text-sm text-slate my-auto">Tidak terdapat kenaikan share pada parpol pembanding.</p>
                @endif
            </div>
        </article>

        <article class="flex flex-col overflow-hidden rounded-xl border-2 border-maroon bg-gradient-to-b from-rose-50 via-white to-rose-100/60">
            <header class="bg-gradient-to-r from-maroon to-red-800 text-white px-5 py-3">
                <span class="text-xs font-bold tracking-wide">PENURUNAN SHARE TERBESAR</span>
            </header>
            <div class="flex flex-col flex-1 p-5">
                @if($turunTerbesarShare && $turunTerbesarShare->selisih_share < 0)
                    <div class="font-display text-xl text-ink">{{ optional($turunTerbesarShare->partai)->singkatan ?? '-' }}</div>
                    <div class="text-xs text-slate mt-1">{{ optional($turunTerbesarShare->partai)->nama_lengkap_parpol ?? '-' }}</div>
                    <div class="font-mono text-2xl font-black text-maroon mt-4">
                        {{ number_format($turunTerbesarShare->selisih_share, 2, ',', '.') }} <span class="text-sm font-semibold">pp</span>
                    </div>
                    <div class="text-xs text-slate font-mono mt-2">
                        {{ number_format($turunTerbesarShare->share_2019, 2, ',', '.') }}% → {{ number_format($turunTerbesarShare->share_2024, 2, ',', '.') }}%
                    </div>
                @else
                    <p class="text-sm text-slate my-auto">Tidak terdapat penurunan share pada parpol pembanding.</p>
                @endif
            </div>
        </article>

        <article class="flex flex-col overflow-hidden rounded-xl border-2 border-gold bg-gradient-to-b from-amber-50 via-white to-amber-100/60">
            <header class="bg-gradient-to-r from-gold to-yellow-600 text-white px-5 py-3">
                <span class="text-xs font-bold tracking-wide">PARPOL BARU PADA 2024</span>
            </header>
            <div class="flex flex-col flex-1 p-5">
                <div class="font-mono text-3xl font-black text-navy">
                    {{ $partaiBaru->count() }} <span class="text-sm font-semibold">parpol</span>
                </div>
                <p class="text-sm text-slate mt-2">
                    Parpol yang tercatat memperoleh suara pada 2024 tetapi tidak memiliki data pembanding pada 2019.
                    Parpol ini tidak dimasukkan dalam perhitungan perubahan share antarperiode.
                </p>
                @if($partaiBaru->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mt-4">
                        @foreach($partaiBaru as $item)
                            <div class="inline-flex items-center gap-2 rounded-lg border border-gold/40 bg-white px-2.5 py-2">
                                @if(optional($item->partai)->logo_url)
                                    <img src="{{ $item->partai->logo_url }}" alt="Logo {{ optional($item->partai)->singkatan }}" class="w-7 h-7 object-contain">
                                @endif
                                <span class="text-xs font-bold text-navy">{{ optional($item->partai)->singkatan ?? '-' }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </article>
    </div>

    {{-- =========================================================
         GRAFIK PERBANDINGAN SHARE TINGKAT KOTA
    ========================================================= --}}

    <div class="bg-white border-2 border-navy rounded-xl shadow-[6px_6px_0_0_#1B2A41] overflow-hidden mb-10">
        <div class="bg-navy text-white px-6 py-5">
            <p class="font-mono text-xs tracking-widest text-gold uppercase mb-1">VISUALISASI DATA</p>
            <h2 class="font-display text-2xl">Perbandingan Proporsi Suara per Parpol</h2>
            <p class="text-sm text-white/75 mt-2 leading-relaxed">
                Grafik membandingkan share setiap parpol pada tingkat Kota Banjarmasin.
                Selisih ditampilkan dalam poin persentase dan bukan merupakan hasil cluster K-Means.
            </p>
        </div>

        <div class="p-6">
            <div class="flex flex-wrap gap-x-5 gap-y-2 mb-6 text-xs font-semibold text-slate">
                <span class="inline-flex items-center gap-2"><span class="w-3 h-3 rounded-sm bg-slate"></span>Pemilu 2019</span>
                <span class="inline-flex items-center gap-2"><span class="w-3 h-3 rounded-sm bg-navy"></span>Pemilu 2024</span>
            </div>

            @forelse($trenKota->sortByDesc(fn ($item) => $item->share_2024 ?? -1) as $data)
                @php
                    $width2019 = $nilaiMaksimumShare > 0 && $data->share_2019 !== null
                        ? max(($data->share_2019 / $nilaiMaksimumShare) * 100, 2)
                        : 0;
                    $width2024 = $nilaiMaksimumShare > 0 && $data->share_2024 !== null
                        ? max(($data->share_2024 / $nilaiMaksimumShare) * 100, 2)
                        : 0;
                    $trendClass = match($data->status_tren_share) {
                        'Naik' => 'text-green-700',
                        'Turun' => 'text-maroon',
                        'Tetap' => 'text-navy',
                        default => 'text-slate'
                    };
                    $trendIcon = match($data->status_tren_share) {
                        'Naik' => '↑',
                        'Turun' => '↓',
                        'Tetap' => '→',
                        default => '•'
                    };
                @endphp

                <div class="py-4 {{ !$loop->last ? 'border-b border-slate/15' : '' }}">
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            @if(optional($data->partai)->logo_url)
                                <div class="w-10 h-10 flex-shrink-0 bg-white rounded-lg border border-slate/20 p-1.5 flex items-center justify-center">
                                    <img src="{{ $data->partai->logo_url }}" alt="Logo {{ optional($data->partai)->singkatan }}" class="w-full h-full object-contain">
                                </div>
                            @else
                                <div class="w-10 h-10 flex-shrink-0 bg-cream rounded-lg border border-gold/50 flex items-center justify-center font-mono font-bold text-navy text-xs">
                                    {{ \Illuminate\Support\Str::limit(optional($data->partai)->singkatan, 3, '') }}
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-bold text-navy truncate">{{ optional($data->partai)->singkatan ?? '-' }}</p>
                                <p class="text-xs text-slate truncate">{{ optional($data->partai)->nama_lengkap_parpol ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-mono text-sm font-bold {{ $trendClass }}">
                                {{ $trendIcon }}
                                @if($data->selisih_share !== null)
                                    {{ $data->selisih_share > 0 ? '+' : '' }}{{ number_format($data->selisih_share, 2, ',', '.') }} pp
                                @else
                                    Tidak dibandingkan
                                @endif
                            </p>
                            <p class="text-xs text-slate">{{ $data->status_tren_share }}</p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <span class="w-12 flex-shrink-0 text-xs font-mono text-slate">2019</span>
                            <div class="flex-1 min-w-0 flex items-center">
                                @if($data->share_2019 !== null)
                                    <div class="h-6 bg-slate rounded flex items-center" style="width: {{ $width2019 }}%;">
                                        <span class="text-[10px] text-white font-mono font-bold px-2 whitespace-nowrap">{{ number_format($data->share_2019, 2, ',', '.') }}%</span>
                                    </div>
                                @else
                                    <span class="text-xs text-slate italic">Data tidak tersedia</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-12 flex-shrink-0 text-xs font-mono text-slate">2024</span>
                            <div class="flex-1 min-w-0 flex items-center">
                                @if($data->share_2024 !== null)
                                    <div class="h-6 bg-navy rounded flex items-center" style="width: {{ $width2024 }}%;">
                                        <span class="text-[10px] text-white font-mono font-bold px-2 whitespace-nowrap">{{ number_format($data->share_2024, 2, ',', '.') }}%</span>
                                    </div>
                                @else
                                    <span class="text-xs text-slate italic">Data tidak tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-lg border border-slate/20 bg-cream/50 p-8 text-center">
                    <p class="font-semibold text-navy">Data tren belum tersedia</p>
                    <p class="text-sm text-slate mt-2">Belum ada data share kota yang dapat ditampilkan.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- =========================================================
         TABEL RINCIAN TREN PROPORSI SUARA
    ========================================================= --}}

    <div class="bg-white border-2 border-navy rounded-xl shadow-[6px_6px_0_0_#1B2A41] overflow-hidden mb-10">
        <div class="bg-cream border-b-2 border-navy px-6 py-5">
            <p class="font-mono text-xs tracking-widest text-gold uppercase mb-1">RINCIAN PERBANDINGAN</p>
            <h2 class="font-display text-2xl text-ink">Tabel Tren Proporsi Suara Parpol</h2>
            <p class="text-sm text-slate mt-2 leading-relaxed">
                Tabel merangkum share tingkat kota pada setiap periode dan perubahan dalam poin persentase.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-sm">
                <thead class="bg-navy text-white">
                    <tr>
                        <th class="px-5 py-4 text-left font-semibold">No.</th>
                        <th class="px-5 py-4 text-left font-semibold">Partai Politik</th>
                        <th class="px-5 py-4 text-right font-semibold">Share 2019</th>
                        <th class="px-5 py-4 text-right font-semibold">Share 2024</th>
                        <th class="px-5 py-4 text-right font-semibold">Selisih (pp)</th>
                        <th class="px-5 py-4 text-center font-semibold">Status Tren</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate/15">
                    @forelse($trenKota->sortByDesc(fn ($item) => $item->share_2024 ?? -1) as $item)
                        @php
                            $statusStyle = match($item->status_tren_share) {
                                'Naik' => 'bg-green-100 text-green-800 border border-green-300',
                                'Turun' => 'bg-rose-100 text-maroon border border-rose-300',
                                'Tetap' => 'bg-blue-50 text-navy border border-blue-200',
                                default => 'bg-slate/10 text-slate border border-slate/20'
                            };
                        @endphp
                        <tr class="hover:bg-cream/40 transition-colors">
                            <td class="px-5 py-4 text-slate font-mono">{{ $loop->iteration }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if(optional($item->partai)->logo_url)
                                        <div class="w-10 h-10 flex-shrink-0 rounded-lg border border-slate/20 bg-white p-1.5 flex items-center justify-center">
                                            <img src="{{ $item->partai->logo_url }}" alt="Logo {{ optional($item->partai)->singkatan }}" class="w-full h-full object-contain">
                                        </div>
                                    @else
                                        <div class="w-10 h-10 flex-shrink-0 rounded-lg border border-gold/40 bg-cream flex items-center justify-center font-mono text-xs font-bold text-navy">
                                            {{ \Illuminate\Support\Str::limit(optional($item->partai)->singkatan, 3, '') }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="font-bold text-navy">{{ optional($item->partai)->singkatan ?? '-' }}</div>
                                        <div class="text-xs text-slate">{{ optional($item->partai)->nama_lengkap_parpol ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right font-mono text-slate">
                                {{ $item->share_2019 !== null ? number_format($item->share_2019, 2, ',', '.') . '%' : '-' }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono text-slate">
                                {{ $item->share_2024 !== null ? number_format($item->share_2024, 2, ',', '.') . '%' : '-' }}
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-bold">
                                @if($item->selisih_share !== null)
                                    <span class="{{ $item->selisih_share > 0 ? 'text-green-700' : ($item->selisih_share < 0 ? 'text-maroon' : 'text-navy') }}">
                                        {{ $item->selisih_share > 0 ? '+' : '' }}{{ number_format($item->selisih_share, 2, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-slate">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-md text-xs font-mono font-semibold {{ $statusStyle }}">
                                    {{ $item->status_tren_share }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <p class="font-semibold text-navy">Belum ada data perbandingan</p>
                                <p class="text-sm text-slate mt-2">Data tren akan ditampilkan setelah hasil perbandingan tersedia.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-5 bg-cream/50 border-t border-slate/20">
            <h3 class="font-display text-lg text-ink mb-3">Catatan dan Cara Membaca Tren</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="rounded-lg border border-slate/20 bg-white p-4">
                    <p class="font-bold text-green-800 text-sm mb-1">↑ Share Naik</p>
                    <p class="text-sm text-slate leading-relaxed">Proporsi suara parpol pada 2024 lebih besar daripada proporsinya pada 2019. Selisih positif dinyatakan dalam poin persentase.</p>
                </div>
                <div class="rounded-lg border border-slate/20 bg-white p-4">
                    <p class="font-bold text-maroon text-sm mb-1">↓ Share Turun</p>
                    <p class="text-sm text-slate leading-relaxed">Proporsi suara parpol pada 2024 lebih kecil daripada proporsinya pada 2019. Selisih negatif dinyatakan dalam poin persentase.</p>
                </div>
                <div class="rounded-lg border border-slate/20 bg-white p-4">
                    <p class="font-bold text-navy text-sm mb-1">→ Share Tetap</p>
                    <p class="text-sm text-slate leading-relaxed">Proporsi suara parpol pada kedua periode sama atau memiliki selisih yang sangat mendekati nol.</p>
                </div>
                <div class="rounded-lg border border-slate/20 bg-white p-4">
                    <p class="font-bold text-navy text-sm mb-1">Perhitungan Selisih</p>
                    <p class="text-sm text-slate leading-relaxed">Selisih share dihitung dari share 2024 dikurangi share 2019. Parpol tanpa data pembanding pada kedua tahun tidak diberi status tren.</p>
                </div>
            </div>
            <p class="text-xs text-slate leading-relaxed mt-4">
                <strong class="text-navy">Batas interpretasi:</strong>
                perubahan proporsi menunjukkan perubahan bagian suara parpol terhadap total suara kota yang menjadi penyebut.
                Perubahan ini tidak dengan sendirinya menjelaskan penyebab perubahan dukungan.
            </p>
        </div>
    </div>

    {{-- =========================================================

         KETERKAITAN DENGAN K-MEANS CLUSTERING

         CARD LANDSCAPE, FULL WIDTH

    ========================================================= --}}

    <div class="bg-white border-2 border-gold rounded-xl shadow-[6px_6px_0_0_#C9A24B] overflow-hidden">

        <div class="bg-navy text-white px-6 py-5 md:px-8">

            <p class="font-mono text-xs tracking-widest text-gold uppercase mb-1">

                INFORMASI LANJUTAN

            </p>

            <h2 class="font-display text-2xl md:text-3xl">

                Keterkaitan dengan K-Means Clustering

            </h2>

<p class="text-sm text-white/80 mt-2 leading-relaxed w-full">                Kedua analisis menggunakan data Pemilu Legislatif 2019 dan 2024

                di Kota Banjarmasin, tetapi memiliki fokus, unit analisis, dan

                tujuan yang berbeda.

            </p>

        </div>

        <div class="p-6 md:p-8">

            <div class="grid md:grid-cols-3 gap-5">

                {{-- Fokus tren --}}

                <div class="rounded-xl border border-slate/20 bg-cream/50 p-5">

                    <div class="w-10 h-10 rounded-lg bg-white border border-gold/50 text-navy flex items-center justify-center mb-4">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17l6-6 4 4 8-8M15 7h6v6"></path>

                        </svg>

                    </div>

                    <h3 class="font-display text-lg text-ink mb-2">

                        1. Tren Perolehan Suara

                    </h3>

                    <p class="text-sm text-slate leading-relaxed">

                        Halaman ini membahas perubahan proporsi suara setiap parpol pada tingkat Kota Banjarmasin antara 2019 dan 2024. Share kota dihitung dari agregasi lima kecamatan, sehingga tren ini memberi gambaran keseluruhan proporsi suara masing-masing parpol.

                    </p>

                </div>

                {{-- Fokus K-Means --}}

                <div class="rounded-xl border border-gold/60 bg-white p-5">

                    <div class="w-10 h-10 rounded-lg bg-cream border border-gold/50 text-navy flex items-center justify-center mb-4">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <circle cx="6" cy="7" r="2" stroke-width="2"></circle>

                            <circle cx="17" cy="6" r="2" stroke-width="2"></circle>

                            <circle cx="12" cy="17" r="2" stroke-width="2"></circle>

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.8 8.2l2.9 6.1M15.2 7.8l-2.1 7"></path>

                        </svg>

                    </div>

                    <h3 class="font-display text-lg text-ink mb-2">

                        2. K-Means Clustering

                    </h3>

                    <p class="text-sm text-slate leading-relaxed">

                        K-Means mengelompokkan kombinasi kecamatan dan partai

                        berdasarkan kemiripan proporsi perolehan suara pada 2019

                        dan 2024. Model utama menggunakan dua fitur, yaitu proporsi

                        suara 2019 dan proporsi suara 2024, untuk menghasilkan

                        dua kelompok karakteristik.

                    </p>

                </div>

                {{-- Mengapa berkaitan --}}

                <div class="rounded-xl border border-navy/20 bg-cream/50 p-5">

                    <div class="w-10 h-10 rounded-lg bg-white border border-navy/20 text-navy flex items-center justify-center mb-4">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h8m-4-4l4 4-4 4M4 6h2a3 3 0 013 3v6a3 3 0 003 3h2"></path>

                        </svg>

                    </div>

                    <h3 class="font-display text-lg text-ink mb-2">

                        3. Mengapa Saling Berkaitan?

                    </h3>

                    <p class="text-sm text-slate leading-relaxed">

                        Perubahan suara yang terlihat pada analisis tren memberikan

                        konteks mengenai dinamika dukungan partai antarperiode.

                        Sementara itu, K-Means melihat kemiripan pola proporsi suara

                        pada setiap kecamatan dan partai. Keduanya melengkapi

                        pemahaman tentang perubahan perolehan suara dari sudut

                        pandang yang berbeda.

                    </p>

                </div>

            </div>

            {{-- Penegasan metodologis --}}

            <div class="mt-6 rounded-xl border-l-4 border-gold bg-cream/60 p-5 md:p-6">

                <h3 class="font-bold text-navy mb-2">

                    Perbedaan yang Perlu Diperhatikan

                </h3>

                <p class="text-sm text-slate leading-relaxed">

                    Tren pada halaman ini menggunakan proporsi suara parpol pada tingkat kota, yang dihitung dari agregasi suara lima kecamatan. Sebaliknya, K-Means menggunakan proporsi suara pada setiap kombinasi kecamatan dan parpol, dengan 70 observasi dan dua fitur, yaitu share 2019 serta share 2024. Data 2024 diagregasikan dari tingkat TPS ke tingkat kecamatan agar dapat dibandingkan dengan data 2019.

                    </p>

            </div>

        </div>

    </div>

</section>

@endsection
