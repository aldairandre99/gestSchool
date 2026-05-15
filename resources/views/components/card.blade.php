@props([
    'title' => null,
    'subtitle' => null,
    'compact' => false,
    'actions' => null,
])

<div {{ $attributes->class(['card', 'card-compact' => $compact]) }}>
    @if($title || $subtitle || $actions)
        <div class="flex items-start justify-between gap-3 mb-6">
            <div>
                @if($title)<h3 class="card-title mb-0">{{ $title }}</h3>@endif
                @if($subtitle)<p class="card-subtitle mb-0 mt-1 normal-case tracking-normal">{{ $subtitle }}</p>@endif
            </div>
            @if($actions)
                <div class="shrink-0 flex items-center gap-2">{{ $actions }}</div>
            @endif
        </div>
    @endif
    {{ $slot }}
</div>
