@props([
    'title',
    'group',
])

<div class="sb-group" data-sb-group="{{ $group }}">
    <button type="button" class="sidebar-section-toggle" data-sb-toggle="{{ $group }}" aria-label="{{ $title }}">
        <span>{{ $title }}</span>
        <x-lucide-chevron-down class="sidebar-section-chev" />
    </button>
    <div class="sb-group-items">
        {{ $slot }}
    </div>
</div>
