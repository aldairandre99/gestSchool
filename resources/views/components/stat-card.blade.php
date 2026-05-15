@props([
    'label',
    'value',
    'icon' => null,
    'variant' => 'primary',
    'trend' => null,
    'href' => null,
])

@php $tag = $href ? 'a' : 'div'; @endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif {{ $attributes->class(['stat-card']) }}>
    <div>
        <p class="stat-label">{{ $label }}</p>
        <p class="stat-value">{{ $value }}</p>
        @if($trend)<p class="stat-trend">{{ $trend }}</p>@endif
    </div>
    @if($icon)
        <span class="stat-icon stat-icon-{{ $variant }}">
            <x-dynamic-component :component="'lucide-' . $icon" class="w-6 h-6" />
        </span>
    @endif
</{{ $tag }}>
