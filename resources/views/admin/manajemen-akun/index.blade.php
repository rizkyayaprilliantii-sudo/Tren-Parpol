@extends('layouts.sidebar')

@section('title', 'Kelola Akun')

@section('page-title', 'Kelola Akun')

@section('content')

<div class="mx-auto max-w-7xl space-y-6">

    {{-- HEADER CONTENT --}}
    <section class="relative overflow-hidden rounded-2xl border-2 border-navy bg-navy px-6 py-6 shadow-sm sm:px-8">

        {{-- DEKORASI HALUS --}}
        <div class="pointer-events-none absolute -right-10 -top-16 h-48 w-48 rounded-full border-[24px] border-gold/10"></div>
        <div class="pointer-events-none absolute -bottom-24 right-24 h-40 w-40 rounded-full bg-white/[0.04] blur-2xl"></div>

        <div class="relative z-10">

            <h2 class="text-2xl font-display font-bold text-white">
                Manajemen Pengguna
            </h2>

            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-white/75">
                Kelola akun admin dan pimpinan yang memiliki akses ke sistem.
            </p>

        </div>

    </section>


    {{-- TOMBOL AKSI --}}
    <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">

        {{-- RIWAYAT AKTIVITAS --}}
        <a
            href="{{ route('admin.riwayat.index') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg border-2 border-navy bg-white px-5 py-2.5 text-sm font-bold text-navy shadow-sm transition-colors hover:bg-navy hover:text-white"
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
                    d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>

            Riwayat Aktivitas
        </a>


        {{-- TAMBAH AKUN --}}
        <a
            href="{{ route('admin.manajemen-akun.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-navy px-5 py-2.5 text-sm font-bold text-white shadow-sm transition-colors hover:bg-[#263957]"
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
                    d="M12 4v16m8-8H4"
                />
            </svg>

            Tambah Akun
        </a>

    </div>


    {{-- NOTIFIKASI SUKSES --}}
    @if (session('success'))

        <div class="rounded-xl border-2 border-[#4B5563] bg-white px-5 py-4 shadow-sm">

            <div class="flex items-start gap-3">

                <span class="mt-1 h-2 w-2 flex-shrink-0 rounded-full bg-gold"></span>

                <div>

                    <p class="text-sm font-bold text-navy">
                        Berhasil
                    </p>

                    <p class="mt-0.5 text-xs leading-relaxed text-slate">
                        {{ session('success') }}
                    </p>

                </div>

            </div>

        </div>

    @endif


    {{-- NOTIFIKASI ERROR --}}
    @if (session('error'))

        <div class="rounded-xl border-2 border-[#4B5563] bg-white px-5 py-4 shadow-sm">

            <div class="flex items-start gap-3">

                <span class="mt-1 h-2 w-2 flex-shrink-0 rounded-full bg-maroon"></span>

                <div>

                    <p class="text-sm font-bold text-navy">
                        Terjadi Kesalahan
                    </p>

                    <p class="mt-0.5 text-xs leading-relaxed text-slate">
                        {{ session('error') }}
                    </p>

                </div>

            </div>

        </div>

    @endif


    {{-- KONTEN TABEL --}}
    <div class="overflow-hidden rounded-xl border-2 border-[#4B5563] bg-white shadow-sm">

        {{-- TOOLBAR PENCARIAN DAN FILTER --}}
        <div class="flex flex-col gap-4 border-b border-navy px-5 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <h3 class="text-sm font-bold text-navy">
                    Daftar Pengguna
                </h3>

                <p class="mt-1 text-xs text-slate">
                    Kelola dan temukan akun yang terdaftar.
                </p>

            </div>


            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">

                {{-- PENCARIAN --}}
                <div class="relative w-full sm:w-64">

                    <svg
                        class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-navy"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m21 21-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>

                    <input
                        type="search"
                        id="searchUser"
                        placeholder="Cari nama atau email..."
                        aria-label="Cari pengguna berdasarkan nama atau email"
                        class="w-full rounded-lg border border-navy bg-white py-2.5 pl-9 pr-3 text-sm text-navy outline-none transition placeholder:text-slate focus:border-navy focus:ring-2 focus:ring-navy/10"
                    >

                </div>


                {{-- FILTER ROLE --}}
                <div class="relative w-full sm:w-40">

                    <select
                        id="filterRole"
                        aria-label="Filter berdasarkan role"
                        class="w-full appearance-none rounded-lg border border-navy bg-white px-3 py-2.5 pr-9 text-sm text-navy outline-none transition focus:border-navy focus:ring-2 focus:ring-navy/10"
                    >
                        <option value="">Semua Role</option>
                        <option value="admin">Admin</option>
                        <option value="pimpinan">Pimpinan</option>
                    </select>

                    <svg
                        class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-navy"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m6 9 6 6 6-6"
                        />
                    </svg>

                </div>

            </div>

        </div>


        {{-- INFORMASI JUMLAH AKUN --}}
        <div class="flex flex-col gap-1 px-5 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-6">

            <p class="text-xs text-slate">

                Total

                <span class="font-bold text-navy">
                    {{ $users->total() }}
                </span>

                akun terdaftar

            </p>


            <p
                id="filterResult"
                class="text-xs text-slate"
                aria-live="polite"
            >
                Menampilkan {{ $users->count() }} akun pada halaman ini
            </p>

        </div>


        {{-- TABEL AKUN --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[950px] text-left text-sm">

                {{-- TABLE HEADER --}}
                <thead class="border-b border-slate-300 bg-navy text-cream">

                    <tr class="font-mono text-xs uppercase tracking-wider">

                        <th class="px-6 py-4 font-bold">
                            No
                        </th>

                        <th class="px-6 py-4 font-bold">
                            Nama
                        </th>

                        <th class="px-6 py-4 font-bold">
                            Email
                        </th>

                        <th class="px-6 py-4 font-bold">
                            Role
                        </th>

                        <th class="px-6 py-4 font-bold">
                            Tanggal Dibuat
                        </th>

                        <th class="px-6 py-4 text-center font-bold">
                            Aksi
                        </th>

                    </tr>

                </thead>


                {{-- TABLE BODY --}}
                <tbody
                    id="userTableBody"
                    class="divide-y divide-slate-100"
                >

                    @forelse ($users as $user)

                        <tr
                            class="user-row transition-colors hover:bg-slate-50"
                            data-name="{{ strtolower($user->name) }}"
                            data-email="{{ strtolower($user->email) }}"
                            data-role="{{ strtolower($user->role) }}"
                        >

                            {{-- NOMOR --}}
                            <td class="px-6 py-5 font-mono text-xs text-slate">
                                {{ $users->firstItem() + $loop->index }}
                            </td>


                            {{-- NAMA --}}
                            <td class="px-6 py-5">

                                <div class="font-bold text-navy">
                                    {{ $user->name }}
                                </div>

                                @if ($user->id === auth()->id())

                                    <div class="mt-1 inline-flex items-center gap-1.5">

                                        <span class="h-1.5 w-1.5 rounded-full bg-gold"></span>

                                        <span class="text-[11px] font-semibold text-gold">
                                            Akun Anda
                                        </span>

                                    </div>

                                @endif

                            </td>


                            {{-- EMAIL --}}
                            <td class="px-6 py-5 text-sm text-slate">
                                {{ $user->email }}
                            </td>


                            {{-- ROLE --}}
                            <td class="px-6 py-5">

                                @if ($user->role === 'admin')

                                    <span class="text-sm font-semibold text-navy">
                                        Admin
                                    </span>

                                @elseif ($user->role === 'pimpinan')

                                    <span class="text-sm font-semibold text-navy">
                                        Pimpinan
                                    </span>

                                @endif

                            </td>


                            {{-- TANGGAL DIBUAT --}}
                            <td class="px-6 py-5 font-mono text-xs text-slate">
                                {{ $user->created_at?->format('d M Y') ?? '-' }}
                            </td>


                            {{-- AKSI --}}
                            <td class="px-6 py-5">

                                <div class="flex items-center justify-center gap-2">

                                    {{-- LIHAT --}}
                                    <a
                                        href="{{ route('admin.manajemen-akun.show', $user) }}"
                                        class="inline-flex min-w-[68px] items-center justify-center rounded-lg border border-blue-600 px-3.5 py-2 text-xs font-bold text-blue-600 transition-colors hover:bg-blue-600 hover:text-white"
                                    >
                                        Lihat
                                    </a>


                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('admin.manajemen-akun.edit', $user) }}"
                                        class="inline-flex min-w-[61px] items-center justify-center rounded-lg bg-amber-500 px-3.5 py-2 text-xs font-bold text-white transition-colors hover:bg-amber-600"
                                    >
                                        Edit
                                    </a>


                                    {{-- HAPUS --}}
                                    @if ($user->id !== auth()->id())

                                        <form
                                            action="{{ route('admin.manajemen-akun.destroy', $user) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus akun {{ $user->name }}?')"
                                        >

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex min-w-[68px] items-center justify-center rounded-lg bg-red-600 px-3.5 py-2 text-xs font-bold text-white transition-colors hover:bg-red-700"
                                            >
                                                Hapus
                                            </button>

                                        </form>

                                    @else

                                        <button
                                            type="button"
                                            disabled
                                            title="Akun yang sedang digunakan tidak dapat dihapus"
                                            class="inline-flex min-w-[68px] cursor-not-allowed items-center justify-center rounded-lg bg-slate-100 px-3.5 py-2 text-xs font-bold text-slate-400"
                                        >
                                            Hapus
                                        </button>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-14 text-center"
                            >

                                <p class="font-display text-lg font-bold text-navy">
                                    Belum Ada Akun
                                </p>

                                <p class="mt-1 text-sm text-slate">
                                    Belum ada akun admin atau pimpinan yang terdaftar dalam sistem.
                                </p>

                            </td>

                        </tr>

                    @endforelse


                    {{-- HASIL PENCARIAN KOSONG --}}
                    <tr
                        id="noSearchResult"
                        class="hidden"
                    >

                        <td
                            colspan="6"
                            class="px-6 py-12 text-center"
                        >

                            <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-[#F4F1E8]">

                                <svg
                                    class="h-5 w-5 text-navy"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    aria-hidden="true"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m21 21-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z"
                                    />
                                </svg>

                            </div>

                            <p class="mt-3 font-display text-base font-bold text-navy">
                                Pengguna Tidak Ditemukan
                            </p>

                            <p class="mt-1 text-sm text-slate">
                                Coba gunakan kata kunci atau filter role yang berbeda.
                            </p>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if ($users->hasPages())

            <div
                id="userPagination"
                class="border-t border-navy px-6 py-4"
            >
                {{ $users->links() }}
            </div>

        @endif

    </div>

</div>


{{-- SCRIPT PENCARIAN DAN FILTER --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const searchInput = document.getElementById('searchUser');
        const roleFilter = document.getElementById('filterRole');
        const tableBody = document.getElementById('userTableBody');
        const resultText = document.getElementById('filterResult');
        const noResult = document.getElementById('noSearchResult');
        const pagination = document.getElementById('userPagination');

        if (!searchInput || !roleFilter || !tableBody) {
            return;
        }

        const rows = Array.from(
            tableBody.querySelectorAll('.user-row')
        );

        const defaultCount = rows.length;


        function filterUsers() {

            const keyword = searchInput.value.trim().toLowerCase();
            const selectedRole = roleFilter.value.toLowerCase();

            let visibleCount = 0;


            rows.forEach(function (row) {

                const name = row.dataset.name || '';
                const email = row.dataset.email || '';
                const role = row.dataset.role || '';


                const matchesKeyword =
                    name.includes(keyword) ||
                    email.includes(keyword);


                const matchesRole =
                    selectedRole === '' ||
                    role === selectedRole;


                const isVisible =
                    matchesKeyword &&
                    matchesRole;


                row.classList.toggle(
                    'hidden',
                    !isVisible
                );


                if (isVisible) {
                    visibleCount++;
                }

            });


            if (noResult) {

                noResult.classList.toggle(
                    'hidden',
                    visibleCount !== 0 ||
                    defaultCount === 0
                );

            }


            if (resultText) {

                if (keyword || selectedRole) {

                    resultText.textContent =
                        'Menampilkan ' +
                        visibleCount +
                        ' dari ' +
                        defaultCount +
                        ' akun pada halaman ini';

                } else {

                    resultText.textContent =
                        'Menampilkan ' +
                        defaultCount +
                        ' akun pada halaman ini';

                }

            }


            if (pagination) {

                pagination.classList.toggle(
                    'hidden',
                    Boolean(keyword || selectedRole)
                );

            }

        }


        searchInput.addEventListener(
            'input',
            filterUsers
        );

        roleFilter.addEventListener(
            'change',
            filterUsers
        );

    });
</script>

@endsection