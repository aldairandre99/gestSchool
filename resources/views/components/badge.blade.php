@props(['variant' => 'muted'])

<span {{ $attributes->class(['badge', 'badge-' . $variant]) }}>{{ $slot }}</span>
