@props([
    'turma',
    'showAno' => false,        // mostra ano lectivo entre parêntesis
    'showLevel' => true,       // mostra badge do curso ou "base"
    'inline' => false,         // versão compacta sem badges para tabelas densas
])

@php
    $nome = ($turma->classe->nome ?? '') . ' ' . ($turma->nome ?? '');
    $isMedio = ($turma->classe->nivel ?? null) === 'ensino_medio';
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5']) }}>
    <span class="font-semibold text-navy">{{ $nome }}</span>

    @if($showLevel && ! $inline)
        @if($isMedio && $turma->curso)
            <span class="badge badge-info" title="{{ $turma->curso->nome }}">{{ $turma->curso->sigla }}</span>
        @elseif(! $isMedio)
            <span class="badge badge-muted" title="{{ __('Basic Education') }}">{{ __('base') }}</span>
        @endif
    @endif

    @if($showAno && $turma->anoLectivo)
        <span class="text-xs text-muted">({{ $turma->anoLectivo->codigo }})</span>
    @endif
</span>
