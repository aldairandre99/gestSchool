<x-app-layout>
    <x-page-header :title="__('Grade Sheet')" :subtitle="$avaliacao->titulo">
        <x-slot name="actions">
            <x-btn variant="secondary" :href="route('avaliacoes.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <div class="flex flex-wrap gap-3 text-xs mb-6 text-muted">
            <span><strong class="text-navy">{{ __('Class Groups') }}:</strong> {{ $avaliacao->atribuicao->turma->classe->nome }} {{ $avaliacao->atribuicao->turma->nome }}</span>
            <span><strong class="text-navy">{{ __('Subjects List') }}:</strong> {{ $avaliacao->atribuicao->disciplina->nome }}</span>
            <span><strong class="text-navy">{{ __('Term') }}:</strong> {{ $avaliacao->trimestre->numero }}º</span>
            <span><strong class="text-navy">{{ __('Max Score') }}:</strong> {{ $avaliacao->max_nota }}</span>
        </div>

        <form method="POST" action="{{ route('notas.gravar', $avaliacao) }}">
            @csrf
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Student') }}</th>
                            <th>{{ __('Score') }}</th>
                            <th>{{ __('Note') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matriculas as $m)
                            @php($existente = $existentes[$m->id] ?? null)
                            <tr>
                                <td>
                                    <div class="font-semibold text-navy">{{ $m->aluno->user->name }}</div>
                                    <div class="text-xs text-muted font-mono">{{ $m->numero_matricula }}</div>
                                </td>
                                <td>
                                    <input type="number" step="0.01" min="0" max="{{ $avaliacao->max_nota }}"
                                           name="valores[{{ $m->id }}]" value="{{ $existente?->valor }}"
                                           class="form-input w-24">
                                </td>
                                <td>
                                    <input type="text" name="observacoes[{{ $m->id }}]" value="{{ $existente?->observacao }}" class="form-input">
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="table-empty">{{ __('No records found.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex gap-3 mt-6">
                <x-btn variant="primary" type="submit" icon="check">{{ __('Save Grades') }}</x-btn>
                <x-btn variant="secondary" :href="route('avaliacoes.index')">{{ __('Cancel') }}</x-btn>
            </div>
        </form>
    </x-card>
</x-app-layout>
