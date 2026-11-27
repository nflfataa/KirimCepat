@php
    $zoneOptions = $zones->mapWithKeys(fn ($zone) => [
        $zone->id => $zone->name.' — Rp'.number_format($zone->rate_per_kg, 0, ',', '.').'/kg',
    ]);
@endphp

<x-layout
    :landing="true"
    title="KirimCepat"
    description="Pembuatan resi otomatis, perhitungan ongkir presisi, dan pelacakan real-time – semua dalam satu platform yang simpel."
>
    <div class="min-h-screen bg-[#E5E5E5] flex justify-center p-3 sm:p-6 lg:p-8">
        <div class="app-container w-full max-w-[1440px] bg-white rounded-[1.5rem] overflow-hidden shadow-[0_10px_40px_rgba(0,0,0,0.06)] flex flex-col">
            
            <header class="w-full px-6 sm:px-10 lg:px-12 py-7 sm:py-8 border-b border-neutral-100">
                <nav class="relative flex items-center justify-between min-h-[46px]" aria-label="Navigasi landing">
                    <div class="hidden md:flex items-center space-x-7 text-sm font-medium text-black">
                        <a href="#lacak" class="hover:opacity-70 transition">Lacak Paket</a>
                        <a href="#tarif" class="hover:opacity-70 transition">Tarif Zona</a>
                        <a href="#bantuan" class="hover:opacity-70 transition">Bantuan</a>
                    </div>

                    <a href="{{ route('home') }}" class="absolute left-1/2 -translate-x-1/2 flex flex-col items-center justify-center group focus:outline-none z-10" aria-label="KirimCepat Beranda">
                        <svg class="w-6 h-6 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                        </svg>
                        <span class="text-xs sm:text-sm font-black tracking-[0.24em] uppercase text-black mt-1">KirimCepat</span>
                    </a>

                    <div class="flex items-center space-x-4 sm:space-x-6 text-sm font-medium ml-auto">
                        <div class="hidden sm:inline-flex items-center gap-1 text-black cursor-default select-none">
                            <span>ID</span>
                            <svg class="w-3 h-3 text-neutral-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </div>

                        @auth
                            <a href="{{ route('kurir.dashboard') }}" class="bg-black text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-full text-xs sm:text-sm font-medium hover:bg-neutral-800 transition shadow-sm">
                                Panel Kurir
                            </a>
                        @else
                            <a href="{{ route('kurir.masuk') }}" class="bg-black text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-full text-xs sm:text-sm font-medium hover:bg-neutral-800 transition shadow-sm">
                                Masuk Kurir
                            </a>
                        @endauth
                    </div>
                </nav>

                <div class="flex md:hidden items-center justify-around mt-4 pt-3 border-t border-neutral-100 text-xs font-medium text-neutral-800">
                    <a href="#ongkir" class="hover:text-black">Cek Ongkir</a>
                    <a href="#lacak" class="hover:text-black">Lacak Paket</a>
                    <a href="#tarif" class="hover:text-black">Tarif Zona</a>
                    <a href="#alur" class="hover:text-black">Alur Paket</a>
                </div>
            </header>

            <section class="mt-14 sm:mt-24 lg:mt-28 mb-8 sm:mb-12 px-6 text-center flex flex-col items-center">
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

            <section class="w-full px-4 sm:px-8 lg:px-12 py-16 sm:py-24 min-h-[85vh] sm:min-h-[90vh] flex flex-col justify-center items-center scroll-mt-0 animate-slide-delay-3 bg-white" id="lacak">
                <div class="max-w-3xl w-full mx-auto text-center" data-scroll-center>
                    <span class="text-xs font-bold tracking-widest uppercase text-neutral-400">Pelacakan Publik</span>
                    <h2 class="text-3xl sm:text-4xl lg:text-[2.6rem] font-bold tracking-tight text-black mt-2">Punya nomor resi?</h2>
                    <p class="text-[#4B5563] text-base sm:text-lg mt-2.5 max-w-xl mx-auto font-normal leading-relaxed">
                        Masukkan 16 karakter nomor resi untuk melihat posisi paket dan seluruh riwayat pengiriman secara langsung tanpa perlu login.
                    </p>

                    <form class="mt-7 max-w-xl mx-auto flex flex-col sm:flex-row gap-3" method="GET" action="{{ route('track.index') }}" role="search">
                        <label class="sr-only" for="resi">Nomor resi</label>
                        <input
                            class="flex-1 min-h-[50px] sm:min-h-[52px] px-5 sm:px-6 border border-neutral-300 rounded-full font-mono text-base text-black placeholder:text-neutral-400 focus:outline-none focus:border-black focus:ring-1 focus:ring-black bg-white shadow-sm"
                            id="resi"
                            name="resi"
                            type="text"
                            autocomplete="off"
                            spellcheck="false"
                            maxlength="24"
                            placeholder="KC20261123000001"
                            required
                        />
                        <button class="bg-black text-white px-8 py-3.5 rounded-full font-semibold text-sm sm:text-base hover:bg-neutral-800 transition shrink-0 shadow-sm" type="submit">
                            Lacak Paket
                        </button>
                    </form>
                    <p class="text-xs text-neutral-500 mt-3">Format resi: KC diikuti 14 digit angka tanggal dan nomor urut.</p>
                </div>
            </section>

            <section class="w-full border-t border-neutral-100 px-6 sm:px-10 lg:px-12 pt-12 sm:pt-16 pb-16 sm:pb-24 bg-white" id="ongkir">
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

                                <div class="flex items-center justify-between pt-1 text-xs">
                                    <button
                                        type="button"
                                        id="btn-fill-sample"
                                        class="text-neutral-500 hover:text-black underline font-medium transition cursor-pointer"
                                    >
                                        Coba contoh (2.3 kg · 20×20×20 cm)
                                    </button>
                                    <span class="text-neutral-400">Volumetrik otomatis</span>
                                </div>
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

            <section class="w-full border-t border-neutral-100 px-6 sm:px-10 lg:px-12 py-16 sm:py-24 bg-neutral-50/50" id="tarif">
                <div class="max-w-6xl mx-auto">
                    <div class="max-w-2xl mb-10">
                        <span class="text-xs font-bold tracking-widest uppercase text-neutral-400">Jangkauan & Harga</span>
                        <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-black mt-2">Tarif per zona.</h2>
                        <p class="text-[#4B5563] text-base mt-2">
                            Berat dikenakan diambil dari nilai terbesar antara berat aktual dan berat volumetrik, lalu dibulatkan ke atas per kilogram.
                        </p>
                    </div>

                    <!-- Clean Sharp Non-Rounded Table Card -->
                    <div class="bg-white border border-neutral-200 rounded-none p-6 sm:p-8 lg:p-10 shadow-xs">
                        <!-- Card Header: Title with counter & controls (Sharp) -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 sm:pb-8 border-b border-neutral-100">
                            <div class="flex items-center gap-2.5">
                                <h3 class="text-xl sm:text-2xl font-bold tracking-tight text-neutral-900">
                                    Tarif per zona
                                </h3>
                                <span class="text-sm font-normal text-neutral-400">
                                    {{ count($zones) }}
                                </span>
                            </div>

                            <div class="flex items-center gap-3">
                                <!-- Sort dropdown (Sharp) -->
                                <div class="flex items-center gap-2">
                                    <label for="sort-zones" class="text-xs text-neutral-400 font-medium">Sort:</label>
                                    <div class="relative">
                                        <select
                                            id="sort-zones"
                                            class="appearance-none bg-white hover:bg-neutral-50 border border-neutral-200 text-neutral-800 text-xs font-semibold rounded-none pl-3 pr-8 py-2 outline-none cursor-pointer transition focus:ring-1 focus:ring-black focus:border-black"
                                        >
                                            <option value="default">Default</option>
                                            <option value="price-asc">Tarif Terendah</option>
                                            <option value="price-desc">Tarif Tertinggi</option>
                                            <option value="name-asc">Nama Zona</option>
                                        </select>
                                        <svg class="w-3.5 h-3.5 text-neutral-400 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Line / Grid View Toggle (Sharp) -->
                                <div class="hidden sm:flex items-center p-1 bg-neutral-100 border border-neutral-200/80 rounded-none text-xs font-medium" role="tablist" aria-label="Tampilan tabel">
                                    <button
                                        type="button"
                                        id="btn-view-line"
                                        class="flex items-center gap-1.5 bg-black text-white px-3 py-1.5 rounded-none shadow-sm transition cursor-pointer"
                                        aria-selected="true"
                                        aria-controls="zone-table-container"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                                            <line x1="3" y1="6" x2="21" y2="6"></line>
                                            <line x1="3" y1="12" x2="21" y2="12"></line>
                                            <line x1="3" y1="18" x2="21" y2="18"></line>
                                        </svg>
                                        <span>Line</span>
                                    </button>
                                    <button
                                        type="button"
                                        id="btn-view-grid"
                                        class="flex items-center gap-1.5 text-neutral-500 hover:text-black px-3 py-1.5 transition cursor-pointer rounded-none"
                                        aria-selected="false"
                                        aria-controls="zone-grid-container"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                                            <rect x="3" y="3" width="7" height="7"></rect>
                                            <rect x="14" y="3" width="7" height="7"></rect>
                                            <rect x="14" y="14" width="7" height="7"></rect>
                                            <rect x="3" y="14" width="7" height="7"></rect>
                                        </svg>
                                        <span>Grid</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Table View (Line, Sharp) -->
                        <div id="zone-table-container" class="overflow-x-auto -mx-6 sm:-mx-8 lg:-mx-10">
                            <table class="w-full text-left border-collapse min-w-[620px]">
                                <caption class="sr-only">Tarif pengiriman per zona</caption>
                                <thead>
                                    <tr class="text-xs font-normal text-neutral-400 border-b border-neutral-100">
                                        <th scope="col" class="pb-3.5 pt-2 px-6 sm:px-8 font-normal">Zona</th>
                                        <th scope="col" class="pb-3.5 pt-2 px-6 font-normal">Kota Terjangkau</th>
                                        <th scope="col" class="pb-3.5 pt-2 px-6 font-normal">Tarif per Kg</th>
                                        <th scope="col" class="pb-3.5 pt-2 px-6 font-normal">Status</th>
                                        <th scope="col" class="pb-3.5 pt-2 px-6 sm:px-8 text-right font-normal sr-only">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="zone-table-body" class="divide-y divide-neutral-100 text-sm">
                                    @foreach ($zones as $zone)
                                        <tr
                                            class="hover:bg-neutral-50/80 transition-colors group cursor-default"
                                            data-zone-name="{{ $zone->name }}"
                                            data-zone-rate="{{ $zone->rate_per_kg }}"
                                        >
                                            <th scope="row" class="py-4 sm:py-4.5 px-6 sm:px-8 font-semibold text-neutral-900 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <svg class="w-4 h-4 text-neutral-400 group-hover:text-black transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <circle cx="12" cy="12" r="10" />
                                                        <line x1="2" y1="12" x2="22" y2="12" />
                                                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                                    </svg>
                                                    <span>{{ $zone->name }}</span>
                                                </div>
                                            </th>
                                            <td class="py-4 sm:py-4.5 px-6 text-neutral-500 leading-relaxed">
                                                {{ $zone->cities }}
                                            </td>
                                            <td class="py-4 sm:py-4.5 px-6 whitespace-nowrap">
                                                <span class="font-mono font-bold text-neutral-900">
                                                    {{ \App\Support\Format::rupiah($zone->rate_per_kg) }}
                                                </span>
                                                <span class="text-neutral-400 text-xs font-normal ml-0.5">/ kg</span>
                                            </td>
                                            <td class="py-4 sm:py-4.5 px-6 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-none text-[10px] font-bold tracking-wider uppercase bg-neutral-100 text-neutral-800 border border-neutral-200">
                                                    AKTIF
                                                </span>
                                            </td>
                                            <td class="py-4 sm:py-4.5 px-6 sm:px-8 text-right whitespace-nowrap">
                                                <svg class="w-4 h-4 text-neutral-300 group-hover:text-neutral-700 group-hover:translate-x-0.5 transition inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Grid View (Alternate view, Sharp) -->
                        <div id="zone-grid-container" class="hidden pt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($zones as $zone)
                                <div
                                    class="p-5 rounded-none border border-neutral-200 bg-white hover:border-black/50 transition-all flex flex-col justify-between group"
                                    data-zone-name="{{ $zone->name }}"
                                    data-zone-rate="{{ $zone->rate_per_kg }}"
                                >
                                    <div>
                                        <div class="flex items-center justify-between gap-3 mb-2">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-neutral-400 group-hover:text-black transition shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <line x1="2" y1="12" x2="22" y2="12" />
                                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                                </svg>
                                                <h4 class="font-bold text-neutral-900 text-base">{{ $zone->name }}</h4>
                                            </div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-none text-[10px] font-bold tracking-wider uppercase bg-neutral-100 text-neutral-800 border border-neutral-200">
                                                AKTIF
                                            </span>
                                        </div>
                                        <p class="text-xs text-neutral-500 line-clamp-2 leading-relaxed">
                                            {{ $zone->cities }}
                                        </p>
                                    </div>
                                    <div class="pt-4 mt-4 border-t border-neutral-100 flex items-center justify-between">
                                        <div>
                                            <span class="text-[11px] text-neutral-400 block">Tarif pengiriman</span>
                                            <span class="font-mono font-bold text-neutral-900 text-sm">
                                                {{ \App\Support\Format::rupiah($zone->rate_per_kg) }}
                                            </span>
                                            <span class="text-neutral-400 text-xs">/ kg</span>
                                        </div>
                                        <svg class="w-4 h-4 text-neutral-300 group-hover:text-black group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <p class="text-xs text-neutral-500 mt-4">
                        Tarif mengikuti konfigurasi zona aktif. Pembulatan dilakukan per kilogram penuh.
                    </p>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const btnLine = document.getElementById('btn-view-line');
                        const btnGrid = document.getElementById('btn-view-grid');
                        const lineView = document.getElementById('zone-table-container');
                        const gridView = document.getElementById('zone-grid-container');
                        const sortSelect = document.getElementById('sort-zones');

                        if (btnLine && btnGrid && lineView && gridView) {
                            btnLine.addEventListener('click', function () {
                                lineView.classList.remove('hidden');
                                gridView.classList.add('hidden');
                                btnLine.classList.add('bg-black', 'text-white', 'shadow-sm');
                                btnLine.classList.remove('text-neutral-500');
                                btnLine.setAttribute('aria-selected', 'true');
                                btnGrid.classList.remove('bg-black', 'text-white', 'shadow-sm');
                                btnGrid.classList.add('text-neutral-500');
                                btnGrid.setAttribute('aria-selected', 'false');
                            });

                            btnGrid.addEventListener('click', function () {
                                lineView.classList.add('hidden');
                                gridView.classList.remove('hidden');
                                btnGrid.classList.add('bg-black', 'text-white', 'shadow-sm');
                                btnGrid.classList.remove('text-neutral-500');
                                btnGrid.setAttribute('aria-selected', 'true');
                                btnLine.classList.remove('bg-black', 'text-white', 'shadow-sm');
                                btnLine.classList.add('text-neutral-500');
                                btnLine.setAttribute('aria-selected', 'false');
                            });
                        }

                        if (sortSelect) {
                            sortSelect.addEventListener('change', function () {
                                const criteria = sortSelect.value;
                                const tableBody = document.getElementById('zone-table-body');
                                if (!tableBody) return;

                                const rows = Array.from(tableBody.querySelectorAll('tr'));
                                rows.sort((a, b) => {
                                    if (criteria === 'price-asc') {
                                        return Number(a.dataset.zoneRate) - Number(b.dataset.zoneRate);
                                    } else if (criteria === 'price-desc') {
                                        return Number(b.dataset.zoneRate) - Number(a.dataset.zoneRate);
                                    } else if (criteria === 'name-asc') {
                                        return a.dataset.zoneName.localeCompare(b.dataset.zoneName);
                                    }
                                    return 0;
                                });
                                rows.forEach(row => tableBody.appendChild(row));

                                if (gridView) {
                                    const cards = Array.from(gridView.children);
                                    cards.sort((a, b) => {
                                        if (criteria === 'price-asc') {
                                            return Number(a.dataset.zoneRate) - Number(b.dataset.zoneRate);
                                        } else if (criteria === 'price-desc') {
                                            return Number(b.dataset.zoneRate) - Number(a.dataset.zoneRate);
                                        } else if (criteria === 'name-asc') {
                                            return a.dataset.zoneName.localeCompare(b.dataset.zoneName);
                                        }
                                        return 0;
                                    });
                                    cards.forEach(card => gridView.appendChild(card));
                                }
                            });
                        }
                    });
                </script>
            </section>

            <section class="w-full border-t border-neutral-100 px-4 sm:px-8 lg:px-12 py-16 sm:py-24 bg-white" id="alur">
                <div class="max-w-6xl mx-auto">
                    <div class="text-center max-w-2xl mx-auto mb-12 sm:mb-16">
                        <span class="text-xs font-bold tracking-widest uppercase text-neutral-400">Transparansi Status</span>
                        <h2 class="text-3xl sm:text-4xl lg:text-[2.6rem] font-bold tracking-tight text-black mt-2">
                            Status Pengiriman Paket
                        </h2>
                        <p class="text-[#4B5563] text-base sm:text-lg mt-3 font-normal leading-relaxed">
                            Pantau pergerakan pengiriman dengan kepastian estimasi waktu. Lima status berurutan satu arah tanpa lompatan atau pembatalan sepihak.
                        </p>
                    </div>

                    <!-- Delivery Status Progress Tracker (Reference Style) -->
                    <div class="bg-white p-2 sm:p-6 lg:p-8">
                        
                        <!-- Desktop / Tablet Horizontal Stepper -->
                        <div class="hidden md:block">
                            <div class="relative flex items-start justify-between">
                                <!-- Connecting Line behind circles -->
                                <div class="absolute top-6 lg:top-7 left-[10%] right-[10%] h-[2px] bg-black -z-0" aria-hidden="true"></div>

                                <!-- Step 1: Pesanan Dibuat -->
                                <div class="relative z-10 flex flex-col items-center text-center w-1/5 px-2">
                                    <div class="w-12 h-12 lg:w-14 lg:h-14 rounded-full bg-black border-2 border-black flex items-center justify-center shadow-sm">
                                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm lg:text-base font-bold text-black mt-4">
                                        {{ \App\Enums\ShipmentStatus::DIBUAT->label() }}
                                    </h3>
                                    <p class="text-xs text-neutral-500 mt-1 leading-snug">
                                        Resi terbit otomatis<br>
                                        <span class="font-mono text-[11px] text-neutral-400">Tahap 01</span>
                                    </p>
                                </div>

                                <!-- Step 2: Dijemput Kurir -->
                                <div class="relative z-10 flex flex-col items-center text-center w-1/5 px-2">
                                    <div class="w-12 h-12 lg:w-14 lg:h-14 rounded-full bg-black border-2 border-black flex items-center justify-center shadow-sm">
                                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm lg:text-base font-bold text-black mt-4">
                                        {{ \App\Enums\ShipmentStatus::DIJEMPUT->label() }}
                                    </h3>
                                    <p class="text-xs text-neutral-500 mt-1 leading-snug">
                                        Paket diambil kurir<br>
                                        <span class="font-mono text-[11px] text-neutral-400">Tahap 02</span>
                                    </p>
                                </div>

                                <!-- Step 3: Dalam Transit -->
                                <div class="relative z-10 flex flex-col items-center text-center w-1/5 px-2">
                                    <div class="w-12 h-12 lg:w-14 lg:h-14 rounded-full bg-black border-2 border-black flex items-center justify-center shadow-sm">
                                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm lg:text-base font-bold text-black mt-4">
                                        {{ \App\Enums\ShipmentStatus::TRANSIT->label() }}
                                    </h3>
                                    <p class="text-xs text-neutral-500 mt-1 leading-snug">
                                        Sortir hub Trans-Jawa<br>
                                        <span class="font-mono text-[11px] text-neutral-400">Tahap 03</span>
                                    </p>
                                </div>

                                <!-- Step 4: Sedang Diantar (In-Progress Clock Icon from Reference) -->
                                <div class="relative z-10 flex flex-col items-center text-center w-1/5 px-2">
                                    <div class="w-12 h-12 lg:w-14 lg:h-14 rounded-full bg-black border-2 border-black flex items-center justify-center shadow-sm">
                                        <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm lg:text-base font-bold text-black mt-4">
                                        {{ \App\Enums\ShipmentStatus::DIANTAR->label() }}
                                    </h3>
                                    <p class="text-xs text-neutral-500 mt-1 leading-snug">
                                        Kurir menuju alamat<br>
                                        <span class="font-mono text-[11px] text-neutral-400">Tahap 04</span>
                                    </p>
                                </div>

                                <!-- Step 5: Diterima (Expected Destination) -->
                                <div class="relative z-10 flex flex-col items-center text-center w-1/5 px-2">
                                    <div class="w-12 h-12 lg:w-14 lg:h-14 rounded-full bg-white border-2 border-black flex items-center justify-center shadow-sm">
                                        <svg class="w-6 h-6 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                            <path d="m3.3 7 8.7 5 8.7-5" />
                                            <path d="M12 22V12" />
                                            <path d="m7.5 4.27 9 5.15" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm lg:text-base font-bold text-black mt-4">
                                        {{ \App\Enums\ShipmentStatus::DITERIMA->label() }}
                                    </h3>
                                    <p class="text-xs text-neutral-500 mt-1 leading-snug">
                                        Terkonfirmasi penerima<br>
                                        <span class="font-mono text-[11px] text-neutral-400">Tahap 05</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Vertical Stepper -->
                        <div class="md:hidden space-y-6 relative pl-4 before:absolute before:top-4 before:bottom-4 before:left-[27px] before:w-[2px] before:bg-black">
                            <!-- Step 1 -->
                            <div class="relative flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-black border-2 border-black flex items-center justify-center shrink-0 z-10 shadow-sm">
                                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                                <div class="pt-0.5">
                                    <span class="font-mono text-[11px] text-neutral-400 uppercase tracking-wider block">Tahap 01 · DIBUAT</span>
                                    <h3 class="text-base font-bold text-black">{{ \App\Enums\ShipmentStatus::DIBUAT->label() }}</h3>
                                    <p class="text-xs text-[#4B5563] mt-1">{{ \App\Enums\ShipmentStatus::DIBUAT->description() }}</p>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="relative flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-black border-2 border-black flex items-center justify-center shrink-0 z-10 shadow-sm">
                                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                                <div class="pt-0.5">
                                    <span class="font-mono text-[11px] text-neutral-400 uppercase tracking-wider block">Tahap 02 · DIJEMPUT</span>
                                    <h3 class="text-base font-bold text-black">{{ \App\Enums\ShipmentStatus::DIJEMPUT->label() }}</h3>
                                    <p class="text-xs text-[#4B5563] mt-1">{{ \App\Enums\ShipmentStatus::DIJEMPUT->description() }}</p>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="relative flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-black border-2 border-black flex items-center justify-center shrink-0 z-10 shadow-sm">
                                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                                <div class="pt-0.5">
                                    <span class="font-mono text-[11px] text-neutral-400 uppercase tracking-wider block">Tahap 03 · TRANSIT</span>
                                    <h3 class="text-base font-bold text-black">{{ \App\Enums\ShipmentStatus::TRANSIT->label() }}</h3>
                                    <p class="text-xs text-[#4B5563] mt-1">{{ \App\Enums\ShipmentStatus::TRANSIT->description() }}</p>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="relative flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-black border-2 border-black flex items-center justify-center shrink-0 z-10 shadow-sm">
                                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                </div>
                                <div class="pt-0.5">
                                    <span class="font-mono text-[11px] text-neutral-400 uppercase tracking-wider block">Tahap 04 · DIANTAR</span>
                                    <h3 class="text-base font-bold text-black">{{ \App\Enums\ShipmentStatus::DIANTAR->label() }}</h3>
                                    <p class="text-xs text-[#4B5563] mt-1">{{ \App\Enums\ShipmentStatus::DIANTAR->description() }}</p>
                                </div>
                            </div>

                            <!-- Step 5 -->
                            <div class="relative flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-white border-2 border-black flex items-center justify-center shrink-0 z-10 shadow-sm">
                                    <svg class="w-5 h-5 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                        <path d="m3.3 7 8.7 5 8.7-5" />
                                        <path d="M12 22V12" />
                                        <path d="m7.5 4.27 9 5.15" />
                                    </svg>
                                </div>
                                <div class="pt-0.5">
                                    <span class="font-mono text-[11px] text-neutral-400 uppercase tracking-wider block">Tahap 05 · DITERIMA</span>
                                    <h3 class="text-base font-bold text-black">{{ \App\Enums\ShipmentStatus::DITERIMA->label() }}</h3>
                                    <p class="text-xs text-[#4B5563] mt-1">{{ \App\Enums\ShipmentStatus::DITERIMA->description() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                            <div><a href="{{ url('/health') }}" class="hover:text-black"></a></div>
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
