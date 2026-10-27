<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Pimpinan | Tren Parpol')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            color-scheme: light;
        }

        html {
            scroll-padding-top: 80px;
        }

        body {
            margin: 0;
            min-height: 100%;
            font-family: 'Inter', sans-serif;
            background: #F4F1E8;
            color: #1B2A41;
        }

        /* =========================
           SIDEBAR SCROLLBAR
        ========================== */

        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(201, 162, 75, 0.35);
            border-radius: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(201, 162, 75, 0.55);
        }

        /* =========================
           SIDEBAR PATTERN
        ========================== */

        .sidebar-pattern {
            position: relative;
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

        /* =========================
           SHELL
        ========================== */

        .pimpinan-shell {
            display: flex;
            min-height: 100vh;
            width: 100%;
            background: #F4F1E8;
        }

        /* =========================
           SIDEBAR
        ========================== */

        .pimpinan-sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;

            display: flex;
            width: 288px;
            min-width: 288px;

            flex-direction: column;
            overflow-y: auto;

            background: #1B2A41 !important;
            color: #F4F1E8 !important;

            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        /* =========================
           BRAND
        ========================== */

        .sidebar-brand {
            border-bottom: 1px solid rgba(244, 241, 232, 0.10);
            padding: 24px;
        }

        .sidebar-brand-inner {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-logo {
            display: flex;
            height: 44px;
            width: 44px;
            flex-shrink: 0;

            align-items: center;
            justify-content: center;

            border: 2px solid rgba(227, 200, 120, 0.60);
            border-radius: 12px;

            background: #7A2E2E;

            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.20);
        }

        .sidebar-logo span {
            font-family: 'Fraunces', serif;
            font-size: 20px;
            font-weight: 700;
            color: #F4F1E8;
        }

        .sidebar-brand-text {
            min-width: 0;
        }

        .sidebar-brand-title,
        .sidebar-brand-subtitle {
            font-family: 'Fraunces', serif;
            font-size: 18px;
            font-weight: 700;
            line-height: 1.1;
            color: #F4F1E8;
        }

        .sidebar-brand-subtitle {
            margin-top: 0;
        }

        /* =========================
           NAVIGATION
        ========================== */

        .sidebar-menu {
            flex: 1;
            padding: 16px;
        }

        .sidebar-section {
            margin-bottom: 8px;
        }

        .sidebar-section:last-child {
            margin-bottom: 0;
        }

        .sidebar-section-links {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        /* =========================
           MENU ITEM
        ========================== */

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;

            width: 100%;

            border-left: 4px solid transparent;
            border-radius: 8px;

            padding: 10px 12px;

            text-decoration: none;

            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;

            color: rgba(244, 241, 232, 0.70) !important;

            transition:
                background 0.2s ease,
                color 0.2s ease,
                border-color 0.2s ease;
        }

        .sidebar-link:hover {
            border-left-color: rgba(201, 162, 75, 0.40);
            background: rgba(255, 255, 255, 0.05);
            color: #E3C878 !important;
        }

        .sidebar-link svg {
            height: 20px;
            width: 20px;
            flex-shrink: 0;
        }

        .sidebar-link span {
            min-width: 0;
        }

        .sidebar-link.active {
            border-left-color: #C9A24B;
            background: rgba(122, 46, 46, 0.55);
            color: #E3C878 !important;

            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.12);
        }

        .sidebar-link.active:hover {
            border-left-color: #C9A24B;
            background: #7A2E2E;
            color: #E3C878 !important;
        }

        /* =========================
           SIDEBAR FOOTER
        ========================== */

        .sidebar-footer {
            margin-top: auto;
            border-top: 1px solid rgba(244, 241, 232, 0.10);
            padding: 16px;
        }

        .logout-button {
            display: flex;
            width: 100%;

            align-items: center;
            justify-content: center;

            gap: 8px;

            border: 1px solid rgba(244, 241, 232, 0.10);
            border-radius: 8px;

            background: transparent;

            padding: 10px 16px;

            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;

            color: rgba(244, 241, 232, 0.65);

            cursor: pointer;

            transition:
                background 0.2s ease,
                color 0.2s ease,
                border-color 0.2s ease;
        }

        .logout-button:hover {
            border-color: #7A2E2E;
            background: #7A2E2E;
            color: #FFFFFF;
        }

        .logout-button svg {
            height: 16px;
            width: 16px;
        }

        /* =========================
           MAIN
        ========================== */

        .pimpinan-main {
            flex: 1;
            min-width: 0;
            margin-left: 288px;
            background: #F4F1E8;
        }

        /* =========================
           HEADER / NAVBAR
        ========================== */

        .pimpinan-header {
            position: sticky;
            top: 0;
            z-index: 30;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 16px;
            min-height: 64px;

            border-bottom: 3px solid #7A2E2E;

            background: #F4F1E8 !important;

            padding: 0 32px;

            box-shadow: 0 4px 0 rgba(122, 46, 46, 0.06);
        }

        .pimpinan-header-main {
            display: flex;
            min-width: 0;

            align-items: center;
            gap: 10px;
        }

        .pimpinan-header-kicker {
            display: none;
        }

        .pimpinan-header-main::before {
            content: "Panel Pimpinan";

            flex-shrink: 0;

            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;

            color: #5B6472;
        }

        .pimpinan-header-main::after {
            content: "›";

            flex-shrink: 0;

            color: #7A2E2E;
            font-size: 20px;
            line-height: 1;
        }

        .pimpinan-header h2 {
            overflow: hidden;

            margin: 0;

            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.25;

            text-overflow: ellipsis;
            white-space: nowrap;

            color: #1B2A41 !important;
        }

        .pimpinan-header p {
            display: none;
        }

        /* =========================
           USER HEADER
        ========================== */

        .header-user {
            display: flex;
            flex-shrink: 0;

            align-items: center;
            gap: 16px;
        }

        .header-date {
            display: none;

            color: #5B6472;

            font-family: 'Inter', sans-serif;
            font-size: 14px;

            white-space: nowrap;
        }

        .header-user-divider {
            display: none;

            width: 1px;
            height: 24px;

            background: rgba(27, 42, 65, 0.15);
        }

        .header-user-chip {
            display: flex;
            align-items: center;
            gap: 10px;

            border: 1px solid rgba(201, 162, 75, 0.40);
            border-radius: 8px;

            background: rgba(201, 162, 75, 0.05);

            padding: 5px 12px 5px 5px;
        }

        .header-user-avatar {
            display: flex;

            height: 32px;
            width: 32px;

            flex-shrink: 0;

            align-items: center;
            justify-content: center;

            border: 1px solid #E3C878;
            border-radius: 999px;

            background: #C9A24B;

            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 700;

            color: #1B2A41;
        }

        .header-user-info {
            min-width: 0;
            line-height: 1.15;
        }

        .header-user-name {
            max-width: 140px;

            overflow: hidden;

            color: #1B2A41;

            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 700;

            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header-user-role {
            margin-top: 3px;

            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            font-weight: 600;

            color: #C9A24B;
        }

        /* =========================
           CONTENT
        ========================== */

        .pimpinan-content {
            font-family: 'Inter', sans-serif;
            padding: 32px;
        }

        /* =========================
           RESPONSIVE
        ========================== */

        @media (min-width: 1024px) {
            .header-date,
            .header-user-divider {
                display: block;
            }
        }

        @media (max-width: 640px) {
            .pimpinan-header {
                padding: 0 16px;
            }

            .pimpinan-header-main {
                gap: 7px;
            }

            .pimpinan-header-main::before {
                font-size: 12px;
            }

            .pimpinan-header h2 {
                font-size: 12px;
            }

            .header-user-chip {
                gap: 7px;
                padding-right: 8px;
            }

            .header-user-name {
                max-width: 90px;
            }

            .pimpinan-content {
                padding: 20px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

<div class="pimpinan-shell">

    <aside class="pimpinan-sidebar sidebar-scroll sidebar-pattern">

        <div class="sidebar-brand">

            <div class="sidebar-brand-inner">

                <div class="sidebar-logo">
                    <span>P</span>
                </div>

                <div class="sidebar-brand-text">

                    <div class="sidebar-brand-title">
                        Tren Perolehan
                    </div>

                    <div class="sidebar-brand-subtitle">
                        Suara Parpol
                    </div>

                </div>

            </div>

        </div>

        <nav class="sidebar-menu">

            {{-- Dashboard --}}

            <div class="sidebar-section">

                <div class="sidebar-section-links">

                    <a
                        href="{{ route('pimpinan.dashboard') }}"
                        class="sidebar-link {{ request()->routeIs('pimpinan.dashboard') ? 'active' : '' }}"
                    >

                        <svg
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7
                                   M5 10v10a1 1 0 001 1h3
                                   m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3
                                   m-6 0a1 1 0 001 1v-4
                                   a1 1 0 011-1h2
                                   a1 1 0 011 1v4
                                   a1 1 0 001 1
                                   m-6 0h6"
                            ></path>
                        </svg>

                        <span>Dashboard</span>

                    </a>

                </div>

            </div>

            {{-- Data Pemilu --}}

            <div class="sidebar-section">

                <div class="sidebar-section-links">

                    <a
                        href="{{ route('pimpinan.data-suara.index') }}"
                        class="sidebar-link {{ request()->routeIs('pimpinan.data-suara.*') ? 'active' : '' }}"
                    >

                        <svg
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 17v-2
                                   m3 2v-4
                                   m3 4v-6
                                   m2 10H7
                                   a2 2 0 01-2-2V5
                                   a2 2 0 012-2h5.586
                                   a1 1 0 01.707.293
                                   l5.414 5.414
                                   a1 1 0 01.293.707V19
                                   a2 2 0 01-2 2z"
                            ></path>
                        </svg>

                        <span>Data Suara</span>

                    </a>

                    <a
                        href="{{ route('pimpinan.rekap.index') }}"
                        class="sidebar-link {{ request()->routeIs('pimpinan.rekap.*') ? 'active' : '' }}"
                    >

                        <svg
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

                        <span>Rekapitulasi</span>

                    </a>

                </div>

            </div>

            {{-- Analisis --}}

            <div class="sidebar-section">

                <div class="sidebar-section-links">

                    <a
                        href="{{ route('pimpinan.clustering.index') }}"
                        class="sidebar-link {{ request()->routeIs('pimpinan.clustering.*') ? 'active' : '' }}"
                    >

                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle
                                cx="6"
                                cy="6"
                                r="2"
                                fill="currentColor"
                            />

                            <circle
                                cx="9"
                                cy="8"
                                r="1.5"
                                opacity="0.6"
                            />

                            <circle
                                cx="17"
                                cy="7"
                                r="2"
                                fill="currentColor"
                            />

                            <circle
                                cx="14"
                                cy="10"
                                r="1.5"
                                opacity="0.6"
                            />

                            <circle
                                cx="11"
                                cy="17"
                                r="2.5"
                                fill="currentColor"
                            />

                            <circle
                                cx="15"
                                cy="18"
                                r="1.5"
                                opacity="0.6"
                            />

                            <circle
                                cx="7"
                                cy="15"
                                r="1"
                                opacity="0.6"
                            />
                        </svg>

                        <span>K-Means Clustering</span>

                    </a>

                    <a
                        href="{{ route('pimpinan.perbandingan.index') }}"
                        class="sidebar-link {{ request()->routeIs('pimpinan.perbandingan.*') ? 'active' : '' }}"
                    >

                        <svg
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 19V5
                                   M4 19h16
                                   M7 15l3-3 3 2 5-7"
                            ></path>
                        </svg>

                        <span>Tren Perolehan Suara</span>

                    </a>

                </div>

            </div>

        </nav>

        {{-- Footer --}}

        <div class="sidebar-footer">

            <form
                method="POST"
                action="{{ route('logout') }}"
            >

                @csrf

                <button
                    type="submit"
                    class="logout-button"
                >

                    <svg
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 16l-4-4
                               m0 0l4-4
                               m-4 4H7
                               m6 4v1
                               a3 3 0 01-3 3H6
                               a3 3 0 01-3-3V7
                               a3 3 0 013-3h4
                               a3 3 0 013 3v1"
                        ></path>
                    </svg>

                    Keluar

                </button>

            </form>

        </div>

    </aside>

    <div class="pimpinan-main">

        <header class="pimpinan-header">

            <div class="pimpinan-header-main">

                <div class="pimpinan-header-kicker">
                    Panel Pimpinan
                </div>

                <h2>
                    @yield('page-title', 'Dashboard Analisis Pemilu')
                </h2>

            </div>

            <div class="header-user">

                <span class="header-date">
                    {{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </span>

                <span
                    class="header-user-divider"
                    aria-hidden="true"
                ></span>

                <div class="header-user-chip">

                    <div class="header-user-avatar">
                        {{ strtoupper(substr(auth()->user()->name ?? 'Pimpinan', 0, 1)) }}
                    </div>

                    <div class="header-user-info">

                        <div class="header-user-name">
                            {{ auth()->user()->name ?? 'Pimpinan' }}
                        </div>

                        <div class="header-user-role">
                            Pimpinan
                        </div>

                    </div>

                </div>

            </div>

        </header>

        <main class="pimpinan-content">
            @yield('content')
        </main>

    </div>

</div>

@stack('scripts')

</body>

</html>