@php
    $meses = [1=>__('January'),2=>__('February'),3=>__('March'),4=>__('April'),5=>__('May'),6=>__('June'),7=>__('July'),8=>__('August'),9=>__('September'),10=>__('October'),11=>__('November'),12=>__('December')];
    $diasSem = [__('Mon'),__('Tue'),__('Wed'),__('Thu'),__('Fri'),__('Sat'),__('Sun')];
    $titulo = $meses[$mes] . ' ' . $ano;
@endphp
<x-pdf-layout :titulo="$titulo" :subtitulo="__('School Calendar')">

    <table style="width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 12px">
        <thead>
            <tr>
                @foreach($diasSem as $d)
                    <th style="border: 1px solid #999; background: #f0f1f6; padding: 4px; font-size: 9px; width: 14.28%">{{ $d }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($semanas as $semana)
                <tr>
                    @foreach($semana as $dia)
                        @php
                            $dateStr = $dia->toDateString();
                            $inMonth = $dia->month === $mes;
                            $evs = $porDia[$dateStr] ?? [];
                            $bg = $inMonth ? '#fff' : '#f8f9fa';
                        @endphp
                        <td style="border: 1px solid #999; vertical-align: top; padding: 3px; height: 70px; background: {{ $bg }}; font-size: 8px">
                            <div style="text-align: right; color: {{ $inMonth ? '#001737' : '#bbb' }}; font-weight: bold">{{ $dia->day }}</div>
                            @foreach($evs as $ev)
                                <div style="background: {{ $ev->cor_efectiva }}20; color: {{ $ev->cor_efectiva }}; border-left: 2px solid {{ $ev->cor_efectiva }}; padding: 1px 3px; margin-top: 2px; font-size: 7px; overflow: hidden;">
                                    {{ \Illuminate\Support\Str::limit($ev->titulo, 18) }}
                                </div>
                            @endforeach
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3 style="color: #001737; font-size: 11pt; margin: 14px 0 6px">{{ __('Events this month') }}</h3>

    @if($mesEventos->isEmpty())
        <p style="color: #76838f; font-size: 9px">{{ __('No events this month') }}</p>
    @else
        <table class="data" style="font-size: 9px">
            <thead>
                <tr>
                    <th style="text-align: left">{{ __('Date') }}</th>
                    <th style="text-align: left">{{ __('Title') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Class Groups') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mesEventos as $ev)
                    <tr>
                        <td>{{ $ev->data_inicio->format('d/m') }}@if($ev->data_fim && $ev->data_fim->ne($ev->data_inicio)) – {{ $ev->data_fim->format('d/m') }}@endif</td>
                        <td class="name" style="text-align: left">{{ $ev->titulo }}</td>
                        <td style="color: {{ $ev->cor_efectiva }}">{{ $ev->tipo_nome }}</td>
                        <td>
                            @if($ev->turma)<x-turma-text :turma="$ev->turma" />
                            @elseif($ev->classe){{ $ev->classe->nome }}
                            @else <span style="color: #ccc">—</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-pdf-layout>
