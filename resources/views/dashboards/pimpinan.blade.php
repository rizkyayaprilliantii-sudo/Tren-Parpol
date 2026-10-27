@extends('layouts.app')
@section('title', 'Dashboard Pimpinan')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="font-display text-4xl font-bold text-navy mb-2">Dashboard Pimpinan</h1>
        <p class="text-slate">Selamat datang, {{ auth()->user()->name }}. Berikut ringkasan analisis data pemilu.</p>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-navy to-[#1a2d4a] rounded-xl p-6 text-white shadow-lg">
            <div class="text-xs font-mono text-gold mb-2">TOTAL PARTAI</div>
            <div class="text-4xl font-black">18</div>
            <div class="text-sm text-white/60 mt-2">Peserta Pileg 2024</div>
        </div>
        <div class="bg-gradient-to-br from-gold to-yellow-600 rounded-xl p-6 text-white shadow-lg">
            <div class="text-xs font-mono text-navy mb-2">PARTAI NAIK</div>
            <div class="text-4xl font-black">9</div>
            <div class="text-sm text-white/80 mt-2">Mengalami kenaikan suara</div>
        </div>
        <div class="bg-gradient-to-br from-maroon to-red-900 rounded-xl p-6 text-white shadow-lg">
            <div class="text-xs font-mono text-gold mb-2">PARTAI TURUN</div>
            <div class="text-4xl font-black">11</div>
            <div class="text-sm text-white/60 mt-2">Mengalami penurunan suara</div>
        </div>
    </div>

    {{-- Info Panel --}}
    <div class="bg-white border border-navy/10 rounded-xl p-8">
        <h2 class="font-display text-2xl text-navy mb-4">📊 Ringkasan Analisis</h2>
        <p class="text-slate mb-6">Dashboard ini menampilkan ringkasan hasil analisis tren suara partai politik di Kota Banjarmasin. Gunakan menu di bawah untuk melihat detail.</p>
        
        <div class="grid md:grid-cols-2 gap-4">
            <a href="{{ route('perbandingan') }}" class="block p-6 bg-cream/50 rounded-xl hover:bg-gold/10 transition-colors border-2 border-transparent hover:border-gold">
                <div class="font-bold text-navy mb-2">📈 Lihat Perbandingan 2019 vs 2024</div>
                <div class="text-sm text-slate">Analisis tren kenaikan dan penurunan suara partai</div>
            </a>
            <a href="{{ route('hasil-klaster') }}" class="block p-6 bg-cream/50 rounded-xl hover:bg-gold/10 transition-colors border-2 border-transparent hover:border-gold">
                <div class="font-bold text-navy mb-2">🔬 Lihat Hasil Clustering</div>
                <div class="text-sm text-slate">Pengelompokan partai berdasarkan perolehan suara</div>
            </a>
        </div>
    </div>
</div>
@endsection