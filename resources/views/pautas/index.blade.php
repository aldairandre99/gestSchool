<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Gradebook') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-sm text-gray-600 mb-4">Escolha uma turma/disciplina e um trimestre.</p>
            <table class="min-w-full text-sm">
                <thead class="text-left text-gray-500 border-b"><tr>
                    <th class="py-2 pr-3">{{ __('Class Groups') }}</th>
                    <th class="py-2 pr-3">{{ __('Subjects List') }}</th>
                    @foreach($trimestres->groupBy('ano_lectivo_id')->first() ?? collect() as $t)
                        <th class="py-2 pr-3 text-center">{{ $t->numero }}º</th>
                    @endforeach
                </tr></thead>
                <tbody>
                    @forelse($atribuicoes as $a)
                        <tr class="border-b last:border-0">
                            <td class="py-2 pr-3 font-medium">{{ $a->turma->classe->nome }} {{ $a->turma->nome }}</td>
                            <td class="py-2 pr-3">{{ $a->disciplina->nome }}</td>
                            @foreach($trimestres->where('ano_lectivo_id', $a->ano_lectivo_id)->sortBy('numero') as $t)
                                <td class="py-2 pr-3 text-center">
                                    <a href="{{ route('pautas.show', ['atribuicao' => $a, 'trimestre' => $t]) }}" class="text-blue-600 hover:underline text-xs">{{ __('View Gradebook') }}</a>
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-4 text-center text-gray-500">{{ __('No records found.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div></div>
</x-app-layout>
