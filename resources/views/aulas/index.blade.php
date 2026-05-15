<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Aulas</h2></x-slot>
    <div class="py-8"><div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            @php($hoje = now()->toDateString())
            @php($hojeActivo = $dataDe === $hoje && $dataAte === $hoje)
            <form method="GET" class="flex flex-wrap items-end gap-3 mb-4">
                <div>
                    <label for="turma_id" class="block text-xs text-gray-500">{{ __('Class Groups') }}</label>
                    <select id="turma_id" name="turma_id" class="mt-1 border-gray-300 rounded-md text-sm">
                        <option value="">Todas</option>
                        @foreach($turmas as $t)
                            <option value="{{ $t->id }}" @selected($turmaId == $t->id)>{{ $t->classe->nome }} {{ $t->nome }} — {{ $t->anoLectivo->codigo }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="disciplina_id" class="block text-xs text-gray-500">{{ __('Subjects List') }}</label>
                    <select id="disciplina_id" name="disciplina_id" class="mt-1 border-gray-300 rounded-md text-sm">
                        <option value="">Todas</option>
                        @foreach($disciplinas as $d)
                            <option value="{{ $d->id }}" @selected($disciplinaId == $d->id)>{{ $d->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="data_de" class="block text-xs text-gray-500">De</label>
                    <input type="date" id="data_de" name="data_de" value="{{ $dataDe }}" class="mt-1 border-gray-300 rounded-md text-sm">
                </div>
                <div>
                    <label for="data_ate" class="block text-xs text-gray-500">Até</label>
                    <input type="date" id="data_ate" name="data_ate" value="{{ $dataAte }}" class="mt-1 border-gray-300 rounded-md text-sm">
                </div>
                <button class="px-3 py-2 bg-gray-100 text-sm rounded">{{ __('Search') }}</button>
                <a href="{{ route('aulas.index', array_filter(['turma_id' => $turmaId, 'disciplina_id' => $disciplinaId, 'data_de' => $hoje, 'data_ate' => $hoje])) }}"
                   class="px-3 py-2 text-sm rounded {{ $hojeActivo ? 'bg-gray-800 text-white' : 'bg-gray-100 hover:bg-gray-200' }}">
                    Hoje
                </a>
                @if($turmaId || $disciplinaId || $dataDe || $dataAte)
                    <a href="{{ route('aulas.index') }}" class="px-3 py-2 text-xs text-gray-500 hover:text-gray-700">Limpar</a>
                @endif
                <a href="{{ route('aulas.create') }}" class="ms-auto px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('New') }} aula</a>
            </form>
            <div class="overflow-x-auto">
            <table class="min-w-full text-sm"><thead class="text-left text-gray-500 border-b"><tr>
                <th class="py-2 pr-3">{{ __('Date') }}</th>
                <th class="py-2 pr-3">Hora</th>
                <th class="py-2 pr-3">{{ __('Class Groups') }}</th>
                <th class="py-2 pr-3">{{ __('Subjects List') }}</th>
                <th class="py-2 pr-3">Nº</th>
                <th class="py-2 pr-3">Sumário</th>
                <th class="py-2 pr-3 text-right">{{ __('Actions') }}</th>
            </tr></thead><tbody>
                @forelse($aulas as $a)
                    <tr class="border-b last:border-0">
                        <td class="py-2 pr-3">{{ $a->data->format('d/m/Y') }}</td>
                        <td class="py-2 pr-3 text-xs">{{ $a->hora_inicio ? \Carbon\Carbon::parse($a->hora_inicio)->format('H:i') : '—' }}@if($a->hora_fim) – {{ \Carbon\Carbon::parse($a->hora_fim)->format('H:i') }}@endif</td>
                        <td class="py-2 pr-3 font-medium">{{ $a->atribuicao->turma->classe->nome }} {{ $a->atribuicao->turma->nome }}</td>
                        <td class="py-2 pr-3">{{ $a->atribuicao->disciplina->nome }}</td>
                        <td class="py-2 pr-3 text-gray-500">{{ $a->numero ?? '—' }}</td>
                        <td class="py-2 pr-3 text-xs text-gray-600">{{ \Illuminate\Support\Str::limit($a->sumario, 60) ?: '—' }}</td>
                        <td class="py-2 pr-3 text-right whitespace-nowrap">
                            <a href="{{ route('presencas.folha', $a) }}" class="text-blue-600 text-xs">{{ __('Mark Attendance') }}</a>
                            <a href="{{ route('aulas.edit', $a) }}" class="text-gray-700 text-xs ms-2">{{ __('Edit') }}</a>
                            <form action="{{ route('aulas.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('?');">@csrf @method('DELETE')<button class="text-red-600 text-xs ms-2">{{ __('Delete') }}</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-4 text-center text-gray-500">{{ __('No records found.') }}</td></tr>
                @endforelse
            </tbody></table>
            </div>
            <div class="mt-4">{{ $aulas->links() }}</div>
        </div>
    </div></div>
</x-app-layout>
