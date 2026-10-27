@extends('layouts.pimpinan')

@section('title', 'Tren Perolehan Suara | Pimpinan')

@section('page-title', 'Tren Perolehan Suara')

@section('content')
@php
    $card = 'overflow-hidden rounded-xl border-2 border-navy bg-white';
    $chartCard = 'overflow-hidden rounded-xl border-2 border-navy bg-white';

    $chartData = collect($dataPerbandingan ?? []);
    $topPartai = $chartData
        ->filter(fn ($item) => ($item['dapat_dibandingkan'] ?? false) === true)
        ->sortByDesc(fn ($item) => (float) ($item['share_2019'] ?? 0) + (float) ($item['share_2024'] ?? 0))
        ->take(5)
        ->values();

    $chartPartai = $topPartai->pluck('partai')->values();
    $chartShare2019 = $topPartai->pluck('share_2019')->values();
    $chartShare2024 = $topPartai->pluck('share_2024')->values();

    $jumlahNaik = $jumlahNaik ?? $jumlahMeningkat ?? $chartData->where('status', 'Naik')->count();
    $jumlahTurun = $jumlahTurun ?? $jumlahMenurun ?? $chartData->where('status', 'Turun')->count();
    $jumlahTetap = $jumlahTetap ?? $chartData->where('status', 'Tetap')->count();
    $jumlahTidakDibandingkan = $chartData->where('status', 'Tidak dibandingkan')->count();

    $chartStatus = [$jumlahNaik, $jumlahTurun, $jumlahTetap];

    $kenaikanTerbesar = $kenaikanTerbesar ?? $chartData
        ->where('status', 'Naik')
        ->sortByDesc('selisih_share')
        ->first();

    $penurunanTerbesar = $penurunanTerbesar ?? $chartData
        ->where('status', 'Turun')
        ->sortBy('selisih_share')
        ->first();
@endphp

<div class="mx-auto max-w-7xl space-y-6 pb-6">

    {{-- HEADER INFORMASI --}}
    <section class="relative overflow-hidden rounded-2xl border-2 border-navy bg-navy px-6 py-6 shadow-sm sm:px-8">
        <div class="relative z-10 max-w-full">
            <h1 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">
                Tren Perolehan Suara Partai Politik
            </h1>
            <p class="mt-3 text-sm leading-6 text-white/85 sm:text-base">
                Halaman Pimpinan menyajikan ringkasan tren perolehan suara partai politik pada Pemilu Legislatif 2019 dan 2024 di Kota Banjarmasin. Analisis utama menggunakan share suara tingkat kota, sedangkan jumlah suara ditampilkan sebagai informasi pendukung.
            </p>
        </div>
        <div class="pointer-events-none absolute -right-10 -top-16 h-48 w-48 rounded-full border-[24px] border-gold/10"></div>
    </section>

    {{-- GRAFIK --}}
    <section class="grid grid-cols-1 gap-3 lg:grid-cols-3">

        {{-- TOTAL SUARA KOTA --}}
        <article class="{{ $chartCard }}">
            <div class="border-b-2 border-navy px-4 py-3">
                <h3 class="text-sm font-black leading-tight text-navy">Total Suara Tingkat Kota</h3>
                <p class="mt-1 text-xs leading-tight text-slate">Jumlah suara seluruh partai pada masing-masing tahun.</p>
            </div>
            <div class="grid grid-cols-1 gap-2 p-3 sm:grid-cols-[1fr_auto] sm:items-center">
                <div class="relative h-48 min-w-0"><canvas id="totalSuaraChart"></canvas></div>
                <div class="rounded-lg bg-cream p-3 sm:w-28">
                    <p class="text-[10px] font-semibold leading-tight text-slate">Perubahan total suara</p>
                    <p class="mt-1 font-mono text-xl font-black {{ ($persentaseTotal ?? 0) >= 0 ? 'text-gold' : 'text-red-800' }}">
                        {{ ($persentaseTotal ?? 0) > 0 ? '+' : '' }}{{ number_format((float) ($persentaseTotal ?? 0), 2, ',', '.') }}%
                    </p>
                    <div class="my-2 border-t border-slate-300"></div>
                    <p class="text-[10px] leading-relaxed text-slate">Perubahan jumlah suara 2024 dibandingkan 2019.</p>
                </div>
            </div>
        </article>

        {{-- LIMA PARTAI DENGAN SHARE TERBESAR --}}
        <article class="{{ $chartCard }}">
            <div class="border-b-2 border-navy px-4 py-3">
                <h3 class="text-sm font-black leading-tight text-navy">Perbandingan Share Partai</h3>
                <p class="mt-1 text-xs leading-tight text-slate">Lima partai dengan gabungan share tertinggi pada kedua periode.</p>
            </div>
            <div class="p-3"><div class="relative h-56 w-full"><canvas id="partaiChart"></canvas></div></div>
        </article>

        {{-- DISTRIBUSI STATUS TREN SHARE --}}
        <article class="{{ $chartCard }}">
            <div class="border-b-2 border-navy px-4 py-3">
                <h3 class="text-sm font-black leading-tight text-navy">Distribusi Tren Share</h3>
                <p class="mt-1 text-xs leading-tight text-slate">Jumlah partai berdasarkan perubahan proporsi suara.</p>
            </div>
            <div class="grid grid-cols-1 items-center gap-2 p-3 sm:grid-cols-[1fr_auto]">
                <div class="relative mx-auto h-48 w-full max-w-[210px]">
                    <canvas id="statusChart"></canvas>
                    <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-[10px] text-slate">Dibandingkan</span>
                        <span class="font-mono text-2xl font-black text-navy">{{ $jumlahNaik + $jumlahTurun + $jumlahTetap }}</span>
                        <span class="text-[10px] text-slate">Partai</span>
                    </div>
                </div>
                <div class="space-y-2 sm:w-28">
                    <div class="flex items-start gap-2"><span class="mt-1 h-2.5 w-2.5 flex-shrink-0 rounded-full bg-emerald-700"></span><div><p class="text-[10px] font-bold text-navy">Share naik</p><p class="text-[10px] text-slate">{{ $jumlahNaik }} partai</p></div></div>
                    <div class="flex items-start gap-2"><span class="mt-1 h-2.5 w-2.5 flex-shrink-0 rounded-full bg-red-800"></span><div><p class="text-[10px] font-bold text-navy">Share turun</p><p class="text-[10px] text-slate">{{ $jumlahTurun }} partai</p></div></div>
                    <div class="flex items-start gap-2"><span class="mt-1 h-2.5 w-2.5 flex-shrink-0 rounded-full bg-slate-500"></span><div><p class="text-[10px] font-bold text-navy">Share tetap</p><p class="text-[10px] text-slate">{{ $jumlahTetap }} partai</p></div></div>
                    @if ($jumlahTidakDibandingkan > 0)
                        <p class="border-t border-slate-200 pt-2 text-[10px] leading-relaxed text-slate">{{ $jumlahTidakDibandingkan }} partai tidak dibandingkan karena data salah satu periode tidak tersedia atau nol.</p>
                    @endif
                </div>
            </div>
        </article>
    </section>

    {{-- METODOLOGI SINGKAT --}}
    <section class="rounded-xl border-2 border-navy bg-white p-5">
        <h2 class="text-sm font-black text-navy">Cara membaca tren</h2>
        <div class="mt-2 grid gap-3 text-xs leading-5 text-slate sm:grid-cols-3">
            <p><strong class="text-navy">Share suara:</strong> suara partai dibagi total suara seluruh partai di kota pada tahun yang sama.</p>
            <p><strong class="text-navy">Selisih share (pp):</strong> share 2024 dikurangi share 2019. Nilai positif berarti proporsi meningkat.</p>
            <p><strong class="text-navy">Status tren:</strong> ditentukan berdasarkan selisih share, bukan hanya perubahan jumlah suara.</p>
        </div>
    </section>

    {{-- PENCARIAN DAN DOWNLOAD --}}
    <section class="overflow-hidden rounded-xl border-2 border-navy bg-white">
        <div class="border-b border-slate-300 px-5 py-3"><h3 class="text-sm font-bold text-navy">Cari Partai Pembanding</h3></div>
        <div class="flex flex-col gap-4 p-5 lg:flex-row lg:items-center">
            <form method="GET" action="{{ route('pimpinan.perbandingan.index') }}" class="flex min-w-0 flex-1 flex-col gap-3 sm:flex-row">
                <input id="search" type="text" name="search" value="{{ $search ?? '' }}" placeholder="Masukkan nama atau singkatan partai..." class="w-full flex-1 rounded-lg border border-navy bg-white px-4 py-2.5 text-sm text-navy outline-none transition placeholder:text-slate/60 focus:border-navy focus:ring-2 focus:ring-navy/20">
                <div class="flex gap-2">
                    <button type="submit" class="rounded-lg border border-navy bg-navy px-5 py-2.5 text-sm font-bold text-cream transition-colors hover:bg-gold hover:text-navy">Tampilkan</button>
                    <a href="{{ route('pimpinan.perbandingan.index') }}" class="rounded-lg border border-navy bg-white px-5 py-2.5 text-center text-sm font-bold text-navy transition-colors hover:bg-cream">Reset</a>
                </div>
            </form>

            <div class="relative flex-shrink-0">
                <button type="button" id="downloadButton" onclick="toggleDownloadMenu()" aria-expanded="false" aria-controls="downloadMenu" class="w-full rounded-lg border border-navy bg-navy px-5 py-2.5 text-sm font-bold text-cream transition-colors hover:bg-gold hover:text-navy sm:w-auto">Download <span class="ml-1">▾</span></button>
                <div id="downloadMenu" class="absolute right-0 z-50 mt-2 hidden w-56 overflow-hidden rounded-lg border border-slate-300 bg-white shadow-lg">
                    <div class="border-b border-slate-200 bg-cream px-4 py-2.5"><p class="text-xs font-bold text-navy">Unduh data</p></div>
                    <a href="{{ route('pimpinan.perbandingan.export.excel', ['search' => $search ?? '']) }}" class="block px-4 py-3 text-sm text-navy transition-colors hover:bg-cream"><span class="block font-bold">Excel</span><span class="mt-0.5 block text-xs text-slate">Data tren share dan jumlah suara</span></a>
                    <a href="{{ route('pimpinan.perbandingan.export.pdf', ['search' => $search ?? '']) }}" class="block border-t border-slate-200 px-4 py-3 text-sm text-navy transition-colors hover:bg-cream"><span class="block font-bold">PDF</span><span class="mt-0.5 block text-xs text-slate">Laporan tren share partai</span></a>
                </div>
            </div>
        </div>
    </section>

    {{-- TABEL SHARE --}}
    <section class="{{ $card }}">
        <div class="border-b-2 border-navy bg-navy px-6 py-5">
            <h3 class="font-display text-xl font-black text-cream">Tren Share Perolehan Suara</h3>
            <p class="mt-1 text-sm leading-relaxed text-cream/75">Perbandingan jumlah suara dan proporsi suara partai pada 2019 dan 2024 di Kota Banjarmasin.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1250px] text-left text-sm">
                <thead class="bg-cream">
                    <tr class="border-b-2 border-navy text-xs font-bold text-navy">
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Partai Politik</th>
                        <th class="px-5 py-4 text-right">Suara 2019</th>
                        <th class="px-5 py-4 text-right">Share 2019</th>
                        <th class="px-5 py-4 text-right">Suara 2024</th>
                        <th class="px-5 py-4 text-right">Share 2024</th>
                        <th class="px-5 py-4 text-right">Selisih Share</th>
                        <th class="px-5 py-4">Status Tren</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($dataPerbandingan as $index => $item)
                        @php
                            $status = strtoupper((string) ($item['status'] ?? $item['status_tren'] ?? 'TIDAK DIBANDINGKAN'));
                            $statusClass = match ($status) {
                                'NAIK', 'MENINGKAT' => 'text-emerald-800',
                                'TURUN', 'MENURUN' => 'text-red-800',
                                default => 'text-slate-700',
                            };
                            $statusBg = match ($status) {
                                'NAIK', 'MENINGKAT' => 'bg-emerald-100 border-emerald-700',
                                'TURUN', 'MENURUN' => 'bg-red-100 border-red-700',
                                'TIDAK DIBANDINGKAN' => 'bg-amber-100 border-amber-600',
                                default => 'bg-slate-100 border-slate-500',
                            };
                            $share2019 = $item['share_2019'] ?? null;
                            $share2024 = $item['share_2024'] ?? null;
                            $selisihShare = $item['selisih_share'] ?? null;
                        @endphp
                        <tr class="transition-colors even:bg-cream/40 hover:bg-gold/10">
                            <td class="px-5 py-4 align-middle"><span class="font-mono text-xs font-semibold text-slate">{{ $index + 1 }}</span></td>
                            <td class="px-5 py-4 align-middle">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg border border-slate-300 bg-white">
                                        @if (!empty($item['logo_url']))
                                            <img src="{{ $item['logo_url'] }}" alt="Logo {{ $item['partai'] ?? 'partai' }}" class="h-full w-full object-contain p-1" loading="lazy">
                                        @else
                                            <span class="font-display text-sm font-black text-navy">{{ strtoupper(substr($item['partai'] ?? 'P', 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[15px] font-bold text-navy">{{ $item['partai'] ?? '-' }}</p>
                                        @if (!empty($item['nama_lengkap']))
                                            <p class="mt-0.5 max-w-xs text-xs leading-relaxed text-slate">{{ $item['nama_lengkap'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right align-middle"><span class="font-mono text-sm font-bold text-navy">{{ number_format((float) ($item['suara_2019'] ?? 0), 0, ',', '.') }}</span></td>
                            <td class="px-5 py-4 text-right align-middle"><span class="font-mono text-sm font-bold text-navy">{{ $share2019 === null ? '—' : number_format((float) $share2019, 2, ',', '.') . '%' }}</span></td>
                            <td class="px-5 py-4 text-right align-middle"><span class="font-mono text-sm font-bold text-navy">{{ number_format((float) ($item['suara_2024'] ?? 0), 0, ',', '.') }}</span></td>
                            <td class="px-5 py-4 text-right align-middle"><span class="font-mono text-sm font-bold text-navy">{{ $share2024 === null ? '—' : number_format((float) $share2024, 2, ',', '.') . '%' }}</span></td>
                            <td class="px-5 py-4 text-right align-middle">
                                <span class="font-mono text-sm font-bold {{ $statusClass }}">
                                    @if ($selisihShare === null)
                                        —
                                    @else
                                        {{ $selisihShare > 0 ? '+' : '' }}{{ number_format((float) $selisihShare, 2, ',', '.') }} pp
                                    @endif
                                </span>
                            </td>
                            <td class="px-5 py-4 align-middle"><span class="inline-block rounded-md border px-3 py-1 text-xs font-bold {{ $statusBg }} {{ $statusClass }}">{{ $status === 'MENINGKAT' ? 'NAIK' : ($status === 'MENURUN' ? 'TURUN' : $status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <p class="font-display text-xl font-black text-navy">Data tren tidak ditemukan</p>
                                <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate">Tidak ada partai yang sesuai dengan pencarian. Coba kata kunci lain atau tekan Reset.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-200 bg-slate-50 px-5 py-3">
            <p class="text-xs leading-5 text-slate">Selisih share dinyatakan dalam poin persentase (pp), yaitu share 2024 dikurangi share 2019. Status tren ditentukan berdasarkan perubahan share, bukan hanya jumlah suara.</p>
        </div>
    </section>

    {{-- RINGKASAN TREN SHARE --}}
    <section class="grid gap-5 md:grid-cols-3">
        <article class="overflow-hidden rounded-xl border-2 border-emerald-700 bg-emerald-50">
            <div class="border-b border-emerald-200 bg-emerald-100 px-5 py-3"><p class="text-sm font-bold text-emerald-900">Partai dengan share meningkat</p></div>
            <div class="p-5">
                <p class="font-mono text-2xl font-black text-emerald-800">{{ $jumlahNaik }}</p>
                @if ($kenaikanTerbesar)
                    <p class="mt-2 text-sm leading-relaxed text-emerald-900">
                        Kenaikan share terbesar: <span class="font-bold">{{ is_array($kenaikanTerbesar) ? ($kenaikanTerbesar['partai'] ?? '-') : ($kenaikanTerbesar->partai ?? '-') }}</span>
                        @php $nilaiNaik = is_array($kenaikanTerbesar) ? ($kenaikanTerbesar['selisih_share'] ?? null) : ($kenaikanTerbesar->selisih_share ?? null); @endphp
                        @if ($nilaiNaik !== null)
                            ({{ $nilaiNaik > 0 ? '+' : '' }}{{ number_format((float) $nilaiNaik, 2, ',', '.') }} pp)
                        @endif
                    </p>
                @else
                    <p class="mt-2 text-sm text-emerald-900">Belum ada partai dengan share meningkat.</p>
                @endif
            </div>
        </article>

        <article class="overflow-hidden rounded-xl border-2 border-red-700 bg-red-50">
            <div class="border-b border-red-200 bg-red-100 px-5 py-3"><p class="text-sm font-bold text-red-900">Partai dengan share menurun</p></div>
            <div class="p-5">
                <p class="font-mono text-2xl font-black text-red-800">{{ $jumlahTurun }}</p>
                @if ($penurunanTerbesar)
                    <p class="mt-2 text-sm leading-relaxed text-red-900">
                        Penurunan share terbesar: <span class="font-bold">{{ is_array($penurunanTerbesar) ? ($penurunanTerbesar['partai'] ?? '-') : ($penurunanTerbesar->partai ?? '-') }}</span>
                        @php $nilaiTurun = is_array($penurunanTerbesar) ? ($penurunanTerbesar['selisih_share'] ?? null) : ($penurunanTerbesar->selisih_share ?? null); @endphp
                        @if ($nilaiTurun !== null)
                            ({{ number_format((float) $nilaiTurun, 2, ',', '.') }} pp)
                        @endif
                    </p>
                @else
                    <p class="mt-2 text-sm text-red-900">Belum ada partai dengan share menurun.</p>
                @endif
            </div>
        </article>

        <article class="overflow-hidden rounded-xl border-2 border-slate-500 bg-slate-50">
            <div class="border-b border-slate-200 bg-slate-200 px-5 py-3"><p class="text-sm font-bold text-slate-800">Partai dengan share tetap</p></div>
            <div class="p-5">
                <p class="font-mono text-2xl font-black text-slate-700">{{ $jumlahTetap }}</p>
                <p class="mt-2 text-sm leading-relaxed text-slate-700">Jumlah partai yang proporsi suaranya tidak mengalami perubahan berarti antara 2019 dan 2024.</p>
            </div>
        </article>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const formatAngka = (value) => new Intl.NumberFormat('id-ID').format(value);

    const totalSuaraCanvas = document.getElementById('totalSuaraChart');
    if (totalSuaraCanvas) {
        new Chart(totalSuaraCanvas, {
            type: 'bar',
            data: {
                labels: ['2019', '2024'],
                datasets: [{
                    data: @json([$total2019 ?? 0, $total2024 ?? 0]),
                    backgroundColor: ['#5B6F89', '#7A2E2E'],
                    borderRadius: 6,
                    maxBarThickness: 42
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: (context) => formatAngka(context.raw) + ' suara' } }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { font: { size: 9 }, color: '#718096', callback: (value) => formatAngka(value) }, grid: { color: 'rgba(27, 42, 65, 0.08)' } },
                    x: { ticks: { color: '#5B6472', font: { size: 10, weight: 'bold' } }, grid: { display: false } }
                }
            }
        });
    }

    const partaiCanvas = document.getElementById('partaiChart');
    if (partaiCanvas) {
        new Chart(partaiCanvas, {
            type: 'bar',
            data: {
                labels: @json($chartPartai),
                datasets: [
                    { label: 'Share 2019 (%)', data: @json($chartShare2019), backgroundColor: '#5B6F89', borderRadius: 4 },
                    { label: 'Share 2024 (%)', data: @json($chartShare2024), backgroundColor: '#7A2E2E', borderRadius: 4 }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { color: '#5B6472', usePointStyle: true, pointStyle: 'circle', boxWidth: 7, font: { size: 9 }, padding: 10 } },
                    tooltip: { callbacks: { label: (context) => context.dataset.label + ': ' + Number(context.raw ?? 0).toLocaleString('id-ID', { maximumFractionDigits: 2 }) + '%' } }
                },
                scales: {
                    x: { beginAtZero: true, ticks: { color: '#718096', font: { size: 9 }, callback: (value) => value + '%' }, grid: { color: 'rgba(27, 42, 65, 0.08)' } },
                    y: { ticks: { color: '#1B2A41', font: { size: 9, weight: 'bold' } }, grid: { display: false } }
                }
            }
        });
    }

    const statusCanvas = document.getElementById('statusChart');
    if (statusCanvas) {
        new Chart(statusCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Share naik', 'Share turun', 'Share tetap'],
                datasets: [{ data: @json($chartStatus), backgroundColor: ['#3F805A', '#7A2E2E', '#718096'], borderColor: '#FFFFFF', borderWidth: 2, hoverOffset: 4 }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: (context) => context.label + ': ' + context.raw + ' partai' } }
                }
            }
        });
    }

    function toggleDownloadMenu() {
        const menu = document.getElementById('downloadMenu');
        const button = document.getElementById('downloadButton');
        if (!menu || !button) return;
        const hidden = menu.classList.toggle('hidden');
        button.setAttribute('aria-expanded', String(!hidden));
    }

    document.addEventListener('click', function (event) {
        const button = document.getElementById('downloadButton');
        const menu = document.getElementById('downloadMenu');
        if (!button || !menu) return;
        if (!button.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
            button.setAttribute('aria-expanded', 'false');
        }
    });
</script>
@endsection
