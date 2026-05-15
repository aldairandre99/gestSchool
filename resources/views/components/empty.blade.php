@props([
    'title' => null,
    'description' => null,
    'icon' => 'inbox',
])
@php($title = $title ?? __('No records'))

<div class="empty">
    <x-dynamic-component :component="'lucide-' . $icon" class="empty-icon" />
    <p class="empty-title">{{ $title }}</p>
    @if($description)<p class="empty-text">{{ $description }}</p>@endif
    @if($slot->isNotEmpty())<div class="mt-4">{{ $slot }}</div>@endif
</div>
