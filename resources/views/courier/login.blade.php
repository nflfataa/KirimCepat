<x-layout
    title="Masuk kurir"
    description="Masuk sebagai kurir untuk memperbarui status paket KirimCepat."
>
    <div class="wrap auth-layout">
        <section class="auth-intro">
            <nav class="crumbs" aria-label="Remah roti">
                <a href="{{ route('home') }}">Beranda</a>
                <span aria-hidden="true"> / </span>
                <span aria-current="page">Masuk kurir</span>
            </nav>

            <h1>Panel kurir</h1>
            <p class="lead">
                Pembaruan status hanya bisa dilakukan kurir yang sudah masuk. Setiap pembaruan menambah
                satu entri riwayat yang bisa dilihat publik di halaman lacak.
            </p>

            <ul class="auth-points">
                <li>Paket hanya bisa maju satu langkah, sesuai urutan pengiriman.</li>
                <li>Catatan kurir bersifat opsional dan tampil di riwayat publik.</li>
                <li>Alamat dan telepon penerima hanya terlihat oleh kurir yang masuk.</li>
            </ul>
        </section>

        <section class="panel auth-card" aria-labelledby="masuk-title">
            <h2 id="masuk-title">Masuk</h2>
            <p class="hint">Gunakan akun kurir yang diberikan admin layanan.</p>

            <form method="POST" action="{{ route('kurir.masuk.store') }}">
                @csrf

                <x-field
                    name="email"
                    label="Email"
                    type="email"
                    autocomplete="username"
                    placeholder="nama@kirimcepat.test"
                    required
                />

                <x-field
                    name="password"
                    label="Kata sandi"
                    type="password"
                    autocomplete="current-password"
                    required
                />

                <label class="checkbox" for="remember">
                    <input id="remember" name="remember" type="checkbox" value="1">
                    <span>Ingat saya di perangkat ini</span>
                </label>

                <button class="btn btn-primary" type="submit">Masuk</button>
            </form>

            @if (app()->environment('local'))
                <div class="demo-box">
                    <strong>Akun demo pengembangan</strong>
                    Email <span class="mono">kurir@kirimcepat.test</span><br>
                    Kata sandi <span class="mono">password</span><br>
                    Hanya tampil di lingkungan lokal.
                </div>
            @endif
        </section>
    </div>
</x-layout>
