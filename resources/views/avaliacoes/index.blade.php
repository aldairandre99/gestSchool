<x-app-layout>
    <x-page-header :title="__('Evaluations')" />

    <x-data-table :createUrl="route('avaliacoes.create')">
        <thead>
            <tr>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Class Groups') }}</th>
                <th>{{ __('Subjects List') }}</th>
                <th>{{ __('Term') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Weight') }}</th>
                <th>{{ __('Date') }}</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @php($tipoCor = ['prova' => 'primary', 'teste' => 'info', 'avaliacao_continua' => 'warning', 'exame' => 'danger'])
            @forelse($avaliacoes as $av)
                <tr>
                    <td class="font-semibold text-navy">{{ $av->titulo }}</td>
                    <td>{{ $av->atribuicao->turma->classe->nome }} {{ $av->atribuicao->turma->nome }}</td>
                    <td>{{ $av->atribuicao->disciplina->nome }}</td>
                    <td>{{ $av->trimestre->numero }}º</td>
                    <td><x-badge :variant="$tipoCor[$av->tipo] ?? 'muted'">{{ str_replace('_', ' ', ucfirst($av->tipo)) }}</x-badge></td>
                    <td>{{ rtrim(rtrim($av->peso, '0'), '.') }}</td>
                    <td>{{ $av->data?->format('d/m') ?? '—' }}</td>
                    <td class="table-actions">
                        <x-btn-link :href="route('notas.folha', $av)">{{ __('Launch Grades') }}</x-btn-link>
                        <x-btn-link variant="muted" :href="route('avaliacoes.edit', $av)">{{ __('Edit') }}</x-btn-link>
                        <form action="{{ route('avaliacoes.destroy', $av) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar?');">
                            @csrf @method('DELETE')<button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="table-empty">{{ __('No records found.') }}</td></tr>
            @endforelse
        </tbody>
        <x-slot name="footer">{{ $avaliacoes->links() }}</x-slot>
    </x-data-table>
</x-app-layout>
