<div class="w-full">

    @php
        $kategori = ['Tinggi', 'Rendah'];

        $styleKategori = [
            'Tinggi' => [
                'badge'  => 'bg-green-700 text-white',
                'card'   => 'bg-green-50 border-green-700',
                'shadow' => 'shadow-[6px_6px_0_0_rgba(21,128,61,0.9)] hover:shadow-[6px_10px_0_0_rgba(21,128,61,0.9)]',
                'label'  => 'bg-green-700 text-white',
            ],

            'Rendah' => [
                'badge'  => 'bg-maroon text-white',
                'card'   => 'bg-rose-50 border-maroon',
                'shadow' => 'shadow-[6px_6px_0_0_rgba(122,46,46,0.9)] hover:shadow-[6px_10px_0_0_rgba(122,46,46,0.9)]',
                'label'  => 'bg-maroon text-white',
            ],
        ];

        $deskripsiKategori = [
            'Tinggi' => 'Observasi kecamatan dan partai yang memiliki karakteristik share suara relatif lebih tinggi berdasarkan posisi centroid K-Means pada ruang fitur share 2019 dan share 2024.',

            'Rendah' => 'Observasi kecamatan dan partai yang memiliki karakteristik share suara relatif lebih rendah berdasarkan posisi centroid K-Means pada ruang fitur share 2019 dan share 2024.',
        ];
    @endphp


    {{-- =========================================================
         INFORMASI ANALISIS
    ========================================================= --}}
    <div class="bg-white border-2 border-navy/20 rounded-lg p-5 mb-6 shadow-[4px_4px_0_0_rgba(27,42,65,0.15)]">

        <p class="text-sm text-slate leading-relaxed">
            Analisis menggunakan
            <strong class="text-navy">70 observasi</strong>
            yang dibentuk dari kombinasi
            <strong class="text-navy">5 kecamatan × 14 partai yang dapat dibandingkan</strong>
            antara Pemilu 2019 dan 2024.

            Variabel yang digunakan dalam proses K-Means adalah
            <strong class="text-navy">share suara 2019</strong> dan
            <strong class="text-navy">share suara 2024</strong>.

            Jumlah klaster yang digunakan adalah
            <strong class="text-navy">K = 2</strong>
            dengan
            <strong class="text-navy">tanpa scaling</strong>.
        </p>

    </div>


    {{-- =========================================================
         RINGKASAN KLASTER
    ========================================================= --}}
    <div class="grid md:grid-cols-3 gap-5 mb-8">

        @foreach($ringkasanKlaster as $item)

            @php
                $style = $styleKategori[$item['kategori']] ?? [
                    'badge' => 'bg-slate-500 text-white',
                    'card' => 'bg-slate-50 border-slate-500',
                    'shadow' => 'shadow-[6px_6px_0_0_rgba(91,100,114,0.5)]',
                    'label' => 'bg-slate-500 text-white',
                ];
            @endphp

            <div class="border-2 p-5 rounded-lg {{ $style['card'] }} {{ $style['shadow'] }} hover:-translate-y-1 transition-all duration-200">

                <div class="inline-block px-2.5 py-1 rounded-full text-xs font-mono font-bold tracking-wide mb-3 {{ $style['label'] }}">
                    KLASTER {{ strtoupper($item['kategori']) }}
                </div>

                <div class="font-mono text-3xl text-navy font-black">
                    {{ $item['jumlah'] }} Observasi
                </div>

                <div class="text-sm text-slate mt-3 space-y-1">

                    <div class="flex justify-between gap-3">
                        <span>Rata-rata Share 2019</span>

                        <span class="font-mono text-navy font-semibold">
                            {{ number_format($item['share_2019'], 2, ',', '.') }}%
                        </span>
                    </div>

                    <div class="flex justify-between gap-3">
                        <span>Rata-rata Share 2024</span>

                        <span class="font-mono text-navy font-semibold">
                            {{ number_format($item['share_2024'], 2, ',', '.') }}%
                        </span>
                    </div>

                </div>

            </div>

        @endforeach


        {{-- =====================================================
             KUALITAS ANALISIS
        ====================================================== --}}
        <div class="border-2 p-5 rounded-lg bg-gold/10 border-gold shadow-[6px_6px_0_0_rgba(201,162,75,0.9)] hover:shadow-[6px_10px_0_0_rgba(201,162,75,0.9)] hover:-translate-y-1 transition-all duration-200">

            <div class="inline-block px-2.5 py-1 rounded-full text-xs font-mono font-bold tracking-wide mb-3 bg-gold text-navy">
                MODEL ANALISIS
            </div>

            <div class="font-mono text-3xl text-navy font-black">
                K = {{ $analisis->jumlah_cluster ?? 2 }}
            </div>

            <div class="text-sm text-slate mt-3 space-y-1">

                <div class="flex justify-between gap-3">
                    <span>Observasi</span>

                    <span class="font-mono text-navy font-semibold">
                        {{ number_format($analisis->jumlah_observasi ?? 70, 0, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between gap-3">
                    <span>Silhouette</span>

                    <span class="font-mono text-navy font-semibold">
                        {{ number_format((float) ($analisis->silhouette_score ?? 0), 4, ',', '.') }}
                    </span>
                </div>

                <div class="flex justify-between gap-3">
                    <span>Scaling</span>

                    <span class="font-mono text-navy font-semibold">
                        {{ $analisis->metode_scaling ?? 'Tanpa Scaling' }}
                    </span>
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         PENJELASAN KATEGORI
    ========================================================= --}}
    <div class="flex flex-wrap gap-5 mb-6">

        @foreach($kategori as $namaKategori)

            @php
                $style = $styleKategori[$namaKategori];
            @endphp

            <div class="flex items-center gap-2">

                <span class="w-4 h-4 rounded {{ $style['badge'] }}"></span>

                <span class="text-sm font-mono text-navy">
                    {{ $namaKategori }}
                </span>

            </div>

        @endforeach

    </div>


    {{-- =========================================================
         TABEL HASIL CLUSTERING
    ========================================================= --}}
    <div class="bg-white border-2 border-navy rounded-lg shadow-[8px_8px_0_0_#1B2A41] overflow-hidden w-full mb-12">

        <div class="bg-navy px-6 py-4">

            <h2 class="font-display text-xl text-cream">
                Hasil Clustering Perbandingan 2019–2024
            </h2>

            <p class="text-xs text-gold-soft font-mono mt-1">
                Pengelompokan berdasarkan share suara partai pada tingkat kecamatan
            </p>

        </div>


        <div class="overflow-x-auto w-full">

            <table class="w-full min-w-[1200px] text-sm">

                <thead class="bg-cream border-b-2 border-navy/15">

                    <tr>

                        <th class="px-4 py-3 text-left font-mono text-xs text-slate">
                            NO
                        </th>

                        <th class="px-4 py-3 text-left font-mono text-xs text-slate">
                            KECAMATAN
                        </th>

                        <th class="px-4 py-3 text-left font-mono text-xs text-slate">
                            PARTAI POLITIK
                        </th>

                        <th class="px-4 py-3 text-right font-mono text-xs text-slate">
                            SHARE 2019
                        </th>

                        <th class="px-4 py-3 text-right font-mono text-xs text-slate">
                            SHARE 2024
                        </th>

                        <th class="px-4 py-3 text-right font-mono text-xs text-slate">
                            SELISIH SHARE
                        </th>

                        <th class="px-4 py-3 text-center font-mono text-xs text-slate">
                            KLASTER
                        </th>

                        <th class="px-4 py-3 text-right font-mono text-xs text-slate">
                            JARAK KE CENTROID
                        </th>

                        <th class="px-4 py-3 text-center font-mono text-xs text-slate">
                            TREN
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($clusters as $index => $cluster)

                        @php
                            $style = $styleKategori[$cluster->kategori_cluster] ?? [
                                'badge' => 'bg-slate-500 text-white',
                                'card' => '',
                                'label' => '',
                            ];
                        @endphp

                        <tr class="border-t border-navy/10 hover:bg-cream/60 transition-colors">

                            {{-- NO --}}
                            <td class="px-4 py-4 font-mono text-slate">
                                {{ $index + 1 }}
                            </td>


                            {{-- KECAMATAN --}}
                            <td class="px-4 py-4">

                                <div class="font-bold text-navy">
                                    {{ $cluster->nama_kecamatan }}
                                </div>

                            </td>


                            {{-- PARTAI --}}
                            <td class="px-4 py-4">

                                <div class="flex items-center gap-3">

                                    {{-- LOGO PARTAI --}}
                                    @if(
                                        isset($cluster->partai) &&
                                        $cluster->partai &&
                                        $cluster->partai->logo_url
                                    )

                                        <div class="w-12 h-12 flex-shrink-0 flex items-center justify-center">

                                            <img
                                                src="{{ $cluster->partai->logo_url }}"
                                                alt="Logo {{ $cluster->partai->singkatan_parpol }}"
                                                class="w-11 h-11 object-contain"
                                            >

                                        </div>

                                    @else

                                        <div class="w-12 h-12 flex-shrink-0 rounded-full bg-navy/5 flex items-center justify-center">

                                            <span class="text-xs font-mono font-bold text-navy">
                                                {{ strtoupper(substr($cluster->singkatan_parpol ?? '-', 0, 5)) }}
                                            </span>

                                        </div>

                                    @endif


                                    <div>

                                        <div class="font-bold text-navy">
                                            {{ $cluster->singkatan_parpol ?? '-' }}
                                        </div>

                                        <div class="text-xs text-slate mt-1">
                                            {{ $cluster->nama_lengkap_parpol ?? '-' }}
                                        </div>

                                    </div>

                                </div>

                            </td>


                            {{-- SHARE 2019 --}}
                            <td class="px-4 py-4 text-right font-mono text-navy font-semibold">
                                {{ number_format((float) $cluster->share_2019, 2, ',', '.') }}%
                            </td>


                            {{-- SHARE 2024 --}}
                            <td class="px-4 py-4 text-right font-mono text-navy font-semibold">
                                {{ number_format((float) $cluster->share_2024, 2, ',', '.') }}%
                            </td>


                            {{-- SELISIH SHARE --}}
                            <td class="px-4 py-4 text-right font-mono font-semibold
                                {{ (float) $cluster->selisih_share > 0
                                    ? 'text-green-700'
                                    : ((float) $cluster->selisih_share < 0
                                        ? 'text-maroon'
                                        : 'text-slate') }}">

                                {{ (float) $cluster->selisih_share > 0 ? '+' : '' }}
                                {{ number_format((float) $cluster->selisih_share, 2, ',', '.') }}%

                            </td>


                            {{-- KLASTER --}}
                            <td class="px-4 py-4 text-center">

                                <span class="inline-block px-3 py-1 rounded-full text-xs font-mono font-bold {{ $style['badge'] }}">
                                    {{ $cluster->kategori_cluster }}
                                </span>

                            </td>


                            {{-- JARAK CENTROID --}}
                            <td class="px-4 py-4 text-right font-mono text-navy">

                                {{ number_format((float) $cluster->jarak_centroid, 4, ',', '.') }}

                            </td>


                            {{-- TREN --}}
                            <td class="px-4 py-4 text-center">

                                @php
                                    $trenClass = match ($cluster->status_tren) {
                                        'Naik' => 'bg-green-700 text-white',
                                        'Turun' => 'bg-maroon text-white',
                                        default => 'bg-slate-500 text-white',
                                    };
                                @endphp

                                <span class="inline-block px-3 py-1 rounded-full text-xs font-mono font-bold {{ $trenClass }}">
                                    {{ $cluster->status_tren }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="px-4 py-10 text-center text-slate">
                                Belum ada hasil clustering.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =========================================================
         KARAKTERISTIK KLASTER
    ========================================================= --}}
    <div>

        <p class="font-mono text-xs tracking-widest text-gold uppercase mb-2">
            ANALISIS KLASTER
        </p>

        <h2 class="font-display text-3xl md:text-4xl text-ink">
            Karakteristik Klaster
        </h2>

        <p class="text-slate mt-3 leading-relaxed max-w-3xl mb-8">
            Karakteristik klaster dijelaskan berdasarkan posisi observasi
            pada dua variabel yang digunakan dalam K-Means, yaitu
            share suara 2019 dan share suara 2024.

            Kategori Tinggi dan Rendah ditentukan berdasarkan posisi
            relatif centroid pada ruang fitur tersebut.
        </p>


        <div class="grid md:grid-cols-2 gap-5">

            @foreach($ringkasanKlaster as $item)

                @php
                    $style = $styleKategori[$item['kategori']];
                @endphp

                <article class="bg-white border-2 rounded-lg p-6 {{ $style['card'] }} {{ $style['shadow'] }} hover:-translate-y-1 transition-all duration-200">

                    <div class="flex items-center gap-3 mb-4">

                        <span class="w-4 h-4 rounded {{ $style['badge'] }}"></span>

                        <h3 class="font-display text-xl text-navy">
                            Klaster {{ $item['kategori'] }}
                        </h3>

                    </div>


                    <dl class="space-y-3 mb-4 text-sm">

                        <div class="flex justify-between gap-4">

                            <dt class="text-slate">
                                Jumlah observasi
                            </dt>

                            <dd class="font-mono text-navy">
                                {{ $item['jumlah'] }}
                            </dd>

                        </div>


                        <div class="flex justify-between gap-4">

                            <dt class="text-slate">
                                Rata-rata Share 2019
                            </dt>

                            <dd class="font-mono text-navy">
                                {{ number_format($item['share_2019'], 2, ',', '.') }}%
                            </dd>

                        </div>


                        <div class="flex justify-between gap-4">

                            <dt class="text-slate">
                                Rata-rata Share 2024
                            </dt>

                            <dd class="font-mono text-navy">
                                {{ number_format($item['share_2024'], 2, ',', '.') }}%
                            </dd>

                        </div>


                        <div class="flex justify-between gap-4">

                            <dt class="text-slate">
                                Rata-rata Selisih Share
                            </dt>

                            <dd class="font-mono text-navy">

                                {{ $item['selisih_share'] > 0 ? '+' : '' }}
                                {{ number_format($item['selisih_share'], 2, ',', '.') }}%

                            </dd>

                        </div>

                    </dl>


                    <p class="text-sm text-slate leading-relaxed">
                        {{ $deskripsiKategori[$item['kategori']] }}
                    </p>

                </article>

            @endforeach

        </div>

    </div>

</div>