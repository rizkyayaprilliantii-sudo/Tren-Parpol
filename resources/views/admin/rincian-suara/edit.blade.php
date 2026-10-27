@extends('layouts.sidebar')

@section('title', 'Edit Data Suara')

@section('page-title', 'Transaksi: Edit Data Suara')

@section('content')

<div class="mx-auto max-w-4xl space-y-6">

    {{-- ==========================================================
        HEADER
    =========================================================== --}}

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>

            <div class="mb-1 flex items-center gap-2">

                <span class="h-1.5 w-1.5 rounded-full bg-gold"></span>

                <span
                    class="font-mono text-[10px] font-bold uppercase tracking-[0.18em] text-gold"
                >
                    Pengelolaan Data Suara
                </span>

            </div>

            <h1 class="font-display text-2xl font-bold text-navy">
                Edit Data Suara
            </h1>

            <p class="mt-1 text-sm text-slate">
                Perbarui jumlah perolehan suara pada data Pemilu {{ $tahun }}.
            </p>

        </div>


        <a
            href="{{ route('admin.rincian.index', ['tahun' => $tahun]) }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg border-2 border-[#4B5563] bg-white px-4 py-2.5 text-sm font-semibold text-slate transition-all hover:border-navy hover:text-navy"
        >

            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"
                ></path>
            </svg>

            Kembali

        </a>

    </div>


    {{-- ==========================================================
        ERROR VALIDASI
    =========================================================== --}}

    @if ($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4">

            <div class="flex items-start gap-3">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mt-0.5 h-5 w-5 shrink-0 text-red-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v3.75m0 3.75h.007v.008H12v-.008ZM10.29 3.86 2.82 17a2.25 2.25 0 0 0 1.95 3.375h14.46A2.25 2.25 0 0 0 21.18 17L13.71 3.86a2.25 2.25 0 0 0-3.42 0Z"
                    ></path>
                </svg>

                <div>

                    <p class="font-semibold text-red-800">
                        Data belum dapat diperbarui.
                    </p>

                    <ul class="mt-1 list-disc pl-5 text-sm text-red-700">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- ==========================================================
        FORM EDIT
    =========================================================== --}}

    <div
        class="overflow-hidden rounded-2xl border-2 border-[#4B5563] bg-white shadow-sm"
    >

        {{-- HEADER FORM --}}

        <div class="border-b border-slate-300 bg-navy px-6 py-5">

            <div class="flex items-center gap-3">

                <div
                    class="flex h-10 w-10 items-center justify-center rounded-lg bg-gold text-white"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16.862 4.487 19.5 7.125m-1.5-4.5a2.121 2.121 0 013 3L7.5 18.75 3 20l1.25-4.5L18 2.625z"
                        ></path>
                    </svg>

                </div>

                <div>

                    <h2 class="font-bold text-white">
                        Detail Data Suara {{ $tahun }}
                    </h2>

                    <p class="text-sm text-cream/70">
                        Identitas wilayah dan partai hanya sebagai referensi.
                    </p>

                </div>

            </div>

        </div>


        <form
            action="{{ route('admin.rincian.update', [
                'tahun' => $tahun,
                'id' => $data->id
            ]) }}"
            method="POST"
            class="p-6"
        >

            @csrf
            @method('PUT')


            {{-- ==================================================
                DATA REFERENSI
            =================================================== --}}

            <div class="space-y-5">

                @if ($tahun === '2019')

                    {{-- KECAMATAN --}}

                    <div>

                        <label
                            class="mb-2 block text-sm font-semibold text-navy"
                        >
                            Kecamatan
                        </label>

                        <div
                            class="rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-medium text-navy"
                        >
                            {{ $data->kecamatan->nama_kecamatan ?? '-' }}
                        </div>

                    </div>


                @else

                    {{-- WILAYAH 2024 --}}

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-3">

                        {{-- KECAMATAN --}}

                        <div>

                            <label
                                class="mb-2 block text-sm font-semibold text-navy"
                            >
                                Kecamatan
                            </label>

                            <div
                                class="rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-medium text-navy"
                            >
                                {{ $data->tps->kecamatan->nama_kecamatan ?? '-' }}
                            </div>

                        </div>


                        {{-- KELURAHAN --}}

                        <div>

                            <label
                                class="mb-2 block text-sm font-semibold text-navy"
                            >
                                Kelurahan
                            </label>

                            <div
                                class="rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 text-sm font-medium text-navy"
                            >
                                {{ $data->tps->kelurahan->nama_kelurahan ?? '-' }}
                            </div>

                        </div>


                        {{-- TPS --}}

                        <div>

                            <label
                                class="mb-2 block text-sm font-semibold text-navy"
                            >
                                No. TPS
                            </label>

                            <div
                                class="rounded-lg border border-slate-300 bg-slate-50 px-4 py-3 font-mono text-sm font-bold text-navy"
                            >
                                TPS
                                {{ str_pad($data->tps->nomor_tps ?? 0, 3, '0', STR_PAD_LEFT) }}
                            </div>

                        </div>

                    </div>

                @endif


                {{-- ==================================================
                    PARTAI
                =================================================== --}}

                <div>

                    <label
                        class="mb-2 block text-sm font-semibold text-navy"
                    >
                        Partai Politik
                    </label>

                    <div
                        class="flex items-center gap-3 rounded-lg border border-slate-300 bg-slate-50 px-4 py-3"
                    >

                        @if ($data->partai && $data->partai->logo_url)

                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-300 bg-white"
                            >

                                <img
                                    src="{{ $data->partai->logo_url }}"
                                    alt="{{ $data->partai->singkatan_parpol }}"
                                    class="h-7 w-7 object-contain"
                                >

                            </div>

                        @endif

                        <div>

                            <p class="font-bold text-navy">
                                {{ $data->partai->singkatan_parpol ?? '-' }}
                            </p>

                            <p class="text-xs text-slate">
                                {{ $data->partai->nama_lengkap_parpol ?? '-' }}
                            </p>

                        </div>

                    </div>

                </div>


                {{-- ==================================================
                    JUMLAH SUARA
                =================================================== --}}

                <div>

                    <label
                        for="jumlah_suara"
                        class="mb-2 block text-sm font-semibold text-navy"
                    >
                        Jumlah Suara
                        <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="number"
                        id="jumlah_suara"
                        name="jumlah_suara"
                        value="{{ old('jumlah_suara', $data->jumlah_suara) }}"
                        min="0"
                        step="1"
                        required
                        class="w-full rounded-lg border-2 border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-navy outline-none transition focus:border-gold focus:ring-2 focus:ring-gold/20 @error('jumlah_suara') border-red-500 @enderror"
                    >

                    @error('jumlah_suara')

                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                    <p class="mt-2 text-xs text-slate">
                        Hanya jumlah suara yang dapat diubah pada transaksi ini.
                    </p>

                </div>


                {{-- ==================================================
                    INFORMASI
                =================================================== --}}

                <div
                    class="rounded-lg border border-gold/30 bg-gold/5 p-4"
                >

                    <div class="flex items-start gap-3">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="mt-0.5 h-5 w-5 shrink-0 text-gold"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v3.75m0 3.75h.007v.008H12v-.008ZM10.29 3.86 2.82 17a2.25 2.25 0 0 0 1.95 3.375h14.46A2.25 2.25 0 0 0 21.18 17L13.71 3.86a2.25 2.25 0 0 0-3.42 0Z"
                            ></path>
                        </svg>

                        <div>

                            <p class="text-sm font-semibold text-navy">
                                Data Referensi
                            </p>

                            <p class="mt-1 text-sm leading-relaxed text-slate">
                                Kecamatan, kelurahan, TPS, dan partai merupakan
                                data referensi. Untuk menjaga konsistensi
                                hubungan data, bagian tersebut tidak dapat
                                diubah pada transaksi edit.
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ==================================================
                BUTTON
            =================================================== --}}

            <div
                class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-200 pt-6 sm:flex-row sm:justify-end"
            >

                <a
                    href="{{ route('admin.rincian.index', ['tahun' => $tahun]) }}"
                    class="inline-flex items-center justify-center rounded-lg border-2 border-[#4B5563] bg-white px-5 py-2.5 text-sm font-semibold text-slate transition-all hover:border-navy hover:text-navy"
                >
                    Batal
                </a>


                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-gold px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-all hover:bg-gold/90"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        ></path>
                    </svg>

                    Simpan Perubahan

                </button>

            </div>

        </form>

    </div>

</div>

@endsection