<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Aulas</h2></x-slot>
    <div class="py-8"><div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-end mb-3"><a href="{{ route('aulas.create') }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('New') }} aula</a></div>
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
