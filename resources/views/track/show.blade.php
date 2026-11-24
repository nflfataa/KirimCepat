@php
    $shipment->loadMissing(['zone', 'histories.courier']);
    $latest = $shipment->histories->last();
@endphp

<x-layout
    title="Resi {{ $shipment->tracking_number }}"
    description="Status dan riwayat paket {{ $shipment->tracking_number }}."
>
    <div class="page-head">
        <div class="wrap">
            <nav class="crumbs" aria-label="Remah roti">
                <a href="{{ route('home') }}">Beranda</a>
                <span aria-hidden="true"> / </span>
                <a href="{{ route('track.index') }}">Lacak resi</a>
                <span aria-hidden="true"> / </span>
                <span aria-current="page">{{ $shipment->tracking_number }}</span>
            </nav>

            <h1>Lacak resi</h1>
            <p class="lead">
                Status sekarang dan seluruh riwayat paket
                <span class="mono">{{ $shipment->tracking_number }}</span>.
            </p>
        </div>
    </div>

    <div class="wrap track-layout">
        <div class="track-main">
            <section class="track-card" aria-labelledby="track-status-title">
                <div class="track-card-head">
                    <div>
                        <span class="label">Nomor resi</span>
                        <span class="resi">{{ $shipment->tracking_number }}</span>
                    </div>
                    <button class="btn btn-outline btn-small" type="button" data-copy="{{ $shipment->tracking_number }}">
                        Salin resi
                    </button>
                </div>

                <div class="track-current">
                    <div>
                        <x-status-pill :status="$shipment->current_status" />
                        <h2 id="track-status-title">{{ $shipment->current_status->label() }}</h2>
                        <p>{{ $shipment->current_status->description() }}</p>
                    </div>
                    <p class="track-updated">
                        Diperbarui<br>
                        {{ \App\Support\Format::dateTime($latest->created_at) }}
                    </p>
                </div>

                <div class="track-route">
                    <x-route-line :status="$shipment->current_status" />
                </div>
            </section>

            <section class="panel panel-pad" aria-labelledby="riwayat-title">
                <h2 id="riwayat-title">Riwayat status</h2>
                <ol class="history">
                    @foreach ($shipment->histories as $history)
                        <li class="{{ $loop->last ? 'is-latest' : '' }}">
                            <span class="history-time">{{ \App\Support\Format::dateTime($history->created_at) }}</span>
                            <p class="history-status">
                                {{ $history->status->label() }}
                                <span class="mono">({{ $history->status->value }})</span>
                            </p>
                            <p class="history-note">
                                {{ $history->courier?->name ?? 'Sistem' }}
                                @if ($history->notes)
                                    — {{ $history->notes }}
                                @endif
                            </p>
                        </li>
                    @endforeach
                </ol>
            </section>
        </div>

        <aside class="track-side">
            <section class="panel panel-pad" aria-labelledby="rincian-title">
                <h2 id="rincian-title">Rincian paket</h2>
                <dl class="side-list">
                    <div>
                        <dt>Pengirim</dt>
                        <dd>{{ $shipment->sender_name }}</dd>
                    </div>
                    <div>
                        <dt>Penerima</dt>
                        <dd>{{ $shipment->receiver_name }}</dd>
                    </div>
                    <div>
                        <dt>Zona tujuan</dt>
                        <dd>{{ $shipment->zone->name }}</dd>
                    </div>
                    <div>
                        <dt>Berat dikenakan</dt>
                        <dd class="mono">{{ \App\Support\Format::weight($shipment->chargeable_weight, 0) }}</dd>
                    </div>
                    <div>
                        <dt>Ongkir</dt>
                        <dd class="mono">{{ \App\Support\Format::rupiah($shipment->total_cost) }}</dd>
                    </div>
                    <div>
                        <dt>Dibuat</dt>
                        <dd>{{ \App\Support\Format::dateTime($shipment->created_at) }}</dd>
                    </div>
                </dl>

                <p class="hint track-privacy">
                    Alamat dan nomor telepon pengirim maupun penerima tidak ditampilkan di halaman publik.
                </p>
            </section>

            <section class="panel panel-pad">
                <h2>Lacak resi lain</h2>
                <p class="hint track-privacy">
                    Gunakan halaman lacak untuk memeriksa nomor resi lain tanpa keluar dari akun.
                </p>
                <a class="btn btn-outline btn-small track-side-link" href="{{ route('track.index') }}">Buka halaman lacak</a>
            </section>
        </aside>
    </div>
</x-layout>
