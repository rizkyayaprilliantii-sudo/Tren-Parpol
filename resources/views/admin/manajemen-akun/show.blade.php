@extends('layouts.sidebar')

@section('title', 'Detail Akun')
@section('page-title', 'Detail Akun')

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
                Detail Akun
            </h2>

            <p class="mt-1 text-sm text-slate">
                Informasi akun pengguna.
            </p>

        </div>

    </div>


    {{-- =========================================================
        DETAIL CARD
    ========================================================== --}}
    <div class="overflow-hidden rounded-xl border-2 border-[#4B5563] bg-white shadow-sm">


        {{-- =====================================================
            ACCOUNT HEADER
        ====================================================== --}}
        <div class="bg-navy px-6 py-6 text-cream">

            <div class="flex items-center gap-4">

                {{-- AVATAR --}}
                <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-full border-2 border-gold bg-white/10 text-xl font-bold text-gold">

                    {{ strtoupper(substr($user->name, 0, 1)) }}

                </div>


                {{-- ACCOUNT INFO --}}
                <div class="min-w-0">

                    <h2 class="truncate text-xl font-bold">
                        {{ $user->name }}
                    </h2>

                    <p class="mt-0.5 truncate text-sm text-cream/70">
                        {{ $user->email }}
                    </p>

                </div>

            </div>

        </div>


        {{-- =====================================================
            ACCOUNT INFORMATION
        ====================================================== --}}
        <div class="divide-y divide-slate-200">


            {{-- NAMA --}}
            <div class="grid gap-2 px-6 py-4 sm:grid-cols-3">

                <span class="text-sm font-semibold text-slate">
                    Nama
                </span>

                <span class="text-sm font-semibold text-navy sm:col-span-2">
                    {{ $user->name }}
                </span>

            </div>


            {{-- EMAIL --}}
            <div class="grid gap-2 px-6 py-4 sm:grid-cols-3">

                <span class="text-sm font-semibold text-slate">
                    Email
                </span>

                <span class="text-sm font-semibold text-navy sm:col-span-2 break-words">
                    {{ $user->email }}
                </span>

            </div>


            {{-- ROLE --}}
            <div class="grid gap-2 px-6 py-4 sm:grid-cols-3">

                <span class="text-sm font-semibold text-slate">
                    Role
                </span>

                <div class="sm:col-span-2">

                    @if ($user->role === 'admin')

                        <span class="inline-flex items-center rounded-full border border-navy bg-navy px-3 py-1 text-xs font-bold text-cream">
                            Admin
                        </span>

                    @else

                        <span class="inline-flex items-center rounded-full border border-gold bg-gold px-3 py-1 text-xs font-bold text-navy">
                            Pimpinan
                        </span>

                    @endif

                </div>

            </div>


            {{-- TANGGAL DIBUAT --}}
            <div class="grid gap-2 px-6 py-4 sm:grid-cols-3">

                <span class="text-sm font-semibold text-slate">
                    Tanggal Dibuat
                </span>

                <span class="font-mono text-xs font-medium text-navy sm:col-span-2">
                    {{ $user->created_at?->format('d M Y, H:i') ?? '-' }}
                </span>

            </div>


            {{-- TERAKHIR DIPERBARUI --}}
            <div class="grid gap-2 px-6 py-4 sm:grid-cols-3">

                <span class="text-sm font-semibold text-slate">
                    Terakhir Diperbarui
                </span>

                <span class="font-mono text-xs font-medium text-navy sm:col-span-2">
                    {{ $user->updated_at?->format('d M Y, H:i') ?? '-' }}
                </span>

            </div>

        </div>


        {{-- =====================================================
            ACTION
        ====================================================== --}}
        <div class="flex flex-col gap-3 border-t border-slate-200 px-6 py-5 sm:flex-row sm:justify-end">


            {{-- KEMBALI --}}
            <a
                href="{{ route('admin.manajemen-akun.index') }}"
                class="inline-flex items-center justify-center rounded-lg border border-navy px-5 py-2.5 text-sm font-bold text-navy transition-colors hover:bg-navy hover:text-cream"
            >
                Kembali
            </a>


            {{-- EDIT --}}
            <a
                href="{{ route('admin.manajemen-akun.edit', $user) }}"
                class="inline-flex items-center justify-center rounded-lg bg-navy px-5 py-2.5 text-sm font-bold text-cream transition-colors hover:bg-[#263957]"
            >
                Edit Akun
            </a>

        </div>

    </div>

</div>

@endsection