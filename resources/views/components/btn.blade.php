@props([
    'variant' => 'primary',
    'size' => null,
    'href' => null,
    'type' => 'button',
    'icon' => null,
])

@php
    $classes = ['btn', 'btn-' . $variant];
    if ($size) $classes[] = 'btn-' . $size;
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        @if($icon)<x-dynamic-component :component="'lucide-' . $icon" class="w-4 h-4" />@endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class($classes) }}>
        @if($icon)<x-dynamic-component :component="'lucide-' . $icon" class="w-4 h-4" />@endif
        {{ $slot }}
    </button>
@endif
