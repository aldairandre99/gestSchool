<x-app-layout>
    <x-page-header :title="__('Guardians')" />

    <x-data-table
        :searchPlaceholder="__('Search')"
        :searchValue="$q ?? ''"
        :createUrl="route('encarregados.create')">
        <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Phone') }}</th>
                <th>Profissão</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($encarregados as $e)
                <tr>
                    <td class="font-semibold text-navy">{{ $e->user->name }}</td>
                    <td class="text-muted">{{ $e->user->email }}</td>
                    <td>{{ $e->user->phone ?? '—' }}</td>
                    <td>{{ $e->profissao ?? '—' }}</td>
                    <td class="table-actions">
                        <x-btn-link :href="route('encarregados.show', $e)">Ver</x-btn-link>
                        <x-btn-link variant="muted" :href="route('encarregados.edit', $e)">{{ __('Edit') }}</x-btn-link>
                        <form action="{{ route('encarregados.destroy', $e) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar?');">
                            @csrf @method('DELETE')<button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="table-empty">{{ __('No records found.') }}</td></tr>
            @endforelse
        </tbody>
        <x-slot name="footer">{{ $encarregados->links() }}</x-slot>
    </x-data-table>
</x-app-layout>
