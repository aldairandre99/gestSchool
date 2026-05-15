<x-app-layout>
    <x-page-header :title="__('Classes')" />

    <x-data-table :createUrl="route('classes.create')">
        <thead>
            <tr>
                <th>{{ __('Order') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Level') }}</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($classes as $c)
                <tr>
                    <td class="text-muted">{{ $c->ordem }}</td>
                    <td class="font-semibold text-navy">{{ $c->nome }}</td>
                    <td>{{ $c->nivel ?? '—' }}</td>
                    <td class="table-actions">
                        <x-btn-link :href="route('classes.show', $c)">Ver</x-btn-link>
                        <x-btn-link variant="muted" :href="route('classes.edit', $c)">{{ __('Edit') }}</x-btn-link>
                        <form action="{{ route('classes.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar?');">
                            @csrf @method('DELETE')<button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="table-empty">{{ __('No records found.') }}</td></tr>
            @endforelse
        </tbody>
        <x-slot name="footer">{{ $classes->links() }}</x-slot>
    </x-data-table>
</x-app-layout>
