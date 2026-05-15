<x-app-layout>
    <x-page-header :title="$ano->codigo">
        <x-slot name="actions">
            <x-btn variant="primary" icon="pencil" :href="route('anos.edit', $ano)">{{ __('Edit') }}</x-btn>
            <x-btn variant="secondary" :href="route('anos.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <dl class="grid grid-cols-2 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Code') }}</dt><dd class="font-mono text-navy">{{ $ano->codigo }}</dd></div>
            <div><dt class="form-label">{{ __('Status') }}</dt><dd>@if($ano->activo)<x-badge variant="success">{{ __('Active Year') }}</x-badge>@else <span class="text-muted">—</span> @endif</dd></div>
            <div><dt class="form-label">{{ __('Start') }}</dt><dd>{{ $ano->inicio->format('d/m/Y') }}</dd></div>
            <div><dt class="form-label">{{ __('End') }}</dt><dd>{{ $ano->fim->format('d/m/Y') }}</dd></div>
        </dl>
    </x-card>
</x-app-layout>
