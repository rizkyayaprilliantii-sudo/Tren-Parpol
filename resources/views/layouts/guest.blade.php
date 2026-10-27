<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap"
            rel="stylesheet"
        >

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Catatan: pola garis ini juga dipakai di layouts/app.blade.php (hero beranda).
             Idealnya dipindah ke satu partial/CSS bersama supaya tidak dobel, tapi untuk
             sekarang disalin di sini biar halaman guest (login/register) tetap konsisten. --}}
        <style>
            :root {
                color-scheme: light;
            }

            .bg-tally-lines {
                background-image: repeating-linear-gradient(
                    100deg,
                    rgba(227, 200, 120, 0.12) 0px,
                    rgba(227, 200, 120, 0.12) 1px,
                    transparent 1px,
                    transparent 28px
                );
            }

            @media (prefers-reduced-motion: reduce) {
                * {
                    animation-duration: 0.01ms !important;
                    transition-duration: 0.01ms !important;
                }
            }
        </style>
    </head>
    <body class="font-sans text-navy antialiased">

        <div class="relative min-h-screen flex flex-col items-center justify-center px-6 py-12 bg-navy overflow-hidden">

            {{-- TEKSTUR & AKSEN, senada hero beranda --}}
            <div class="absolute inset-0 bg-tally-lines opacity-40"></div>
            <div class="absolute top-0 right-0 w-1/2 h-full bg-gold/5 rounded-l-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-maroon/10 rounded-full blur-3xl pointer-events-none"></div>

            {{-- BRAND MARK --}}
            <a href="/" class="relative z-10 flex flex-col items-center gap-3 mb-8 group">
                <span class="w-16 h-16 rounded-xl border-2 border-gold bg-gold/10 flex items-center justify-center font-display text-gold text-2xl group-hover:bg-gold group-hover:text-navy transition-colors duration-300">
                    P
                </span>
                <span class="font-mono text-[10px] text-gold-soft uppercase tracking-widest">
                    Tren Parpol
                </span>
            </a>

            {{-- CARD --}}
            <div class="relative z-10 w-full sm:max-w-md bg-white border-2 border-gold shadow-[8px_8px_0_0_rgba(201,162,75,0.35)] rounded-xl px-6 py-8 sm:px-8">
                {{ $slot }}
            </div>

        </div>

    </body>
</html>