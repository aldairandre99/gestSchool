@props([
    'code' => '',
    'icon' => 'alert-triangle',
    'variant' => 'warning',
    'title',
    'message' => null,
])

@php
    $iconBg = [
        'primary' => 'bg-primary-soft text-primary',
        'success' => 'bg-success-soft text-success',
        'info'    => 'bg-info-soft text-info',
        'warning' => 'bg-warning-soft text-yellow-700',
        'danger'  => 'bg-danger-soft text-danger',
        'muted'   => 'bg-gray-100 text-muted',
    ][$variant] ?? 'bg-gray-100 text-muted';
@endphp

<x-error-layout :title="$code . ' — ' . $title">
    <div class="card text-center">
        <div class="mx-auto w-20 h-20 rounded-full inline-flex items-center justify-center {{ $iconBg }} mb-6">
            <x-dynamic-component :component="'lucide-' . $icon" class="w-10 h-10" />
        </div>

        @if($code)
            <p class="text-sm font-mono uppercase tracking-widest text-muted">{{ __('Error') }} {{ $code }}</p>
        @endif

        <h1 class="page-title mt-2">{{ $title }}</h1>

        @if($message)
            <p class="text-sm text-muted mt-3 max-w-md mx-auto">{{ $message }}</p>
        @endif

        @if($slot->isNotEmpty())
            <div class="mt-4">{{ $slot }}</div>
        @endif

        <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
            <x-btn variant="primary" icon="home" :href="auth()->check() ? route('dashboard') : url('/')">
                {{ auth()->check() ? __('Dashboard') : __('Home') }}
            </x-btn>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <x-lucide-arrow-left class="w-4 h-4" />
                {{ __('Back') }}
            </a>
        </div>
    </div>
</x-error-layout>
