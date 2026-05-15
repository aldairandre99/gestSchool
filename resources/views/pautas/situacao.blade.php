<x-app-layout>
    <x-page-header
        :title="__('Final results')"
        :subtitle="$turma->classe->nome . ' ' . $turma->nome . ($turma->curso ? ' · ' . $turma->curso->sigla : '') . ' · ' . $turma->anoLectivo->codigo">
        <x-slot name="actions">
            <x-btn variant="primary" icon="printer" href="javascript:print()">{{ __('Print') }}</x-btn>
            <x-btn variant="secondary" :href="route('pautas.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    @php
        $counts = [
            'aprovado' => count($agrupado['aprovado']),
            'recurso' => count($agrupado['recurso']),
            'reprovado' => count($agrupado['reprovado']),
            'em_curso' => count($agrupado['em_curso']),
        ];
        $total = array_sum($counts);
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6 no-print">
        <div class="bg-success-soft rounded p-4 text-center">
            <p class="stat-label text-success">{{ __('Approved') }}</p>
            <p class="text-2xl font-bold text-success">{{ $counts['aprovado'] }}</p>
        </div>
        <div class="bg-warning-soft rounded p-4 text-center">
            <p class="stat-label text-yellow-700">{{ __('Second-chance') }}</p>
            <p class="text-2xl font-bold text-yellow-700">{{ $counts['recurso'] }}</p>
        </div>
        <div class="bg-danger-soft rounded p-4 text-center">
            <p class="stat-label text-danger">{{ __('Failed') }}</p>
            <p class="text-2xl font-bold text-danger">{{ $counts['reprovado'] }}</p>
        </div>
        <div class="bg-gray-50 rounded p-4 text-center">
            <p class="stat-label">{{ __('Total') }}</p>
            <p class="text-2xl font-bold text-navy">{{ $total }}</p>
        </div>
    </div>

    <div class="print-page">
        @include('pautas._print-header', [
            'titulo' => $turma->classe->nome . ' ' . $turma->nome . ($turma->curso ? ' (' . $turma->curso->sigla . ')' : ''),
            'subtitulo' => __('Final results') . ' · ' . $turma->anoLectivo->codigo,
        ])

        <x-card>
            <div class="table-wrapper">
                <table class="table print-table text-sm">
                    <thead>
                        <tr>
                            <th class="text-left">#</th>
                            <th class="text-left">{{ __('Student') }}</th>
                            <th class="text-left">{{ __('Enrollment Number') }}</th>
                            <th class="text-center">{{ __('General average') }}</th>
                            <th class="text-center">{{ __('Negatives') }}</th>
                            <th class="text-center">{{ __('Situation') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($idx = 0)
                        @foreach(['aprovado', 'recurso', 'reprovado', 'em_curso'] as $sit)
                            @foreach($agrupado[$sit] as $m)
                                @php($idx++)
                                <tr>
                                    <td class="text-muted">{{ $idx }}</td>
                                    <td class="font-semibold text-navy">{{ $m->aluno->user->name }}</td>
                                    <td class="font-mono text-xs">{{ $m->numero_matricula }}</td>
                                    <td class="text-center font-bold {{ ($resumo[$m->id]['media_geral'] ?? null) !== null && $resumo[$m->id]['media_geral'] < $calc->notaMinima ? 'nota-negativa' : 'text-navy' }}">
                                        {{ $resumo[$m->id]['media_geral'] ?? '—' }}
                                    </td>
                                    <td class="text-center text-xs text-muted">
                                        @if(empty($resumo[$m->id]['negativas']))
                                            <span class="text-success">0</span>
                                        @else
                                            <span class="text-danger font-semibold" title="{{ implode(', ', $resumo[$m->id]['negativas']) }}">
                                                {{ count($resumo[$m->id]['negativas']) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center situacao-{{ $sit }}">
                                        {{ __(ucfirst($sit)) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        @if($total === 0)
                            <tr><td colspan="6" class="table-empty">{{ __('No records found.') }}</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="text-xs text-muted mt-4 space-y-1">
                <p><strong>{{ __('Formula') }}:</strong> {{ $calc->formulaDescricao() }}</p>
                <p><strong>{{ __('Rule') }}:</strong> 0 {{ __('negatives') }} → {{ __('Approved') }} · 1–{{ $calc->maxNegativasRecurso }} → {{ __('Second-chance') }} · {{ $calc->maxNegativasRecurso + 1 }}+ → {{ __('Failed') }}</p>
            </div>
        </x-card>
    </div>
</x-app-layout>
