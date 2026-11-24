<x-layout
    title="Resi tidak ditemukan"
    description="Nomor resi tidak ditemukan. Periksa kembali 16 karakter nomor resi KirimCepat."
>
    <div class="wrap wrap-narrow not-found">
        <nav class="crumbs" aria-label="Remah roti">
            <a href="{{ route('home') }}">Beranda</a>
            <span aria-hidden="true"> / </span>
            <a href="{{ route('track.index') }}">Lacak resi</a>
            <span aria-hidden="true"> / </span>
            <span aria-current="page">Tidak ditemukan</span>
        </nav>

        <h1>{{ $invalidFormat ? 'Format resi belum sesuai.' : 'Resi tidak ditemukan.' }}</h1>

        <p class="lead">
            @if ($invalidFormat)
                Nomor <span class="mono">{{ $resi ?: 'yang dimasukkan' }}</span> tidak cocok dengan format resi KirimCepat.
                Resi terdiri dari 16 karakter: KC, delapan digit tanggal, lalu enam digit nomor urut.
            @else
                Tidak ada pengiriman dengan resi <span class="mono">{{ $resi }}</span>. Periksa kembali nomor resi
                pada bukti pengiriman, lalu coba lagi.
            @endif
        </p>

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
                value="{{ $resi }}"
                placeholder="KC20261123000001"
                required
                autofocus
            >
            <button class="btn btn-ink" type="submit">Lacak lagi</button>
        </form>

        <p class="format-note">
            Format yang benar: <span class="mono">KC</span> + tanggal pembuatan
            <span class="mono">YYYYMMDD</span> + enam digit nomor urut, contoh
            <span class="mono">KC20261123000001</span>.
        </p>
    </div>
</x-layout>
