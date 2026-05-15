@props([
    'title',
    'subtitle' => null,
    'actions' => null,
    'breadcrumb' => null,
])

<header class="page-header">
    <div>
        @if($breadcrumb)<div class="page-breadcrumb mb-1">{{ $breadcrumb }}</div>@endif
        <h1 class="page-title">{{ $title }}</h1>
        @isset($subtitleSlot)
            <div class="page-subtitle">{{ $subtitleSlot }}</div>
        @elseif($subtitle)
            <p class="page-subtitle">{{ $subtitle }}</p>
        @endisset
    </div>
    @if($actions)
        <div class="flex items-center gap-2">{{ $actions }}</div>
    @endif
</header>
