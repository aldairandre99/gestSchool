@props([
    'href',
    'icon' => null,
    'active' => false,
    'label' => null,
])

@php
    $tooltip = $label ?? trim(strip_tags($slot));
@endphp

<a href="{{ $href }}" title="{{ $tooltip }}" {{ $attributes->class(['sidebar-link', 'is-active' => $active]) }}>
    @if($icon)<x-dynamic-component :component="'lucide-' . $icon" class="sidebar-icon" />@endif
    <span>{{ $slot }}</span>
</a>
