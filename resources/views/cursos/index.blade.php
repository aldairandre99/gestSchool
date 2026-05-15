<x-app-layout>
    <x-page-header title="Cursos" subtitle="Cursos do ensino médio" />

    <x-data-table :createUrl="route('cursos.create')">
        <thead>
            <tr>
                <th>{{ __('Abbreviation') }}</th>
                <th>{{ __('Name') }}</th>
                <th>Anos / Classes</th>
                <th>{{ __('Status') }}</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cursos as $c)
                <tr>
                    <td class="font-mono text-xs"><x-badge variant="primary">{{ $c->sigla }}</x-badge></td>
                    <td class="font-semibold text-navy">{{ $c->nome }}</td>
                    <td>{{ $c->classes_count }} anos</td>
                    <td>@if($c->activo)<x-badge variant="success">{{ __('Active') }}</x-badge>@else<x-badge variant="muted">{{ __('Inactive') }}</x-badge>@endif</td>
                    <td class="table-actions">
                        <x-btn-link :href="route('cursos.show', $c)">Ver</x-btn-link>
                        <x-btn-link variant="muted" :href="route('cursos.edit', $c)">{{ __('Edit') }}</x-btn-link>
                        <form action="{{ route('cursos.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar?');">
                            @csrf @method('DELETE')<button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="table-empty">{{ __('No records found.') }}</td></tr>
            @endforelse
        </tbody>
        <x-slot name="footer">{{ $cursos->links() }}</x-slot>
    </x-data-table>
</x-app-layout>
