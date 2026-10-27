<svg viewBox="0 0 800 600" class="w-full max-w-3xl mx-auto">
    @foreach($kecamatanWithCluster as $kec)
        <path
            d="{{ $kec->svg_path }}"
            fill="{{ $warnaCluster[$kec->cluster_label] ?? '#ddd' }}"
            stroke="#0B1F3A"
            stroke-width="1.5"
            data-cluster="{{ $kec->cluster_label }}"
            class="hover:opacity-80 cursor-pointer"
        >
            <title>{{ $kec->nama }} — Klaster {{ $kec->cluster_label }}</title>
        </path>
    @endforeach
</svg>