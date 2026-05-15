@php
    $titulo = $turma->nome_completo;
    $subtitulo = __('Schedule') . ' · ' . $turma->anoLectivo->codigo;
@endphp
<x-pdf-layout :titulo="$titulo" :subtitulo="$subtitulo">
    <div class="info-row">
        <strong>{{ __('School Year') }}:</strong> {{ $turma->anoLectivo->codigo }}
        @if($turma->curso) · <strong>{{ __('Course') }}:</strong> {{ $turma->curso->nome }} @endif
        · <strong>{{ __('Class Director') }}:</strong> {{ $turma->directorTurma?->user?->name ?? '—' }}
    </div>

    @include('pdf.horarios._grid', ['modo' => 'turma'])
</x-pdf-layout>
