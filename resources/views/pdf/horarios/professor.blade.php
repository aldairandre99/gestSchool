@php
    $titulo = $professor->user->name;
    $subtitulo = __('Schedule');
@endphp
<x-pdf-layout :titulo="$titulo" :subtitulo="$subtitulo">
    @include('pdf.horarios._grid', ['modo' => 'professor'])
</x-pdf-layout>
