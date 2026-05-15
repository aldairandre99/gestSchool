<x-app-layout>
    <x-page-header :title="__('Class Groups')" />

    <x-data-table :createUrl="route('turmas.create')">
        <thead>
            <tr>
                <th>{{ __('Class Groups') }}</th>
                <th>{{ __('School Year') }}</th>
                <th>{{ __('Room') }}</th>
                <th>{{ __('Shift') }}</th>
                <th>{{ __('Class Director') }}</th>
                <th>{{ __('Students') }}</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($turmas as $t)
                <tr>
                    <td class="font-semibold text-navy">{{ $t->classe->nome }} {{ $t->nome }}</td>
                    <td>{{ $t->anoLectivo->codigo }}</td>
                    <td>{{ $t->sala ?? '—' }}</td>
                    <td>{{ $t->turno ?? '—' }}</td>
                    <td>{{ $t->directorTurma?->user?->name ?? '—' }}</td>
                    <td><x-badge variant="primary">{{ $t->alunos_count }}</x-badge></td>
                    <td class="table-actions">
                        <x-btn-link :href="route('turmas.show', $t)">Ver</x-btn-link>
                        <x-btn-link variant="muted" :href="route('turmas.edit', $t)">{{ __('Edit') }}</x-btn-link>
                        <form action="{{ route('turmas.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar?');">
                            @csrf @method('DELETE')<button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="table-empty">{{ __('No records found.') }}</td></tr>
            @endforelse
        </tbody>
        <x-slot name="footer">{{ $turmas->links() }}</x-slot>
    </x-data-table>
</x-app-layout>
