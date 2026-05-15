{{-- Cabeçalho que só aparece na impressão --}}
<div class="print-only print-header mb-4">
    <h1 class="text-navy font-bold">{{ config('app.name', 'GestSchool') }}</h1>
    <p class="text-muted">{{ $titulo ?? '' }}</p>
    @if(isset($subtitulo))<p class="text-muted">{{ $subtitulo }}</p>@endif
    <p class="text-muted">Impressão: {{ now()->format('d/m/Y H:i') }}</p>
    <hr class="my-2">
</div>
