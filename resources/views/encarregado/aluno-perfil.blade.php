<x-app-layout>
    <x-page-header :title="__('Student Profile')" :subtitle="$aluno->user->name">
        <x-slot name="actions">
            @php($ma = $aluno->matriculaActiva())
            @if($ma)
                <x-btn variant="primary" icon="file-text" :href="route('boletim.show', $ma)">{{ __('Report Card') }}</x-btn>
            @endif
            <x-btn variant="secondary" :href="route('meus-educandos.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card :title="__('Personal Data')">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Process Number') }}</dt><dd class="font-mono text-navy">{{ $aluno->numero_processo }}</dd></div>
            <div><dt class="form-label">{{ __('Birth Date') }}</dt><dd>{{ $aluno->data_nascimento?->format('d/m/Y') ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Gender') }}</dt><dd>{{ $aluno->sexo === 'M' ? __('Male') : ($aluno->sexo === 'F' ? __('Female') : '—') }}</dd></div>
            <div><dt class="form-label">{{ __('Nationality') }}</dt><dd>{{ $aluno->nacionalidade }}</dd></div>
            <div><dt class="form-label">{{ __('Place of Birth') }}</dt><dd>{{ $aluno->naturalidade ?? '—' }}</dd></div>
        </dl>
    </x-card>

    <x-card :title="__('Academic Data')">
        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Grade') }}</dt><dd class="text-navy">{{ $aluno->classe ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Class') }}</dt><dd class="text-navy">{{ $aluno->turma ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('School Year') }}</dt><dd class="text-navy">{{ $aluno->ano_lectivo ?? '—' }}</dd></div>
        </dl>
    </x-card>

    <x-card :title="__('Guardians of this student')">
        <ul class="space-y-2 text-sm">
            @foreach($aluno->encarregados as $e)
                <li class="flex items-center gap-3">
                    <span class="text-navy font-semibold">{{ $e->user->name }}</span>
                    <x-badge variant="muted">{{ ucfirst($e->pivot->parentesco) }}</x-badge>
                </li>
            @endforeach
        </ul>
    </x-card>
</x-app-layout>
