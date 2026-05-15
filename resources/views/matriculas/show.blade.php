<x-app-layout>
    <x-page-header :title="$matricula->numero_matricula" :subtitle="$matricula->aluno->user->name">
        <x-slot name="actions">
            <x-btn variant="primary" icon="file-text" :href="route('boletim.show', $matricula)">{{ __('Report Card') }}</x-btn>
            <x-btn variant="secondary" :href="route('matriculas.edit', $matricula)">{{ __('Edit') }}</x-btn>
            <x-btn variant="secondary" :href="route('matriculas.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <dl class="grid grid-cols-2 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Student') }}</dt><dd class="text-navy font-semibold">{{ $matricula->aluno->user->name }}</dd></div>
            <div><dt class="form-label">{{ __('Class Groups') }}</dt><dd><x-turma-label :turma="$matricula->turma" /></dd></div>
            <div><dt class="form-label">{{ __('School Year') }}</dt><dd>{{ $matricula->anoLectivo->codigo }}</dd></div>
            <div><dt class="form-label">{{ __('Status') }}</dt><dd>{{ ucfirst($matricula->estado) }}</dd></div>
            <div><dt class="form-label">{{ __('Enrollment Date') }}</dt><dd>{{ $matricula->data_matricula?->format('d/m/Y') }}</dd></div>
        </dl>
    </x-card>
</x-app-layout>
