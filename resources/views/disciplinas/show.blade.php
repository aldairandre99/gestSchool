<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ $disciplina->nome }}</h2></x-slot>
    <div class="py-8"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6 text-sm">
            <dl class="grid grid-cols-3 gap-3">
                <div><dt class="text-gray-500">{{ __('Name') }}</dt><dd class="font-medium">{{ $disciplina->nome }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Abbreviation') }}</dt><dd class="font-mono">{{ $disciplina->sigla ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Weekly Hours') }}</dt><dd>{{ $disciplina->carga_horaria_semanal ?? '—' }}</dd></div>
            </dl>
            <div class="mt-6 flex gap-3"><a href="{{ route('disciplinas.edit', $disciplina) }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Edit') }}</a><a href="{{ route('disciplinas.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a></div>
        </div>
    </div></div>
</x-app-layout>
