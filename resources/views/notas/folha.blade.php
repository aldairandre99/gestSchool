<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Grade Sheet') }} — {{ $avaliacao->titulo }}</h2></x-slot>
    <div class="py-8"><div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-sm text-gray-600 mb-4 flex gap-4 flex-wrap">
                <span><strong>{{ __('Class Groups') }}:</strong> {{ $avaliacao->atribuicao->turma->classe->nome }} {{ $avaliacao->atribuicao->turma->nome }}</span>
                <span><strong>{{ __('Subjects List') }}:</strong> {{ $avaliacao->atribuicao->disciplina->nome }}</span>
                <span><strong>{{ __('Term') }}:</strong> {{ $avaliacao->trimestre->numero }}º</span>
                <span><strong>{{ __('Max Score') }}:</strong> {{ $avaliacao->max_nota }}</span>
            </div>
            <form method="POST" action="{{ route('notas.gravar', $avaliacao) }}">
                @csrf
                <table class="min-w-full text-sm">
                    <thead class="text-left text-gray-500 border-b"><tr>
                        <th class="py-2 pr-3">{{ __('Student') }}</th>
                        <th class="py-2 pr-3">{{ __('Score') }}</th>
                        <th class="py-2 pr-3">Observação</th>
                    </tr></thead>
                    <tbody>
                        @forelse($matriculas as $m)
                            @php($existente = $existentes[$m->id] ?? null)
                            <tr class="border-b last:border-0">
                                <td class="py-2 pr-3">
                                    <div class="font-medium text-gray-800">{{ $m->aluno->user->name }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $m->numero_matricula }}</div>
                                </td>
                                <td class="py-2 pr-3">
                                    <input type="number" step="0.01" min="0" max="{{ $avaliacao->max_nota }}"
                                           name="valores[{{ $m->id }}]" value="{{ $existente?->valor }}"
                                           class="w-24 border-gray-300 rounded-md text-sm">
                                </td>
                                <td class="py-2 pr-3">
                                    <input type="text" name="observacoes[{{ $m->id }}]" value="{{ $existente?->observacao }}" class="w-full border-gray-300 rounded-md text-sm">
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-4 text-center text-gray-500">{{ __('No records found.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-6 flex gap-3">
                    <button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save Grades') }}</button>
                    <a href="{{ route('avaliacoes.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a>
                </div>
            </form>
        </div>
    </div></div>
</x-app-layout>
