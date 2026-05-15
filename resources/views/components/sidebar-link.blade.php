@props([
    'href',
    'icon' => null,
    'active' => false,
])

<a href="{{ $href }}" {{ $attributes->class(['sidebar-link', 'is-active' => $active]) }}>
    @if($icon)<x-dynamic-component :component="'lucide-' . $icon" class="sidebar-icon" />@endif
    <span>{{ $slot }}</span>
</a>
