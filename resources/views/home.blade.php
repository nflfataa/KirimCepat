@php
    $zoneOptions = $zones->mapWithKeys(fn ($zone) => [
        $zone->id => $zone->name.' — Rp'.number_format($zone->rate_per_kg, 0, ',', '.').'/kg',
    ]);
@endphp

<x-layout
    :landing="true"
    title="KirimCepat — Kirim paket lebih cepat, pantau tanpa ribet"
    description="Pembuatan resi otomatis, perhitungan ongkir presisi, dan pelacakan real-time – semua dalam satu platform yang simpel."
>
    <div class="min-h-screen bg-[#E5E5E5] flex justify-center p-3 sm:p-6 lg:p-8">
        <div class="app-container w-full max-w-[1440px] bg-white rounded-[1.5rem] overflow-hidden shadow-[0_10px_40px_rgba(0,0,0,0.06)] flex flex-col">
            
            <header class="w-full px-6 sm:px-10 lg:px-12 py-7 sm:py-8 border-b border-neutral-100">
                <nav class="flex items-center justify-between" aria-label="Navigasi landing">
                    <div class="hidden md:flex items-center space-x-7 text-sm font-medium text-black">
                        <a href="#ongkir" class="inline-flex items-center gap-1.5 hover:opacity-70 transition">
                            Cek Ongkir
                            <svg class="w-3 h-3 text-neutral-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#lacak" class="inline-flex items-center gap-1.5 hover:opacity-70 transition">
                            Lacak Paket
                            <svg class="w-3 h-3 text-neutral-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#bantuan" class="hover:opacity-70 transition">Bantuan</a>
                    </div>

                    <a href="{{ route('home') }}" class="flex flex-col items-center justify-center group focus:outline-none">
                        <svg class="w-6 h-6 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                        </svg>
                        <span class="text-xs sm:text-sm font-black tracking-[0.24em] uppercase text-black mt-1">KirimCepat</span>
                    </a>

                    <div class="flex items-center space-x-4 sm:space-x-6 text-sm font-medium">
                        <div class="hidden sm:inline-flex items-center gap-1 text-black cursor-default select-none">
                            <span>ID</span>
                            <svg class="w-3 h-3 text-neutral-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </div>

                        @auth
                            <a href="{{ route('kurir.dashboard') }}" class="text-neutral-700 hover:text-black transition">Panel Kurir</a>
                        @else
                            <a href="{{ route('kurir.masuk') }}" class="text-neutral-700 hover:text-black transition">Masuk Kurir</a>
                        @endauth

                        <a href="{{ route('shipments.create') }}" class="bg-black text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-full text-xs sm:text-sm font-medium hover:bg-neutral-800 transition shadow-sm">
                            Buat Pengiriman
                        </a>
                    </div>
                </nav>

                <div class="flex md:hidden items-center justify-around mt-4 pt-3 border-t border-neutral-100 text-xs font-medium text-neutral-800">
                    <a href="#ongkir" class="hover:text-black">Cek Ongkir</a>
                    <a href="#lacak" class="hover:text-black">Lacak Paket</a>
                    <a href="#alur" class="hover:text-black">Alur Paket</a>
                    <a href="#tarif" class="hover:text-black">Tarif</a>
                </div>
            </header>

            <section class="mt-14 sm:mt-24 lg:mt-28 mb-12 sm:mb-16 px-6 text-center flex flex-col items-center">
                <h1 class="animate-slide-up text-4xl sm:text-6xl md:text-7xl lg:text-[5.5rem] leading-[1.05] font-black text-black tracking-tight max-w-5xl">
                    Kirim paket lebih cepat,<br>pantau tanpa ribet.
                </h1>
                <p class="animate-slide-delay-1 mt-6 sm:mt-8 text-base sm:text-lg md:text-xl text-[#4B5563] max-w-3xl font-normal leading-relaxed">
                    Pembuatan resi otomatis, perhitungan ongkir presisi, dan pelacakan real-time – semua dalam satu platform yang simpel.
                </p>

                <div class="animate-slide-delay-2 mt-8 sm:mt-10 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('shipments.create') }}" class="inline-flex items-center gap-2 bg-black text-white px-7 py-3.5 rounded-full text-sm font-semibold hover:bg-neutral-800 transition">
                        <span>Buat Pengiriman Sekarang</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                    <a href="#ongkir" class="inline-flex items-center gap-1.5 bg-neutral-100 text-black px-7 py-3.5 rounded-full text-sm font-semibold hover:bg-neutral-200 transition">
                        <span>Hitung Ongkir</span>
                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </a>
                </div>
            </section>

            <div class="w-full px-4 sm:px-8 lg:px-12 pb-14 sm:pb-20 animate-slide-delay-3">
                <img
                    src="{{ asset('images/fleet.png') }}"
                    onerror="this.onerror=null;this.src='{{ asset('images/fleet-unsplash.jpg') }}';"
                    alt="Armada Utama KirimCepat Volvo Trans-Jawa"
                    width="1290"
                    height="503"
                    fetchpriority="high"
                    decoding="async"
                    class="w-full h-[320px] sm:h-[460px] md:h-[560px] lg:h-[620px] object-cover rounded-2xl object-center border border-neutral-100 shadow-sm"
                />
            </div>

            <section class="w-full border-t border-neutral-100 px-6 sm:px-10 lg:px-12 py-16 sm:py-24 bg-white" id="ongkir">
                <div class="max-w-6xl mx-auto">
                    <div class="max-w-2xl mb-12 text-left">
                        <span class="text-xs font-bold tracking-widest uppercase text-neutral-400">Kalkulator Akurat</span>
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-black mt-2">Cek ongkir</h2>
                        <p class="text-[#4B5563] text-base mt-2">
                            Hitung estimasi tarif sesuai berat aktual dan volumetrik ke seluruh zona tujuan di Jawa Timur.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        <div class="lg:col-span-7 bg-white border border-neutral-200 rounded-2xl p-6 sm:p-8">
                            <form
                                data-rate-form
                                data-rate-target="#calc-result"
                                data-rate-cta="true"
                                aria-labelledby="calc-heading"
                                data-endpoint="{{ url('/api/ongkir') }}"
                                data-create-url="{{ route('shipments.create') }}"
                                class="space-y-6"
                            >
                                <div class="space-y-5">
                                    <x-select-field
                                        name="zone_id"
                                        label="Zona tujuan"
                                        :options="$zoneOptions"
                                        placeholder="Pilih zona tujuan pengiriman"
                                        required
                                    />

                                    <x-field
                                        name="actual_weight"
                                        label="Berat aktual (kg)"
                                        type="number"
                                        step="0.01"
                                        min="0.01"
                                        max="1000"
                                        inputmode="decimal"
                                        placeholder="Contoh: 2.3"
                                        hint="Dapat berupa pecahan desimal (contoh: 1.25 atau 2.3)."
                                        required
                                    />

                                    <div>
                                        <label class="block text-sm font-semibold text-neutral-900 mb-2">
                                            Dimensi paket (cm) <span class="text-red-500">*</span>
                                        </label>
                                        <div class="grid grid-cols-3 gap-3">
                                            <x-field name="length" label="Panjang" type="number" step="0.01" min="0.01" max="1000" inputmode="decimal" placeholder="P (cm)" required />
                                            <x-field name="width" label="Lebar" type="number" step="0.01" min="0.01" max="1000" inputmode="decimal" placeholder="L (cm)" required />
                                            <x-field name="height" label="Tinggi" type="number" step="0.01" min="0.01" max="1000" inputmode="decimal" placeholder="T (cm)" required />
                                        </div>
                                    </div>
                                </div>

                                <button class="w-full bg-black text-white py-3.5 px-6 rounded-full font-semibold text-sm hover:bg-neutral-800 transition" type="submit">
                                    Hitung ongkir
                                </button>
                            </form>
                        </div>

                        <div class="lg:col-span-5 space-y-6">
                            <div class="bg-neutral-50 border border-neutral-200 rounded-2xl p-6 sm:p-7">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 id="calc-heading" class="text-base font-semibold text-black">Rincian Perhitungan</h3>
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-neutral-500">
                                        Hasil Estimasi
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </span>
                                </div>
                                
                                <div
                                    class="calc-result"
                                    id="calc-result"
                                    aria-live="polite"
                                    data-empty-message="Isi zona, berat, dan dimensi di kalkulator. Rincian muncul di sini."
                                >
                                    <p class="calc-empty text-sm text-[#4B5563] py-6 text-center">
                                        Isi zona, berat, dan dimensi di kalkulator. Rincian muncul di sini.
                                    </p>
                                </div>
                            </div>

                            <div class="border border-neutral-200 rounded-2xl p-6 text-xs text-[#4B5563] space-y-2">
                                <strong class="text-black block text-sm">Ketentuan Perhitungan:</strong>
                                <p>• Berat volumetrik dihitung dengan rumus: <code>(P × L × T) / 6000</code>.</p>
                                <p>• Berat dikenakan diambil dari nilai tertinggi antara berat aktual dan volumetrik.</p>
                                <p>• Pembulatan ke atas per kilogram penuh berikutnya (contoh: 1.01 kg dihitung 2 kg).</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="w-full border-t border-neutral-100 px-6 sm:px-10 lg:px-12 py-16 sm:py-24 bg-neutral-50" id="lacak">
                <div class="max-w-4xl mx-auto text-center">
                    <span class="text-xs font-bold tracking-widest uppercase text-neutral-400">Pelacakan Publik</span>
                    <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-black mt-2">Punya nomor resi?</h2>
                    <p class="text-[#4B5563] text-base mt-2 max-w-xl mx-auto">
                        Masukkan 16 karakter nomor resi untuk melihat posisi paket dan seluruh riwayat pengiriman secara langsung tanpa perlu login.
                    </p>

                    <form class="mt-8 max-w-xl mx-auto flex flex-col sm:flex-row gap-3" method="GET" action="{{ route('track.index') }}" role="search">
                        <label class="sr-only" for="resi">Nomor resi</label>
                        <input
                            class="flex-1 min-h-[50px] px-5 border border-neutral-300 rounded-full font-mono text-base text-black placeholder-neutral-400 focus:outline-none focus:border-black focus:ring-1 focus:ring-black bg-white"
                            id="resi"
                            name="resi"
                            type="text"
                            autocomplete="off"
                            spellcheck="false"
                            maxlength="24"
                            placeholder="KC20261123000001"
                            required
                        />
                        <button class="bg-black text-white px-8 py-3.5 rounded-full font-semibold text-sm hover:bg-neutral-800 transition" type="submit">
                            Lacak
                        </button>
                    </form>
                    <p class="text-xs text-neutral-500 mt-3">Format resi: KC diikuti 14 digit angka tanggal dan nomor urut.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-14 text-left">
                        <div class="bg-white border border-neutral-200 rounded-xl p-5">
                            <span class="text-xs font-bold text-neutral-400 uppercase tracking-wider block mb-1">01</span>
                            <strong class="text-black text-sm block">Tanpa Login</strong>
                            <p class="text-xs text-[#4B5563] mt-1 leading-relaxed">Pengirim dan penerima dapat melacak resi kapan pun secara publik.</p>
                        </div>
                        <div class="bg-white border border-neutral-200 rounded-xl p-5">
                            <span class="text-xs font-bold text-neutral-400 uppercase tracking-wider block mb-1">02</span>
                            <strong class="text-black text-sm block">Riwayat Kronologis</strong>
                            <p class="text-xs text-[#4B5563] mt-1 leading-relaxed">Setiap perpindahan tercatat lengkap dengan waktu dan nama kurir penanggung jawab.</p>
                        </div>
                        <div class="bg-white border border-neutral-200 rounded-xl p-5">
                            <span class="text-xs font-bold text-neutral-400 uppercase tracking-wider block mb-1">03</span>
                            <strong class="text-black text-sm block">Privasi Terjaga</strong>
                            <p class="text-xs text-[#4B5563] mt-1 leading-relaxed">Nomor telepon dan alamat lengkap tidak dipublikasikan pada halaman pelacakan.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="w-full border-t border-neutral-100 px-6 sm:px-10 lg:px-12 py-16 sm:py-24 bg-white" id="alur">
                <div class="max-w-6xl mx-auto">
                    <div class="max-w-2xl mb-12">
                        <span class="text-xs font-bold tracking-widest uppercase text-neutral-400">Transparansi Status</span>
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-black mt-2">Lima status, satu arah.</h2>
                        <p class="text-[#4B5563] text-base mt-2">
                            Setiap pembaruan menambah satu entri riwayat berisi status, waktu, nama kurir, dan catatan.
                            Status tidak bisa mundur, melompat, atau diulang.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        @foreach (\App\Enums\ShipmentStatus::cases() as $status)
                            <div class="border border-neutral-200 rounded-xl p-5 flex flex-col justify-between hover:border-black transition">
                                <div>
                                    <span class="text-xs font-bold font-mono text-neutral-400 block mb-2">
                                        {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} · {{ $status->value }}
                                    </span>
                                    <h3 class="text-base font-bold text-black mb-2">{{ $status->label() }}</h3>
                                    <p class="text-xs text-[#4B5563] leading-relaxed">{{ $status->description() }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section class="w-full border-t border-neutral-100 px-6 sm:px-10 lg:px-12 py-16 sm:py-24 bg-neutral-50" id="tarif">
                <div class="max-w-6xl mx-auto">
                    <div class="max-w-2xl mb-10">
                        <span class="text-xs font-bold tracking-widest uppercase text-neutral-400">Jangkauan & Harga</span>
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-black mt-2">Tarif per zona.</h2>
                        <p class="text-[#4B5563] text-base mt-2">
                            Berat dikenakan diambil dari nilai terbesar antara berat aktual dan berat volumetrik, lalu dibulatkan ke atas per kilogram.
                        </p>
                    </div>

                    <div class="bg-white border border-neutral-200 rounded-2xl overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <caption class="sr-only">Tarif pengiriman per zona</caption>
                            <thead>
                                <tr class="border-b border-neutral-200 bg-neutral-100/60 text-xs font-semibold uppercase tracking-wider text-neutral-600">
                                    <th scope="col" class="py-4 px-6">Zona</th>
                                    <th scope="col" class="py-4 px-6">Kota Terjangkau</th>
                                    <th scope="col" class="py-4 px-6 text-right">Tarif</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100 text-sm">
                                @foreach ($zones as $zone)
                                    <tr class="hover:bg-neutral-50 transition">
                                        <th scope="row" class="py-4 px-6 font-semibold text-black whitespace-nowrap">{{ $zone->name }}</th>
                                        <td class="py-4 px-6 text-[#4B5563]">{{ $zone->cities }}</td>
                                        <td class="py-4 px-6 text-right font-mono font-semibold text-black whitespace-nowrap">
                                            {{ \App\Support\Format::rupiah($zone->rate_per_kg) }} / kg
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <p class="text-xs text-neutral-500 mt-4">
                        Tarif mengikuti konfigurasi zona aktif. Pembulatan dilakukan per kilogram penuh.
                    </p>
                </div>
            </section>

            <footer class="w-full border-t border-neutral-200 px-6 sm:px-10 lg:px-12 py-12 bg-white" id="bantuan">
                <div class="max-w-6xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="space-y-2 max-w-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                            </svg>
                            <span class="text-sm font-black tracking-[0.2em] uppercase text-black">KirimCepat</span>
                        </div>
                        <p class="text-xs text-[#4B5563] leading-relaxed">
                            Ekosistem pengiriman paket Jawa Timur. Ongkir presisi, resi otomatis, dan riwayat status transparan dalam satu platform.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-8 text-xs font-medium text-neutral-700">
                        <div class="space-y-2">
                            <strong class="text-black block text-xs uppercase tracking-wider mb-1">Layanan</strong>
                            <div><a href="{{ route('shipments.create') }}" class="hover:text-black">Buat Pengiriman</a></div>
                            <div><a href="#ongkir" class="hover:text-black">Cek Ongkir</a></div>
                            <div><a href="#lacak" class="hover:text-black">Lacak Resi</a></div>
                        </div>

                        <div class="space-y-2">
                            <strong class="text-black block text-xs uppercase tracking-wider mb-1">Informasi</strong>
                            <div><a href="#alur" class="hover:text-black">Alur Status</a></div>
                            <div><a href="#tarif" class="hover:text-black">Daftar Tarif Zona</a></div>
                            <div><a href="{{ url('/health') }}" class="hover:text-black">Status Sistem</a></div>
                        </div>

                        <div class="space-y-2">
                            <strong class="text-black block text-xs uppercase tracking-wider mb-1">Kurir</strong>
                            <div><a href="{{ route('kurir.masuk') }}" class="hover:text-black">Portal Masuk Kurir</a></div>
                            @auth
                                <div><a href="{{ route('kurir.dashboard') }}" class="hover:text-black">Panel Dashboard</a></div>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="max-w-6xl mx-auto mt-10 pt-6 border-t border-neutral-100 flex flex-col sm:flex-row justify-between items-center text-xs text-neutral-400 gap-3">
                    <p>Waktu operasional dan sistem ditampilkan dalam WIB.</p>
                    <p>&copy; {{ date('Y') }} KirimCepat. Hak Cipta Dilindungi.</p>
                </div>
            </footer>

        </div>
    </div>
</x-layout>
