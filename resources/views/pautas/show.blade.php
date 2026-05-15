<x-app-layout>
    <x-page-header
        :title="__('Gradebook')"
        :subtitle="$atribuicao->turma->classe->nome . ' ' . $atribuicao->turma->nome . ' · ' . $atribuicao->disciplina->nome . ' · ' . $trimestre->numero . 'º ' . __('Term')">
        <x-slot name="actions">
            <x-btn variant="secondary" :href="route('pautas.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Student') }}</th>
                        @foreach($avaliacoes as $av)
                            <th class="text-center">
                                <div class="text-xs">{{ $av->titulo }}</div>
                                <div class="text-[10px] text-muted normal-case tracking-normal">peso {{ rtrim(rtrim($av->peso, '0'), '.') }}</div>
                            </th>
                        @endforeach
                        <th class="text-center !text-navy font-bold">{{ __('Term Average') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($matriculas as $m)
                        <tr>
                            <td class="font-semibold text-navy">{{ $m->aluno->user->name }}</td>
                            @foreach($avaliacoes as $av)
                                @php($v = $notasMap[$m->id][$av->id] ?? null)
                                <td class="text-center">{{ $v !== null ? rtrim(rtrim((string) $v, '0'), '.') : '—' }}</td>
                            @endforeach
                            <td class="text-center font-bold {{ ($medias[$m->id] ?? null) !== null && $medias[$m->id] < 10 ? 'text-danger' : 'text-navy' }}">
                                {{ ($medias[$m->id] ?? null) !== null ? $medias[$m->id] : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="{{ 2 + count($avaliacoes) }}" class="table-empty">{{ __('No records found.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>
