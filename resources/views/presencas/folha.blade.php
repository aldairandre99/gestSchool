<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Attendance Sheet') }} — {{ $atribuicao->turma->classe->nome }} {{ $atribuicao->turma->nome }} · {{ $atribuicao->disciplina->nome }}</h2></x-slot>
    <div class="py-8"><div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            <form method="GET" class="flex gap-3 items-end mb-4">
                <div>
                    <x-input-label for="data" :value="__('Date')" />
                    <input type="date" name="data" id="data" value="{{ $data }}" class="border-gray-300 rounded-md text-sm mt-1">
                </div>
                <button class="px-3 py-2 bg-gray-100 text-sm rounded">{{ __('Search') }}</button>
            </form>
            <form method="POST" action="{{ route('presencas.gravar', $atribuicao) }}">
                @csrf
                <input type="hidden" name="data" value="{{ $data }}">
                <table class="min-w-full text-sm">
                    <thead class="text-left text-gray-500 border-b"><tr>
                        <th class="py-2 pr-3">{{ __('Student') }}</th>
                        <th class="py-2 pr-3">{{ __('Status') }}</th>
                        <th class="py-2 pr-3">Observação</th>
                    </tr></thead>
                    <tbody>
                        @forelse($matriculas as $m)
                            @php($existente = $existentes[$m->id] ?? null)
                            <tr class="border-b last:border-0">
                                <td class="py-2 pr-3">
                                    <div class="font-medium text-gray-800">{{ $m->aluno->user->name }}</div>
                                    <div class="text-xs text-gray-500 font-mono">{{ $m->numero_matricula }}</div>
                                </td>
                                <td class="py-2 pr-3">
                                    <select name="estados[{{ $m->id }}]" class="border-gray-300 rounded-md text-sm">
                                        @foreach([
                                            'presente' => __('Present'),
                                            'falta' => __('Absent'),
                                            'falta_justificada' => __('Justified Absence'),
                                            'atraso' => __('Late'),
                                        ] as $k => $label)
                                            <option value="{{ $k }}" @selected(($existente?->estado ?? 'presente') === $k)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-2 pr-3">
                                    <input type="text" name="observacoes[{{ $m->id }}]" value="{{ $existente?->observacao }}" class="w-full border-gray-300 rounded-md text-sm">
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-4 text-center text-gray-500">{{ __('No records found.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-6 flex gap-3">
                    <button class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Save Attendance') }}</button>
                    <a href="{{ route('presencas.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a>
                </div>
            </form>
        </div>
    </div></div>
</x-app-layout>
