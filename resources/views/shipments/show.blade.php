@php
    $shipment->loadMissing(['zone', 'histories']);
    $dimensions = \App\Support\Format::number($shipment->length).' × '
        .\App\Support\Format::number($shipment->width).' × '
        .\App\Support\Format::number($shipment->height).' cm';
    $latest = $shipment->histories->last();
@endphp

<x-layout
    title="KirimCepat"
    description="Pengiriman tersimpan. Simpan nomor resi untuk melacak paket."
>
    <div class="wrap wrap-narrow receipt-wrap">
        <div class="receipt-intro">
            <nav class="crumbs" aria-label="Remah roti">
                <a href="{{ route('home') }}">Beranda</a>
                <span aria-hidden="true"> / </span>
                <a href="{{ route('shipments.create') }}">Kirim paket</a>
                <span aria-hidden="true"> / </span>
                <span aria-current="page">Resi terbit</span>
            </nav>

            <h1>Pengiriman tersimpan.</h1>
            <p class="lead">
                Resi {{ $shipment->tracking_number }} sudah terbit. Simpan nomor ini untuk melacak paket.
                Halaman rincian ini berlaku 24 jam; halaman lacak publik tidak menampilkan alamat dan telepon.
            </p>
        </div>

        <article class="ticket">
            <div class="ticket-block ticket-top">
                <div>
                    <span class="label">Nomor resi</span>
                    <span class="resi">{{ $shipment->tracking_number }}</span>
                </div>
                <button class="btn btn-outline btn-small" type="button" data-copy="{{ $shipment->tracking_number }}">
                    Salin resi
                </button>
            </div>

            <div class="ticket-block ticket-grid">
                <div>
                    <span class="label">Status awal</span>
                    <x-status-pill :status="$shipment->current_status" />
                </div>
                <div>
                    <span class="label">Dibuat</span>
                    <span>{{ \App\Support\Format::dateTime($shipment->created_at) }}</span>
                </div>
                <div>
                    <span class="label">Zona tujuan</span>
                    <span>{{ $shipment->zone->name }}</span>
                </div>
                <div>
                    <span class="label">Berat dikenakan</span>
                    <span class="mono">{{ \App\Support\Format::weight($shipment->chargeable_weight, 0) }}</span>
                </div>
            </div>

            <div class="ticket-block ticket-parties">
                <div>
                    <span class="label">Pengirim</span>
                    <p class="party-name">{{ $shipment->sender_name }}</p>
                    <p class="party-line">{{ $shipment->sender_phone }}</p>
                    <p class="party-line">{{ $shipment->sender_address }}</p>
                </div>
                <div>
                    <span class="label">Penerima</span>
                    <p class="party-name">{{ $shipment->receiver_name }}</p>
                    <p class="party-line">{{ $shipment->receiver_phone }}</p>
                    <p class="party-line">{{ $shipment->receiver_address }}</p>
                </div>
            </div>

            <div class="ticket-block">
                <span class="label">Rincian ongkir</span>
                <table class="breakdown">
                    <tbody>
                        <tr>
                            <th scope="row">Berat aktual</th>
                            <td>{{ \App\Support\Format::weight($shipment->actual_weight) }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Berat volumetrik</th>
                            <td>{{ \App\Support\Format::weight($shipment->volumetric_weight) }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Dimensi</th>
                            <td>{{ $dimensions }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Berat dikenakan</th>
                            <td>{{ \App\Support\Format::weight($shipment->chargeable_weight, 0) }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Tarif zona</th>
                            <td>{{ \App\Support\Format::rupiah($shipment->zone->rate_per_kg) }} / kg</td>
                        </tr>
                        <tr>
                            <th scope="row">Total ongkir</th>
                            <td>{{ \App\Support\Format::rupiah($shipment->total_cost) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="ticket-block ticket-actions">
                <a class="btn btn-primary" href="{{ route('track.show', $shipment) }}">Lacak paket</a>
                <a class="btn btn-outline" href="{{ route('shipments.create') }}">Buat pengiriman lain</a>
                <span class="hint">
                    Status terakhir: {{ $latest?->status?->label() ?? $shipment->current_status->label() }}.
                </span>
            </div>
        </article>
    </div>
</x-layout>
