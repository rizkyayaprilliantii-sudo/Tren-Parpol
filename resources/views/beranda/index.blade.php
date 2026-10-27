@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- =========================================================
     HERO
========================================================= --}}

<section class="bg-navy text-cream relative overflow-hidden">
    <div class="absolute inset-0 bg-tally-lines opacity-40"></div>
    <div class="absolute top-0 right-0 w-1/2 h-full bg-gold/5 rounded-l-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-maroon/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6 pt-12 pb-32 md:pt-16 md:pb-40">
        <div class="max-w-3xl">
            <div class="max-w-4xl">
                <h1 class="font-display text-5xl md:text-6xl lg:text-7xl leading-[1.1] mb-6 tracking-tight">
                    Tren Perolehan
                    <br>
                    <span class="text-gold">Suara Partai Politik</span>
                    <br>
                    Kota Banjarmasin
                </h1>

                <p class="text-lg md:text-xl text-cream/80 max-w-none md:whitespace-nowrap leading-relaxed">
                    Analisis Tren Perolehan Suara Partai Politik pada Pemilu Legislatif 2019 dan 2024
                    menggunakan algoritma <em class="text-gold-soft not-italic font-semibold">K-Means Clustering</em>.
                </p>
            </div>
        </div>
    </div>
</section>


{{-- =========================================================
     STATISTIK
========================================================= --}}

<section class="relative z-10 -mt-32">
    <div class="max-w-7xl mx-auto px-6">
        @php
            $statItems = [
                ['val' => $stats['partai_2019'], 'label' => 'Partai', 'sub' => 'Pileg 2019'],
                ['val' => $stats['partai_2024'], 'label' => 'Partai', 'sub' => 'Pileg 2024'],
                ['val' => $stats['cluster'], 'label' => 'Cluster', 'sub' => 'Kategori'],
                ['val' => $stats['periode'], 'label' => 'Periode', 'sub' => 'Tahun pemilu'],
            ];
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 bg-cream border-2 border-navy shadow-[8px_8px_0_0_#1B2A41]">
            @foreach($statItems as $s)
                <div class="p-6 md:p-8 border-navy
                    {{ $loop->index < 2 ? 'border-b-2 md:border-b-0' : '' }}
                    {{ $loop->odd ? 'border-r-2' : '' }}
                    {{ $loop->last ? 'md:border-r-0' : 'md:border-r-2' }}">

                    <div class="font-mono text-5xl md:text-6xl font-black text-navy leading-none">
                        {{ $s['val'] }}
                    </div>

                    <div class="mt-4 h-1.5 w-12 bg-maroon"></div>

                    <div class="mt-3 text-base md:text-lg font-extrabold text-navy">
                        {{ $s['label'] }}
                    </div>

                    <div class="text-xs md:text-sm font-medium text-slate">
                        {{ $s['sub'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


{{-- =========================================================
     RINGKASAN TREN PROPORSI SUARA
========================================================= --}}

<section class="max-w-7xl mx-auto px-6 py-20">
    <div class="mb-12">
        <p class="font-mono text-xs tracking-widest text-gold uppercase mb-2">
            RINGKASAN TREN
        </p>

        <h2 class="font-display text-3xl md:text-4xl text-ink">
            Perubahan Proporsi Suara Partai Politik
        </h2>

        <p class="text-slate mt-3 leading-relaxed">
            Ringkasan perubahan proporsi suara partai politik pada Pemilu Legislatif
            2019 dan 2024. Perubahan ditampilkan dalam poin persentase (pp)
            berdasarkan partai yang memiliki data pada kedua periode.
        </p>
    </div>

    @php
        $trendCards = [
            [
                'item' => $naikTertinggi,
                'title' => 'Kenaikan Tertinggi',
                'empty' => 'Tidak terdapat data kenaikan proporsi suara.',
                'sign' => '+',
                'box' => 'border-green-800 from-green-50 via-white to-green-100/60 shadow-[6px_6px_0_0_rgba(22,101,52,0.9)] hover:shadow-[6px_10px_0_0_rgba(22,101,52,0.9)]',
                'head' => 'from-green-800 to-green-600 text-white',
                'mono' => 'bg-green-800',
                'logo' => 'border-green-200',
                'txt' => 'text-green-700',
                'badge' => 'bg-green-700 text-white',
                'grid' => 'border-green-800/20',
                'cell' => 'bg-green-100',
                'c24' => 'text-green-800',
                'bar24' => 'bg-green-600',
                'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
            ],
            [
                'item' => $turunTerdalam,
                'title' => 'Penurunan Terbesar',
                'empty' => 'Tidak terdapat data penurunan proporsi suara.',
                'sign' => '',
                'box' => 'border-maroon from-rose-50 via-white to-rose-100/60 shadow-[6px_6px_0_0_rgba(122,46,46,0.9)] hover:shadow-[6px_10px_0_0_rgba(122,46,46,0.9)]',
                'head' => 'from-maroon to-red-800 text-white',
                'mono' => 'bg-maroon',
                'logo' => 'border-maroon/20',
                'txt' => 'text-maroon',
                'badge' => 'bg-maroon text-white',
                'grid' => 'border-maroon/20',
                'cell' => 'bg-rose-100',
                'c24' => 'text-maroon',
                'bar24' => 'bg-maroon',
                'icon' => 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-stretch">
        @foreach($trendCards as $c)
            @php
                $item = $c['item'];
                $partaiT = optional($item)->partai;

                if ($item) {
                    $maxV = max($item->share_2019, $item->share_2024, 0.0001);
                    $w2019 = round(($item->share_2019 / $maxV) * 100);
                    $w2024 = round(($item->share_2024 / $maxV) * 100);
                }
            @endphp

            <article class="flex flex-col overflow-hidden rounded-xl border-2 bg-gradient-to-b {{ $c['box'] }} hover:-translate-y-1 transition-all duration-200">
                <header class="bg-gradient-to-r {{ $c['head'] }} px-6 py-3 flex items-center justify-between">
                    <span class="text-sm font-bold tracking-wide">
                        {{ $c['title'] }}
                    </span>

                    <svg class="w-5 h-5 flex-shrink-0"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2.5"
                            d="{{ $c['icon'] }}">
                        </path>
                    </svg>
                </header>

                <div class="flex flex-col flex-1 p-6">
                    @if($item)
                        {{-- IDENTITAS PARTAI --}}
                        <div class="flex items-center gap-4 min-h-14 mb-5">
                            @if(optional($partaiT)->logo_url)
                                <div class="w-14 h-14 flex-shrink-0 bg-white rounded-lg border-2 {{ $c['logo'] }} p-2 flex items-center justify-center">
                                    <img
                                        src="{{ $partaiT->logo_url }}"
                                        alt="Logo {{ optional($partaiT)->singkatan }}"
                                        class="w-full h-full object-contain"
                                    >
                                </div>
                            @else
                                <div class="w-14 h-14 flex-shrink-0 rounded-lg {{ $c['mono'] }} text-white flex items-center justify-center font-mono font-bold">
                                    {{ \Illuminate\Support\Str::limit(optional($partaiT)->singkatan, 3, '') }}
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <div class="font-display text-2xl text-ink leading-tight truncate">
                                    {{ optional($partaiT)->singkatan }}
                                </div>

                                <div
                                    class="text-xs text-slate font-medium truncate"
                                    title="{{ optional($partaiT)->nama_lengkap_parpol }}"
                                >
                                    {{ optional($partaiT)->nama_lengkap_parpol }}
                                </div>
                            </div>
                        </div>

                        {{-- ANGKA UTAMA: PERUBAHAN PROPORSI SUARA --}}
                        <div class="flex flex-wrap items-center gap-3 min-h-10 mb-4">
                            <div class="flex items-baseline gap-2 min-w-0">
                                <span class="font-mono text-3xl xl:text-4xl font-black {{ $c['txt'] }} leading-none whitespace-nowrap">
                                    {{ $c['sign'] }}{{ number_format(abs($item->selisih_share), 2, ',', '.') }}
                                </span>

                                <span class="text-sm font-semibold {{ $c['txt'] }}">
                                    pp
                                </span>
                            </div>

                            <div class="ml-auto flex-shrink-0 px-2.5 py-1 {{ $c['badge'] }} text-xs font-mono font-bold rounded whitespace-nowrap">
                                Proporsi 2024: {{ number_format($item->share_2024, 2, ',', '.') }}%
                            </div>
                        </div>

                        {{-- PERBANDINGAN PROPORSI SUARA --}}
                        <div class="h-12 mb-5 flex flex-col justify-center gap-2" aria-hidden="true">
                            <div class="flex items-center gap-3">
                                <span class="w-9 text-[10px] font-mono font-bold text-slate">
                                    2019
                                </span>

                                <div class="flex-1 h-2.5 rounded-full bg-navy/10 overflow-hidden">
                                    <div
                                        class="h-full rounded-full bg-slate/60"
                                        style="width: {{ $w2019 }}%"
                                    ></div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="w-9 text-[10px] font-mono font-bold {{ $c['c24'] }}">
                                    2024
                                </span>

                                <div class="flex-1 h-2.5 rounded-full bg-navy/10 overflow-hidden">
                                    <div
                                        class="h-full rounded-full {{ $c['bar24'] }}"
                                        style="width: {{ $w2024 }}%"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        {{-- RINCIAN PROPORSI DAN JUMLAH SUARA --}}
                        <div class="mt-auto grid grid-cols-2 border-2 {{ $c['grid'] }} rounded-lg overflow-hidden text-center bg-white/70">
                            <div class="py-2.5 border-r-2 {{ $c['grid'] }}">
                                <div class="text-[10px] font-bold text-slate uppercase tracking-wider">
                                    Proporsi 2019
                                </div>

                                <div class="font-mono text-sm font-bold text-slate">
                                    {{ number_format($item->share_2019, 2, ',', '.') }}%
                                </div>

                                <div class="text-[10px] text-slate mt-1">
                                    {{ number_format($item->suara_2019, 0, ',', '.') }} suara
                                </div>
                            </div>

                            <div class="py-2.5 {{ $c['cell'] }}">
                                <div class="text-[10px] font-bold {{ $c['c24'] }} uppercase tracking-wider">
                                    Proporsi 2024
                                </div>

                                <div class="font-mono text-sm font-bold text-navy">
                                    {{ number_format($item->share_2024, 2, ',', '.') }}%
                                </div>

                                <div class="text-[10px] text-slate mt-1">
                                    {{ number_format($item->suara_2024, 0, ',', '.') }} suara
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-slate my-auto">
                            {{ $c['empty'] }}
                        </p>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
</section>


{{-- =========================================================
     PERBANDINGAN: TIGA PEROLEHAN SUARA TERTINGGI
========================================================= --}}

<section class="bg-white border-y-2 border-navy/10">
    <div class="max-w-7xl mx-auto px-6 py-20">
        <div class="mb-8">
            <p class="font-mono text-xs tracking-widest text-gold uppercase mb-2">
                PERBANDINGAN
            </p>

            <h2 class="font-display text-3xl md:text-4xl text-ink">
                Tiga Perolehan Suara Tertinggi
            </h2>

            <p class="text-slate mt-3 leading-relaxed">
                Perbandingan tiga partai dengan perolehan suara tertinggi pada setiap periode pemilu.
                Proporsi suara ditampilkan sebagai informasi pendukung.
            </p>
        </div>

        @php
            $periodeList = [
                [
                    'tahun' => '2019',
                    'data' => $topPartai2019,
                    'head' => 'bg-navy',
                    'accent' => 'border-navy',
                    'shadow' => 'shadow-[6px_6px_0_0_#1B2A41]',
                    'bar' => 'bg-navy',
                    'mono' => 'bg-navy',
                ],
                [
                    'tahun' => '2024',
                    'data' => $topPartai2024,
                    'head' => 'bg-maroon',
                    'accent' => 'border-maroon',
                    'shadow' => 'shadow-[6px_6px_0_0_rgba(122,46,46,0.95)]',
                    'bar' => 'bg-maroon',
                    'mono' => 'bg-maroon',
                ],
            ];
        @endphp

        <div class="grid md:grid-cols-2 gap-8 items-stretch">
            @foreach($periodeList as $p)
                @php
                    $rows = collect($p['data'])->values();
                    $maxSuara = optional($rows->first())->total_suara ?: 1;
                @endphp

                <div class="flex flex-col bg-cream border-2 {{ $p['accent'] }} {{ $p['shadow'] }} rounded-xl overflow-hidden">
                    <div class="{{ $p['head'] }} text-cream px-6 py-5 flex items-end justify-between">
                        <div>
                            <div class="text-xs font-semibold text-cream/70 mb-1">
                                Pemilu Legislatif
                            </div>

                            <div class="font-mono text-4xl font-black leading-none">
                                {{ $p['tahun'] }}
                            </div>
                        </div>

                        <div class="text-xs font-bold text-gold-soft border border-gold/60 rounded-full px-3 py-1">
                            Suara tertinggi
                        </div>
                    </div>

                    <div class="flex-1 p-5 md:p-6 space-y-4">
                        @forelse($rows as $index => $partai)
                            @php
                                $rank = $index + 1;
                                $persen = round(($partai->total_suara / $maxSuara) * 100);
                                $logoUrl = optional($partai->partai)->logo_url;
                                $singkat = optional($partai->partai)->singkatan;
                                $namaFull = optional($partai->partai)->nama_lengkap_parpol;
                            @endphp

                            @if($rank === 1)
                                {{-- PERINGKAT 1 --}}
                                <div class="rounded-lg border-2 border-navy bg-gradient-to-r from-gold-soft via-gold/80 to-gold p-4 shadow-[4px_4px_0_0_#1B2A41]">
                                    <div class="flex items-center gap-3 sm:gap-4">
                                        <div class="w-12 h-12 rounded-md bg-navy text-gold font-mono text-2xl font-black flex items-center justify-center flex-shrink-0">
                                            1
                                        </div>

                                        @if($logoUrl)
                                            <div class="w-12 h-12 bg-white rounded-md border-2 border-navy/20 p-1.5 flex items-center justify-center flex-shrink-0">
                                                <img
                                                    src="{{ $logoUrl }}"
                                                    alt="Logo {{ $singkat }}"
                                                    class="w-full h-full object-contain"
                                                >
                                            </div>
                                        @else
                                            <div class="w-12 h-12 rounded-md bg-white/70 border-2 border-navy/20 text-navy font-mono text-xs font-bold flex items-center justify-center flex-shrink-0">
                                                {{ \Illuminate\Support\Str::limit($singkat, 3, '') }}
                                            </div>
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            <div class="font-display text-xl font-bold text-navy truncate">
                                                {{ $singkat }}
                                            </div>

                                            <div class="text-xs text-navy/70 truncate">
                                                {{ $namaFull }}
                                            </div>
                                        </div>

                                        <div class="text-right flex-shrink-0">
                                            <div class="font-mono text-xl font-black text-navy leading-none whitespace-nowrap">
                                                {{ number_format($partai->total_suara, 0, ',', '.') }}
                                            </div>

                                            <div class="text-[10px] font-bold text-navy/70 mt-1">
                                                suara
                                            </div>

                                            @if($partai->share !== null)
                                                <div class="text-[10px] font-bold text-navy/80 mt-1">
                                                    {{ number_format($partai->share, 2, ',', '.') }}% proporsi
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-3 h-2 rounded-full bg-navy/20 overflow-hidden">
                                        <div
                                            class="h-full bg-navy rounded-full"
                                            style="width: {{ $persen }}%"
                                        ></div>
                                    </div>
                                </div>
                            @else
                                {{-- PERINGKAT 2 DAN 3 --}}
                                <div class="rounded-lg border-2 border-navy/15 bg-white p-4">
                                    <div class="flex items-center gap-3 sm:gap-4">
                                        <div class="w-12 h-12 rounded-md {{ $rank === 2 ? 'bg-slate' : 'bg-amber-700' }} text-white font-mono text-xl font-black flex items-center justify-center flex-shrink-0">
                                            {{ $rank }}
                                        </div>

                                        @if($logoUrl)
                                            <div class="w-12 h-12 bg-white rounded-md border border-navy/10 p-1.5 flex items-center justify-center flex-shrink-0">
                                                <img
                                                    src="{{ $logoUrl }}"
                                                    alt="Logo {{ $singkat }}"
                                                    class="w-full h-full object-contain"
                                                >
                                            </div>
                                        @else
                                            <div class="w-12 h-12 rounded-md bg-cream border border-navy/10 text-navy font-mono text-xs font-bold flex items-center justify-center flex-shrink-0">
                                                {{ \Illuminate\Support\Str::limit($singkat, 3, '') }}
                                            </div>
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            <div class="font-bold text-navy truncate">
                                                {{ $singkat }}
                                            </div>

                                            <div class="text-xs text-slate truncate">
                                                {{ $namaFull }}
                                            </div>
                                        </div>

                                        <div class="text-right flex-shrink-0">
                                            <div class="font-mono text-xl font-bold text-maroon leading-none whitespace-nowrap">
                                                {{ number_format($partai->total_suara, 0, ',', '.') }}
                                            </div>

                                            <div class="text-[10px] text-slate mt-1">
                                                suara
                                            </div>

                                            @if($partai->share !== null)
                                                <div class="text-[10px] text-slate mt-1">
                                                    {{ number_format($partai->share, 2, ',', '.') }}% proporsi
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-3 h-2 rounded-full bg-navy/10 overflow-hidden">
                                        <div
                                            class="h-full {{ $p['bar'] }} rounded-full"
                                            style="width: {{ $persen }}%"
                                        ></div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p class="text-sm text-slate py-6 text-center">
                                Data perolehan suara belum tersedia.
                            </p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
