<x-layout
    title="Status sistem"
    description="Pemeriksaan koneksi database dan endpoint kesehatan KirimCepat."
>
    <div class="page-head">
        <div class="wrap wrap-narrow">
            <nav class="crumbs" aria-label="Remah roti">
                <a href="{{ route('home') }}">Beranda</a>
                <span aria-hidden="true"> / </span>
                <span aria-current="page">Status sistem</span>
            </nav>

            <h1>Status sistem</h1>
            <p class="lead">
                Halaman ini menampilkan hasil endpoint <span class="mono">/health</span>. Permintaan dengan
                header JSON menerima respons JSON untuk smoke test.
            </p>
        </div>
    </div>

    <div class="wrap wrap-narrow page-body">
        <div class="health-grid">
            <div class="health-item">
                <span class="label">Status</span>
                <strong>{{ $health['status'] === 'ok' ? 'OK' : 'Error' }}</strong>
            </div>
            <div class="health-item">
                <span class="label">Database</span>
                <strong>{{ $health['database'] }}</strong>
            </div>
            <div class="health-item">
                <span class="label">HTTP</span>
                <strong>{{ $httpCode }}</strong>
            </div>
        </div>

        <pre class="code">{{ json_encode($health, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>

        <p class="hint table-note">
            Diperiksa {{ \App\Support\Format::dateTime(\Carbon\Carbon::parse($health['timestamp'])) }}.
        </p>

        <div class="hero-actions">
            <a class="btn btn-outline btn-small" href="{{ url('/health') }}">Muat ulang</a>
            <a class="btn btn-outline btn-small" href="{{ route('home') }}">Kembali ke beranda</a>
        </div>
    </div>
</x-layout>
