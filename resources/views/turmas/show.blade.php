<x-app-layout>
    <x-page-header :title="$turma->classe->nome . ' ' . $turma->nome" :subtitle="$turma->anoLectivo->codigo">
        <x-slot name="actions">
            <x-btn variant="primary" icon="pencil" :href="route('turmas.edit', $turma)">{{ __('Edit') }}</x-btn>
            <x-btn variant="secondary" :href="route('turmas.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <dl class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Room') }}</dt><dd>{{ $turma->sala ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Shift') }}</dt><dd>{{ $turma->turno ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Capacity') }}</dt><dd>{{ $turma->capacidade }}</dd></div>
            <div><dt class="form-label">{{ __('Class Director') }}</dt><dd>{{ $turma->directorTurma?->user?->name ?? '—' }}</dd></div>
        </dl>
    </x-card>

    <x-card :title="__('Students')">
        @if($turma->matriculas->where('estado', 'activa')->isEmpty())
            <x-empty title="Sem alunos matriculados" />
        @else
            <ul class="space-y-1 text-sm">
                @foreach($turma->matriculas->where('estado', 'activa') as $m)
                    <li class="flex items-center gap-3">
                        <a href="{{ route('alunos.show', $m->aluno) }}" class="text-navy font-semibold hover:underline">{{ $m->aluno->user->name }}</a>
                        <span class="text-xs text-muted font-mono">{{ $m->numero_matricula }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-card>

    <x-card :title="__('Assignments')">
        @if($turma->atribuicoes->isEmpty())
            <x-empty title="Sem atribuições" />
        @else
            <ul class="space-y-1 text-sm">
                @foreach($turma->atribuicoes as $a)
                    <li><span class="text-navy font-semibold">{{ $a->disciplina->nome }}</span> <span class="text-muted">— {{ $a->professor->user->name }}</span></li>
                @endforeach
            </ul>
        @endif
    </x-card>
</x-app-layout>
