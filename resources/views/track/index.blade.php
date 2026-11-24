<x-layout
    title="Lacak resi"
    description="Masukkan nomor resi KirimCepat untuk melihat status paket dan riwayatnya tanpa login."
>
    <div class="page-head">
        <div class="wrap wrap-narrow">
            <nav class="crumbs" aria-label="Remah roti">
                <a href="{{ route('home') }}">Beranda</a>
                <span aria-hidden="true"> / </span>
                <span aria-current="page">Lacak resi</span>
            </nav>

            <h1>Lacak resi</h1>
            <p class="lead">
                Masukkan 16 karakter nomor resi. Status dan riwayat paket bisa dilihat tanpa login;
                alamat lengkap dan nomor telepon tidak ditampilkan.
            </p>
        </div>
    </div>

    <div class="wrap wrap-narrow page-body">
        <form class="track-form" method="GET" action="{{ route('track.index') }}" role="search">
            <label class="sr-only" for="resi">Nomor resi</label>
            <input
                class="input"
                id="resi"
                name="resi"
                type="text"
                autocomplete="off"
                spellcheck="false"
                maxlength="24"
                placeholder="KC20261123000001"
                required
                autofocus
            >
            <button class="btn btn-ink" type="submit">Lacak</button>
        </form>

        <p class="hint track-note">
            Format resi: KC, delapan digit tanggal, lalu enam digit nomor urut. Contoh di atas hanya format.
        </p>

        <div class="panel panel-pad track-help">
            <h2>Yang tampil di halaman lacak</h2>
            <ul class="plain-list">
                <li>Status paket saat ini dan waktu pembaruan terakhir.</li>
                <li>Seluruh riwayat status, berurutan dari yang paling awal.</li>
                <li>Nama kurir dan catatan bila kurir menambahkannya.</li>
                <li>Nama pengirim dan penerima, tanpa alamat dan telepon.</li>
            </ul>
        </div>
    </div>
</x-layout>
