<x-layout
    title="KirimCepat"
    description="Perbarui status paket yang sedang ditangani."
>
    <div class="page-head">
        <div class="wrap">
            <nav class="crumbs" aria-label="Remah roti">
                <a href="{{ route('home') }}">Beranda</a>
                <span aria-hidden="true"> / </span>
                <span aria-current="page">Panel kurir</span>
            </nav>

            <h1>Perbarui status paket</h1>
            <p class="lead">
                Setiap paket hanya bisa maju satu langkah. Semua paket aktif tampil untuk kurir yang masuk;
                MVP belum membagi paket per kurir.
            </p>
            <p class="hint">Masuk sebagai {{ auth()->user()->name }}.</p>
        </div>
    </div>

    <div class="wrap page-body">
        @if (session('status'))
            <div class="alert alert-status" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->has('status'))
            <div class="alert alert-error" role="alert">
                {{ $errors->first('status') }}
            </div>
        @endif

        <div class="section-title-row">
            <h2>Perlu tindakan</h2>
            <span class="count">{{ $active->count() }} paket</span>
        </div>

        @if ($active->isEmpty())
            <div class="empty">
                <strong>Tidak ada paket yang menunggu pembaruan.</strong>
                Paket baru muncul di sini setelah pengiriman dibuat. Paket yang sudah DITERIMA turun ke daftar di bawah.
            </div>
        @else
            <ul class="worklist">
                @foreach ($active as $shipment)
                    @php
                        $next = $shipment->current_status->next();
                    @endphp

                    <li class="job">
                        <div class="job-head">
                            <div>
                                <a class="job-resi" href="{{ route('track.show', $shipment) }}">
                                    {{ $shipment->tracking_number }}
                                </a>
                                <p class="job-route">{{ $shipment->zone->name }}</p>
                            </div>
                            <x-status-pill :status="$shipment->current_status" />
                        </div>

                        <div class="job-body">
                            <dl>
                                <div>
                                    <dt>Penerima</dt>
                                    <dd>{{ $shipment->receiver_name }} · {{ $shipment->receiver_phone }}</dd>
                                </div>
                                <div>
                                    <dt>Alamat penerima</dt>
                                    <dd>{{ $shipment->receiver_address }}</dd>
                                </div>
                            </dl>

                            <dl>
                                <div>
                                    <dt>Paket</dt>
                                    <dd>
                                        {{ \App\Support\Format::weight($shipment->chargeable_weight, 0) }} dikenakan
                                        · {{ \App\Support\Format::number($shipment->length) }} ×
                                        {{ \App\Support\Format::number($shipment->width) }} ×
                                        {{ \App\Support\Format::number($shipment->height) }} cm
                                    </dd>
                                </div>
                                <div>
                                    <dt>Terakhir diperbarui</dt>
                                    <dd>{{ \App\Support\Format::dateTime($shipment->updated_at) }}</dd>
                                </div>
                            </dl>
                        </div>

                        @if ($next)
                            <div class="job-action">
                                <p class="job-next">
                                    Berikutnya: <strong>{{ $next->value }}</strong> — {{ $next->label() }}.
                                </p>

                                <form
                                    class="job-action-form"
                                    method="POST"
                                    action="{{ route('kurir.shipments.status', $shipment) }}"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <x-field
                                        name="notes"
                                        :label="'Catatan untuk '.$shipment->tracking_number.' (opsional)'"
                                        :id="'notes-'.$shipment->id"
                                        placeholder="Contoh: paket dijemput pukul 14.00"
                                    />

                                    <button class="btn btn-primary" type="submit">Simpan pembaruan</button>
                                </form>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="section-title-row">
            <h2>Terakhir diterima</h2>
            <span class="count">{{ $finished->count() }} paket</span>
        </div>

        @if ($finished->isEmpty())
            <div class="empty">
                <strong>Belum ada paket yang diterima.</strong>
                Paket muncul di sini setelah statusnya mencapai DITERIMA.
            </div>
        @else
            <ul class="finished-list">
                @foreach ($finished as $shipment)
                    <li>
                        <a class="mono link" href="{{ route('track.show', $shipment) }}">
                            {{ $shipment->tracking_number }}
                        </a>
                        <span>{{ $shipment->receiver_name }} · {{ $shipment->zone->name }}</span>
                        <time datetime="{{ $shipment->updated_at->toIso8601String() }}">
                            {{ \App\Support\Format::dateTime($shipment->updated_at) }}
                        </time>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-layout>
