@props([
    'name',
    'label' => null,
    'value' => '1',
    'checked' => false,
    'hiddenFallback' => true,
])

@php $id = $attributes->get('id') ?? $name; @endphp

<label class="form-check">
    @if($hiddenFallback)<input type="hidden" name="{{ $name }}" value="0">@endif
    <input type="checkbox" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}" @checked($checked) {{ $attributes }}>
    @if($label)<span>{{ $label }}</span>@endif
</label>
