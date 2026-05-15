<x-app-layout>
    <x-page-header :title="__('Students')" />

    <x-data-table
        :searchPlaceholder="__('Search') . ' nome, processo, turma'"
        :searchValue="$q ?? ''"
        :createUrl="auth()->user()->hasAnyRole(['director_geral', 'director_pedagogico', 'secretario']) ? route('alunos.create') : null">
        <thead>
            <tr>
                <th>{{ __('Process Number') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Grade') }}</th>
                <th>{{ __('Class') }}</th>
                <th>{{ __('School Year') }}</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($alunos as $a)
                <tr>
                    <td class="font-mono text-xs">{{ $a->numero_processo }}</td>
                    <td class="font-semibold text-navy">{{ $a->user->name }}</td>
                    <td>{{ $a->classe ?? '—' }}</td>
                    <td>{{ $a->turma ?? '—' }}</td>
                    <td class="text-muted">{{ $a->ano_lectivo ?? '—' }}</td>
                    <td class="table-actions">
                        <x-btn-link :href="route('alunos.show', $a)">Ver</x-btn-link>
                        @hasanyrole('director_geral|director_pedagogico|secretario')
                            <x-btn-link variant="muted" :href="route('alunos.edit', $a)">{{ __('Edit') }}</x-btn-link>
                            <form action="{{ route('alunos.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar?');">
                                @csrf @method('DELETE')<button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                            </form>
                        @endhasanyrole
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="table-empty">{{ __('No records found.') }}</td></tr>
            @endforelse
        </tbody>
        <x-slot name="footer">{{ $alunos->links() }}</x-slot>
    </x-data-table>
</x-app-layout>
