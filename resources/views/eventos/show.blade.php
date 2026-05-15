<x-app-layout>
    <x-page-header :title="$evento->titulo" :subtitle="$evento->tipo_nome">
        <x-slot name="actions">
            @hasanyrole('director_geral|director_pedagogico|secretario')
                <x-btn variant="primary" icon="pencil" :href="route('eventos.edit', $evento)">{{ __('Edit') }}</x-btn>
            @endhasanyrole
            <x-btn variant="secondary" :href="route('eventos.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <div class="flex items-start gap-3 mb-4">
            <div class="w-3 h-12 rounded shrink-0" style="background-color: {{ $evento->cor_efectiva }};"></div>
            <div>
                <h3 class="text-lg font-bold text-navy">{{ $evento->titulo }}</h3>
                <p class="text-sm text-muted">{{ $evento->tipo_nome }} · {{ $evento->anoLectivo->codigo }}</p>
            </div>
        </div>

        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Start') }}</dt><dd>{{ $evento->data_inicio->format('d/m/Y') }}@if($evento->hora_inicio) {{ \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i') }}@endif</dd></div>
            @if($evento->data_fim && $evento->data_fim->ne($evento->data_inicio))
                <div><dt class="form-label">{{ __('End') }}</dt><dd>{{ $evento->data_fim->format('d/m/Y') }}@if($evento->hora_fim) {{ \Carbon\Carbon::parse($evento->hora_fim)->format('H:i') }}@endif</dd></div>
            @endif
            <div><dt class="form-label">{{ __('All day') }}</dt><dd>{{ $evento->dia_inteiro ? __('Yes') : __('No') }}</dd></div>
            @if($evento->classe)
                <div><dt class="form-label">{{ __('Class') }}</dt><dd>{{ $evento->classe->nome }}</dd></div>
            @endif
            @if($evento->turma)
                <div><dt class="form-label">{{ __('Class Groups') }}</dt><dd><x-turma-label :turma="$evento->turma" /></dd></div>
            @endif
            <div><dt class="form-label">{{ __('Author') }}</dt><dd>{{ $evento->autor?->name ?? '—' }}</dd></div>
        </dl>

        @if($evento->descricao)
            <div class="mt-6 pt-6 border-t border-gray-100">
                <dt class="form-label mb-2">{{ __('Description') }}</dt>
                <p class="text-sm text-navy whitespace-pre-line">{{ $evento->descricao }}</p>
            </div>
        @endif
    </x-card>
</x-app-layout>
