@props([
    'name',
    'label' => null,
    'value' => null,
    'rows' => 3,
    'help' => null,
    'required' => false,
])

@php $id = $attributes->get('id') ?? $name; @endphp

<div class="form-group">
    @if($label)<label for="{{ $id }}" class="form-label">{{ $label }}@if($required) <span class="text-danger">*</span>@endif</label>@endif
    <textarea name="{{ $name }}"
              id="{{ $id }}"
              rows="{{ $rows }}"
              @if($required) required @endif
              {{ $attributes->class(['form-textarea', 'border-danger' => $errors->has($name)]) }}>{{ old($name, $value) }}</textarea>
    @if($help)<span class="form-help">{{ $help }}</span>@endif
    @error($name)<span class="form-error">{{ $message }}</span>@enderror
</div>
