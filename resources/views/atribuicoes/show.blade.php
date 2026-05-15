<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Assignment') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <div class="bg-white shadow rounded-lg p-6 text-sm">
            <dl class="grid grid-cols-2 gap-3">
                <div><dt class="text-gray-500">{{ __('Teacher') }}</dt><dd class="font-medium">{{ $atribuicao->professor->user->name }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Class Groups') }}</dt><dd>{{ $atribuicao->turma->classe->nome }} {{ $atribuicao->turma->nome }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Subjects List') }}</dt><dd>{{ $atribuicao->disciplina->nome }}</dd></div>
                <div><dt class="text-gray-500">{{ __('School Year') }}</dt><dd>{{ $atribuicao->anoLectivo->codigo }}</dd></div>
            </dl>
            <div class="mt-6 flex gap-3"><a href="{{ route('atribuicoes.edit', $atribuicao) }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Edit') }}</a><a href="{{ route('atribuicoes.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a></div>
        </div>
    </div></div>
</x-app-layout>
