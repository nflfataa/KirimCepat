@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'hint' => null,
    'required' => false,
])

@php
    $id = $attributes->get('id') ?? 'field-'.$name;
    $error = $errors->first($name);
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

    @if ($type === 'textarea')
        <textarea
            @if ($required) required @endif
            @if ($error) aria-invalid="true" @endif
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
            {{ $control }}
        >{{ old($name, $value) }}</textarea>
    @else
        <input
            type="{{ $type }}"
            value="{{ old($name, $value) }}"
            @if ($required) required @endif
            @if ($error) aria-invalid="true" @endif
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
            {{ $control }}
        >
    @endif

    @if ($hint)
        <p class="hint" id="{{ $id }}-hint">{{ $hint }}</p>
    @endif

    @if ($error)
        <p class="error" id="{{ $id }}-error">{{ $error }}</p>
    @endif
</div>
