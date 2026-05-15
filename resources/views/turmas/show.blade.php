<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ $turma->classe->nome }} {{ $turma->nome }} <span class="text-sm font-normal text-gray-500 ms-2">{{ $turma->anoLectivo->codigo }}</span></h2></x-slot>
    <div class="py-8"><div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6 text-sm">
            <dl class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div><dt class="text-gray-500">{{ __('Room') }}</dt><dd>{{ $turma->sala ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Shift') }}</dt><dd>{{ $turma->turno ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Capacity') }}</dt><dd>{{ $turma->capacidade }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Class Director') }}</dt><dd>{{ $turma->directorTurma?->user?->name ?? '—' }}</dd></div>
            </dl>

            <h3 class="text-xs uppercase text-gray-500 mt-6 mb-2">{{ __('Students') }}</h3>
            <ul class="space-y-1">
                @forelse($turma->matriculas->where('estado', 'activa') as $m)
                    <li class="flex items-center gap-2">
                        <a href="{{ route('alunos.show', $m->aluno) }}" class="font-medium text-blue-600 hover:underline">{{ $m->aluno->user->name }}</a>
                        <span class="text-xs text-gray-500 font-mono">{{ $m->numero_matricula }}</span>
                    </li>
                @empty
                    <li class="text-gray-500">{{ __('No records found.') }}</li>
                @endforelse
            </ul>

            <h3 class="text-xs uppercase text-gray-500 mt-6 mb-2">{{ __('Assignments') }}</h3>
            <ul class="space-y-1">
                @foreach($turma->atribuicoes as $a)
                    <li class="text-sm">
                        <span class="font-medium">{{ $a->disciplina->nome }}</span> — {{ $a->professor->user->name }}
                    </li>
                @endforeach
            </ul>

            <div class="mt-6 flex gap-3"><a href="{{ route('turmas.edit', $turma) }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Edit') }}</a><a href="{{ route('turmas.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a></div>
        </div>
    </div></div>
</x-app-layout>
