<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Aula — {{ $aula->data->format('d/m/Y') }} · {{ $aula->atribuicao->turma->classe->nome }} {{ $aula->atribuicao->turma->nome }} · {{ $aula->atribuicao->disciplina->nome }}</h2></x-slot>
    <div class="py-8"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6 text-sm">
            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div><dt class="text-gray-500 text-xs">{{ __('Date') }}</dt><dd>{{ $aula->data->format('d/m/Y') }}</dd></div>
                <div><dt class="text-gray-500 text-xs">Horário</dt><dd>{{ $aula->hora_inicio ? \Carbon\Carbon::parse($aula->hora_inicio)->format('H:i') : '—' }}@if($aula->hora_fim) – {{ \Carbon\Carbon::parse($aula->hora_fim)->format('H:i') }}@endif</dd></div>
                <div><dt class="text-gray-500 text-xs">Nº</dt><dd>{{ $aula->numero ?? '—' }}</dd></div>
                <div class="sm:col-span-3"><dt class="text-gray-500 text-xs">Sumário</dt><dd class="whitespace-pre-line">{{ $aula->sumario ?? '—' }}</dd></div>
                @if($aula->conteudo_planeado)
                    <div class="sm:col-span-3"><dt class="text-gray-500 text-xs">Conteúdo planeado</dt><dd class="whitespace-pre-line text-gray-600">{{ $aula->conteudo_planeado }}</dd></div>
                @endif
            </dl>

            @php($total = $aula->presencas->count())
            @php($presentes = $aula->presencas->where('estado', 'presente')->count())
            @php($faltas = $aula->presencas->where('estado', 'falta')->count())
            @php($faltasJust = $aula->presencas->where('estado', 'falta_justificada')->count())
            @php($atrasos = $aula->presencas->where('estado', 'atraso')->count())

            <div class="mt-6 grid grid-cols-2 sm:grid-cols-5 gap-2 text-center text-xs">
                <div class="bg-gray-50 rounded p-2"><div class="text-gray-500">Total</div><div class="text-lg font-semibold">{{ $total }}</div></div>
                <div class="bg-green-50 rounded p-2"><div class="text-green-700">Presentes</div><div class="text-lg font-semibold text-green-800">{{ $presentes }}</div></div>
                <div class="bg-red-50 rounded p-2"><div class="text-red-700">Faltas</div><div class="text-lg font-semibold text-red-800">{{ $faltas }}</div></div>
                <div class="bg-yellow-50 rounded p-2"><div class="text-yellow-700">Faltas just.</div><div class="text-lg font-semibold text-yellow-800">{{ $faltasJust }}</div></div>
                <div class="bg-orange-50 rounded p-2"><div class="text-orange-700">Atrasos</div><div class="text-lg font-semibold text-orange-800">{{ $atrasos }}</div></div>
            </div>

            <div class="mt-6 flex gap-3">
                <a href="{{ route('presencas.folha', $aula) }}" class="px-4 py-2 bg-blue-700 text-white text-sm rounded">{{ __('Mark Attendance') }}</a>
                <a href="{{ route('aulas.edit', $aula) }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Edit') }}</a>
                <a href="{{ route('aulas.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a>
            </div>
        </div>
    </div></div>
</x-app-layout>
