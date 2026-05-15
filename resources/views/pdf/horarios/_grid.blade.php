@php
    $tempos = config('escola.tempos_lectivos');
    $diasAll = \App\Models\Horario::diasSemana();
    $dias = [];
    foreach (config('escola.dias_lectivos', [1,2,3,4,5]) as $d) {
        $dias[$d] = $diasAll[$d];
    }
@endphp
<table class="data" style="font-size: 9px; table-layout: fixed">
    <thead>
        <tr>
            <th style="width: 80px">{{ __('Time') }}</th>
            @foreach($dias as $num => $nome)
                <th>{{ $nome }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($tempos as $tempoNum => $bloco)
            <tr>
                <td style="font-size: 8px">
                    <strong>{{ $tempoNum }}º</strong><br>
                    {{ $bloco[0] }} – {{ $bloco[1] }}
                </td>
                @foreach($dias as $diaNum => $diaNome)
                    @php($key = $diaNum . '-' . $tempoNum)
                    @php($slot = $horarios[$key] ?? null)
                    <td style="vertical-align: top; padding: 4px">
                        @if($slot)
                            @foreach($slot as $h)
                                <div style="font-size: 9px; line-height: 1.3">
                                    <strong>{{ $h->atribuicao->disciplina->nome }}</strong><br>
                                    <span style="color: #76838f">
                                        @if($modo === 'turma')
                                            {{ $h->atribuicao->professor->user->name }}
                                        @else
                                            <x-turma-text :turma="$h->atribuicao->turma" />
                                        @endif
                                    </span>
                                    @if($h->sala)<br><span style="color: #76838f; font-size: 8px">Sala {{ $h->sala }}</span>@endif
                                </div>
                            @endforeach
                        @else
                            <span style="color: #ccc">—</span>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
