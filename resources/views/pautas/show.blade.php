<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Gradebook') }} — {{ $atribuicao->turma->classe->nome }} {{ $atribuicao->turma->nome }} · {{ $atribuicao->disciplina->nome }} · {{ $trimestre->numero }}º</h2></x-slot>
    <div class="py-8"><div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="text-left text-gray-500 border-b"><tr>
                    <th class="py-2 pr-3">{{ __('Student') }}</th>
                    @foreach($avaliacoes as $av)
                        <th class="py-2 pr-3 text-center">
                            <div class="text-xs">{{ $av->titulo }}</div>
                            <div class="text-[10px] text-gray-400">peso {{ rtrim(rtrim($av->peso, '0'), '.') }}</div>
                        </th>
                    @endforeach
                    <th class="py-2 pr-3 text-center font-semibold">{{ __('Term Average') }}</th>
                </tr></thead>
                <tbody>
                    @forelse($matriculas as $m)
                        <tr class="border-b last:border-0">
                            <td class="py-2 pr-3 font-medium">{{ $m->aluno->user->name }}</td>
                            @foreach($avaliacoes as $av)
                                @php($v = $notasMap[$m->id][$av->id] ?? null)
                                <td class="py-2 pr-3 text-center">{{ $v !== null ? rtrim(rtrim((string) $v, '0'), '.') : '—' }}</td>
                            @endforeach
                            <td class="py-2 pr-3 text-center font-semibold {{ ($medias[$m->id] ?? null) !== null && $medias[$m->id] < 10 ? 'text-red-600' : 'text-gray-800' }}">
                                {{ ($medias[$m->id] ?? null) !== null ? $medias[$m->id] : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ 2 + count($avaliacoes) }}" class="py-4 text-center text-gray-500">{{ __('No records found.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-6"><a href="{{ route('pautas.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a></div>
        </div>
    </div></div>
</x-app-layout>
