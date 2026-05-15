@props([
    'name',
    'label' => null,
    'value' => null,
    'help' => null,
    'required' => false,
    'placeholder' => '—',
])

@php $id = $attributes->get('id') ?? $name; @endphp

<div class="form-group">
    @if($label)<label for="{{ $id }}" class="form-label">{{ $label }}@if($required) <span class="text-danger">*</span>@endif</label>@endif
    <select name="{{ $name }}"
            id="{{ $id }}"
            @if($required) required @endif
            {{ $attributes->class(['form-select', 'border-danger' => $errors->has($name)]) }}>
        @if($placeholder !== null)<option value="">{{ $placeholder }}</option>@endif
        {{ $slot }}
    </select>
    @if($help)<span class="form-help">{{ $help }}</span>@endif
    @error($name)<span class="form-error">{{ $message }}</span>@enderror
</div>
