@extends('layouts.sidebar')

@section('title', 'Tren Perolehan Suara')

@section('page-title', 'Tren Perolehan Suara 2019–2024')

@section('content')
<div class="mx-auto max-w-7xl space-y-6">

    {{-- HEADER DAN STATISTIK --}}
    <section class="relative overflow-hidden rounded-2xl border-2 border-navy bg-navy px-6 py-6 shadow-sm sm:px-8">
        <div class="relative z-10 w-full">
            <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                Tren Perolehan Suara 2019–2024
            </h1>

            <p class="mt-3 max-w-4xl text-sm leading-6 text-white/85 sm:text-base">
                Analisis perubahan perolehan suara partai politik pada Pemilu Legislatif
                2019 dan 2024 di tingkat Kota Banjarmasin. Tren utama ditentukan berdasarkan
                perubahan share suara, sedangkan jumlah suara digunakan sebagai data pendukung.
            </p>

            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">

                {{-- TREN NAIK --}}
                <div class="rounded-xl border-2 border-gold bg-navy p-5">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-[0.14em] text-cream/80">
                        Tren Naik
                    </p>

                    <div class="mt-2 text-3xl font-black text-cream">
                        {{ $stats['naik'] ?? 0 }}
                        <span class="text-sm font-semibold">Partai</span>
                    </div>

                    <p class="mt-2 text-xs text-cream/70">
                        Share suara meningkat
                    </p>
                </div>

                {{-- TREN TURUN --}}
                <div class="rounded-xl border-2 border-gold bg-navy p-5">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-[0.14em] text-cream/80">
                        Tren Turun
                    </p>

                    <div class="mt-2 text-3xl font-black text-cream">
                        {{ $stats['turun'] ?? 0 }}
                        <span class="text-sm font-semibold">Partai</span>
                    </div>

                    <p class="mt-2 text-xs text-cream/70">
                        Share suara menurun
                    </p>
                </div>

                {{-- TREN TETAP --}}
                <div class="rounded-xl border-2 border-gold bg-navy p-5">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-[0.14em] text-cream/80">
                        Tren Tetap
                    </p>

                    <div class="mt-2 text-3xl font-black text-cream">
                        {{ $stats['tetap'] ?? 0 }}
                        <span class="text-sm font-semibold">Partai</span>
                    </div>

                    <p class="mt-2 text-xs text-cream/70">
                        Share suara tidak berubah
                    </p>
                </div>

            </div>
        </div>

        {{-- DEKORASI --}}
        <div class="pointer-events-none absolute -right-10 -top-16 h-48 w-48 rounded-full border-[24px] border-gold/10"></div>
        <div class="pointer-events-none absolute -bottom-24 right-24 h-40 w-40 rounded-full bg-white/[0.04] blur-2xl"></div>
    </section>

    {{-- RINGKASAN TOTAL SUARA --}}
    <section class="grid grid-cols-1 gap-4 md:grid-cols-2">

        <div class="rounded-xl border-2 border-navy bg-white p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                Total Suara Kota Tahun 2019
            </p>

            <p class="mt-2 text-2xl font-black text-navy">
                {{ number_format((float) ($total2019 ?? 0), 0, ',', '.') }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Dasar perhitungan share suara tahun 2019
            </p>
        </div>

        <div class="rounded-xl border-2 border-navy bg-white p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                Total Suara Kota Tahun 2024
            </p>

            <p class="mt-2 text-2xl font-black text-navy">
                {{ number_format((float) ($total2024 ?? 0), 0, ',', '.') }}
            </p>

            <p class="mt-1 text-xs text-slate-500">
                Dasar perhitungan share suara tahun 2024
            </p>
        </div>

    </section>

    {{-- KETERANGAN METODE --}}
    <section class="rounded-xl border-2 border-navy bg-white p-5">
        <h2 class="text-base font-bold text-navy">
            Dasar Analisis Tren
        </h2>

        <div class="mt-3 space-y-2 text-sm leading-6 text-slate-600">
            <p>
                <span class="font-semibold text-navy">Share suara</span>
                merupakan proporsi suara yang diperoleh partai terhadap total suara
                pada tahun pemilu yang bersangkutan di tingkat Kota Banjarmasin.
            </p>

            <p>
                <span class="font-semibold text-navy">Selisih share (pp)</span>
                merupakan share tahun 2024 dikurangi share tahun 2019.
                Nilai positif menunjukkan kenaikan proporsi suara, sedangkan nilai
                negatif menunjukkan penurunan proporsi suara.
            </p>

            <p>
                <span class="font-semibold text-navy">Jumlah suara</span>
                ditampilkan sebagai informasi pendukung dan tidak menjadi satu-satunya
                dasar penentuan status tren.
            </p>
        </div>
    </section>

    {{-- DOWNLOAD --}}
    <div class="flex justify-end">
        <div class="relative">
            <button
                type="button"
                onclick="toggleDownloadMenu()"
                aria-expanded="false"
                aria-controls="downloadMenu"
                class="inline-flex items-center gap-2 rounded-lg border-2 border-navy bg-navy px-4 py-2.5 text-sm font-medium text-white transition-colors hover:border-gold hover:bg-gold hover:text-navy"
            >
                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"
                    />
                </svg>

                Download

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                    />
                </svg>
            </button>

            <div
                id="downloadMenu"
                class="absolute right-0 z-50 mt-2 hidden w-48 overflow-hidden rounded-xl border-2 border-slate-200 bg-white shadow-lg"
            >
                <a
                    href="{{ route('admin.perbandingan.export.excel') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-navy transition-colors hover:bg-slate-50"
                >
                    <svg
                        class="h-4 w-4 text-green-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6M5 21h14a2 2 0 002-2V7.828a2 2 0 00-.586-1.414l-3.828-3.828A2 2 0 0015.172 2H5a2 2 0 00-2 2v15a2 2 0 002 2z"
                        />
                    </svg>
                    Download Excel
                </a>

                <a
                    href="{{ route('admin.perbandingan.export.pdf') }}"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-navy transition-colors hover:bg-slate-50"
                >
                    <svg
                        class="h-4 w-4 text-red-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M7 3h8l4 4v14H7a2 2 0 01-2-2V5a2 2 0 012-2z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 3v5h5"
                        />
                    </svg>
                    Download PDF
                </a>
            </div>
        </div>
    </div>

    {{-- TABEL TREN SHARE --}}
    <section class="overflow-hidden rounded-xl border-2 border-[#4B5563] bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <div class="flex flex-col justify-between gap-3 md:flex-row md:items-center">
                <div>
                    <h2 class="text-lg font-bold text-navy">
                        Data Tren Share Suara Partai Politik
                    </h2>

                    <p class="mt-1 text-xs leading-5 text-slate-500">
                        Perbandingan proporsi suara partai politik pada Pemilu Legislatif
                        2019 dan 2024 di tingkat Kota Banjarmasin.
                    </p>
                </div>

                <span class="inline-flex items-center self-start rounded-full bg-navy px-3 py-1.5 text-xs font-mono font-bold text-cream">
                    2019 → 2024
                </span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">

                <thead class="border-b border-slate-300 bg-navy font-mono text-xs uppercase tracking-wider text-cream">
                    <tr>
                        <th class="whitespace-nowrap px-5 py-4 text-center">No.</th>
                        <th class="whitespace-nowrap px-5 py-4">Partai Politik</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Suara 2019</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Share 2019</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Suara 2024</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Share 2024</th>
                        <th class="whitespace-nowrap px-5 py-4 text-right">Selisih Share</th>
                        <th class="whitespace-nowrap px-5 py-4 text-center">Tren Share</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($data as $item)
                        @php
                            $share2019 = (float) ($item->share_2019 ?? 0);
                            $share2024 = (float) ($item->share_2024 ?? 0);
                            $selisihShare = (float) ($item->selisih_share ?? ($share2024 - $share2019));

                            $statusTren = strtoupper((string) ($item->status_tren_share ?? ''));

                            if (!in_array($statusTren, ['NAIK', 'TURUN', 'TETAP'], true)) {
                                if ($selisihShare > 0) {
                                    $statusTren = 'NAIK';
                                } elseif ($selisihShare < 0) {
                                    $statusTren = 'TURUN';
                                } else {
                                    $statusTren = 'TETAP';
                                }
                            }

                            if ($statusTren === 'NAIK') {
                                $trendClass = 'border-green-200 bg-green-100 text-green-800';
                                $selisihClass = 'text-green-700';
                                $trendIcon = '↗';
                            } elseif ($statusTren === 'TURUN') {
                                $trendClass = 'border-red-200 bg-red-100 text-red-800';
                                $selisihClass = 'text-red-700';
                                $trendIcon = '↘';
                            } else {
                                $trendClass = 'border-slate-200 bg-slate-100 text-slate-700';
                                $selisihClass = 'text-slate-600';
                                $trendIcon = '→';
                            }

                            $partai = $item->partai ?? null;
                        @endphp

                        <tr class="transition-colors hover:bg-slate-50">

                            {{-- NOMOR --}}
                            <td class="px-5 py-4 text-center font-mono text-xs text-slate-500">
                                {{ $loop->iteration }}
                            </td>

                            {{-- IDENTITAS PARTAI --}}
                            <td class="px-5 py-4">
                                <div class="flex min-w-[180px] items-center gap-3">
                                    @if ($partai && !empty($partai->logo_url))
                                        <img
                                            src="{{ $partai->logo_url }}"
                                            alt="{{ $partai->singkatan_parpol ?? 'Logo partai' }}"
                                            class="h-8 w-8 rounded border border-slate-200 bg-white object-contain p-0.5"
                                            loading="lazy"
                                        >
                                    @endif

                                    <div>
                                        <div class="font-bold text-navy">
                                            {{ $partai->singkatan_parpol ?? $item->singkatan_parpol ?? '-' }}
                                        </div>

                                        <div class="mt-0.5 text-xs text-slate-500">
                                            {{ $partai->nama_lengkap_parpol ?? $item->nama_lengkap_parpol ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- JUMLAH SUARA 2019 --}}
                            <td class="whitespace-nowrap px-5 py-4 text-right font-mono font-semibold text-navy">
                                {{ number_format((float) ($item->suara_2019 ?? 0), 0, ',', '.') }}
                            </td>

                            {{-- SHARE 2019 --}}
                            <td class="whitespace-nowrap px-5 py-4 text-right font-mono font-semibold text-navy">
                                {{ number_format($share2019, 2, ',', '.') }}%
                            </td>

                            {{-- JUMLAH SUARA 2024 --}}
                            <td class="whitespace-nowrap px-5 py-4 text-right font-mono font-semibold text-navy">
                                {{ number_format((float) ($item->suara_2024 ?? 0), 0, ',', '.') }}
                            </td>

                            {{-- SHARE 2024 --}}
                            <td class="whitespace-nowrap px-5 py-4 text-right font-mono font-semibold text-navy">
                                {{ number_format($share2024, 2, ',', '.') }}%
                            </td>

                            {{-- SELISIH SHARE DALAM POIN PERSENTASE --}}
                            <td class="whitespace-nowrap px-5 py-4 text-right font-mono font-bold {{ $selisihClass }}">
                                @if ($selisihShare > 0)
                                    +
                                @endif
                                {{ number_format($selisihShare, 2, ',', '.') }} pp
                            </td>

                            {{-- STATUS TREN BERDASARKAN SHARE --}}
                            <td class="whitespace-nowrap px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1 rounded-full border px-3 py-1 text-xs font-bold {{ $trendClass }}">
                                    <span aria-hidden="true">{{ $trendIcon }}</span>
                                    {{ $statusTren }}
                                </span>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-500">
                                <p class="font-mono text-sm">
                                    Belum ada data tren share suara yang tersedia.
                                </p>
                                <p class="mt-2 text-xs">
                                    Pastikan data Pemilu 2019 dan 2024 berhasil dibaca oleh controller.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="border-t border-slate-200 bg-slate-50 px-6 py-4">
            <p class="text-xs leading-5 text-slate-500">
                Catatan: Selisih share dinyatakan dalam poin persentase (pp), yaitu
                share 2024 dikurangi share 2019. Status naik, turun, atau tetap
                ditentukan berdasarkan selisih share, bukan semata-mata perubahan jumlah suara.
            </p>
        </div>

    </section>

</div>

<script>
    function toggleDownloadMenu() {
        const menu = document.getElementById('downloadMenu');
        const button = document.querySelector('[aria-controls="downloadMenu"]');

        if (!menu || !button) {
            return;
        }

        const isHidden = menu.classList.toggle('hidden');
        button.setAttribute('aria-expanded', String(!isHidden));
    }

    document.addEventListener('click', function (event) {
        const menu = document.getElementById('downloadMenu');
        const button = document.querySelector('[aria-controls="downloadMenu"]');

        if (!menu || !button) {
            return;
        }

        if (!menu.contains(event.target) && !button.contains(event.target)) {
            menu.classList.add('hidden');
            button.setAttribute('aria-expanded', 'false');
        }
    });
</script>
@endsection