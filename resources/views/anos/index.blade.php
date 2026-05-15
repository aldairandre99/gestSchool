<x-app-layout>
    <x-page-header :title="__('Academic Years')" />

    <x-data-table :createUrl="route('anos.create')">
        <thead>
            <tr>
                <th>{{ __('Code') }}</th>
                <th>{{ __('Start') }}</th>
                <th>{{ __('End') }}</th>
                <th>{{ __('Status') }}</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($anos as $a)
                <tr>
                    <td class="font-mono text-navy">{{ $a->codigo }}</td>
                    <td>{{ $a->inicio->format('d/m/Y') }}</td>
                    <td>{{ $a->fim->format('d/m/Y') }}</td>
                    <td>@if($a->activo)<x-badge variant="success">{{ __('Active Year') }}</x-badge>@else<span class="text-muted text-xs">—</span>@endif</td>
                    <td class="table-actions">
                        <x-btn-link variant="muted" :href="route('anos.edit', $a)">{{ __('Edit') }}</x-btn-link>
                        <form action="{{ route('anos.destroy', $a) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Delete?') }}');">
                            @csrf @method('DELETE')<button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="table-empty">{{ __('No records found.') }}</td></tr>
            @endforelse
        </tbody>
        <x-slot name="footer">{{ $anos->links() }}</x-slot>
    </x-data-table>
</x-app-layout>
