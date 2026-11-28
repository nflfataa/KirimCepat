@props([
    'status',
    'tone' => 'current',
])

@php
    $status = $status instanceof \App\Enums\ShipmentStatus
        ? $status
        : \App\Enums\ShipmentStatus::from($status);
    $toneClass = $tone === 'done' ? 'is-done' : 'is-current';
@endphp

<span class="status-pill {{ $toneClass }}">
    <span class="status-code">{{ $status->value }}</span>
    <span class="status-label">{{ $status->label() }}</span>
</span>
