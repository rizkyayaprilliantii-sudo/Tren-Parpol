@extends('layouts.sidebar')

@section('title', 'Riwayat Aktivitas')
@section('page-title', 'Riwayat Aktivitas')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="flex items-start justify-between gap-6">

        <div class="min-w-0">

            <h1 class="font-display text-2xl font-bold text-navy">
                Riwayat Aktivitas Admin
            </h1>

            <p class="mt-1.5 max-w-xl text-sm leading-relaxed text-slate">
                Catatan aktivitas yang dilakukan oleh Admin dalam sistem.
            </p>

        </div>


        {{-- KEMBALI KE KELOLA AKUN --}}
        <a
            href="{{ route('admin.manajemen-akun.index') }}"
            class="inline-flex shrink-0 items-center gap-2 rounded-lg border-2 border-navy bg-white px-4 py-2 text-sm font-bold text-navy shadow-sm transition-colors hover:bg-navy hover:text-cream"
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
                    d="M15 19l-7-7 7-7"
                />
            </svg>

            Kelola Akun

        </a>

    </div>


    {{-- =========================================================
        PENCARIAN
    ========================================================== --}}
    <div class="rounded-xl border-2 border-[#4B5563] bg-white p-5 shadow-sm">

        <form
            action="{{ route('admin.riwayat.index') }}"
            method="GET"
            class="flex items-center gap-3"
        >

            {{-- INPUT SEARCH --}}
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Cari nama admin, aktivitas, atau keterangan..."
                class="min-w-0 flex-1 rounded-lg border-2 border-[#4B5563] bg-white px-4 py-2.5 text-sm text-navy outline-none transition focus:border-gold focus:ring-1 focus:ring-gold"
            >


            {{-- TOMBOL CARI --}}
            <button
                type="submit"
                class="inline-flex shrink-0 items-center justify-center rounded-lg bg-navy px-5 py-2.5 text-sm font-bold text-cream transition-colors hover:bg-[#263957]"
            >
                Cari
            </button>


            {{-- TOMBOL RESET --}}
            @if ($search)

                <a
                    href="{{ route('admin.riwayat.index') }}"
                    class="inline-flex shrink-0 items-center justify-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-bold text-slate transition-colors hover:bg-slate-50"
                >
                    Reset
                </a>

            @endif

        </form>

    </div>


    {{-- =========================================================
        TABEL AKTIVITAS
    ========================================================== --}}
    <div class="overflow-hidden rounded-xl border-2 border-[#4B5563] bg-white shadow-sm">

        {{-- TABLE TITLE --}}
        <div class="flex items-center justify-between gap-4 border-b border-slate-200 px-6 py-5">

            <div>

                <h2 class="font-display text-lg font-bold text-navy">
                    Aktivitas Admin
                </h2>

                <p class="mt-1 text-xs text-slate">
                    Daftar aktivitas penting yang tercatat dalam sistem.
                </p>

            </div>


            <div class="shrink-0 font-mono text-xs font-semibold text-slate">

                {{ $logs->total() }} aktivitas

            </div>

        </div>


        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[850px] text-left text-sm">

                {{-- TABLE HEADER --}}
                <thead class="border-b border-slate-300 bg-navy text-cream">

                    <tr class="font-mono text-xs uppercase tracking-wider">

                        <th class="w-16 px-6 py-4 font-bold">
                            No
                        </th>

                        <th class="w-56 px-6 py-4 font-bold">
                            Nama
                        </th>

                        <th class="w-52 px-6 py-4 font-bold">
                            Aktivitas
                        </th>

                        <th class="px-6 py-4 font-bold">
                            Keterangan
                        </th>

                        <th class="w-40 px-6 py-4 font-bold">
                            Waktu
                        </th>

                    </tr>

                </thead>


                {{-- TABLE BODY --}}
                <tbody class="divide-y divide-slate-100">

                    @forelse ($logs as $log)

                        <tr class="transition-colors hover:bg-slate-50">

                            {{-- NO --}}
                            <td class="px-6 py-5 font-mono text-xs text-slate">

                                {{ $logs->firstItem() + $loop->index }}

                            </td>


                            {{-- NAMA --}}
                            <td class="px-6 py-5">

                                @if ($log->user)

                                    <span class="font-semibold text-navy">
                                        {{ $log->user->name }}
                                    </span>

                                @else

                                    <span class="text-sm text-slate">
                                        Akun tidak tersedia
                                    </span>

                                @endif

                            </td>


                            {{-- AKTIVITAS --}}
                            <td class="px-6 py-5">

                                <span class="font-semibold text-navy">
                                    {{ $log->activity }}
                                </span>

                            </td>


                            {{-- KETERANGAN --}}
                            <td class="px-6 py-5">

                                <p class="max-w-lg text-sm leading-relaxed text-slate">
                                    {{ $log->description ?? '-' }}
                                </p>

                            </td>


                            {{-- WAKTU --}}
                            <td class="whitespace-nowrap px-6 py-5">

                                <div class="font-mono text-xs font-semibold text-navy">
                                    {{ $log->created_at?->format('d M Y') ?? '-' }}
                                </div>

                                <div class="mt-1 text-xs text-slate">
                                    {{ $log->created_at?->format('H:i') ?? '-' }}
                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-14 text-center"
                            >

                                <div class="mx-auto max-w-md">

                                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">

                                        <svg
                                            class="h-6 w-6 text-slate"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            aria-hidden="true"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>

                                    </div>

                                    <p class="mt-4 font-display text-lg font-bold text-navy">
                                        Belum Ada Aktivitas
                                    </p>

                                    <p class="mt-1 text-sm leading-relaxed text-slate">
                                        Belum terdapat aktivitas Admin yang tercatat dalam sistem.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}
        @if ($logs->hasPages())

            <div class="border-t border-slate-200 px-6 py-4">

                {{ $logs->links() }}

            </div>

        @endif

    </div>

</div>

@endsection