@props([
    'variant' => null,
    'href' => '#',
])

@php
    $classes = ['btn-link'];
    if ($variant) $classes[] = 'btn-link-' . $variant;
@endphp

<a href="{{ $href }}" {{ $attributes->class($classes) }}>{{ $slot }}</a>
