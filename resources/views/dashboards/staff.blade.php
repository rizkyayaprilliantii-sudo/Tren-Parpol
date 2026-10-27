@extends('layouts.sidebar')
@section('title', 'Dashboard Staf')
@section('page-title', 'Dashboard Staf Analis')

@section('sidebar-menu')
    {{-- Menu Khusus Staf --}}
    <div class="text-xs font-mono text-cream/40 uppercase tracking-wider mb-2 px-4">Menu Utama</div>
    
    <a href="{{ route('staf.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-gold/20 text-gold rounded-lg font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        Dashboard
    </a>

    <div class="text-xs font-mono text-cream/40 uppercase tracking-wider mt-6 mb-2 px-4">Input Data</div>
    
    <a href="#" class="flex items-center gap-3 px-4 py-3 text-cream/70 hover:text-cream hover:bg-cream/10 rounded-lg font-medium transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Input Data Suara
    </a>

    <a href="#" class="flex items-center gap-3 px-4 py-3 text-cream/70 hover:text-cream hover:bg-cream/10 rounded-lg font-medium transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
        Kelola Data Master
    </a>

    <div class="text-xs font-mono text-cream/40 uppercase tracking-wider mt-6 mb-2 px-4">Laporan</div>
    
    <a href="{{ route('data-suara') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-cream/70 hover:text-cream hover:bg-cream/10 rounded-lg font-medium transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Lihat Tren Suara
    </a>

    <a href="{{ route('beranda') }}" target="_blank" class="flex items-center gap-3 px-4 py-3 text-cream/70 hover:text-cream hover:bg-cream/10 rounded-lg font-medium transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
        </svg>
        Website Publik
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-600">
        <div class="text-xs font-mono text-slate uppercase tracking-wider mb-1">Data Suara 2024</div>
        <div class="text-3xl font-black text-blue-600">1,940</div>
        <div class="text-xs text-slate mt-1">TPS Terdata</div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-gold">
        <div class="text-xs font-mono text-slate uppercase tracking-wider mb-1">Partai Politik</div>
        <div class="text-3xl font-black text-gold">18</div>
        <div class="text-xs text-slate mt-1">Peserta Pemilu</div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-600">
        <div class="text-xs font-mono text-slate uppercase tracking-wider mb-1">Status Data</div>
        <div class="text-lg font-bold text-green-600 mt-2">✅ Valid</div>
        <div class="text-xs text-slate mt-1">Terakhir update: Hari ini</div>
    </div>
</div>

<div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl p-8 text-white">
    <h2 class="font-display text-2xl font-bold mb-4">📝 Dashboard Staf Analis</h2>
    <p class="text-white/80 mb-6">Gunakan menu di sidebar untuk input data suara dan kelola data master. Pastikan data yang diinput sudah valid sebelum diproses.</p>
    <div class="flex gap-4 text-sm font-mono">
        <span class="px-3 py-1 bg-white/20 rounded-full">Role: Staf</span>
        <span class="px-3 py-1 bg-white/10 rounded-full">Level: Input & Edit</span>
    </div>
</div>
@endsection