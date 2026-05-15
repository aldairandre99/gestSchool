<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Assignments') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-end mb-3"><a href="{{ route('atribuicoes.create') }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('New') }}</a></div>
            <table class="min-w-full text-sm"><thead class="text-left text-gray-500 border-b"><tr>
                <th class="py-2 pr-3">{{ __('Teacher') }}</th>
                <th class="py-2 pr-3">{{ __('Class Groups') }}</th>
                <th class="py-2 pr-3">{{ __('Subjects List') }}</th>
                <th class="py-2 pr-3">{{ __('School Year') }}</th>
                <th class="py-2 pr-3 text-right">{{ __('Actions') }}</th>
            </tr></thead><tbody>
                @forelse($atribuicoes as $a)
                    <tr class="border-b last:border-0">
                        <td class="py-2 pr-3 font-medium">{{ $a->professor->user->name }}</td>
                        <td class="py-2 pr-3">{{ $a->turma->classe->nome }} {{ $a->turma->nome }}</td>
                        <td class="py-2 pr-3">{{ $a->disciplina->nome }}</td>
                        <td class="py-2 pr-3">{{ $a->anoLectivo->codigo }}</td>
                        <td class="py-2 pr-3 text-right">
                            <a href="{{ route('atribuicoes.edit', $a) }}" class="text-gray-700 text-xs">{{ __('Edit') }}</a>
                            <form action="{{ route('atribuicoes.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('?');">@csrf @method('DELETE')<button class="text-red-600 text-xs ms-2">{{ __('Delete') }}</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-center text-gray-500">{{ __('No records found.') }}</td></tr>
                @endforelse
            </tbody></table>
            <div class="mt-4">{{ $atribuicoes->links() }}</div>
        </div>
    </div></div>
</x-app-layout>
