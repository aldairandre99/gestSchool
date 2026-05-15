@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'help' => null,
    'required' => false,
])

@php $id = $attributes->get('id') ?? $name; @endphp

<div class="form-group">
    @if($label)<label for="{{ $id }}" class="form-label">{{ $label }}@if($required) <span class="text-danger">*</span>@endif</label>@endif
    <input type="{{ $type }}"
           name="{{ $name }}"
           id="{{ $id }}"
           value="{{ old($name, $value) }}"
           @if($required) required @endif
           {{ $attributes->class(['form-input', 'border-danger' => $errors->has($name)]) }}>
    @if($help)<span class="form-help">{{ $help }}</span>@endif
    @error($name)<span class="form-error">{{ $message }}</span>@enderror
</div>
