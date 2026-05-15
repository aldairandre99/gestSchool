<x-app-layout>
    <x-page-header :title="__('Teachers')" />

    <x-data-table
        :searchPlaceholder="__('Search') . ' nome ou email'"
        :searchValue="$q ?? ''"
        :createUrl="auth()->user()->hasAnyRole(['director_geral', 'director_pedagogico', 'secretario']) ? route('professores.create') : null">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Subjects') }}</th>
                <th>Assistente</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($professores as $p)
                <tr>
                    <td class="text-muted font-mono text-xs">{{ $p->numero_professor ?? '—' }}</td>
                    <td class="font-semibold text-navy">{{ $p->user->name }}</td>
                    <td class="text-muted">{{ $p->user->email }}</td>
                    <td>{{ $p->disciplinas ?? '—' }}</td>
                    <td>
                        @if($p->assistente)<x-badge variant="info">{{ __('Yes') }}</x-badge>
                        @else<span class="text-muted text-xs">{{ __('No') }}</span>@endif
                    </td>
                    <td class="table-actions">
                        <x-btn-link :href="route('professores.show', $p)">{{ __('View') }}</x-btn-link>
                        <x-btn-link variant="muted" :href="route('professores.edit', $p)">{{ __('Edit') }}</x-btn-link>
                        <form action="{{ route('professores.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Delete?') }}');">
                            @csrf @method('DELETE')<button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="table-empty">{{ __('No records found.') }}</td></tr>
            @endforelse
        </tbody>
        <x-slot name="footer">{{ $professores->links() }}</x-slot>
    </x-data-table>
</x-app-layout>
