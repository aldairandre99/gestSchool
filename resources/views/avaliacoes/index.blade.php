<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Evaluations') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-end mb-3"><a href="{{ route('avaliacoes.create') }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('New') }}</a></div>
            <table class="min-w-full text-sm"><thead class="text-left text-gray-500 border-b"><tr>
                <th class="py-2 pr-3">{{ __('Title') }}</th>
                <th class="py-2 pr-3">{{ __('Class Groups') }}</th>
                <th class="py-2 pr-3">{{ __('Subjects List') }}</th>
                <th class="py-2 pr-3">{{ __('Term') }}</th>
                <th class="py-2 pr-3">{{ __('Type') }}</th>
                <th class="py-2 pr-3">{{ __('Weight') }}</th>
                <th class="py-2 pr-3">{{ __('Date') }}</th>
                <th class="py-2 pr-3 text-right">{{ __('Actions') }}</th>
            </tr></thead><tbody>
                @forelse($avaliacoes as $av)
                    <tr class="border-b last:border-0">
                        <td class="py-2 pr-3 font-medium">{{ $av->titulo }}</td>
                        <td class="py-2 pr-3">{{ $av->atribuicao->turma->classe->nome }} {{ $av->atribuicao->turma->nome }}</td>
                        <td class="py-2 pr-3">{{ $av->atribuicao->disciplina->nome }}</td>
                        <td class="py-2 pr-3">{{ $av->trimestre->numero }}º</td>
                        <td class="py-2 pr-3 text-xs">{{ __(str_replace('_', ' ', ucfirst($av->tipo))) }}</td>
                        <td class="py-2 pr-3">{{ rtrim(rtrim($av->peso, '0'), '.') }}</td>
                        <td class="py-2 pr-3">{{ $av->data?->format('d/m') ?? '—' }}</td>
                        <td class="py-2 pr-3 text-right">
                            <a href="{{ route('notas.folha', $av) }}" class="text-blue-600 text-xs">{{ __('Launch Grades') }}</a>
                            <a href="{{ route('avaliacoes.edit', $av) }}" class="text-gray-700 text-xs ms-2">{{ __('Edit') }}</a>
                            <form action="{{ route('avaliacoes.destroy', $av) }}" method="POST" class="inline" onsubmit="return confirm('?');">@csrf @method('DELETE')<button class="text-red-600 text-xs ms-2">{{ __('Delete') }}</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="py-4 text-center text-gray-500">{{ __('No records found.') }}</td></tr>
                @endforelse
            </tbody></table>
            <div class="mt-4">{{ $avaliacoes->links() }}</div>
        </div>
    </div></div>
</x-app-layout>
