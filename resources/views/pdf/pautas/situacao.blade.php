@php
    $titulo = $turma->nome_completo;
    $subtitulo = __('Final results') . ' · ' . $turma->anoLectivo->codigo;
    $counts = [
        'aprovado' => count($agrupado['aprovado']),
        'recurso' => count($agrupado['recurso']),
        'reprovado' => count($agrupado['reprovado']),
        'em_curso' => count($agrupado['em_curso']),
    ];
    $total = array_sum($counts);
@endphp
<x-pdf-layout :titulo="$titulo" :subtitulo="$subtitulo">
    <div class="info-row">
        <strong>{{ __('School Year') }}:</strong> {{ $turma->anoLectivo->codigo }}
        @if($turma->curso) · <strong>{{ __('Course') }}:</strong> {{ $turma->curso->nome }} @endif
    </div>

    <table class="stat-grid">
        <tr>
            <td class="ok"><div class="label">{{ __('Approved') }}</div><div class="value approved">{{ $counts['aprovado'] }}</div></td>
            <td class="warn"><div class="label">{{ __('Second-chance') }}</div><div class="value second">{{ $counts['recurso'] }}</div></td>
            <td class="bad"><div class="label">{{ __('Failed') }}</div><div class="value failed">{{ $counts['reprovado'] }}</div></td>
            <td><div class="label">{{ __('Total') }}</div><div class="value">{{ $total }}</div></td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 4%">#</th>
                <th style="width: 40%; text-align: left">{{ __('Student') }}</th>
                <th style="width: 18%">{{ __('Enrollment Number') }}</th>
                <th>{{ __('General average') }}</th>
                <th>{{ __('Negatives') }}</th>
                <th>{{ __('Situation') }}</th>
            </tr>
        </thead>
        <tbody>
            @php($idx = 0)
            @foreach(['aprovado', 'recurso', 'reprovado', 'em_curso'] as $sit)
                @foreach($agrupado[$sit] as $m)
                    @php($idx++)
                    <tr>
                        <td>{{ $idx }}</td>
                        <td class="name">{{ $m->aluno->user->name }}</td>
                        <td class="number">{{ $m->numero_matricula }}</td>
                        <td class="{{ ($resumo[$m->id]['media_geral'] ?? null) !== null && $resumo[$m->id]['media_geral'] < $calc->notaMinima ? 'neg' : '' }}"><strong>{{ $resumo[$m->id]['media_geral'] ?? '—' }}</strong></td>
                        <td>
                            @if(empty($resumo[$m->id]['negativas']))
                                <span class="approved">0</span>
                            @else
                                <span class="neg">{{ count($resumo[$m->id]['negativas']) }}</span>
                            @endif
                        </td>
                        <td class="{{ $sit === 'aprovado' ? 'approved' : ($sit === 'recurso' ? 'second' : ($sit === 'reprovado' ? 'failed' : 'pending')) }}">{{ __(ucfirst($sit)) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="footer-note">
        <p><strong>{{ __('Formula') }}:</strong> {{ $calc->formulaDescricao() }}</p>
        <p><strong>{{ __('Rule') }}:</strong> 0 {{ __('negatives') }} → {{ __('Aprovado') }} · 1–{{ $calc->maxNegativasRecurso }} → {{ __('Recurso') }} · {{ $calc->maxNegativasRecurso + 1 }}+ → {{ __('Reprovado') }}</p>
    </div>

    <div class="sig-block">
        <div class="sig-cell"><div class="sig-line">{{ __('Class Director') }}</div></div>
        <div class="sig-cell"><div class="sig-line">{{ __('Director Pedagógico') }}</div></div>
    </div>
</x-pdf-layout>
