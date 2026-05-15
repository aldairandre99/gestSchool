<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Class Groups') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-end mb-3"><a href="{{ route('turmas.create') }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('New') }}</a></div>
            <table class="min-w-full text-sm"><thead class="text-left text-gray-500 border-b"><tr>
                <th class="py-2 pr-3">{{ __('Class Groups') }}</th>
                <th class="py-2 pr-3">{{ __('School Year') }}</th>
                <th class="py-2 pr-3">{{ __('Room') }}</th>
                <th class="py-2 pr-3">{{ __('Shift') }}</th>
                <th class="py-2 pr-3">{{ __('Class Director') }}</th>
                <th class="py-2 pr-3">{{ __('Students') }}</th>
                <th class="py-2 pr-3 text-right">{{ __('Actions') }}</th>
            </tr></thead><tbody>
                @forelse($turmas as $t)
                    <tr class="border-b last:border-0">
                        <td class="py-2 pr-3 font-medium">{{ $t->classe->nome }} {{ $t->nome }}</td>
                        <td class="py-2 pr-3">{{ $t->anoLectivo->codigo }}</td>
                        <td class="py-2 pr-3">{{ $t->sala ?? '—' }}</td>
                        <td class="py-2 pr-3">{{ $t->turno ?? '—' }}</td>
                        <td class="py-2 pr-3">{{ $t->directorTurma?->user?->name ?? '—' }}</td>
                        <td class="py-2 pr-3">{{ $t->alunos_count }}</td>
                        <td class="py-2 pr-3 text-right">
                            <a href="{{ route('turmas.show', $t) }}" class="text-blue-600 text-xs">View</a>
                            <a href="{{ route('turmas.edit', $t) }}" class="text-gray-700 text-xs ms-2">{{ __('Edit') }}</a>
                            <form action="{{ route('turmas.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('?');">@csrf @method('DELETE')<button class="text-red-600 text-xs ms-2">{{ __('Delete') }}</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-4 text-center text-gray-500">{{ __('No records found.') }}</td></tr>
                @endforelse
            </tbody></table>
            <div class="mt-4">{{ $turmas->links() }}</div>
        </div>
    </div></div>
</x-app-layout>
