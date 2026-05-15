<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Enrollments') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            <form method="GET" class="flex flex-wrap gap-2 mb-4 items-end">
                <div class="grow"><input type="text" name="q" value="{{ $q }}" placeholder="{{ __('Search') }}" class="border-gray-300 rounded-md text-sm w-full sm:w-72"></div>
                <select name="ano_lectivo_id" class="border-gray-300 rounded-md text-sm">
                    <option value="">{{ __('School Year') }}</option>
                    @foreach($anos as $a)<option value="{{ $a->id }}" @selected($anoId == $a->id)>{{ $a->codigo }}</option>@endforeach
                </select>
                <select name="turma_id" class="border-gray-300 rounded-md text-sm">
                    <option value="">{{ __('Class Groups') }}</option>
                    @foreach($turmas as $t)<option value="{{ $t->id }}" @selected($turmaId == $t->id)>{{ $t->classe->nome }} {{ $t->nome }}</option>@endforeach
                </select>
                <button class="px-3 py-2 bg-gray-100 text-sm rounded">{{ __('Search') }}</button>
                <a href="{{ route('matriculas.create') }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded ms-auto">{{ __('New') }}</a>
            </form>
            <div class="overflow-x-auto"><table class="min-w-full text-sm"><thead class="text-left text-gray-500 border-b"><tr>
                <th class="py-2 pr-3">Nº</th>
                <th class="py-2 pr-3">{{ __('Student') }}</th>
                <th class="py-2 pr-3">{{ __('Class Groups') }}</th>
                <th class="py-2 pr-3">{{ __('School Year') }}</th>
                <th class="py-2 pr-3">{{ __('Status') }}</th>
                <th class="py-2 pr-3 text-right">{{ __('Actions') }}</th>
            </tr></thead><tbody>
                @forelse($matriculas as $m)
                    <tr class="border-b last:border-0">
                        <td class="py-2 pr-3 font-mono text-xs">{{ $m->numero_matricula }}</td>
                        <td class="py-2 pr-3 font-medium">{{ $m->aluno->user->name }}</td>
                        <td class="py-2 pr-3">{{ $m->turma->classe->nome }} {{ $m->turma->nome }}</td>
                        <td class="py-2 pr-3">{{ $m->anoLectivo->codigo }}</td>
                        <td class="py-2 pr-3 text-xs">{{ __(str_replace('_', ' ', ucfirst($m->estado))) }}</td>
                        <td class="py-2 pr-3 text-right">
                            <a href="{{ route('boletim.show', $m) }}" class="text-blue-600 text-xs">{{ __('Report Card') }}</a>
                            <a href="{{ route('matriculas.edit', $m) }}" class="text-gray-700 text-xs ms-2">{{ __('Edit') }}</a>
                            <form action="{{ route('matriculas.destroy', $m) }}" method="POST" class="inline" onsubmit="return confirm('?');">@csrf @method('DELETE')<button class="text-red-600 text-xs ms-2">{{ __('Delete') }}</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-4 text-center text-gray-500">{{ __('No records found.') }}</td></tr>
                @endforelse
            </tbody></table></div>
            <div class="mt-4">{{ $matriculas->links() }}</div>
        </div>
    </div></div>
</x-app-layout>
