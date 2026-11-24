@props([
    'title' => null,
    'description' => 'KirimCepat menghitung ongkir dari berat aktual dan volumetrik, menerbitkan nomor resi otomatis, dan menampilkan riwayat status paket tanpa login.',
    'landing' => false,
])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' — KirimCepat' : 'KirimCepat — Hitung ongkir, kirim paket, lacak resi' }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="theme-color" content="#111111">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body @class(['bg-[#E5E5E5]' => $landing])>
    <a class="skip-link" href="#konten">Lewati ke konten</a>

    @if ($landing)
        <main id="konten">
            {{ $slot }}
        </main>
    @else
        <header class="site-header">
            <div class="wrap header-row">
                <a class="brand" href="{{ route('home') }}">
                    <svg class="w-6 h-6 text-black shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                    </svg>
                    <span class="brand-name text-xs sm:text-sm font-black tracking-[0.24em] uppercase text-black">KirimCepat</span>
                </a>

                <nav class="site-nav" id="primary-nav" aria-label="Navigasi utama">
                    <div class="nav-links">
                        <a href="{{ route('home') }}">Beranda</a>
                        <a href="{{ route('shipments.create') }}" @if (request()->routeIs('shipments.*')) aria-current="page" @endif>Kirim paket</a>
                        @if (! request()->routeIs('shipments.*') && ! request()->routeIs('kurir.*'))
                            <a href="{{ route('track.index') }}" @if (request()->routeIs('track.*')) aria-current="page" @endif>Lacak resi</a>
                        @endif
                    </div>

                    <div class="nav-actions">
                        @auth
                            <a class="nav-login" href="{{ route('kurir.dashboard') }}">Panel kurir</a>
                            <form method="POST" action="{{ route('kurir.keluar') }}">
                                @csrf
                                <button class="nav-button" type="submit">Keluar</button>
                            </form>
                        @else
                            <a class="nav-login" href="{{ route('kurir.masuk') }}">Masuk kurir</a>
                            @if (! request()->routeIs('shipments.create'))
                                <a class="btn btn-primary btn-small" href="{{ route('shipments.create') }}">Kirim paket</a>
                            @endif
                        @endauth
                    </div>
                </nav>

                <div class="header-actions">
                    @auth
                        <a class="nav-login" href="{{ route('kurir.dashboard') }}">Panel kurir</a>
                        <form method="POST" action="{{ route('kurir.keluar') }}">
                            @csrf
                            <button class="nav-button" type="submit">Keluar</button>
                        </form>
                    @else
                        <a class="nav-login" href="{{ route('kurir.masuk') }}">Masuk kurir</a>
                        @if (! request()->routeIs('shipments.create'))
                            <a class="btn btn-primary btn-small" href="{{ route('shipments.create') }}">Kirim paket</a>
                        @endif
                    @endauth
                </div>

                <button
                    class="nav-toggle"
                    type="button"
                    aria-expanded="false"
                    aria-controls="primary-nav"
                    data-nav-toggle
                >
                    Menu
                </button>
            </div>
        </header>

        <main id="konten">
            {{ $slot }}
        </main>

        <footer class="site-footer">
            <div class="wrap footer-main">
                <div class="footer-brand">
                    <a class="brand" href="{{ route('home') }}">
                        <svg class="w-5 h-5 text-black shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                        </svg>
                        <span class="brand-name text-sm font-black tracking-[0.2em] uppercase text-black">KirimCepat</span>
                    </a>
                    <p>Pengiriman paket di Jawa Timur. Ongkir, resi, dan riwayat status dalam satu alur.</p>
                </div>

                <nav class="footer-nav" aria-label="Tautan footer">
                    <a href="{{ route('home') }}">Beranda</a>
                    <a href="{{ route('shipments.create') }}">Kirim paket</a>
                    @if (! request()->routeIs('shipments.*') && ! request()->routeIs('kurir.*'))
                        <a href="{{ route('track.index') }}">Lacak resi</a>
                    @endif
                    <a href="{{ route('kurir.masuk') }}">Masuk kurir</a>
                    <a href="{{ url('/health') }}">Status sistem</a>
                </nav>
            </div>

            <div class="wrap footer-base">
                <p>Waktu ditampilkan dalam WIB.</p>
                <p>&copy; {{ date('Y') }} KirimCepat</p>
            </div>
        </footer>
    @endif

    <p class="sr-only" aria-live="polite" data-announcer></p>
</body>
</html>