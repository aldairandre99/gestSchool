<x-app-layout>
    <x-page-header :title="__('Gradebook')" subtitle="Escolha uma turma/disciplina e um trimestre" />

    <x-card>
        @if($atribuicoes->isEmpty())
            <x-empty title="{{ __('No assignments') }}" />
        @else
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{ __('Class Groups') }}</th>
                            <th>{{ __('Subjects List') }}</th>
                            @foreach($trimestres->groupBy('ano_lectivo_id')->first() ?? collect() as $t)
                                <th class="text-center">{{ $t->numero }}º</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($atribuicoes as $a)
                            <tr>
                                <td class="font-semibold text-navy">{{ $a->turma->classe->nome }} {{ $a->turma->nome }}</td>
                                <td>{{ $a->disciplina->nome }}</td>
                                @foreach($trimestres->where('ano_lectivo_id', $a->ano_lectivo_id)->sortBy('numero') as $t)
                                    <td class="text-center">
                                        <x-btn-link :href="route('pautas.show', ['atribuicao' => $a, 'trimestre' => $t])">{{ __('View') }}</x-btn-link>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>
</x-app-layout>
