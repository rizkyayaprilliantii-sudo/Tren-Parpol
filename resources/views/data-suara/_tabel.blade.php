{{-- Tabel Rekap per Partai --}}
<div class="bg-white border-2 border-navy rounded-xl overflow-hidden shadow-[8px_8px_0_0_#1B2A41]">

    {{-- HEADER TABEL --}}
    <div class="bg-navy px-6 py-4">

        <h2 class="font-display text-xl text-cream">
            Rekapitulasi Perolehan Suara Pileg {{ $tahun }}
        </h2>

        <p class="text-xs text-gold-soft font-mono mt-1">
            Berdasarkan partai politik · Kota Banjarmasin
        </p>

    </div>


    {{-- TABEL --}}
    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-cream border-b-2 border-navy/15">

                <tr>

                    <th class="px-6 py-4 text-left font-mono text-xs text-slate font-bold">
                        PERINGKAT
                    </th>

                    <th class="px-6 py-4 text-left font-mono text-xs text-slate font-bold">
                        PARTAI POLITIK
                    </th>

                    <th class="px-6 py-4 text-right font-mono text-xs text-slate font-bold">
                        TOTAL PEROLEHAN SUARA
                    </th>

                    <th class="px-6 py-4 text-left font-mono text-xs text-slate font-bold">
                        PROPORSI
                    </th>

                    <th class="px-6 py-4 text-right font-mono text-xs text-slate font-bold">
                        %
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($data as $row)

                    @php
                        $persentase = $totalSuara > 0
                            ? ($row->total_suara / $totalSuara) * 100
                            : 0;

                        $barWidth = min($persentase * 2, 100);

                        $medals = ['🥇', '🥈', '🥉'];
                    @endphp


                    <tr class="border-t border-navy/10 hover:bg-cream/60 transition-colors">

                        {{-- PERINGKAT --}}
                        <td class="px-6 py-4">

                            @if($row->peringkat <= 3)

                                <span class="text-2xl">
                                    {{ $medals[$row->peringkat - 1] }}
                                </span>

                            @else

                                <span class="font-mono text-lg font-bold text-navy">
                                    #{{ $row->peringkat }}
                                </span>

                            @endif

                        </td>


                        {{-- PARTAI --}}
                        <td class="px-6 py-4">

                            <div class="flex items-center gap-3">

                                {{-- LOGO PARTAI --}}
                                @if($row->partai && $row->partai->logo_url)

                                    <div class="w-12 h-12 flex-shrink-0 flex items-center justify-center">

                                        <img
                                            src="{{ $row->partai->logo_url }}"
                                            alt="Logo {{ $row->partai->singkatan_parpol }}"
                                            class="w-11 h-11 object-contain"
                                        >

                                    </div>

                                @else

                                    {{-- Placeholder jika logo tidak ditemukan --}}
                                    <div class="w-12 h-12 flex-shrink-0 rounded-full bg-navy/5 flex items-center justify-center">

                                        <span class="text-xs font-mono text-navy/40">
                                            —
                                        </span>

                                    </div>

                                @endif


                                {{-- NAMA PARTAI --}}
                                <div>

                                    <div class="font-bold text-navy">
                                        {{ $row->partai->singkatan_parpol ?? '-' }}
                                    </div>

                                    <div class="text-xs text-slate">
                                        {{ $row->partai->nama_lengkap_parpol ?? '-' }}
                                    </div>

                                </div>

                            </div>

                        </td>


                        {{-- TOTAL SUARA --}}
                        <td class="px-6 py-4 text-right">

                            <div class="font-mono text-xl font-black text-maroon">
                                {{ number_format($row->total_suara) }}
                            </div>

                            <div class="text-xs text-slate">
                                suara
                            </div>

                        </td>


                        {{-- PROPORSI --}}
                        <td class="px-6 py-4 w-64">

                            <div class="h-3 bg-cream rounded-full overflow-hidden">

                                <div
                                    class="h-full bg-gradient-to-r from-gold to-yellow-500 rounded-full transition-all duration-500"
                                    style="width: {{ $barWidth }}%"
                                ></div>

                            </div>

                        </td>


                        {{-- PERSENTASE --}}
                        <td class="px-6 py-4 text-right">

                            <span class="inline-block px-3 py-1 bg-gold/10 text-navy font-mono font-bold text-sm rounded-full">
                                {{ number_format($persentase, 2) }}%
                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="5"
                            class="px-6 py-12 text-center text-slate"
                        >

                            <div class="text-4xl mb-2">
                                📭
                            </div>

                            <div class="font-mono">
                                Tidak ada data untuk tahun {{ $tahun }}.
                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>


            {{-- TOTAL --}}
            <tfoot class="bg-cream border-t-2 border-navy/15">

                <tr>

                    <td
                        colspan="2"
                        class="px-6 py-4 font-bold text-navy"
                    >
                        TOTAL
                    </td>

                    <td class="px-6 py-4 text-right font-mono text-xl font-black text-maroon">
                        {{ number_format($totalSuara) }}
                    </td>

                    <td class="px-6 py-4"></td>

                    <td class="px-6 py-4 text-right font-mono font-bold text-navy">
                        100.00%
                    </td>

                </tr>

            </tfoot>

        </table>

    </div>

</div>