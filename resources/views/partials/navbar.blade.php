<nav class="bg-gradient-to-r from-navy via-navy to-navy/95 text-cream border-b-2 border-gold shadow-lg shadow-navy/20 relative z-40">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        {{-- Brand --}}
        <a href="{{ route('beranda') }}" class="flex items-center gap-3 group">
            <span class="w-10 h-10 rounded-full border-2 border-gold bg-gold/10 flex items-center justify-center font-display text-gold text-lg group-hover:bg-gold group-hover:text-navy transition-colors duration-300">
                P
            </span>
            <div class="leading-tight">
                <div class="font-display text-lg tracking-tight">Tren Perolehan Suara</div>
                <div class="text-[10px] text-gold-soft font-mono uppercase tracking-widest">Partai Politik</div>
            </div>
        </a>

        {{-- Menu tengah --}}
        <div class="hidden md:flex items-center gap-8 text-sm font-medium">
            <a href="{{ route('beranda') }}"
               class="relative py-1 hover:text-gold-soft transition-colors after:absolute after:left-0 after:-bottom-1 after:h-[2px] after:w-0 after:bg-gold after:transition-all hover:after:w-full">
                Beranda
            </a>
            <a href="{{ route('data-suara') }}"
               class="relative py-1 hover:text-gold-soft transition-colors after:absolute after:left-0 after:-bottom-1 after:h-[2px] after:w-0 after:bg-gold after:transition-all hover:after:w-full">
                Data Suara
            </a>
            <a href="{{ route('hasil-klaster') }}"
               class="relative py-1 hover:text-gold-soft transition-colors after:absolute after:left-0 after:-bottom-1 after:h-[2px] after:w-0 after:bg-gold after:transition-all hover:after:w-full">
                Hasil Klaster
            </a>
            <a href="{{ route('perbandingan') }}"
               class="relative py-1 hover:text-gold-soft transition-colors after:absolute after:left-0 after:-bottom-1 after:h-[2px] after:w-0 after:bg-gold after:transition-all hover:after:w-full">
                Tren Suara
            </a>
            <a href="{{ route('tentang') }}"
               class="relative py-1 hover:text-gold-soft transition-colors after:absolute after:left-0 after:-bottom-1 after:h-[2px] after:w-0 after:bg-gold after:transition-all hover:after:w-full">
                Tentang
            </a>
        </div>

        {{-- Kanan: User info & logout --}}
        <div class="ml-4 flex items-center gap-4">
            @auth
                {{-- Jika sudah login --}}
                <div class="flex items-center gap-3 pr-4 border-r border-cream/15">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-bold text-cream leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] font-mono text-gold-soft uppercase tracking-wider">{{ auth()->user()->role }}</div>
                    </div>
                    <div class="w-9 h-9 bg-gold text-navy rounded-full flex items-center justify-center font-bold text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>

                {{-- Tombol logout --}}
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold text-cream/80 hover:text-cream hover:bg-maroon/40 rounded-lg transition-colors">
                        Logout
                    </button>
                </form>
            @else
                {{-- Jika belum login --}}
                <a href="{{ route('login') }}"
                   class="px-5 py-2 bg-gold text-navy text-sm font-bold rounded-lg hover:bg-gold-soft transition-colors shadow-md shadow-gold/20">
                    Login
                </a>
            @endauth
        </div>

    </div>
</nav>