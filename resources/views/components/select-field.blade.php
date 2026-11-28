@props([
    'name',
    'label',
    'options' => [],
    'value' => null,
    'hint' => null,
    'required' => false,
    'placeholder' => null,
])

@php
    $id = $attributes->get('id') ?? 'field-'.$name;
    $error = $errors->first($name);
    $selected = old($name, $value);
    $describedBy = collect([
        $hint ? $id.'-hint' : null,
        $error ? $id.'-error' : null,
    ])->filter()->implode(' ');
    $control = $attributes->merge(['class' => 'input', 'id' => $id, 'name' => $name]);
@endphp

<div class="field">
    <label for="{{ $id }}">
        {{ $label }}@if ($required)<span class="req" aria-hidden="true">*</span><span class="sr-only"> wajib</span>@endif
    </label>

    <div class="select-wrapper relative w-full">
        <select
            @if ($required) required @endif
            @if ($error) aria-invalid="true" @endif
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
            {{ $control->merge(['class' => 'appearance-none pr-10 cursor-pointer bg-white']) }}
        >
            @if ($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif

            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected((string) $selected === (string) $optionValue)>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>

        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-neutral-500" aria-hidden="true">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>

    @if ($hint)
        <p class="hint" id="{{ $id }}-hint">{{ $hint }}</p>
    @endif

    @if ($error)
        <p class="error" id="{{ $id }}-error">{{ $error }}</p>
    @endif
</div>
