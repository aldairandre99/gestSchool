<x-app-layout>
    <x-page-header :title="$trimestre->numero . 'º ' . __('Term')" :subtitle="$trimestre->anoLectivo->codigo">
        <x-slot name="actions">
            <x-btn variant="primary" icon="pencil" :href="route('trimestres.edit', $trimestre)">{{ __('Edit') }}</x-btn>
            <x-btn variant="secondary" :href="route('trimestres.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <dl class="grid grid-cols-2 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Start') }}</dt><dd>{{ $trimestre->inicio->format('d/m/Y') }}</dd></div>
            <div><dt class="form-label">{{ __('End') }}</dt><dd>{{ $trimestre->fim->format('d/m/Y') }}</dd></div>
            <div><dt class="form-label">{{ __('Status') }}</dt><dd>
                @if($trimestre->aberto)<x-badge variant="success">{{ __('Open') }}</x-badge>
                @else<x-badge variant="muted">{{ __('Closed') }}</x-badge>@endif
            </dd></div>
        </dl>
    </x-card>
</x-app-layout>
