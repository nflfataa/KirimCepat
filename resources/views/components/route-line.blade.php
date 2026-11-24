@props([
    'status',
])

@php
    $status = $status instanceof \App\Enums\ShipmentStatus
        ? $status
        : \App\Enums\ShipmentStatus::from($status);
    $steps = \App\Enums\ShipmentStatus::cases();
    $currentIndex = array_search($status, $steps, true);
@endphp

<ol class="route-line" aria-label="Tahap pengiriman">
    @foreach ($steps as $index => $step)
        <li
            class="route-step @if ($index < $currentIndex) is-done @elseif ($index === $currentIndex) is-current @endif"
            @if ($index === $currentIndex) aria-current="step" @endif
        >
            <span class="route-code">{{ $step->value }}</span>
            <span class="route-label">{{ $step->label() }}</span>
        </li>
    @endforeach
</ol>
