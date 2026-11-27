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

<div class="route-line-wrapper">
    <ol class="route-line" aria-label="Tahap pengiriman">
        @foreach ($steps as $index => $step)
            @php
                $isDone = $index < $currentIndex;
                $isCurrent = $index === $currentIndex;
            @endphp
            <li
                class="route-step @if ($isDone) is-done @elseif ($isCurrent) is-current @else is-pending @endif"
                @if ($isCurrent) aria-current="step" @endif
            >
                <div class="route-node" aria-hidden="true">
                    @if ($isDone)
                        <svg class="route-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    @elseif ($isCurrent)
                        @if ($step === \App\Enums\ShipmentStatus::DITERIMA)
                            <svg class="route-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                                <path d="m3.3 7 8.7 5 8.7-5" />
                                <path d="M12 22V12" />
                                <path d="m7.5 4.27 9 5.15" />
                            </svg>
                        @else
                            <svg class="route-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        @endif
                    @elseif ($step === \App\Enums\ShipmentStatus::DITERIMA)
                        <svg class="route-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
                            <path d="m3.3 7 8.7 5 8.7-5" />
                            <path d="M12 22V12" />
                            <path d="m7.5 4.27 9 5.15" />
                        </svg>
                    @else
                        <span class="route-dot"></span>
                    @endif
                </div>
                <div class="route-text">
                    <span class="route-code">{{ $step->value }}</span>
                    <strong class="route-label">{{ $step->label() }}</strong>
                </div>
            </li>
        @endforeach
    </ol>
</div>
