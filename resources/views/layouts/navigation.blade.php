@php
    $navLinks = [
        ['route' => 'beranda',       'label' => 'Beranda'],
        ['route' => 'data-suara',    'label' => 'Data Suara'],
        ['route' => 'hasil-klaster', 'label' => 'Hasil Klaster'],
        ['route' => 'perbandingan',  'label' => 'Perbandingan'],
        ['route' => 'tentang',       'label' => 'Tentang'],
    ];
@endphp

<nav class="bg-navy text-cream border-b-4 border-gold">

    <div class="max-w-7xl mx-auto px-6">

        <div class="flex items-center justify-between h-20">

            {{-- BRAND --}}
            <a
                href="{{ route('beranda') }}"
                class="group flex items-center gap-3 focus:outline-none focus-visible:ring-4 focus-visible:ring-gold/60 rounded-sm"
            >

                <div class="w-11 h-11 bg-gold border-2 border-cream flex items-center justify-center shadow-[3px_3px_0_0_rgba(244,241,232,0.9)] group-hover:shadow-[1px_1px_0_0_rgba(244,241,232,0.9)] group-hover:translate-x-0.5 group-hover:translate-y-0.5 transition-all duration-150">
                    <span class="font-mono text-sm font-black text-navy">
                        TP
                    </span>
                </div>

                <div>
                    <div class="font-display text-xl font-bold leading-none">
                        Tren 
                    </div>

                    <div class="text-[10px] font-mono font-semibold text-gold-soft tracking-[0.2em] mt-1.5">
                        PILEG BANJARMASIN
                    </div>
                </div>

            </a>


            {{-- DESKTOP NAVIGATION --}}
            <div class="hidden md:flex items-stretch gap-1 h-20">

                @foreach($navLinks as $link)
                    @php $aktif = request()->routeIs($link['route']); @endphp

                    <a
                        href="{{ route($link['route']) }}"
                        class="relative flex items-center px-4 text-sm font-mono font-bold transition-colors duration-150
                               focus:outline-none focus-visible:bg-cream/10
                               {{ $aktif
                                    ? 'text-gold bg-cream/5'
                                    : 'text-cream/75 hover:text-gold-soft hover:bg-cream/5' }}"
                    >
                        {{ $link['label'] }}

                        {{-- garis penanda: tetap muncul di halaman aktif, tumbuh saat hover --}}
                        <span class="absolute left-3 right-3 bottom-0 h-1 bg-gold origin-left transition-transform duration-200
                                     {{ $aktif ? 'scale-x-100' : 'scale-x-0' }}"></span>
                    </a>
                @endforeach

            </div>


            {{-- MOBILE MENU BUTTON --}}
            <button
                type="button"
                onclick="document.getElementById('mobile-menu').classList.toggle('hidden')"
                class="md:hidden w-11 h-11 border-2 border-cream/60 bg-cream/5 flex items-center justify-center text-cream
                       hover:border-gold hover:text-gold hover:bg-cream/10
                       focus:outline-none focus-visible:ring-4 focus-visible:ring-gold/60
                       transition-colors"
                aria-label="Buka menu"
            >

                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2.5"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>

            </button>

        </div>


        {{-- MOBILE NAVIGATION --}}
        <div
            id="mobile-menu"
            class="hidden md:hidden border-t-2 border-cream/15 py-4"
        >

            <div class="space-y-1">

                @foreach($navLinks as $link)
                    @php $aktif = request()->routeIs($link['route']); @endphp

                    <a
                        href="{{ route($link['route']) }}"
                        class="block px-4 py-3 text-sm font-mono font-bold border-l-4 transition-colors
                               {{ $aktif
                                    ? 'border-gold bg-cream/10 text-gold'
                                    : 'border-transparent text-cream/75 hover:border-gold-soft hover:bg-cream/5 hover:text-gold-soft' }}"
                    >
                        {{ $link['label'] }}
                    </a>
                @endforeach

            </div>

        </div>

    </div>

</nav>