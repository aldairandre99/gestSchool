<x-app-layout>
    <x-page-header :title="__('Users')" subtitle="Gestão de utilizadores do sistema" />

    <x-data-table
        :searchPlaceholder="__('Search') . ' nome ou email'"
        :searchValue="$q ?? ''"
        :createUrl="route('users.create')">
        <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Roles') }}</th>
                <th>{{ __('Status') }}</th>
                <th class="text-right">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td class="font-semibold text-navy">{{ $user->name }}</td>
                    <td class="text-muted">{{ $user->email }}</td>
                    <td>
                        @foreach($user->roles as $r)
                            <x-badge variant="muted">{{ str_replace('_', ' ', $r->name) }}</x-badge>
                        @endforeach
                    </td>
                    <td>
                        @if($user->is_active)
                            <x-badge variant="success">{{ __('Active') }}</x-badge>
                        @else
                            <x-badge variant="danger">{{ __('Inactive') }}</x-badge>
                        @endif
                    </td>
                    <td class="table-actions">
                        <x-btn-link :href="route('users.show', $user)">{{ __('View') }}</x-btn-link>
                        <x-btn-link variant="muted" :href="route('users.edit', $user)">{{ __('Edit') }}</x-btn-link>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Delete?') }}');">
                            @csrf @method('DELETE')
                            <button class="btn-link btn-link-danger">{{ __('Delete') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="table-empty">{{ __('No records found.') }}</td></tr>
            @endforelse
        </tbody>
        <x-slot name="footer">{{ $users->links() }}</x-slot>
    </x-data-table>
</x-app-layout>
