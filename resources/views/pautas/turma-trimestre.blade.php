<x-app-layout>
    <x-page-header
        :title="__('Class gradebook for a term')"
        :subtitle="$turma->classe->nome . ' ' . $turma->nome . ($turma->curso ? ' · ' . $turma->curso->sigla : '') . ' · ' . $trimestre->numero . 'º ' . __('Term') . ' · ' . $turma->anoLectivo->codigo">
        <x-slot name="actions">
            <x-btn variant="danger" icon="file-down" :href="route('pautas.turma-trimestre.pdf', ['turma' => $turma, 'trimestre' => $trimestre])">{{ __('Export PDF') }}</x-btn>
            <x-btn variant="primary" icon="printer" href="javascript:print()">{{ __('Print') }}</x-btn>
            <x-btn variant="secondary" :href="route('pautas.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <div class="print-page">
        @include('pautas._print-header', [
            'titulo' => $turma->classe->nome . ' ' . $turma->nome . ($turma->curso ? ' (' . $turma->curso->sigla . ')' : ''),
            'subtitulo' => $trimestre->numero . 'º ' . __('Term') . ' · ' . $turma->anoLectivo->codigo,
        ])

        <x-card>
            <div class="table-wrapper">
                <table class="table print-table text-xs">
                    <thead>
                        <tr>
                            <th class="text-left">{{ __('Student') }}</th>
                            @foreach($atribuicoes as $atr)
                                <th class="text-center" title="{{ $atr->disciplina->nome }}">{{ $atr->disciplina->sigla ?: \Illuminate\Support\Str::limit($atr->disciplina->nome, 5) }}</th>
                            @endforeach
                            <th class="text-center !text-navy">{{ __('Average') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matriculas as $m)
                            <tr>
                                <td class="font-semibold text-navy">{{ $m->aluno->user->name }}</td>
                                @foreach($atribuicoes as $atr)
                                    @php($v = $mediasTurma[$m->id][$atr->disciplina_id] ?? null)
                                    <td class="text-center {{ ($v !== null && $v < $calc->notaMinima) ? 'nota-negativa' : '' }}">
                                        {{ $v !== null ? rtrim(rtrim((string) $v, '0'), '.') : '—' }}
                                    </td>
                                @endforeach
                                <td class="text-center font-bold {{ ($mediaGeral[$m->id] ?? null) !== null && $mediaGeral[$m->id] < $calc->notaMinima ? 'nota-negativa' : 'text-navy' }}">
                                    {{ ($mediaGeral[$m->id] ?? null) !== null ? $mediaGeral[$m->id] : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="{{ 2 + count($atribuicoes) }}" class="table-empty">{{ __('No records found.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <p class="text-xs text-muted mt-4">
                <strong>{{ __('Passing grade') }}:</strong> ≥ {{ $calc->notaMinima }} · <strong>{{ __('Average') }}:</strong> {{ __('simple average of all subjects.') }}
            </p>
        </x-card>
    </div>
</x-app-layout>
