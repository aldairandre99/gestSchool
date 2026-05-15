<x-app-layout>
    <x-page-header :title="__('Assignments')" />

    <x-data-table :createUrl="route('atribuicoes.create')">
        <thead>
            <tr>
                <th>{{ __('Teacher') }}</th>
                <th>{{ __('Class Groups') }}</th>
                <th>{{ __('Subjects List') }}</th>
                <th>{{ __('School Year') }}</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($atribuicoes as $a)
                <tr>
                    <td class="font-semibold text-navy">{{ $a->professor->user->name }}</td>
                    <td>{{ $a->turma->classe->nome }} {{ $a->turma->nome }}</td>
                    <td>{{ $a->disciplina->nome }}</td>
                    <td>{{ $a->anoLectivo->codigo }}</td>
                    <td class="table-actions">
                        <x-btn-link variant="muted" :href="route('atribuicoes.edit', $a)">{{ __('Edit') }}</x-btn-link>
                        <form action="{{ route('atribuicoes.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar?');">
                            @csrf @method('DELETE')<button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="table-empty">{{ __('No records found.') }}</td></tr>
            @endforelse
        </tbody>
        <x-slot name="footer">{{ $atribuicoes->links() }}</x-slot>
    </x-data-table>
</x-app-layout>
