<!DOCTYPE html>

<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(212, 175, 55, 0.35);
            border-radius: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(212, 175, 55, 0.55);
        }

        /* SIDEBAR PATTERN */
        .sidebar-pattern {
            overflow: hidden;
        }

        .sidebar-pattern::before {
            content: "";
            position: absolute;
            inset: 0;

            background-image:
                repeating-linear-gradient(
                    100deg,
                    rgba(227, 200, 120, 0.045) 0px,
                    rgba(227, 200, 120, 0.045) 1px,
                    transparent 1px,
                    transparent 30px
                );

            pointer-events: none;
        }

        .sidebar-pattern > * {
            position: relative;
            z-index: 1;
        }
    </style>
</head>

<body class="bg-cream text-ink font-body antialiased">

    <div class="flex min-h-screen">

        {{-- SIDEBAR --}}
        <aside
            class="sidebar-scroll sidebar-pattern fixed inset-y-0 left-0 z-40 flex w-72 flex-col overflow-y-auto bg-navy text-cream shadow-2xl"
        >

            {{-- LOGO --}}
            <div class="border-b border-cream/10 p-6">

                <div class="flex items-center gap-3">

                    <div
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border-2 border-gold-soft/60 bg-gold shadow-lg"
                    >
                        <span class="font-display text-xl font-bold text-navy">
                            P
                        </span>
                    </div>

                    <div class="min-w-0">
                        <div class="font-display text-lg font-bold leading-tight text-cream">
                            Tren Perolehan
                            <br>
                            Suara Parpol
                        </div>
                    </div>

                </div>

            </div>

            {{-- NAVIGASI --}}
            <nav class="flex-1 space-y-2 p-4">

                {{-- DASHBOARD --}}
                <div>
                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="group flex items-center gap-3 rounded-lg border-l-4 px-3 py-2.5 text-sm font-medium transition-all
                        {{ request()->routeIs('admin.dashboard')
                            ? 'border-gold bg-gold/15 text-gold-soft shadow-sm'
                            : 'border-transparent text-cream/70 hover:border-gold/40 hover:bg-white/5 hover:text-gold-soft' }}"
                    >
                        <svg
                            class="h-5 w-5 shrink-0 transition-transform group-hover:scale-105"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7 M5 10v10a1 1 0 001 1h3 m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3 m-6 0a1 1 0 001 1v-4 a1 1 0 011-1h2 a1 1 0 011 1v4 a1 1 0 001 1 m-6 0h6"
                            ></path>
                        </svg>

                        Dashboard
                    </a>
                </div>

                {{-- DATA SUARA DAN REKAPITULASI --}}
                <div class="space-y-1">

                    {{-- DATA SUARA --}}
                    <a
                        href="{{ route('admin.rincian.index') }}"
                        class="group flex items-center gap-3 rounded-lg border-l-4 px-3 py-2.5 text-sm transition-all
                        {{ request()->routeIs('admin.rincian.*')
                            ? 'border-gold bg-gold/15 text-gold-soft shadow-sm'
                            : 'border-transparent text-cream/70 hover:border-gold/40 hover:bg-white/5 hover:text-gold-soft' }}"
                    >
                        <svg
                            class="h-5 w-5 shrink-0 transition-transform group-hover:scale-105"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 17v-2 m3 2v-4 m3 4v-6 m2 10H7 a2 2 0 01-2-2V5 a2 2 0 012-2h5.586 a1 1 0 01.707.293 l5.414 5.414 a1 1 0 01.293.707V19 a2 2 0 01-2 2z"
                            ></path>
                        </svg>

                        Data Suara
                    </a>

                    {{-- REKAPITULASI --}}
                    <a
                        href="{{ route('admin.rekap.index') }}"
                        class="group flex items-center gap-3 rounded-lg border-l-4 px-3 py-2.5 text-sm transition-all
                        {{ request()->routeIs('admin.rekap.*')
                            ? 'border-gold bg-gold/15 text-gold-soft shadow-sm'
                            : 'border-transparent text-cream/70 hover:border-gold/40 hover:bg-white/5 hover:text-gold-soft' }}"
                    >
                        <svg
                            class="h-5 w-5 shrink-0 transition-transform group-hover:scale-105"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5H7
                                   a2 2 0 00-2 2v12
                                   a2 2 0 002 2h10
                                   a2 2 0 002-2V7
                                   a2 2 0 00-2-2h-2
                                   M9 5
                                   a2 2 0 002 2h2
                                   a2 2 0 002-2
                                   M9 5
                                   a2 2 0 012-2h2
                                   a2 2 0 012 2
                                   m-5 8h8
                                   m-8 4h8"
                            ></path>
                        </svg>

                        Rekapitulasi
                    </a>

                    {{-- K-MEANS CLUSTERING --}}
                    <a
                        href="{{ route('admin.clustering.index') }}"
                        class="group flex items-center gap-3 rounded-lg border-l-4 px-3 py-2.5 text-sm transition-all
                        {{ request()->routeIs('admin.clustering.*')
                            ? 'border-gold bg-gold/15 text-gold-soft shadow-sm'
                            : 'border-transparent text-cream/70 hover:border-gold/40 hover:bg-white/5 hover:text-gold-soft' }}"
                    >
                        <svg
                            class="h-5 w-5 shrink-0 transition-transform group-hover:scale-105"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <!-- Cluster 1 -->
                            <circle cx="6" cy="6" r="2" fill="currentColor"/>
                            <circle cx="9" cy="8" r="1.5" opacity="0.6"/>

                            <!-- Cluster 2 -->
                            <circle cx="17" cy="7" r="2" fill="currentColor"/>
                            <circle cx="14" cy="10" r="1.5" opacity="0.6"/>

                            <!-- Cluster 3 -->
                            <circle cx="11" cy="17" r="2.5" fill="currentColor"/>
                            <circle cx="15" cy="18" r="1.5" opacity="0.6"/>
                            <circle cx="7" cy="15" r="1" opacity="0.6"/>
                        </svg>

                        K-Means Clustering
                    </a>

                    {{-- TREN PEROLEHAN SUARA --}}
                    <a
                        href="{{ route('admin.perbandingan.index') }}"
                        class="group flex items-center gap-3 rounded-lg border-l-4 px-3 py-2.5 text-sm transition-all
                        {{ request()->routeIs('admin.perbandingan.*')
                            ? 'border-gold bg-gold/15 text-gold-soft shadow-sm'
                            : 'border-transparent text-cream/70 hover:border-gold/40 hover:bg-white/5 hover:text-gold-soft' }}"
                    >
                        <svg
                            class="h-5 w-5 shrink-0 transition-transform group-hover:scale-105"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 19V5 M4 19h16 M7 15l3-3 3 2 5-7"
                            ></path>
                        </svg>

                        Tren Perolehan Suara
                    </a>

                </div>

                {{-- KELOLA AKUN --}}
                <div>
                    <a
                        href="{{ route('admin.manajemen-akun.index') }}"
                        class="group flex items-center gap-3 rounded-lg border-l-4 px-3 py-2.5 text-sm transition-all
                        {{ request()->routeIs('admin.manajemen-akun.*')
                            ? 'border-gold bg-gold/15 text-gold-soft shadow-sm'
                            : 'border-transparent text-cream/70 hover:border-gold/40 hover:bg-white/5 hover:text-gold-soft' }}"
                    >
                        <svg
                            class="h-5 w-5 shrink-0 transition-transform group-hover:scale-105"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4.354 a4 4 0 110 5.292 M15 21H3v-1 a6 6 0 0112 0v1 zm0 0h6v-1 a6 6 0 00-9-5.197 M13 7 a4 4 0 11-8 0 a4 4 0 018 0z"
                            ></path>
                        </svg>

                        Kelola Akun
                    </a>
                </div>

            </nav>

            {{-- LOGOUT --}}
            <div class="mt-auto border-t border-cream/10 p-4">

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="group flex w-full items-center justify-center gap-2 rounded-lg border border-cream/10 px-4 py-2.5 text-sm font-medium text-cream/65 transition-all hover:border-maroon hover:bg-maroon hover:text-white"
                    >
                        <svg
                            class="h-4 w-4 transition-transform group-hover:-translate-x-0.5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l-4-4 m0 0l4-4 m4 4H7 m6 4v1 a3 3 0 01-3 3H6 a3 3 0 01-3-3V7 a3 3 0 013-3h4 a3 3 0 013 3v1"
                            ></path>
                        </svg>

                        Keluar
                    </button>
                </form>

            </div>

        </aside>

        {{-- KONTEN UTAMA --}}
        <div class="ml-72 flex min-h-screen min-w-0 flex-1 flex-col">

            {{-- NAVBAR --}}
            <header class="sticky top-0 z-30 border-b-[3px] border-gold bg-white shadow-sm">

                <div class="flex h-16 items-center justify-between gap-4 px-8">

                    {{-- Breadcrumb --}}
                    <nav class="flex min-w-0 items-center gap-2 text-sm" aria-label="Breadcrumb">
                        <span class="hidden text-slate sm:inline">
                            Panel Admin
                        </span>

                        <svg
                            class="hidden h-4 w-4 shrink-0 text-gold sm:block"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2.5"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>

                        <span class="truncate font-semibold text-navy">
                            @yield('page-title', 'Dashboard')
                        </span>
                    </nav>

                    {{-- Tanggal dan pengguna --}}
                    <div class="flex shrink-0 items-center gap-4">

                        <span class="hidden text-sm text-slate lg:inline">
                            {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                        </span>

                        <span class="hidden h-6 w-px bg-navy/15 lg:block"></span>

                        {{-- Chip pengguna --}}
                        <div class="flex items-center gap-3 rounded-full bg-navy py-1 pl-1 pr-5 shadow-[0_3px_0_0_rgba(201,162,75,0.55)]">

                            <div class="relative">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gold text-base font-bold text-navy">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>

                                <span
                                    class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-navy bg-green-500"
                                    title="Sedang aktif"
                                ></span>
                            </div>

                            <div class="hidden leading-tight sm:block">
                                <div class="max-w-[140px] truncate text-sm font-semibold text-white">
                                    {{ auth()->user()->name }}
                                </div>

                                <div class="text-xs text-white">
                                    {{ auth()->user()->role === 'admin' ? 'Pengelola Data' : 'Pimpinan' }}
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </header>

            {{-- ISI HALAMAN --}}
            <main class="min-w-0 flex-1 bg-cream p-8">
                @yield('content')
            </main>

        </div>

    </div>

    @stack('scripts')

</body>

</html>