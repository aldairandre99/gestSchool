<x-app-layout>
    <x-page-header :title="__('Subjects List')" />

    <x-data-table :createUrl="route('disciplinas.create')">
        <thead>
            <tr>
                <th>{{ __('Abbreviation') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Weekly Hours') }}</th>
                <th>{{ __('Status') }}</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($disciplinas as $d)
                <tr>
                    <td class="font-mono text-xs text-muted">{{ $d->sigla ?? '—' }}</td>
                    <td class="font-semibold text-navy">{{ $d->nome }}</td>
                    <td>{{ $d->carga_horaria_semanal ?? '—' }}</td>
                    <td>@if($d->activa)<x-badge variant="success">{{ __('Active') }}</x-badge>@else<x-badge variant="muted">{{ __('Inactive') }}</x-badge>@endif</td>
                    <td class="table-actions">
                        <x-btn-link variant="muted" :href="route('disciplinas.edit', $d)">{{ __('Edit') }}</x-btn-link>
                        <form action="{{ route('disciplinas.destroy', $d) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar?');">
                            @csrf @method('DELETE')<button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="table-empty">{{ __('No records found.') }}</td></tr>
            @endforelse
        </tbody>
        <x-slot name="footer">{{ $disciplinas->links() }}</x-slot>
    </x-data-table>
</x-app-layout>
