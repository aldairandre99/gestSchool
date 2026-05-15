<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Subjects List') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-end mb-3"><a href="{{ route('disciplinas.create') }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('New') }}</a></div>
            <table class="min-w-full text-sm"><thead class="text-left text-gray-500 border-b"><tr>
                <th class="py-2 pr-3">{{ __('Abbreviation') }}</th>
                <th class="py-2 pr-3">{{ __('Name') }}</th>
                <th class="py-2 pr-3">{{ __('Weekly Hours') }}</th>
                <th class="py-2 pr-3">{{ __('Status') }}</th>
                <th class="py-2 pr-3 text-right">{{ __('Actions') }}</th>
            </tr></thead><tbody>
                @forelse($disciplinas as $d)
                    <tr class="border-b last:border-0">
                        <td class="py-2 pr-3 font-mono text-xs">{{ $d->sigla ?? '—' }}</td>
                        <td class="py-2 pr-3 font-medium">{{ $d->nome }}</td>
                        <td class="py-2 pr-3">{{ $d->carga_horaria_semanal ?? '—' }}</td>
                        <td class="py-2 pr-3">{{ $d->activa ? __('Active') : __('Inactive') }}</td>
                        <td class="py-2 pr-3 text-right">
                            <a href="{{ route('disciplinas.edit', $d) }}" class="text-gray-700 text-xs">{{ __('Edit') }}</a>
                            <form action="{{ route('disciplinas.destroy', $d) }}" method="POST" class="inline" onsubmit="return confirm('?');">@csrf @method('DELETE')<button class="text-red-600 text-xs ms-2">{{ __('Delete') }}</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-center text-gray-500">{{ __('No records found.') }}</td></tr>
                @endforelse
            </tbody></table>
            <div class="mt-4">{{ $disciplinas->links() }}</div>
        </div>
    </div></div>
</x-app-layout>
