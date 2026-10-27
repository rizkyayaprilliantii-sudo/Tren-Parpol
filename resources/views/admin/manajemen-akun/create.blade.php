@extends('layouts.sidebar')

@section('title', 'Tambah Akun')
@section('page-title', 'Tambah Akun')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="mb-6">

        <a
            href="{{ route('admin.manajemen-akun.index') }}"
            class="inline-flex items-center text-sm font-semibold text-navy transition-colors hover:text-gold"
        >
            <svg
                class="mr-1.5 h-4 w-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7"
                />
            </svg>

            Kembali
        </a>

        <div class="mt-4">

            <h2 class="font-display text-2xl font-bold text-navy">
                Tambah Akun
            </h2>

            <p class="mt-1 text-sm text-slate">
                Tambahkan akun admin atau pimpinan baru.
            </p>

        </div>

    </div>


    {{-- =========================================================
        FORM CARD
    ========================================================== --}}
    <div class="overflow-hidden rounded-xl border-2 border-[#4B5563] bg-white shadow-sm">

        {{-- =====================================================
            CARD HEADER
        ====================================================== --}}
        <div class="bg-navy px-6 py-5 text-cream">

            <h3 class="text-lg font-bold">
                Informasi Akun
            </h3>

            <p class="mt-1 text-xs text-cream/70">
                Masukkan informasi akun dan tentukan hak akses pengguna.
            </p>

        </div>


        {{-- =====================================================
            FORM
        ====================================================== --}}
        <div class="p-6">

            <form
                action="{{ route('admin.manajemen-akun.store') }}"
                method="POST"
                class="space-y-5"
            >

                @csrf


                {{-- =================================================
                    NAMA
                ================================================== --}}
                <div>

                    <label
                        for="name"
                        class="mb-2 block text-sm font-semibold text-navy"
                    >
                        Nama
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autocomplete="name"
                        class="w-full rounded-lg border-2 border-[#4B5563] bg-white px-4 py-2.5 text-sm text-navy outline-none transition-colors focus:border-gold focus:ring-0"
                    >

                    @error('name')
                        <p class="mt-1.5 text-xs font-medium text-maroon">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- =================================================
                    EMAIL
                ================================================== --}}
                <div>

                    <label
                        for="email"
                        class="mb-2 block text-sm font-semibold text-navy"
                    >
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        class="w-full rounded-lg border-2 border-[#4B5563] bg-white px-4 py-2.5 text-sm text-navy outline-none transition-colors focus:border-gold focus:ring-0"
                    >

                    @error('email')
                        <p class="mt-1.5 text-xs font-medium text-maroon">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- =================================================
                    ROLE
                ================================================== --}}
                <div>

                    <label
                        for="role"
                        class="mb-2 block text-sm font-semibold text-navy"
                    >
                        Role
                    </label>

                    <select
                        id="role"
                        name="role"
                        required
                        class="w-full rounded-lg border-2 border-[#4B5563] bg-white px-4 py-2.5 text-sm text-navy outline-none transition-colors focus:border-gold focus:ring-0"
                    >

                        <option value="">
                            Pilih role
                        </option>

                        <option
                            value="admin"
                            {{ old('role') === 'admin' ? 'selected' : '' }}
                        >
                            Admin
                        </option>

                        <option
                            value="pimpinan"
                            {{ old('role') === 'pimpinan' ? 'selected' : '' }}
                        >
                            Pimpinan
                        </option>

                    </select>

                    @error('role')
                        <p class="mt-1.5 text-xs font-medium text-maroon">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- =================================================
                    PASSWORD
                ================================================== --}}
                <div>

                    <label
                        for="password"
                        class="mb-2 block text-sm font-semibold text-navy"
                    >
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-lg border-2 border-[#4B5563] bg-white px-4 py-2.5 text-sm text-navy outline-none transition-colors focus:border-gold focus:ring-0"
                    >

                    @error('password')
                        <p class="mt-1.5 text-xs font-medium text-maroon">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                {{-- =================================================
                    KONFIRMASI PASSWORD
                ================================================== --}}
                <div>

                    <label
                        for="password_confirmation"
                        class="mb-2 block text-sm font-semibold text-navy"
                    >
                        Konfirmasi Password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-lg border-2 border-[#4B5563] bg-white px-4 py-2.5 text-sm text-navy outline-none transition-colors focus:border-gold focus:ring-0"
                    >

                </div>


                {{-- =================================================
                    ACTION
                ================================================== --}}
                <div class="flex flex-col-reverse gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:justify-end">

                    {{-- BATAL --}}
                    <a
                        href="{{ route('admin.manajemen-akun.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-navy px-5 py-2.5 text-sm font-bold text-navy transition-colors hover:bg-navy hover:text-cream"
                    >
                        Batal
                    </a>


                    {{-- SIMPAN --}}
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-navy px-5 py-2.5 text-sm font-bold text-cream transition-colors hover:bg-[#263957]"
                    >
                        Simpan Akun
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection