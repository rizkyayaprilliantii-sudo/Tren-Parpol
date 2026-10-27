@extends('layouts.sidebar')
@section('title', 'Proses Clustering')
@section('page-title', 'Proses Clustering K-Means')
@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    {{-- =========================================================
        NOTIFIKASI
    ========================================================== --}}
    @if(session('success'))
        <div class="relative flex items-start gap-3 overflow-hidden rounded-xl border-2 border-navy bg-white p-4 pl-5 shadow-sm">
            <span class="absolute inset-y-0 left-0 w-1.5 bg-green-500"></span>
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-navy">Analisis berhasil dijalankan</p>
                <p class="mt-0.5 text-xs leading-relaxed text-slate">{{ session('success') }}</p>
            </div>
            <button
                type="button"
                onclick="this.parentElement.remove()"
                class="shrink-0 rounded-lg p-1.5 text-slate/60 transition-colors hover:bg-navy/5 hover:text-navy focus:outline-none focus-visible:ring-2 focus-visible:ring-gold"
                aria-label="Tutup notifikasi"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif
    @if(session('error'))
        <div class="relative flex items-start gap-3 overflow-hidden rounded-xl border-2 border-navy bg-white p-4 pl-5 shadow-sm">
            <span class="absolute inset-y-0 left-0 w-1.5 bg-red-500"></span>
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86l-7.1 12.28A2 2 0 004.06 18h15.88a2 2 0 001.73-2.99l-7.1-12.28a2 2 0 00-3.42 0z" />
                </svg>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold text-navy">Analisis gagal dijalankan</p>
                <p class="mt-0.5 text-xs leading-relaxed text-slate">{{ session('error') }}</p>
            </div>
            <button
                type="button"
                onclick="this.parentElement.remove()"
                class="shrink-0 rounded-lg p-1.5 text-slate/60 transition-colors hover:bg-navy/5 hover:text-navy focus:outline-none focus-visible:ring-2 focus-visible:ring-gold"
                aria-label="Tutup notifikasi"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif
    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <section class="relative overflow-hidden rounded-2xl border-2 border-navy bg-navy">
        <div class="relative z-10 px-6 py-7 sm:px-8 sm:py-8 lg:px-10">
            <div class="max-w-4xl">
                <div class="mb-3 flex flex-wrap items-center gap-2">
                    <span class="rounded-full border border-gold/50 bg-gold/10 px-3 py-1 font-mono text-[10px] font-bold uppercase tracking-[0.16em] text-gold-soft">
                        Analisis Data
                    </span>
                    <span class="rounded-full border border-white/15 bg-white/5 px-3 py-1 font-mono text-[10px] font-semibold text-cream/70">
                        Pemilu 2019–2024
                    </span>
                </div>
                <h1 class="font-display text-2xl font-bold tracking-tight text-white sm:text-3xl lg:text-4xl">
                    Proses Clustering K-Means
                </h1>
                <p class="mt-3 max-w-3xl text-sm leading-6 text-white/75 sm:text-[15px]">
                    Halaman ini digunakan untuk mengelompokkan data perolehan suara
                    partai politik berdasarkan kemiripan
                    <strong class="text-cream">
                        share suara (proporsi suara)
                    </strong>
                    pada Pemilu 2019 dan 2024 di Kota Banjarmasin.
                </p>
            </div>
            {{-- RINGKASAN HEADER --}}
            <div class="mt-7 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border-2 border-white/15 bg-white/[0.04] px-4 py-3">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-[0.14em] text-cream/55">Periode</p>
                    <p class="mt-1 text-sm font-bold text-cream">2019 → 2024</p>
                </div>
                <div class="rounded-xl border-2 border-white/15 bg-white/[0.04] px-4 py-3">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-[0.14em] text-cream/55">Algoritma</p>
                    <p class="mt-1 text-sm font-bold text-cream">K-Means</p>
                </div>
                <div class="rounded-xl border-2 border-white/15 bg-white/[0.04] px-4 py-3">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-[0.14em] text-cream/55">Jumlah Klaster</p>
                    <p class="mt-1 text-sm font-bold text-cream">K = 2</p>
                </div>
                <div class="rounded-xl border-2 border-white/15 bg-white/[0.04] px-4 py-3">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-[0.14em] text-cream/55">Unit Analisis</p>
                    <p class="mt-1 text-sm font-bold text-cream">Kecamatan × Partai</p>
                </div>
            </div>
        </div>
        {{-- DEKORASI --}}
        <div class="pointer-events-none absolute -right-20 -top-24 h-64 w-64 rounded-full border-[30px] border-gold/10"></div>
        <div class="pointer-events-none absolute -bottom-28 right-24 h-48 w-48 rounded-full bg-white/[0.035] blur-2xl"></div>
    </section>
    {{-- =========================================================
        KONFIGURASI ANALISIS + DATA YANG DIANALISIS
    ========================================================== --}}
    <div class="space-y-6">
        {{-- ===================== KONFIGURASI ANALISIS ===================== --}}
        <section class="overflow-hidden rounded-2xl border-2 border-navy bg-white shadow-[6px_6px_0_0_rgba(27,42,65,0.10)]">
            {{-- HEADER --}}
            <div class="relative overflow-hidden bg-gradient-to-r from-navy via-navy to-[#2B4163] px-6 py-5">
                <div class="relative z-10 flex items-center gap-3">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gold text-navy shadow-md shadow-black/20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="font-display text-lg font-bold leading-tight text-white">Konfigurasi Analisis</h2>
                        <p class="text-xs text-cream/65">Parameter yang dipakai saat K-Means dijalankan.</p>
                    </div>
                    <span class="hidden rounded-full border border-gold/50 bg-gold/10 px-3 py-1 font-mono text-[11px] font-bold text-gold-soft sm:inline-flex">
                        K = 2
                    </span>
                </div>
                <div class="pointer-events-none absolute -right-8 -top-12 h-32 w-32 rounded-full border-[16px] border-gold/10"></div>
                <div class="absolute inset-x-0 bottom-0 h-1 bg-gold"></div>
            </div>
            {{-- ISI --}}
            <dl
                class="grid grid-cols-1 gap-3 bg-cream/50 p-5 sm:grid-cols-2 sm:p-6"
                style="background-image: radial-gradient(rgba(27,42,65,0.09) 1px, transparent 1px); background-size: 16px 16px;"
            >
                {{-- PERIODE --}}
                <div class="flex items-start gap-3 rounded-xl border-2 border-l-4 border-navy border-l-gold bg-white p-4 shadow-sm transition-all hover:-translate-y-0.5 hover:border-navy hover:border-l-gold hover:shadow-md">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gold/15 text-navy ring-1 ring-gold/40">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <dt class="text-xs font-semibold text-slate">Periode</dt>
                        <dd class="mt-0.5 text-sm font-bold text-navy">Pemilu 2019 dan 2024</dd>
                    </div>
                </div>
                {{-- JUMLAH KLASTER --}}
                <div class="flex items-start gap-3 rounded-xl border-2 border-l-4 border-navy border-l-gold bg-white p-4 shadow-sm transition-all hover:-translate-y-0.5 hover:border-navy hover:border-l-gold hover:shadow-md">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gold/15 text-navy ring-1 ring-gold/40">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="7" cy="8" r="3" stroke-width="2" />
                            <circle cx="17" cy="16" r="3" stroke-width="2" />
                            <path stroke-linecap="round" stroke-width="2" d="M10 8h4m-2 0v8h2" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <dt class="text-xs font-semibold text-slate">Jumlah klaster</dt>
                        <dd class="mt-0.5 flex items-center gap-2 text-sm font-bold text-navy">
                            2 Klaster
                            <span class="rounded-md bg-navy px-1.5 py-0.5 font-mono text-[10px] font-bold text-gold-soft">K = 2</span>
                        </dd>
                    </div>
                </div>
                {{-- FITUR --}}
                <div class="flex items-start gap-3 rounded-xl border-2 border-l-4 border-navy border-l-gold bg-white p-4 shadow-sm transition-all hover:-translate-y-0.5 hover:border-navy hover:border-l-gold hover:shadow-md">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gold/15 text-navy ring-1 ring-gold/40">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <dt class="text-xs font-semibold text-slate">Fitur analisis</dt>
                        <dd class="mt-0.5 text-sm font-bold text-navy">Share suara 2019 &amp; 2024</dd>
                        <p class="mt-1 text-xs leading-5 text-slate">Proporsi suara partai pada tiap kecamatan.</p>
                    </div>
                </div>
                {{-- PENGOLAHAN DATA --}}
                <div class="flex items-start gap-3 rounded-xl border-2 border-l-4 border-navy border-l-gold bg-white p-4 shadow-sm transition-all hover:-translate-y-0.5 hover:border-navy hover:border-l-gold hover:shadow-md">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gold/15 text-navy ring-1 ring-gold/40">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h10M4 17h6" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <dt class="text-xs font-semibold text-slate">Pengolahan data</dt>
                        <dd class="mt-0.5 text-sm font-bold text-navy">Tanpa scaling</dd>
                        <p class="mt-1 text-xs leading-5 text-slate">Kedua fitur memakai satuan yang sama.</p>
                    </div>
                </div>
                {{-- UNIT ANALISIS --}}
                <div class="flex items-start gap-3 rounded-xl border-2 border-l-4 border-navy border-l-gold bg-white p-4 shadow-sm transition-all hover:-translate-y-0.5 hover:border-navy hover:border-l-gold hover:shadow-md sm:col-span-2">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gold/15 text-navy ring-1 ring-gold/40">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18M10 3v18M6 3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3z" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <dt class="text-xs font-semibold text-slate">Unit analisis</dt>
                        <dd class="mt-0.5 text-sm font-bold text-navy">Kecamatan × Partai Politik</dd>
                        <p class="mt-1 text-xs leading-5 text-slate">Satu data mewakili satu partai pada satu kecamatan.</p>
                    </div>
                </div>
            </dl>
        </section>
        {{-- ===================== DATA YANG DIANALISIS ===================== --}}
        <section class="overflow-hidden rounded-2xl border-2 border-navy bg-white shadow-[6px_6px_0_0_rgba(27,42,65,0.10)]">
            {{-- HEADER --}}
            <div class="relative overflow-hidden bg-gradient-to-r from-navy via-navy to-[#2B4163] px-6 py-5 sm:px-7">
                <div class="relative z-10 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gold text-navy shadow-md shadow-black/20">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7c0-1.1 3.58-2 8-2s8 .9 8 2-3.58 2-8 2-8-.9-8-2zm0 0v10c0 1.1 3.58 2 8 2s8-.9 8-2V7M4 12c0 1.1 3.58 2 8 2s8-.9 8-2" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-display text-lg font-bold leading-tight text-white">Data yang dianalisis</h2>
                            <p class="mt-0.5 max-w-2xl text-xs leading-5 text-cream/65">
                                Data akhir yang digunakan dalam proses pengelompokan setelah penyamaan tingkat data dan pemilihan partai yang dapat dibandingkan.
                            </p>
                        </div>
                    </div>
                    <span class="inline-flex w-fit shrink-0 items-center rounded-full border border-gold/50 bg-gold/10 px-3 py-1.5 font-mono text-xs font-bold text-gold-soft">
                        70 data analisis
                    </span>
                </div>
                <div class="pointer-events-none absolute -right-8 -top-12 h-32 w-32 rounded-full border-[16px] border-gold/10"></div>
                <div class="absolute inset-x-0 bottom-0 h-1 bg-gold"></div>
            </div>
            {{-- RINGKASAN DATA --}}
            <div
                class="grid grid-cols-1 gap-4 bg-cream/50 p-6 sm:grid-cols-2 sm:p-7 lg:grid-cols-4"
                style="background-image: radial-gradient(rgba(27,42,65,0.09) 1px, transparent 1px); background-size: 16px 16px;"
            >
                {{-- DATA AKHIR (SOROTAN) --}}
                <div class="relative overflow-hidden rounded-xl border-2 border-navy bg-navy px-5 py-4 shadow-md">
                    <div class="flex flex-wrap items-baseline gap-x-2 gap-y-1">
                        <p class="text-xs font-semibold text-cream/70">Data akhir</p>
                        <p class="font-display text-4xl font-extrabold leading-none text-gold-soft">70</p>
                        <p class="text-xs font-semibold text-cream/70">data</p>
                        <p class="text-xs leading-5 text-cream/65">
                            5 kecamatan × 14 partai yang dapat dibandingkan.
                        </p>
                    </div>
                    <div class="pointer-events-none absolute -bottom-6 -right-6 h-20 w-20 rounded-full border-[10px] border-gold/15"></div>
                </div>
                {{-- DATA SUMBER 2019 --}}
                <div class="rounded-xl border-2 border-t-4 border-navy border-t-gold bg-white px-5 py-4 shadow-sm">
                    <div class="flex flex-wrap items-baseline gap-x-2 gap-y-1">
                        <p class="text-xs font-semibold text-slate">Data sumber 2019</p>
                        <p class="font-display text-3xl font-extrabold leading-none text-navy">80</p>
                        <p class="text-xs leading-5 text-slate">record pada tingkat kecamatan × partai.</p>
                    </div>
                </div>
                {{-- DATA SUMBER 2024 --}}
                <div class="rounded-xl border-2 border-t-4 border-navy border-t-navy bg-white px-5 py-4 shadow-sm">
                    <div class="flex flex-wrap items-baseline gap-x-2 gap-y-1">
                        <p class="text-xs font-semibold text-slate">Data sumber 2024</p>
                        <p class="font-display text-3xl font-extrabold leading-none text-navy">34.920</p>
                        <p class="text-xs leading-5 text-slate">record pada tingkat TPS × partai sebelum agregasi.</p>
                    </div>
                </div>
                {{-- PARTAI COMPARABLE --}}
                <div class="rounded-xl border-2 border-t-4 border-navy border-t-green-500 bg-white px-5 py-4 shadow-sm">
                    <div class="flex flex-wrap items-baseline gap-x-2 gap-y-1">
                        <p class="text-xs font-semibold text-slate">Partai comparable</p>
                        <p class="font-display text-3xl font-extrabold leading-none text-navy">14</p>
                        <p class="text-xs leading-5 text-slate">
                            partai yang memiliki data pada 2019 dan 2024. Partai yang hanya muncul pada salah satu tahun tidak dimasukkan agar share 2019 dan share 2024 dapat dibandingkan secara langsung.
                        </p>
                    </div>
                </div>
            </div>
            {{-- GRANULARITAS + PENYESUAIAN --}}
            <div class="grid grid-cols-1 gap-4 border-t-2 border-navy bg-white p-6 sm:p-7 lg:grid-cols-2">
                {{-- GRANULARITAS --}}
                <div class="flex items-start gap-3 rounded-xl border-2 border-navy bg-cream/60 px-5 py-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-navy text-gold-soft shadow-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-navy">Granularitas data</p>
                        <p class="mt-1 text-xs leading-5 text-slate">
                            Data 2019 tersedia pada tingkat kecamatan, sedangkan data 2024 tersedia pada tingkat TPS.
                        </p>
                    </div>
                </div>
                {{-- PENYESUAIAN --}}
                <div class="flex items-start gap-3 rounded-xl border-2 border-navy bg-cream/60 px-5 py-4">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-navy text-gold-soft shadow-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-navy">Penyesuaian data</p>
                        <p class="mt-1 text-xs leading-5 text-slate">
                            Perbedaan tingkat data diatasi dengan mengagregasi suara 2024 dari TPS ke kecamatan agar kedua tahun memiliki tingkat wilayah yang sama sebelum dibandingkan.
                        </p>
                    </div>
                </div>
            </div>
            {{-- TOMBOL --}}
            <div class="border-t-2 border-navy bg-white px-6 py-5 sm:px-7">
                <form action="{{ route('admin.clustering.jalankan') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="group flex w-full items-center justify-center gap-2.5 rounded-xl border-2 border-navy bg-navy px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-navy/20 transition-all duration-200 hover:-translate-y-0.5 hover:border-gold hover:bg-gold hover:text-navy hover:shadow-gold/30 focus:outline-none focus-visible:ring-2 focus-visible:ring-gold focus-visible:ring-offset-2 active:translate-y-0"
                    >
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5.14v13.72a1 1 0 001.53.85l10.29-6.86a1.02 1.02 0 000-1.7L9.53 4.29A1 1 0 008 5.14z"/>
                        </svg>
                        <span>Jalankan Analisis</span>
                    </button>
                </form>
            </div>
        </section>
    </div>
    {{-- =========================================================
        PETUNJUK SEBELUM ANALISIS
    ========================================================== --}}
    @if(!$analisisTerakhir)
        <div class="flex items-start gap-4 rounded-2xl border-2 border-navy bg-white px-5 py-5 sm:items-center">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gold/15 text-gold">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-navy">Analisis siap dijalankan</p>
                <p class="mt-0.5 text-xs leading-5 text-slate">
                    Klik
                    <span class="font-semibold text-navy">“Jalankan Analisis”</span>
                    untuk mengelompokkan 70 data perbandingan berdasarkan share suara
                    pada Pemilu 2019 dan 2024.
                </p>
            </div>
        </div>
    @endif
    {{-- =========================================================
        HASIL ANALISIS
    ========================================================== --}}
    @if($analisisTerakhir)
        {{-- =====================================================
            KUALITAS HASIL PENGELOMPOKAN
        ====================================================== --}}
        @php
            $skor    = (float) $analisisTerakhir->silhouette_score;
$posisi  = max(0, min(1, $skor)) * 100;            if ($skor >= 0.7) {
                $levelLabel = 'Sangat kuat';
                $levelText  = 'Struktur klaster sangat kuat dan terpisah dengan jelas.';
                $levelBadge = 'bg-green-100 text-green-700 ring-green-200';
                $levelDot   = 'bg-green-500';
                $levelTint  = 'border-green-300 bg-green-50';
            } elseif ($skor >= 0.5) {
                $levelLabel = 'Cukup baik';
                $levelText  = 'Struktur klaster cukup baik dan dapat diterima.';
                $levelBadge = 'bg-gold/20 text-navy ring-gold/40';
                $levelDot   = 'bg-gold';
                $levelTint  = 'border-gold/50 bg-gold/10';
            } else {
                $levelLabel = 'Lemah';
                $levelText  = 'Struktur klaster masih lemah.';
                $levelBadge = 'bg-red-100 text-red-700 ring-red-200';
                $levelDot   = 'bg-red-500';
                $levelTint  = 'border-red-300 bg-red-50';
            }
        @endphp
        <section class="overflow-hidden rounded-2xl border-2 border-navy bg-white shadow-[6px_6px_0_0_rgba(27,42,65,0.10)]">
            {{-- HEADER --}}
            <div class="relative overflow-hidden bg-gradient-to-r from-navy via-navy to-[#2B4163] px-6 py-5 sm:px-7">
                <div class="relative z-10 flex items-start justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gold text-navy shadow-md shadow-black/20">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.5-1.5a7.5 7.5 0 11-15 0 7.5 7.5 0 0115 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-display text-lg font-bold leading-tight text-white">Kualitas Hasil Pengelompokan</h2>
                            <p class="mt-0.5 text-xs leading-5 text-cream/65">
                                Silhouette Score menunjukkan seberapa baik data terbagi ke dalam klaster.
                            </p>
                        </div>
                    </div>
                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold ring-1 {{ $levelBadge }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $levelDot }}"></span>
                        {{ $levelLabel }}
                    </span>
                </div>
                <div class="pointer-events-none absolute -right-8 -top-12 h-32 w-32 rounded-full border-[16px] border-gold/10"></div>
                <div class="absolute inset-x-0 bottom-0 h-1 bg-gold"></div>
            </div>
            {{-- ISI --}}
            <div
                class="grid grid-cols-1 gap-5 bg-cream/50 p-6 sm:p-7 md:grid-cols-[auto_1fr] md:items-stretch"
                style="background-image: radial-gradient(rgba(27,42,65,0.09) 1px, transparent 1px); background-size: 16px 16px;"
            >
                {{-- SKOR --}}
                <div class="relative flex min-w-[240px] flex-col justify-center overflow-hidden rounded-xl border-2 border-navy bg-navy px-6 py-5 shadow-md">
                    <p class="text-xs font-semibold text-cream/70">Silhouette Score</p>
                    <p class="mt-1 font-display text-5xl font-extrabold leading-none tracking-tight text-gold-soft">
                        {{ number_format($skor, 4, ',', '.') }}
                    </p>
                    <p class="mt-2 text-[11px] text-cream/55">Rentang nilai −1 sampai 1</p>
                    <div class="pointer-events-none absolute -bottom-8 -right-8 h-28 w-28 rounded-full border-[12px] border-gold/15"></div>
                </div>
                {{-- GAUGE + INTERPRETASI --}}
                <div class="rounded-xl border-2 border-navy bg-white p-5 shadow-sm">
                    <div class="relative pt-1">
                        <div class="flex h-2.5 overflow-hidden rounded-full">
                            <div class="w-1/2 bg-red-200"></div>
                            <div class="w-[20%] bg-gold/50"></div>
                            <div class="w-[30%] bg-green-300"></div>
                        </div>
                        <span
                            class="absolute top-0 -translate-x-1/2 rounded-full border-[3px] border-white bg-navy shadow-md ring-1 ring-navy/30"
                            style="left: {{ $posisi }}%; height: 18px; width: 18px;"
                            aria-hidden="true"
                        ></span>
                    </div>
                    <div class="mt-2 flex text-[11px] font-medium text-slate">
                        <span class="w-1/2">Lemah <span class="text-slate/60">(&lt; 0,5)</span></span>
                        <span class="w-[20%]">Cukup</span>
                        <span class="w-[30%] text-right">Kuat <span class="text-slate/60">(≥ 0,7)</span></span>
                    </div>
                    <div class="mt-4 flex items-start gap-2.5 rounded-xl border-2 px-4 py-3 {{ $levelTint }}">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs leading-5 text-slate">
                            <span class="font-bold text-navy">Interpretasi:</span>
                            {{ $levelText }}
                        </p>
                    </div>
                </div>
            </div>
        </section>
        {{-- =====================================================
            HASIL PENGELOMPOKAN
        ====================================================== --}}
        <section id="hasil-clustering-export" class="mt-6 overflow-hidden rounded-2xl border-2 border-navy bg-white shadow-sm">
            {{-- HEADER HASIL --}}
            <div class="border-b-2 border-navy bg-navy px-6 py-6 sm:px-7">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="font-mono text-[10px] font-bold uppercase tracking-[0.16em] text-gold-soft">
                            Hasil Analisis
                        </p>
                        <h2 class="mt-1 font-display text-xl font-bold text-cream">
                            Hasil Pengelompokan
                        </h2>
                        <p class="mt-1.5 max-w-3xl text-xs leading-5 text-cream/65">
                            Data dikelompokkan berdasarkan kemiripan share suara
                            pada Pemilu 2019 dan 2024.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                        <span class="inline-flex w-fit shrink-0 items-center rounded-lg border-2 border-white/15 bg-white/10 px-3 py-1.5 font-mono text-xs font-semibold text-cream">
                            2019 → 2024
                        </span>
                        <div class="relative">
                            <button
                                type="button"
                                id="downloadButtonClustering"
                                onclick="toggleDownloadMenuClustering()"
                                aria-expanded="false"
                                aria-controls="downloadMenuClustering"
                                class="inline-flex w-fit shrink-0 items-center justify-center gap-2 rounded-lg border-2 border-gold bg-gold px-4 py-2 text-xs font-bold text-navy transition-colors duration-200 hover:border-gold-soft hover:bg-gold-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-gold focus-visible:ring-offset-2 focus-visible:ring-offset-navy"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v12m0 0l-4-4m4 4l4-4M5 17v3h14v-3" />
                                </svg>
                                <span>Download Hasil</span>
                                <svg id="downloadArrowClustering" class="h-4 w-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div
                                id="downloadMenuClustering"
                                class="absolute right-0 z-50 mt-2 hidden w-48 overflow-hidden rounded-xl border-2 border-slate-200 bg-white shadow-lg"
                            >
                                <div class="border-b border-slate-200 px-4 py-3">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate">Pilih Format</p>
                                </div>
                                <button
                                    type="button"
                                    onclick="downloadHasilClusteringExcel()"
                                    class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm font-medium text-navy transition-colors hover:bg-slate-50"
                                >
                                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-50 text-green-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2zM9 15l2 2 4-4" />
                                        </svg>
                                    </span>
                                    <span>Download Excel</span>
                                </button>
                                <button
                                    type="button"
                                    onclick="downloadHasilClusteringPDF()"
                                    class="flex w-full items-center gap-3 px-4 py-3 text-left text-sm font-medium text-navy transition-colors hover:bg-slate-50"
                                >
                                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2zM14 3v6h5M9 14h6M9 17h6" />
                                        </svg>
                                    </span>
                                    <span>Download PDF</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- RINGKASAN HASIL --}}
            <div class="grid grid-cols-1 gap-3 border-b-2 border-navy bg-slate-50 p-4 sm:grid-cols-3">
                <div class="rounded-xl border-2 border-navy bg-white px-4 py-4">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-wide text-slate">Jumlah Klaster</p>
                    <p class="mt-1.5 text-sm font-bold text-navy">2 Klaster</p>
                </div>
                <div class="rounded-xl border-2 border-navy bg-white px-4 py-4">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-wide text-slate">Jumlah Data</p>
                    <p class="mt-1.5 text-sm font-bold text-navy">70 Data</p>
                </div>
                <div class="rounded-xl border-2 border-navy bg-white px-4 py-4">
                    <p class="font-mono text-[10px] font-bold uppercase tracking-wide text-slate">Fitur</p>
                    <p class="mt-1.5 text-sm font-bold text-navy">Share Suara 2019 &amp; 2024</p>
                </div>
            </div>
            {{-- RINGKASAN ANGGOTA SETIAP KLASTER --}}
            @php
                $dataSemuaKlaster = collect($data ?? []);
                $anggotaTinggi = $dataSemuaKlaster
                    ->filter(fn ($item) => strtolower(trim((string) ($item->kategori_cluster ?? ''))) === 'tinggi')
                    ->sortBy(fn ($item) => ($item->nama_kecamatan ?? '') . ' ' . ($item->singkatan_parpol ?? $item->partai->singkatan_parpol ?? ''))
                    ->values();
                $anggotaRendah = $dataSemuaKlaster
                    ->filter(fn ($item) => strtolower(trim((string) ($item->kategori_cluster ?? ''))) === 'rendah')
                    ->sortBy(fn ($item) => ($item->nama_kecamatan ?? '') . ' ' . ($item->singkatan_parpol ?? $item->partai->singkatan_parpol ?? ''))
                    ->values();
            @endphp
            <div class="grid grid-cols-1 gap-5 border-b-2 border-navy bg-cream/40 p-4 sm:p-5 lg:grid-cols-2">
                {{-- SELURUH ANGGOTA KLASTER TINGGI --}}
                @if ($anggotaTinggi->isNotEmpty())
                    <section class="overflow-hidden rounded-xl border-2 border-navy bg-white">
                        <div class="flex items-center justify-between gap-3 border-b-2 border-navy bg-green-700 px-4 py-3 text-white">
                            <div>
                                <h3 class="font-bold">Anggota Klaster Tinggi</h3>
                                <p class="mt-0.5 text-xs text-white/80">Daftar lengkap berdasarkan kecamatan dan partai.</p>
                            </div>
                            <span class="rounded-lg border border-white/30 bg-white/10 px-2.5 py-1 font-mono text-sm font-bold">
                                {{ $anggotaTinggi->count() }}
                            </span>
                        </div>
                        <div class="max-h-[34rem] divide-y divide-navy/10 overflow-y-auto">
                            @foreach ($anggotaTinggi as $anggota)
                                <div class="flex items-center justify-between gap-3 px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-navy">
                                            {{ $anggota->singkatan_parpol ?? ($anggota->partai->singkatan_parpol ?? 'Partai tidak tersedia') }}
                                        </p>
                                        <p class="mt-0.5 truncate text-xs text-slate">
                                            {{ $anggota->nama_lengkap_parpol ?? ($anggota->partai->nama_lengkap_parpol ?? '') }}
                                        </p>
                                        <p class="mt-1 text-xs font-medium text-slate">
                                            Kecamatan {{ $anggota->nama_kecamatan ?? '-' }}
                                        </p>
                                    </div>
                                    <span class="shrink-0 rounded-lg border border-green-700 bg-green-600 px-2.5 py-1 text-[11px] font-bold text-white">
                                        Tinggi
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
                {{-- SELURUH ANGGOTA KLASTER RENDAH --}}
                @if ($anggotaRendah->isNotEmpty())
                    <section class="overflow-hidden rounded-xl border-2 border-navy bg-white">
                        <div class="flex items-center justify-between gap-3 border-b-2 border-navy bg-red-700 px-4 py-3 text-white">
                            <div>
                                <h3 class="font-bold">Anggota Klaster Rendah</h3>
                                <p class="mt-0.5 text-xs text-white/80">Daftar lengkap berdasarkan kecamatan dan partai.</p>
                            </div>
                            <span class="rounded-lg border border-white/30 bg-white/10 px-2.5 py-1 font-mono text-sm font-bold">
                                {{ $anggotaRendah->count() }}
                            </span>
                        </div>
                        <div class="max-h-[34rem] divide-y divide-navy/10 overflow-y-auto">
                            @foreach ($anggotaRendah as $anggota)
                                <div class="flex items-center justify-between gap-3 px-4 py-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-bold text-navy">
                                            {{ $anggota->singkatan_parpol ?? ($anggota->partai->singkatan_parpol ?? 'Partai tidak tersedia') }}
                                        </p>
                                        <p class="mt-0.5 truncate text-xs text-slate">
                                            {{ $anggota->nama_lengkap_parpol ?? ($anggota->partai->nama_lengkap_parpol ?? '') }}
                                        </p>
                                        <p class="mt-1 text-xs font-medium text-slate">
                                            Kecamatan {{ $anggota->nama_kecamatan ?? '-' }}
                                        </p>
                                    </div>
                                    <span class="shrink-0 rounded-lg border border-red-700 bg-red-600 px-2.5 py-1 text-[11px] font-bold text-white">
                                        Rendah
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
                @if ($anggotaTinggi->isEmpty() && $anggotaRendah->isEmpty())
                    <div class="rounded-xl border-2 border-navy bg-white px-5 py-8 text-center lg:col-span-2">
                        <p class="text-sm font-bold text-navy">Belum ada anggota klaster untuk ditampilkan.</p>
                        <p class="mt-1 text-xs text-slate">Jalankan analisis clustering untuk melihat partai yang masuk kategori Tinggi dan Rendah.</p>
                    </div>
                @endif
            </div>
            {{-- TABEL --}}
            <div class="overflow-x-auto">
                <table id="tabel-hasil-clustering" class="min-w-[1180px] w-full text-left text-sm">
                    <thead class="border-b-2 border-navy bg-cream">
                        <tr>
                            <th class="whitespace-nowrap px-4 py-4 text-center font-mono text-[10px] font-bold uppercase tracking-[0.12em] text-navy">
                                No
                            </th>
                            <th class="whitespace-nowrap px-5 py-4 font-mono text-[10px] font-bold uppercase tracking-[0.12em] text-navy">
                                Kecamatan
                            </th>
                            <th class="px-5 py-4 font-mono text-[10px] font-bold uppercase tracking-[0.12em] text-navy">
                                Partai Politik
                            </th>
                            <th class="whitespace-nowrap px-5 py-4 text-right font-mono text-[10px] font-bold uppercase tracking-[0.12em] text-navy">
                                Share Suara 2019
                            </th>
                            <th class="whitespace-nowrap px-5 py-4 text-right font-mono text-[10px] font-bold uppercase tracking-[0.12em] text-navy">
                                Share Suara 2024
                            </th>
                            <th class="whitespace-nowrap px-5 py-4 text-right font-mono text-[10px] font-bold uppercase tracking-[0.12em] text-navy">
                                Perubahan Share
                            </th>
                            <th class="whitespace-nowrap px-5 py-4 text-center font-mono text-[10px] font-bold uppercase tracking-[0.12em] text-navy">
                                Klaster
                            </th>
                            <th class="whitespace-nowrap px-5 py-4 text-center font-mono text-[10px] font-bold uppercase tracking-[0.12em] text-navy">
                                Kategori
                            </th>
                            <th class="whitespace-nowrap px-5 py-4 text-right font-mono text-[10px] font-bold uppercase tracking-[0.12em] text-navy">
                                Jarak ke Pusat Klaster
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-navy/20">
                        @forelse($data as $item)
                            @php
                                $badgeClass = match($item->kategori_cluster) {
                                    'Tinggi' =>
                                        'border-green-700 bg-green-600 text-white',
                                    'Rendah' =>
                                        'border-red-700 bg-red-600 text-white',
                                    default =>
                                        'border-navy bg-slate-100 text-slate-800',
                                };
                                $badgeIcon = match($item->kategori_cluster) {
                                    'Tinggi' => '▲',
                                    'Rendah' => '▼',
                                    default  => '●',
                                };
                                $selisihShare = (float) ($item->selisih_share ?? 0);
                                $perubahanClass = $selisihShare > 0
                                    ? 'text-green-700'
                                    : (
                                        $selisihShare < 0
                                            ? 'text-red-700'
                                            : 'text-slate'
                                    );
                                // Nomor urut (ikut menyesuaikan jika memakai pagination)
                                $nomor = method_exists($data, 'firstItem')
                                    ? $data->firstItem() + $loop->index
                                    : $loop->iteration;
                            @endphp
                            <tr class="transition-colors hover:bg-cream/50">
                                {{-- NO --}}
                                <td class="whitespace-nowrap px-4 py-4 text-center">
                                    <span class="inline-flex h-7 min-w-[1.75rem] items-center justify-center rounded-lg bg-navy/5 px-1.5 font-mono text-xs font-bold text-navy">
                                        {{ $nomor }}
                                    </span>
                                </td>
                                {{-- KECAMATAN --}}
                                <td class="whitespace-nowrap px-5 py-4 font-semibold text-navy">
                                    {{ $item->nama_kecamatan }}
                                </td>
                                {{-- PARTAI --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        {{-- LOGO PARTAI --}}
                                        @if($item->partai && $item->partai->logo_url)
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border-2 border-navy bg-white">
                                                <img
                                                    src="{{ $item->partai->logo_url }}"
                                                    class="h-8 w-8 object-contain"
                                                    alt="Logo {{ $item->partai->singkatan_parpol }}"
                                                >
                                            </div>
                                        @else
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border-2 border-navy bg-navy/5">
                                                <span class="font-mono text-[10px] font-bold text-navy">
                                                    {{ strtoupper(substr($item->partai->singkatan_parpol ?? '-', 0, 5)) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <div class="font-bold text-navy">
                                                {{ $item->partai->singkatan_parpol ?? '-' }}
                                            </div>
                                            <div class="mt-0.5 max-w-[240px] truncate text-xs text-slate">
                                                {{ $item->partai->nama_lengkap_parpol ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                {{-- SHARE 2019 --}}
                                <td class="whitespace-nowrap px-5 py-4 text-right font-mono font-bold text-navy">
                                    {{ number_format((float) ($item->share_2019 ?? 0), 2, ',', '.') }}%
                                </td>
                                {{-- SHARE 2024 --}}
                                <td class="whitespace-nowrap px-5 py-4 text-right font-mono font-bold text-navy">
                                    {{ number_format((float) ($item->share_2024 ?? 0), 2, ',', '.') }}%
                                </td>
                                {{-- PERUBAHAN SHARE --}}
                                <td class="whitespace-nowrap px-5 py-4 text-right font-mono font-bold {{ $perubahanClass }}">
                                    {{ $selisihShare > 0 ? '+' : '' }}{{ number_format($selisihShare, 2, ',', '.') }}%
                                </td>
                                {{-- KLASTER --}}
                                <td class="whitespace-nowrap px-5 py-4 text-center">
                                    <span class="font-mono font-bold text-navy">
                                        Klaster {{ $item->cluster }}
                                    </span>
                                </td>
                                {{-- KATEGORI --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 rounded-lg border-2 px-3 py-1 text-xs font-bold shadow-sm {{ $badgeClass }}">
                                        <span class="text-[9px] leading-none">{{ $badgeIcon }}</span>
                                        {{ $item->kategori_cluster }}
                                    </span>
                                </td>
                                {{-- JARAK --}}
                                <td class="whitespace-nowrap px-5 py-4 text-right font-mono text-slate">
                                    {{ number_format((float) ($item->jarak_centroid ?? 0), 4, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-slate">
                                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl border-2 border-navy bg-navy/5 text-navy">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-navy">
                                        Belum ada hasil clustering
                                    </p>
                                    <p class="mt-1 text-xs text-slate">
                                        Jalankan analisis untuk menampilkan hasil pengelompokan.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- CATATAN --}}
            <div class="border-t-2 border-navy bg-slate-50 px-6 py-4">
                <p class="text-xs leading-5 text-slate">
                    <span class="font-bold text-navy">
                        Catatan:
                    </span>
                    Share suara menunjukkan proporsi suara yang diperoleh partai
                    terhadap total suara partai pada masing-masing kecamatan dan tahun.
                    Perubahan share dihitung dari share suara 2024 dikurangi share suara 2019.
                    Nilai perubahan ini digunakan untuk melihat perkembangan perolehan suara
                    setelah proses pengelompokan.
                </p>
            </div>
        </section>
    @endif
</div>

<style>
    @media print {
        body * {
            visibility: hidden !important;
        }

        #hasil-clustering-export,
        #hasil-clustering-export * {
            visibility: visible !important;
        }

        #hasil-clustering-export {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            border: 0 !important;
            box-shadow: none !important;
        }

        #downloadButtonClustering,
        #downloadMenuClustering {
            display: none !important;
        }

        #hasil-clustering-export .max-h-\[34rem\] {
            max-height: none !important;
            overflow: visible !important;
        }

        #hasil-clustering-export .overflow-x-auto {
            overflow: visible !important;
        }

        #tabel-hasil-clustering {
            min-width: 0 !important;
            width: 100% !important;
            font-size: 9px !important;
        }

        #tabel-hasil-clustering th,
        #tabel-hasil-clustering td {
            padding: 5px !important;
        }
    }
</style>

@if($analisisTerakhir)
<script>
    function toggleDownloadMenuClustering() {
        const menu = document.getElementById('downloadMenuClustering');
        const arrow = document.getElementById('downloadArrowClustering');
        const button = document.getElementById('downloadButtonClustering');

        if (!menu || !arrow || !button) return;

        const isOpening = menu.classList.contains('hidden');
        menu.classList.toggle('hidden');
        arrow.classList.toggle('rotate-180', isOpening);
        button.setAttribute('aria-expanded', String(isOpening));
    }

    function closeDownloadMenuClustering() {
        const menu = document.getElementById('downloadMenuClustering');
        const arrow = document.getElementById('downloadArrowClustering');
        const button = document.getElementById('downloadButtonClustering');

        if (menu) menu.classList.add('hidden');
        if (arrow) arrow.classList.remove('rotate-180');
        if (button) button.setAttribute('aria-expanded', 'false');
    }

    document.addEventListener('click', function (event) {
        const button = document.getElementById('downloadButtonClustering');
        const menu = document.getElementById('downloadMenuClustering');

        if (button && menu && !button.contains(event.target) && !menu.contains(event.target)) {
            closeDownloadMenuClustering();
        }
    });

    function downloadHasilClusteringExcel() {
        const table = document.getElementById('tabel-hasil-clustering');

        if (!table) {
            alert('Tabel hasil clustering belum tersedia.');
            return;
        }

        const rows = Array.from(table.querySelectorAll('tr'));
        const csv = rows.map((row) => {
            const cells = Array.from(row.querySelectorAll('th, td'));
            return cells.map((cell) => {
                const value = (cell.innerText || cell.textContent || '')
                    .replace(/\s+/g, ' ')
                    .trim()
                    .replace(/"/g, '""');
                return `"${value}"`;
            }).join(',');
        }).join('\r\n');

        const blob = new Blob(['\uFEFF' + csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'hasil-clustering-pemilu-2019-2024.csv';
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(url);
        closeDownloadMenuClustering();
    }

    function downloadHasilClusteringPDF() {
        closeDownloadMenuClustering();
        window.print();
    }
</script>
@endif

@endsection
