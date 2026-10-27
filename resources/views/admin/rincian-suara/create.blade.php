@extends('layouts.sidebar')

@section('title', 'Tambah Data Suara')

@section('page-title', 'Transaksi: Tambah Data Suara')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider text-gold">
                Pengelolaan Data Suara
            </p>

            <h1 class="mt-1 text-2xl font-bold text-ink">
                Tambah Data Suara
            </h1>

            <p class="mt-1 text-sm text-slate">
                Tambahkan data perolehan suara partai politik untuk Pemilu
                {{ $tahun }}.
            </p>
        </div>

        <a
            href="{{ route('admin.rincian.index', ['tahun' => $tahun]) }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-ink transition hover:border-gold hover:text-gold"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"
                />
            </svg>

            Kembali
        </a>
    </div>


    {{-- Error Validation --}}
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
                    />
                </svg>

                <div>
                    <p class="font-semibold text-red-800">
                        Data belum dapat disimpan.
                    </p>

                    <ul class="mt-1 list-disc pl-5 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    @endif


    {{-- Form --}}
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">

        {{-- Form Header --}}
        <div class="border-b border-gray-200 px-6 py-5">
            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gold/10 text-gold">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 4.5v15m7.5-7.5h-15"
                        />
                    </svg>
                </div>

                <div>
                    <h2 class="font-bold text-ink">
                        Form Data Suara {{ $tahun }}
                    </h2>

                    <p class="text-sm text-slate">
                        Lengkapi data sesuai wilayah dan partai yang dipilih.
                    </p>
                </div>

            </div>
        </div>


        <form
            action="{{ route('admin.rincian.store') }}"
            method="POST"
            class="p-6"
        >
            @csrf

            {{-- Tahun --}}
            <input
                type="hidden"
                name="tahun"
                value="{{ $tahun }}"
            >


            {{-- ========================= --}}
            {{-- DATA 2019 --}}
            {{-- ========================= --}}
            @if ($tahun === '2019')

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- Kecamatan --}}
                    <div>
                        <label
                            for="id_kecamatan"
                            class="mb-2 block text-sm font-semibold text-ink"
                        >
                            Kecamatan
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="id_kecamatan"
                            name="id_kecamatan"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-ink outline-none transition focus:border-gold focus:ring-2 focus:ring-gold/20 @error('id_kecamatan') border-red-500 @enderror"
                        >
                            <option value="">
                                Pilih Kecamatan
                            </option>

                            @foreach ($kecamatan as $item)
                                <option
                                    value="{{ $item->id }}"
                                    {{ old('id_kecamatan') == $item->id ? 'selected' : '' }}
                                >
                                    {{ $item->nama_kecamatan }}
                                </option>
                            @endforeach
                        </select>

                        @error('id_kecamatan')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Partai --}}
                    <div>
                        <label
                            for="id_partai"
                            class="mb-2 block text-sm font-semibold text-ink"
                        >
                            Partai Politik
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="id_partai"
                            name="id_partai"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-ink outline-none transition focus:border-gold focus:ring-2 focus:ring-gold/20 @error('id_partai') border-red-500 @enderror"
                        >
                            <option value="">
                                Pilih Partai Politik
                            </option>

                            @foreach ($partai as $item)
                                <option
                                    value="{{ $item->id }}"
                                    {{ old('id_partai') == $item->id ? 'selected' : '' }}
                                >
                                    {{ $item->singkatan_parpol }}
                                    -
                                    {{ $item->nama_lengkap_parpol }}
                                </option>
                            @endforeach
                        </select>

                        @error('id_partai')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>


            {{-- ========================= --}}
            {{-- DATA 2024 --}}
            {{-- ========================= --}}
            @else

                <div class="space-y-6">

                    {{-- TPS --}}
                    <div>
                        <label
                            for="id_tps"
                            class="mb-2 block text-sm font-semibold text-ink"
                        >
                            TPS
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="id_tps"
                            name="id_tps"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-ink outline-none transition focus:border-gold focus:ring-2 focus:ring-gold/20 @error('id_tps') border-red-500 @enderror"
                        >
                            <option value="">
                                Pilih TPS
                            </option>

                            @foreach ($tps as $item)
                                <option
                                    value="{{ $item->id }}"
                                    data-kecamatan="{{ $item->kecamatan->nama_kecamatan ?? '-' }}"
                                    data-kelurahan="{{ $item->kelurahan->nama_kelurahan ?? '-' }}"
                                    data-nomor-tps="{{ $item->nomor_tps }}"
                                    {{ old('id_tps') == $item->id ? 'selected' : '' }}
                                >
                                    TPS {{ $item->nomor_tps }}
                                    -
                                    {{ $item->kelurahan->nama_kelurahan ?? '-' }}
                                    -
                                    {{ $item->kecamatan->nama_kecamatan ?? '-' }}
                                </option>
                            @endforeach
                        </select>

                        @error('id_tps')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>


                    {{-- Informasi Wilayah --}}
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate">
                                Kecamatan
                            </p>

                            <p
                                id="info-kecamatan"
                                class="mt-1 font-semibold text-ink"
                            >
                                -
                            </p>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate">
                                Kelurahan
                            </p>

                            <p
                                id="info-kelurahan"
                                class="mt-1 font-semibold text-ink"
                            >
                                -
                            </p>
                        </div>

                    </div>


                    {{-- Partai --}}
                    <div>
                        <label
                            for="id_partai"
                            class="mb-2 block text-sm font-semibold text-ink"
                        >
                            Partai Politik
                            <span class="text-red-500">*</span>
                        </label>

                        <select
                            id="id_partai"
                            name="id_partai"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-ink outline-none transition focus:border-gold focus:ring-2 focus:ring-gold/20 @error('id_partai') border-red-500 @enderror"
                        >
                            <option value="">
                                Pilih Partai Politik
                            </option>

                            @foreach ($partai as $item)
                                <option
                                    value="{{ $item->id }}"
                                    {{ old('id_partai') == $item->id ? 'selected' : '' }}
                                >
                                    {{ $item->singkatan_parpol }}
                                    -
                                    {{ $item->nama_lengkap_parpol }}
                                </option>
                            @endforeach
                        </select>

                        @error('id_partai')
                            <p class="mt-1.5 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

            @endif


            {{-- Jumlah Suara --}}
            <div class="mt-6">

                <label
                    for="jumlah_suara"
                    class="mb-2 block text-sm font-semibold text-ink"
                >
                    Jumlah Suara
                    <span class="text-red-500">*</span>
                </label>

                <input
                    type="number"
                    id="jumlah_suara"
                    name="jumlah_suara"
                    value="{{ old('jumlah_suara') }}"
                    min="0"
                    step="1"
                    required
                    placeholder="Masukkan jumlah suara"
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-ink outline-none transition focus:border-gold focus:ring-2 focus:ring-gold/20 @error('jumlah_suara') border-red-500 @enderror"
                >

                @error('jumlah_suara')
                    <p class="mt-1.5 text-xs text-red-600">
                        {{ $message }}
                    </p>
                @enderror

                <p class="mt-2 text-xs text-slate">
                    Masukkan jumlah suara dalam bilangan bulat dan tidak boleh
                    kurang dari 0.
                </p>

            </div>


            {{-- Informasi --}}
            <div class="mt-6 rounded-lg border border-gold/30 bg-gold/5 p-4">

                <div class="flex items-start gap-3">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="mt-0.5 h-5 w-5 shrink-0 text-gold"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v3.75m0 3.75h.007v.008H12v-.008ZM10.29 3.86 2.82 17a2.25 2.25 0 0 0 1.95 3.375h14.46A2.25 2.25 0 0 0 21.18 17L13.71 3.86a2.25 2.25 0 0 0-3.42 0Z"
                        />
                    </svg>

                    <div>
                        <p class="text-sm font-semibold text-ink">
                            Perhatian
                        </p>

                        @if ($tahun === '2019')
                            <p class="mt-1 text-sm text-slate">
                                Data suara Pemilu 2019 dicatat pada tingkat
                                kecamatan. Pastikan kombinasi kecamatan dan
                                partai belum memiliki data.
                            </p>
                        @else
                            <p class="mt-1 text-sm text-slate">
                                Data suara Pemilu 2024 dicatat pada tingkat
                                TPS. Kecamatan dan kelurahan akan mengikuti
                                TPS yang dipilih secara otomatis.
                            </p>
                        @endif
                    </div>

                </div>

            </div>


            {{-- Tombol --}}
            <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('admin.rincian.index', ['tahun' => $tahun]) }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-ink transition hover:border-gray-400"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-gold px-5 py-2.5 text-sm font-bold text-white transition hover:bg-gold/90"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 4.5v15m7.5-7.5h-15"
                        />
                    </svg>

                    Simpan Data
                </button>

            </div>

        </form>

    </div>

</div>


{{-- Script khusus 2024 --}}
@if ($tahun === '2024')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tpsSelect = document.getElementById('id_tps');
            const kecamatanInfo = document.getElementById('info-kecamatan');
            const kelurahanInfo = document.getElementById('info-kelurahan');

            function updateWilayah() {
                const selectedOption =
                    tpsSelect.options[tpsSelect.selectedIndex];

                if (!selectedOption || !selectedOption.value) {
                    kecamatanInfo.textContent = '-';
                    kelurahanInfo.textContent = '-';
                    return;
                }

                kecamatanInfo.textContent =
                    selectedOption.dataset.kecamatan || '-';

                kelurahanInfo.textContent =
                    selectedOption.dataset.kelurahan || '-';
            }

            tpsSelect.addEventListener('change', updateWilayah);

            updateWilayah();
        });
    </script>
@endif

@endsection