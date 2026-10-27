@extends('layouts.sidebar')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Pengelola Data')

@section('content')
<div class="space-y-8 max-w-7xl mx-auto">
    
    {{-- 1. HEADER WELCOME --}}
    <div class="bg-gradient-to-r from-navy via-[#1a2d4a] to-[#0d1b2e] rounded-2xl p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gold/10 rounded-full blur-3xl -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <h2 class="font-display text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }} 👋</h2>
            <p class="text-cream/70 mb-6 max-w-xl">Pantau integritas data pemilu, kelola pengguna, dan jalankan analisis K-Means dari satu tempat.</p>
            <div class="flex flex-wrap gap-3">
                <span class="px-4 py-1.5 bg-gold/20 border border-gold/40 rounded-full text-gold text-xs font-bold uppercase tracking-wider">👑 Pengelola Data (Admin)</span>
                <span class="px-4 py-1.5 bg-white/10 rounded-full text-cream/80 text-xs font-mono">Session: {{ now()->format('d M Y, H:i') }}</span>
            </div>
        </div>
    </div>

    {{-- 2. DATA MASTER OVERVIEW --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-display text-lg font-bold text-navy flex items-center gap-2">
                <span class="w-1 h-6 bg-gold rounded-full"></span> Data Master
            </h3>
            <span class="text-xs font-mono text-slate">Referensi Utama Sistem</span>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">🏛️</div>
                </div>
                <div class="text-2xl font-black text-navy">{{ $countPartai }}</div>
                <div class="text-xs text-slate font-medium mt-1">Partai Politik</div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-10 h-10 bg-green-50 text-green-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">📍</div>
                </div>
                <div class="text-2xl font-black text-navy">{{ $countKecamatan }}</div>
                <div class="text-xs text-slate font-medium mt-1">Kecamatan</div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-10 h-10 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">🏘️</div>
                </div>
                <div class="text-2xl font-black text-navy">{{ $countKelurahan }}</div>
                <div class="text-xs text-slate font-medium mt-1">Kelurahan</div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">🗳️</div>
                </div>
                <div class="text-2xl font-black text-navy">{{ number_format($countTps) }}</div>
                <div class="text-xs text-slate font-medium mt-1">Total TPS</div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-10 h-10 bg-slate-100 text-slate-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">👥</div>
                </div>
                <div class="text-2xl font-black text-navy">{{ $countUsers }}</div>
                <div class="text-xs text-slate font-medium mt-1">Pengguna Sistem</div>
            </div>
        </div>
    </div>

    {{-- 3. DATA TRANSAKSI & ANALISIS --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-display text-lg font-bold text-navy flex items-center gap-2">
                <span class="w-1 h-6 bg-maroon rounded-full"></span> Data Transaksi & Analisis
            </h3>
            <span class="text-xs font-mono text-slate">Output Pengolahan Data</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Suara 2024 --}}
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-6 rounded-xl text-white shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="relative z-10">
                    <div class="text-xs font-mono text-blue-200 uppercase tracking-wider mb-2">Total Suara Sah (2024)</div>
                    <div class="text-3xl font-black mb-1">{{ number_format($totalSuara2024) }}</div>
                    <div class="text-xs text-blue-200 mt-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        {{ number_format($rawData2024) }} baris data mentah
                    </div>
                </div>
            </div>
            
            {{-- Suara 2019 --}}
            <div class="bg-gradient-to-br from-slate-700 to-slate-900 p-6 rounded-xl text-white shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="relative z-10">
                    <div class="text-xs font-mono text-slate-300 uppercase tracking-wider mb-2">Total Suara Sah (2019)</div>
                    <div class="text-3xl font-black mb-1">{{ number_format($totalSuara2019) }}</div>
                    <div class="text-xs text-slate-400 mt-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                        {{ number_format($rawData2019) }} baris data mentah
                    </div>
                </div>
            </div>
            
            {{-- Status Clustering --}}
            <div class="bg-gradient-to-br from-gold to-yellow-600 p-6 rounded-xl text-navy shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/20 rounded-full blur-2xl -mr-10 -mt-10"></div>
                <div class="relative z-10">
                    <div class="text-xs font-mono text-navy/70 uppercase tracking-wider mb-2">Status Clustering</div>
                    <div class="text-2xl font-black mb-1 flex items-center gap-2">
                        @if($cluster2024 > 0) ✅ Selesai @else ⏳ Belum @endif
                    </div>
                    <div class="text-xs text-navy/80 mt-4">
                        Ter-cluster: {{ $cluster2024 }} Partai (2024)
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. DISTRIBUSI PENGGUNA & QUICK ACTIONS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Distribusi Pengguna --}}
        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="font-display text-base font-bold text-navy">Distribusi Hak Akses</h3>
            </div>
            <div class="divide-y divide-slate-100">
                <div class="p-4 flex items-center justify-between hover:bg-maroon/5 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-maroon/10 text-maroon rounded-full flex items-center justify-center text-sm">👑</div>
                        <span class="text-sm font-medium text-navy">Admin</span>
                    </div>
                    <span class="font-black text-maroon">{{ $roleAdmin }}</span>
                </div>
                <div class="p-4 flex items-center justify-between hover:bg-gold/5 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gold/10 text-gold rounded-full flex items-center justify-center text-sm">🏢</div>
                        <span class="text-sm font-medium text-navy">Pimpinan</span>
                    </div>
                    <span class="font-black text-gold">{{ $rolePimpinan }}</span>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="lg:col-span-2">
            <h3 class="font-display text-lg font-bold text-navy mb-4 flex items-center gap-2">
                <span class="w-1 h-6 bg-blue-600 rounded-full"></span> Aksi Cepat
            </h3>
            <div class="grid md:grid-cols-3 gap-4">
                <a href="#" class="group block bg-white border-2 border-dashed border-navy/20 rounded-xl p-5 hover:border-gold hover:bg-gold/5 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform text-white">👥</div>
                        <div>
                            <div class="font-bold text-sm text-navy group-hover:text-gold transition-colors">Kelola Pengguna</div>
                            <div class="text-xs text-slate">Tambah/Edit akun</div>
                        </div>
                    </div>
                </a>

                <a href="#" class="group block bg-white border-2 border-dashed border-navy/20 rounded-xl p-5 hover:border-gold hover:bg-gold/5 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-gold to-yellow-600 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform text-white">📤</div>
                        <div>
                            <div class="font-bold text-sm text-navy group-hover:text-gold transition-colors">Upload Excel</div>
                            <div class="text-xs text-slate">Import data KPU</div>
                        </div>
                    </div>
                </a>

                <a href="#" class="group block bg-white border-2 border-dashed border-navy/20 rounded-xl p-5 hover:border-gold hover:bg-gold/5 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-maroon to-red-900 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform text-white">🐍</div>
                        <div>
                            <div class="font-bold text-sm text-navy group-hover:text-gold transition-colors">Jalankan K-Means</div>
                            <div class="text-xs text-slate">Proses clustering</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection