<x-guest-layout>

    {{-- KEMBALI KE BERANDA --}}
    <div class="mb-6">
        <a
            href="{{ route('beranda') }}"
            class="group inline-flex items-center gap-2 text-xs font-mono font-semibold uppercase tracking-widest text-slate hover:text-navy transition-colors"
        >
            <span class="transition-transform duration-150 group-hover:-translate-x-1">←</span>
            Kembali ke Beranda
        </a>
    </div>

    <div class="mb-7 text-center">
        <h1 class="font-display text-2xl text-ink">
            Buat Akun Tren Parpol
        </h1>

        <p class="mt-2 text-sm leading-6 text-slate">
            Lengkapi data berikut untuk membuat akun dan mengakses sistem analisis tren perolehan suara.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- NAMA LENGKAP --}}
        <div>
            <x-input-label
                for="name"
                :value="__('Nama Lengkap')"
                class="mb-2 text-sm font-bold text-navy"
            />

            <x-text-input
                id="name"
                class="block w-full rounded-lg border-2 border-navy/15 px-4 py-3 text-sm shadow-sm focus:border-gold focus:ring-gold/30"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="Masukkan nama lengkap"
            />

            <x-input-error
                :messages="$errors->get('name')"
                class="mt-2"
            />
        </div>

        {{-- EMAIL --}}
        <div>
            <x-input-label
                for="email"
                :value="__('Email')"
                class="mb-2 text-sm font-bold text-navy"
            />

            <x-text-input
                id="email"
                class="block w-full rounded-lg border-2 border-navy/15 px-4 py-3 text-sm shadow-sm focus:border-gold focus:ring-gold/30"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
                placeholder="nama@email.com"
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />
        </div>

        {{-- PASSWORD --}}
        <div>
            <x-input-label
                for="password"
                :value="__('Password')"
                class="mb-2 text-sm font-bold text-navy"
            />

            <div class="relative">
                <x-text-input
                    id="password"
                    class="block w-full rounded-lg border-2 border-navy/15 px-4 py-3 pr-20 text-sm shadow-sm focus:border-gold focus:ring-gold/30"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                />

                <button
                    type="button"
                    onclick="togglePassword('password', this)"
                    class="absolute inset-y-0 right-3 my-auto h-fit rounded-md px-2 py-1 text-xs font-mono font-bold text-slate transition hover:bg-cream hover:text-navy"
                >
                    Lihat
                </button>
            </div>

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />
        </div>

        {{-- KONFIRMASI PASSWORD --}}
        <div>
            <x-input-label
                for="password_confirmation"
                :value="__('Konfirmasi Password')"
                class="mb-2 text-sm font-bold text-navy"
            />

            <div class="relative">
                <x-text-input
                    id="password_confirmation"
                    class="block w-full rounded-lg border-2 border-navy/15 px-4 py-3 pr-20 text-sm shadow-sm focus:border-gold focus:ring-gold/30"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Ulangi password"
                />

                <button
                    type="button"
                    onclick="togglePassword('password_confirmation', this)"
                    class="absolute inset-y-0 right-3 my-auto h-fit rounded-md px-2 py-1 text-xs font-mono font-bold text-slate transition hover:bg-cream hover:text-navy"
                >
                    Lihat
                </button>
            </div>

            <x-input-error
                :messages="$errors->get('password_confirmation')"
                class="mt-2"
            />
        </div>

        {{-- TOMBOL DAFTAR --}}
        <x-primary-button
            class="w-full justify-center gap-2 rounded-sm border-2 border-navy bg-gold px-4 py-3 text-sm font-extrabold tracking-wide text-navy
                   shadow-[4px_4px_0_0_#1B2A41]
                   hover:bg-gold-soft hover:shadow-[2px_2px_0_0_#1B2A41] hover:translate-x-0.5 hover:translate-y-0.5
                   active:shadow-none active:translate-x-1 active:translate-y-1
                   transition-all duration-150"
        >
            {{ __('Daftar') }}
        </x-primary-button>

        {{-- LINK LOGIN --}}
        <p class="text-center text-sm text-slate">
            Sudah memiliki akun?

            <a
                href="{{ route('login') }}"
                class="font-bold text-gold transition hover:text-maroon"
            >
                Masuk di sini
            </a>
        </p>
    </form>

    {{-- FOOTER --}}
    <div class="mt-7 border-t-2 border-navy/10 pt-5 text-center">
        <p class="text-xs font-mono leading-5 text-slate">
            Analisis Tren Perolehan Suara Partai Politik Kota Banjarmasin
        </p>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);

            if (!input) {
                return;
            }

            const isPassword = input.type === 'password';

            input.type = isPassword ? 'text' : 'password';
            button.textContent = isPassword ? 'Sembunyi' : 'Lihat';
        }
    </script>

</x-guest-layout>