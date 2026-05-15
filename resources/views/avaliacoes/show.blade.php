<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ $avaliacao->titulo }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><x-flash />
        <div class="bg-white shadow rounded-lg p-6 text-sm">
            <dl class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div><dt class="text-gray-500">{{ __('Class Groups') }}</dt><dd>{{ $avaliacao->atribuicao->turma->classe->nome }} {{ $avaliacao->atribuicao->turma->nome }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Subjects List') }}</dt><dd>{{ $avaliacao->atribuicao->disciplina->nome }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Term') }}</dt><dd>{{ $avaliacao->trimestre->numero }}º</dd></div>
                <div><dt class="text-gray-500">{{ __('Type') }}</dt><dd>{{ ucfirst(str_replace('_', ' ', $avaliacao->tipo)) }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Date') }}</dt><dd>{{ $avaliacao->data?->format('d/m/Y') ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Weight') }}</dt><dd>{{ $avaliacao->peso }}</dd></div>
                <div><dt class="text-gray-500">{{ __('Max Score') }}</dt><dd>{{ $avaliacao->max_nota }}</dd></div>
            </dl>
            <div class="mt-6 flex gap-3">
                <a href="{{ route('notas.folha', $avaliacao) }}" class="px-4 py-2 bg-blue-700 text-white text-sm rounded">{{ __('Launch Grades') }}</a>
                <a href="{{ route('avaliacoes.edit', $avaliacao) }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Edit') }}</a>
                <a href="{{ route('avaliacoes.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a>
            </div>
        </div>
    </div></div>
</x-app-layout>
