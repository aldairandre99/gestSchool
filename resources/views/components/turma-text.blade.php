@props([
    'turma',
    'showAno' => false,
])
@php
    $nome = ($turma->classe->nome ?? '') . ' ' . ($turma->nome ?? '');
    $isMedio = ($turma->classe->nivel ?? null) === 'ensino_medio';
    $extra = '';
    if ($isMedio && $turma->curso) {
        $extra = ' (' . $turma->curso->sigla . ')';
    } elseif (! $isMedio) {
        $extra = ' [' . __('base') . ']';
    }
@endphp{{ $nome }}{{ $extra }}@if($showAno && $turma->anoLectivo) — {{ $turma->anoLectivo->codigo }}@endif
