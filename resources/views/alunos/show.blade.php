<x-app-layout>
    <x-page-header :title="$aluno->user->name">
        <x-slot name="actions">
            @hasanyrole('director_geral|director_pedagogico|secretario')
                <x-btn variant="primary" icon="pencil" :href="route('alunos.edit', $aluno)">{{ __('Edit') }}</x-btn>
            @endhasanyrole
            <x-btn variant="secondary" :href="route('alunos.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card :title="__('Personal Data')">
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Name') }}</dt><dd class="text-navy font-semibold">{{ $aluno->user->name }}</dd></div>
            <div><dt class="form-label">{{ __('Process Number') }}</dt><dd class="font-mono">{{ $aluno->numero_processo }}</dd></div>
            <div><dt class="form-label">{{ __('BI Number') }}</dt><dd>{{ $aluno->bi ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Birth Date') }}</dt><dd>{{ $aluno->data_nascimento?->format('d/m/Y') ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Gender') }}</dt><dd>{{ $aluno->sexo === 'M' ? __('Male') : ($aluno->sexo === 'F' ? __('Female') : '—') }}</dd></div>
            <div><dt class="form-label">Nacionalidade</dt><dd>{{ $aluno->nacionalidade }}</dd></div>
            <div><dt class="form-label">Naturalidade</dt><dd>{{ $aluno->naturalidade ?? '—' }}</dd></div>
            <div class="sm:col-span-2"><dt class="form-label">{{ __('Address') }}</dt><dd>{{ $aluno->morada ?? '—' }}</dd></div>
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
        @if($aluno->encarregados->isEmpty())
            <x-empty title="Sem encarregados associados" />
        @else
            <ul class="space-y-2">
                @foreach($aluno->encarregados as $e)
                    <li class="flex items-center gap-3">
                        <span class="text-navy font-semibold">{{ $e->user->name }}</span>
                        <x-badge variant="muted">{{ __(ucfirst($e->pivot->parentesco)) }}</x-badge>
                        @if($e->pivot->principal)<x-badge variant="warning">principal</x-badge>@endif
                        <span class="text-xs text-muted ms-2">{{ $e->user->phone ?? $e->user->email }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-card>
</x-app-layout>
