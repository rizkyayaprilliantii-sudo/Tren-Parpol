<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, viewport-fit=cover"
    >

    <title>
        @yield('title', 'Analisis Pileg Banjarmasin') — PKL
    </title>

    {{-- Google Fonts (JetBrains Mono & Inter ditambah bobot 800 untuk font-extrabold/black) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=JetBrains+Mono:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{--
        Tailwind dikompilasi lewat Vite.
        Warna (navy, cream, gold, gold-soft, maroon, slate, ink) dan font
        (display, mono, sans) didaftarkan di tailwind.config.js,
        BUKAN lewat <script>tailwind.config = ...</script> (itu hanya jalan di Tailwind CDN).
    --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>

        :root {
            color-scheme: light;
        }

        html {
            scroll-padding-top: env(safe-area-inset-top, 0px);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #F4F1E8;
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

        /* Fokus keyboard terlihat jelas di seluruh situs */
        a:focus-visible,
        button:focus-visible {
            outline: 3px solid #C9A24B;
            outline-offset: 3px;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }

    </style>

    @stack('styles')

</head>


<body class="bg-cream text-navy antialiased">

    {{-- Navbar --}}
    @include('partials.navbar')


    <main class="min-h-screen">

        @yield('content')

    </main>


    {{-- Footer --}}
    @include('partials.footer')


    @stack('scripts')

</body>

</html>