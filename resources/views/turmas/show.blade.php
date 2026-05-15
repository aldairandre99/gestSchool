<x-app-layout>
    <x-page-header :title="__('Class Groups')">
        <x-slot name="subtitleSlot">
            <x-turma-label :turma="$turma" :showAno="true" />
        </x-slot>
        <x-slot name="actions">
            <x-btn variant="primary" icon="pencil" :href="route('turmas.edit', $turma)">{{ __('Edit') }}</x-btn>
            <x-btn variant="secondary" :href="route('turmas.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <dl class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Level') }}</dt>
                <dd>
                    @if($turma->classe->nivel === 'ensino_medio')
                        <x-badge variant="info">{{ __('Secondary Education') }}</x-badge>
                    @else
                        <x-badge variant="muted">{{ __('Basic Education') }}</x-badge>
                    @endif
                </dd>
            </div>
            <div><dt class="form-label">{{ __('Course') }}</dt><dd>{{ $turma->curso?->sigla ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Room') }}</dt><dd>{{ $turma->sala ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Shift') }}</dt><dd>{{ $turma->turno ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Capacity') }}</dt><dd>{{ $turma->capacidade }}</dd></div>
            <div><dt class="form-label">{{ __('Class Director') }}</dt><dd>{{ $turma->directorTurma?->user?->name ?? '—' }}</dd></div>
        </dl>
    </x-card>

    <x-card :title="__('Curriculum subjects')">
        @if($disciplinas->isEmpty())
            <x-empty title="{{ __('Curriculum to be defined') }}" description="{{ __('Assign subjects to this class/course.') }}"  />
        @else
            <div class="flex flex-wrap gap-2">
                @foreach($disciplinas as $d)<x-badge variant="muted">{{ $d->nome }}</x-badge>@endforeach
            </div>
        @endif
    </x-card>

    <x-card :title="__('Students')">
        @if($turma->matriculas->where('estado', 'activa')->isEmpty())
            <x-empty title="{{ __('No students enrolled') }}" />
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
            <x-empty title="{{ __('No assignments') }}" />
        @else
            <ul class="space-y-1 text-sm">
                @foreach($turma->atribuicoes as $a)
                    <li><span class="text-navy font-semibold">{{ $a->disciplina->nome }}</span> <span class="text-muted">— {{ $a->professor->user->name }}</span></li>
                @endforeach
            </ul>
        @endif
    </x-card>
</x-app-layout>
