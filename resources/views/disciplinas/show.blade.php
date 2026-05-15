<x-app-layout>
    <x-page-header :title="$disciplina->nome">
        <x-slot name="actions">
            <x-btn variant="primary" icon="pencil" :href="route('disciplinas.edit', $disciplina)">{{ __('Edit') }}</x-btn>
            <x-btn variant="secondary" :href="route('disciplinas.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <dl class="grid grid-cols-3 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Name') }}</dt><dd class="text-navy font-semibold">{{ $disciplina->nome }}</dd></div>
            <div><dt class="form-label">{{ __('Abbreviation') }}</dt><dd class="font-mono">{{ $disciplina->sigla ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Weekly Hours') }}</dt><dd>{{ $disciplina->carga_horaria_semanal ?? '—' }}</dd></div>
        </dl>
    </x-card>
</x-app-layout>
