@extends('layouts.app')

@section('title', 'Tentang')

@section('content')

<section class="max-w-5xl mx-auto px-6 py-20">

    {{-- =========================================================
         HEADER
    ========================================================= --}}
    <div class="relative bg-white border-2 border-navy rounded-xl
                shadow-[8px_8px_0_0_#1B2A41] overflow-hidden mb-14">

        <div class="absolute top-0 left-0 w-2 h-full bg-gold"></div>

        <div class="pl-6 pr-6 py-7 md:pl-10 md:pr-10 md:py-9">

            <p class="font-mono text-xs tracking-widest text-gold uppercase mb-3">
                TENTANG WEBSITE
            </p>

            <h1 class="font-display text-3xl md:text-4xl text-ink">
                Analisis Tren Perolehan Suara Partai Politik
            </h1>

            <p class="text-slate mt-4 leading-relaxed">
                Website ini dikembangkan untuk menyajikan dan menganalisis
                tren perolehan suara partai politik pada Pemilu Legislatif
                tahun 2019 dan 2024 di Kota Banjarmasin. Analisis dilakukan
                dengan membandingkan proporsi suara partai politik pada kedua
                periode serta mengelompokkan partai berdasarkan kemiripan
                proporsi suaranya menggunakan algoritma
                <em>K-Means Clustering</em>.
            </p>

        </div>
    </div>


    {{-- =========================================================
         TUJUAN WEBSITE
    ========================================================= --}}
    <div class="mb-14">

        <p class="font-mono text-xs tracking-widest text-gold uppercase mb-2">
            TUJUAN
        </p>

        <h2 class="font-display text-2xl md:text-3xl text-ink mb-5">
            Tujuan Website
        </h2>

        <div class="bg-white border-2 border-navy rounded-lg p-6 md:p-8
                    shadow-[4px_4px_0_0_#1B2A41]">

            <p class="text-slate leading-relaxed">
                Website ini bertujuan untuk membantu menyajikan data
                perolehan suara partai politik secara terstruktur, menampilkan
                perubahan proporsi suara antara Pemilu Legislatif 2019 dan
                2024, serta menyajikan hasil pengelompokan partai politik
                berdasarkan kemiripan proporsi suara pada kedua periode.
                Hasil analisis diharapkan dapat mempermudah pengguna dalam
                memahami pola dan kecenderungan perolehan suara partai politik
                di Kota Banjarmasin.
            </p>

        </div>
    </div>


    {{-- =========================================================
         RUANG LINGKUP PENELITIAN
    ========================================================= --}}
    <div class="mb-14">

        <p class="font-mono text-xs tracking-widest text-gold uppercase mb-2">
            RUANG LINGKUP
        </p>

        <h2 class="font-display text-2xl md:text-3xl text-ink mb-5">
            Gambaran Umum Penelitian
        </h2>

        <div class="grid sm:grid-cols-2 gap-5">

            {{-- OBJEK PENELITIAN --}}
            <div class="bg-white border-2 border-navy rounded-lg p-6
                        shadow-[4px_4px_0_0_#1B2A41]">

                <div class="font-mono text-xs text-gold mb-2 tracking-wider">
                    OBJEK PENELITIAN
                </div>

                <h3 class="font-display text-xl text-navy mb-3">
                    Partai Politik
                </h3>

                <p class="text-sm text-slate leading-relaxed">
                    Data perolehan suara partai politik yang menjadi objek
                    perbandingan pada Pemilu Legislatif tahun 2019 dan 2024
                    di Kota Banjarmasin.
                </p>

            </div>


            {{-- PERIODE DATA --}}
            <div class="bg-white border-2 border-navy rounded-lg p-6
                        shadow-[4px_4px_0_0_#1B2A41]">

                <div class="font-mono text-xs text-gold mb-2 tracking-wider">
                    PERIODE DATA
                </div>

                <h3 class="font-display text-xl text-navy mb-3">
                    2019 dan 2024
                </h3>

                <p class="text-sm text-slate leading-relaxed">
                    Membandingkan data perolehan suara pada dua periode
                    Pemilu Legislatif untuk mengidentifikasi perubahan
                    proporsi suara partai politik.
                </p>

            </div>


            {{-- ANALISIS TREN --}}
            <div class="bg-white border-2 border-navy rounded-lg p-6
                        shadow-[4px_4px_0_0_#1B2A41]">

                <div class="font-mono text-xs text-gold mb-2 tracking-wider">
                    ANALISIS TREN
                </div>

                <h3 class="font-display text-xl text-navy mb-3">
                    Perubahan Proporsi Suara
                </h3>

                <p class="text-sm text-slate leading-relaxed">
                    Membandingkan proporsi suara setiap partai terhadap
                    keseluruhan suara pada masing-masing periode.
                    Perubahan proporsi dinyatakan dalam poin persentase
                    untuk menunjukkan peningkatan atau penurunan
                    proporsi suara.
                </p>

            </div>


            {{-- ALGORITMA --}}
            <div class="bg-white border-2 border-navy rounded-lg p-6
                        shadow-[4px_4px_0_0_#1B2A41]">

                <div class="font-mono text-xs text-gold mb-2 tracking-wider">
                    METODE PENGELOMPOKAN
                </div>

                <h3 class="font-display text-xl text-navy mb-3">
                    K-Means Clustering
                </h3>

                <p class="text-sm text-slate leading-relaxed">
                    Digunakan untuk mengelompokkan partai politik berdasarkan
                    kemiripan nilai proporsi suara tahun 2019 dan 2024.
                    Hasil pengelompokan membantu menggambarkan karakteristik
                    partai berdasarkan pola perolehan suaranya.
                </p>

            </div>

        </div>
    </div>


    {{-- =========================================================
         PENJELASAN INDIKATOR
    ========================================================= --}}
    <div class="mb-14">

        <p class="font-mono text-xs tracking-widest text-gold uppercase mb-2">
            INDIKATOR ANALISIS
        </p>

        <h2 class="font-display text-2xl md:text-3xl text-ink mb-5">
            Memahami Hasil Analisis
        </h2>

        <div class="space-y-5">

            {{-- PROPORSI SUARA --}}
            <div class="bg-white border-2 border-navy rounded-lg p-6
                        shadow-[4px_4px_0_0_#1B2A41]">

                <h3 class="font-display text-lg text-navy mb-2">
                    1. Proporsi Suara
                </h3>

                <p class="text-sm text-slate leading-relaxed">
                    Proporsi suara menunjukkan bagian suara yang diperoleh
                    suatu partai dibandingkan dengan keseluruhan suara yang
                    menjadi dasar perhitungan pada periode yang sama.
                    Indikator ini digunakan untuk membandingkan posisi
                    perolehan suara partai pada tahun 2019 dan 2024.
                </p>

            </div>


            {{-- SELISIH PROPORSI --}}
            <div class="bg-white border-2 border-navy rounded-lg p-6
                        shadow-[4px_4px_0_0_#1B2A41]">

                <h3 class="font-display text-lg text-navy mb-2">
                    2. Perubahan Proporsi Suara
                </h3>

                <p class="text-sm text-slate leading-relaxed">
                    Perubahan proporsi suara diperoleh dari selisih proporsi
                    suara tahun 2024 dan tahun 2019. Nilai positif menunjukkan
                    peningkatan proporsi, nilai negatif menunjukkan penurunan,
                    sedangkan nilai nol menunjukkan proporsi yang tetap.
                    Perubahan ini dinyatakan dalam poin persentase (pp).
                </p>

            </div>


            {{-- HASIL CLUSTERING --}}
            <div class="bg-white border-2 border-navy rounded-lg p-6
                        shadow-[4px_4px_0_0_#1B2A41]">

                <h3 class="font-display text-lg text-navy mb-2">
                    3. Hasil K-Means Clustering
                </h3>

                <p class="text-sm text-slate leading-relaxed">
                    Hasil clustering menunjukkan kelompok partai politik
                    yang memiliki kemiripan berdasarkan fitur proporsi
                    suara tahun 2019 dan 2024. Pengelompokan ini berbeda
                    dari peringkat perolehan suara maupun urutan kenaikan
                    dan penurunan proporsi suara.
                </p>

            </div>

        </div>
    </div>


 </section>

@endsection
