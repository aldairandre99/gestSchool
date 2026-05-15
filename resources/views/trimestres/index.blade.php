<x-app-layout>
    <x-page-header :title="__('Terms')" />

    <x-data-table :createUrl="route('trimestres.create')">
        <thead>
            <tr>
                <th>{{ __('School Year') }}</th>
                <th>{{ __('Term') }}</th>
                <th>{{ __('Start') }}</th>
                <th>{{ __('End') }}</th>
                <th>{{ __('Status') }}</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($trimestres as $t)
                <tr>
                    <td>{{ $t->anoLectivo->codigo }}</td>
                    <td class="font-semibold text-navy">{{ $t->numero }}º</td>
                    <td>{{ $t->inicio->format('d/m/Y') }}</td>
                    <td>{{ $t->fim->format('d/m/Y') }}</td>
                    <td>
                        @if($t->aberto)<x-badge variant="success">{{ __('Open') }}</x-badge>
                        @else<x-badge variant="muted">{{ __('Closed') }}</x-badge>@endif
                    </td>
                    <td class="table-actions">
                        <x-btn-link variant="muted" :href="route('trimestres.edit', $t)">{{ __('Edit') }}</x-btn-link>
                        <form action="{{ route('trimestres.destroy', $t) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Delete?') }}');">
                            @csrf @method('DELETE')<button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="table-empty">{{ __('No records found.') }}</td></tr>
            @endforelse
        </tbody>
        <x-slot name="footer">{{ $trimestres->links() }}</x-slot>
    </x-data-table>
</x-app-layout>
