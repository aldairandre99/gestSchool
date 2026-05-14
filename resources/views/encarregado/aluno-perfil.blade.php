<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Student Profile') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6 text-sm">
            <h3 class="text-lg font-semibold text-gray-800">{{ $aluno->user->name }}</h3>

            <h4 class="text-xs uppercase text-gray-500 mt-4 mb-2">{{ __('Personal Data') }}</h4>
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div><dt class="text-gray-500">{{ __('Process Number') }}</dt><dd class="font-mono">{{ $aluno->numero_processo }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Birth Date') }}</dt><dd>{{ $aluno->data_nascimento?->format('d/m/Y') ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Gender') }}</dt><dd>{{ $aluno->sexo === 'M' ? __('Male') : ($aluno->sexo === 'F' ? __('Female') : '—') }}</dd></div>
                <div><dt class="text-gray-500">Nacionalidade</dt><dd>{{ $aluno->nacionalidade }}</dd></div>
                <div><dt class="text-gray-500">Naturalidade</dt><dd>{{ $aluno->naturalidade ?? '—' }}</dd></div>
            </dl>

            <h4 class="text-xs uppercase text-gray-500 mt-6 mb-2">{{ __('Academic Data') }}</h4>
            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div><dt class="text-gray-500">{{ __('Grade') }}</dt><dd>{{ $aluno->classe ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Class') }}</dt><dd>{{ $aluno->turma ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">{{ __('School Year') }}</dt><dd>{{ $aluno->ano_lectivo ?? '—' }}</dd></div>
            </dl>

            <h4 class="text-xs uppercase text-gray-500 mt-6 mb-2">{{ __('Guardians of this student') }}</h4>
            <ul class="space-y-1">
                @foreach($aluno->encarregados as $e)
                    <li>
                        <span class="font-medium">{{ $e->user->name }}</span>
                        <span class="text-xs text-gray-500">({{ ucfirst($e->pivot->parentesco) }})</span>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6">
                <a href="{{ route('meus-educandos.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a>
            </div>
        </div>
    </div></div>
</x-app-layout>
