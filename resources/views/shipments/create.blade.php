@php
    $zoneOptions = $zones->mapWithKeys(fn ($zone) => [
        $zone->id => $zone->name.' — Rp'.number_format($zone->rate_per_kg, 0, ',', '.').'/kg',
    ]);
@endphp

<x-layout
    title="Buat pengiriman"
    description="Isi data pengirim, penerima, dan paket. Ongkir dihitung saat pengiriman disimpan dan nomor resi diterbitkan otomatis."
>
    <div class="page-head">
        <div class="wrap">
            <nav class="crumbs" aria-label="Remah roti">
                <a href="{{ route('home') }}">Beranda</a>
                <span aria-hidden="true"> / </span>
                <span aria-current="page">Kirim paket</span>
            </nav>

            <h1>Buat pengiriman</h1>
            <p class="lead">
                Nomor resi dibuat sistem setelah data tersimpan. Ongkir dihitung dari zona tujuan dan berat
                yang dikenakan, bukan dari isian manual.
            </p>
        </div>
    </div>

    <form
        class="wrap form-layout"
        method="POST"
        action="{{ route('shipments.store') }}"
        data-rate-watch
        data-rate-target="#shipment-summary"
        data-endpoint="{{ url('/api/ongkir') }}"
    >
        @csrf

        <div class="form-column">
            @if ($errors->has('tracking'))
                <div class="alert alert-error" role="alert">
                    {{ $errors->first('tracking') }}
                </div>
            @endif

            <section class="form-section" aria-labelledby="pengirim-title">
                <div class="form-section-head">
                    <h2 id="pengirim-title">Pengirim</h2>
                    <p>Data orang yang menyerahkan paket.</p>
                </div>

                <div class="form-fields">
                    <x-field
                        name="sender_name"
                        label="Nama pengirim"
                        autocomplete="name"
                        placeholder="Nama lengkap"
                        required
                    />

                    <x-field
                        name="sender_phone"
                        label="Telepon pengirim"
                        type="tel"
                        autocomplete="tel"
                        placeholder="0812 3456 7890"
                        required
                    />

                    <x-field
                        name="sender_address"
                        label="Alamat pengirim"
                        type="textarea"
                        autocomplete="street-address"
                        placeholder="Jalan, nomor, kelurahan, kota"
                        required
                    />
                </div>
            </section>

            <section class="form-section" aria-labelledby="penerima-title">
                <div class="form-section-head">
                    <h2 id="penerima-title">Penerima</h2>
                    <p>Alamat dan nomor telepon penerima terlindungi secara aman.</p>
                </div>

                <div class="form-fields">
                    <x-field
                        name="receiver_name"
                        label="Nama penerima"
                        autocomplete="name"
                        placeholder="Nama lengkap"
                        required
                    />

                    <x-field
                        name="receiver_phone"
                        label="Telepon penerima"
                        type="tel"
                        autocomplete="tel"
                        placeholder="0812 3456 7890"
                        required
                    />

                    <x-field
                        name="receiver_address"
                        label="Alamat penerima"
                        type="textarea"
                        autocomplete="street-address"
                        placeholder="Jalan, nomor, kelurahan, kota"
                        required
                    />
                </div>
            </section>

            <section class="form-section" aria-labelledby="paket-title">
                <div class="form-section-head">
                    <h2 id="paket-title">Paket dan tujuan</h2>
                    <p>Berat dan dimensi harus lebih dari 0.</p>
                </div>

                <div class="form-fields">
                    <x-select-field
                        name="zone_id"
                        label="Zona tujuan"
                        :options="$zoneOptions"
                        :value="$prefill['zone_id']"
                        placeholder="Pilih zona"
                        required
                    />

                    <div class="field-row">
                        <x-field
                            name="actual_weight"
                            label="Berat aktual (kg)"
                            type="number"
                            step="0.01"
                            min="0.01"
                            max="1000"
                            inputmode="decimal"
                            :value="$prefill['actual_weight']"
                            placeholder="Contoh: 2.3"
                            hint="Berat dari timbangan, boleh pecahan."
                            required
                        />

                        <x-field
                            name="length"
                            label="Panjang (cm)"
                            type="number"
                            step="0.01"
                            min="0.01"
                            max="1000"
                            inputmode="decimal"
                            :value="$prefill['length']"
                            required
                        />
                    </div>

                    <div class="field-row">
                        <x-field
                            name="width"
                            label="Lebar (cm)"
                            type="number"
                            step="0.01"
                            min="0.01"
                            max="1000"
                            inputmode="decimal"
                            :value="$prefill['width']"
                            required
                        />

                        <x-field
                            name="height"
                            label="Tinggi (cm)"
                            type="number"
                            step="0.01"
                            min="0.01"
                            max="1000"
                            inputmode="decimal"
                            :value="$prefill['height']"
                            required
                        />
                    </div>
                </div>
            </section>
        </div>

        <aside class="summary" aria-labelledby="ringkasan-title">
            <div class="summary-head">
                <h2 id="ringkasan-title">Ringkasan ongkir</h2>
                <p>Berat dikenakan dibulatkan ke atas per kg.</p>
            </div>

            <div
                class="summary-body"
                id="shipment-summary"
                aria-live="polite"
                data-empty-message="Isi zona, berat, dan dimensi untuk melihat rincian ongkir."
            >
                <p class="calc-empty">Isi zona, berat, dan dimensi untuk melihat rincian ongkir.</p>
            </div>

            <div class="summary-submit">
                <button class="btn btn-primary" type="submit">Buat pengiriman &amp; terbitkan resi</button>
                <p class="hint">
                    Ongkir dihitung ulang di server saat data disimpan, memakai rumus yang sama dengan ringkasan ini.
                </p>
            </div>
        </aside>
    </form>
</x-layout>
