<x-app-layout>
    @php($total = $aula->presencas->count())
    @php($presentes = $aula->presencas->where('estado', 'presente')->count())
    @php($faltas = $aula->presencas->where('estado', 'falta')->count())
    @php($faltasJust = $aula->presencas->where('estado', 'falta_justificada')->count())
    @php($atrasos = $aula->presencas->where('estado', 'atraso')->count())

    <x-page-header :title="__('Lesson') . ' — ' . $aula->data->format('d/m/Y')">
        <x-slot name="subtitleSlot">
            <x-turma-label :turma="$aula->atribuicao->turma" /> · {{ $aula->atribuicao->disciplina->nome }}
        </x-slot>
        <x-slot name="actions">
            <x-btn variant="primary" icon="clipboard-check" :href="route('presencas.folha', $aula)">{{ __('Mark Attendance') }}</x-btn>
            <x-btn variant="secondary" :href="route('aulas.edit', $aula)">{{ __('Edit') }}</x-btn>
            <x-btn variant="secondary" :href="route('aulas.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Date') }}</dt><dd>{{ $aula->data->format('d/m/Y') }}</dd></div>
            <div><dt class="form-label">{{ __('Time') }}</dt><dd>{{ $aula->hora_inicio ? \Carbon\Carbon::parse($aula->hora_inicio)->format('H:i') : '—' }}@if($aula->hora_fim) – {{ \Carbon\Carbon::parse($aula->hora_fim)->format('H:i') }}@endif</dd></div>
            <div><dt class="form-label">{{ __('Nº') ?? 'Nº' }}</dt><dd>{{ $aula->numero ?? '—' }}</dd></div>
            <div class="sm:col-span-3"><dt class="form-label">{{ __('Summary') }}</dt><dd class="whitespace-pre-line text-navy">{{ $aula->sumario ?? '—' }}</dd></div>
            @if($aula->conteudo_planeado)
                <div class="sm:col-span-3"><dt class="form-label">{{ __('Planned content') }}</dt><dd class="whitespace-pre-line text-muted">{{ $aula->conteudo_planeado }}</dd></div>
            @endif
        </dl>
    </x-card>

    <x-card :title="__('Attendance summary')">
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <div class="bg-gray-50 rounded p-4 text-center">
                <p class="stat-label">{{ __('Total') }}</p>
                <p class="text-2xl font-bold text-navy">{{ $total }}</p>
            </div>
            <div class="bg-success-soft rounded p-4 text-center">
                <p class="stat-label text-success">{{ __('Present') }}</p>
                <p class="text-2xl font-bold text-success">{{ $presentes }}</p>
            </div>
            <div class="bg-danger-soft rounded p-4 text-center">
                <p class="stat-label text-danger">{{ __('Absent') }}</p>
                <p class="text-2xl font-bold text-danger">{{ $faltas }}</p>
            </div>
            <div class="bg-warning-soft rounded p-4 text-center">
                <p class="stat-label text-yellow-800">{{ __('Justified Absence') }}</p>
                <p class="text-2xl font-bold text-yellow-800">{{ $faltasJust }}</p>
            </div>
            <div class="rounded p-4 text-center" style="background:#fff1e3">
                <p class="stat-label" style="color:#b86a14">{{ __('Late') }}</p>
                <p class="text-2xl font-bold" style="color:#b86a14">{{ $atrasos }}</p>
            </div>
        </div>
    </x-card>
</x-app-layout>
