<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ $matricula->numero_matricula }}</h2></x-slot>
    <div class="py-8"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <div class="bg-white shadow rounded-lg p-6 text-sm">
            <dl class="grid grid-cols-2 gap-3">
                <div><dt class="text-gray-500">{{ __('Student') }}</dt><dd class="font-medium">{{ $matricula->aluno->user->name }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Class Groups') }}</dt><dd>{{ $matricula->turma->classe->nome }} {{ $matricula->turma->nome }}</dd></div>
                <div><dt class="text-gray-500">{{ __('School Year') }}</dt><dd>{{ $matricula->anoLectivo->codigo }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Status') }}</dt><dd>{{ ucfirst($matricula->estado) }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Enrollment Date') }}</dt><dd>{{ $matricula->data_matricula?->format('d/m/Y') }}</dd></div>
            </dl>
            <div class="mt-6 flex gap-3">
                <a href="{{ route('boletim.show', $matricula) }}" class="px-4 py-2 bg-blue-700 text-white text-sm rounded">{{ __('Report Card') }}</a>
                <a href="{{ route('matriculas.edit', $matricula) }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Edit') }}</a>
                <a href="{{ route('matriculas.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a>
            </div>
        </div>
    </div></div>
</x-app-layout>
