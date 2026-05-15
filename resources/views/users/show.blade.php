<x-app-layout>
    <x-page-header :title="$user->name">
        <x-slot name="actions">
            <x-btn variant="primary" icon="pencil" :href="route('users.edit', $user)">{{ __('Edit') }}</x-btn>
            <x-btn variant="secondary" :href="route('users.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    <x-card>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
            <div><dt class="form-label">{{ __('Email') }}</dt><dd class="text-navy">{{ $user->email }}</dd></div>
            <div><dt class="form-label">{{ __('Phone') }}</dt><dd class="text-navy">{{ $user->phone ?? '—' }}</dd></div>
            <div><dt class="form-label">{{ __('Status') }}</dt><dd>
                @if($user->is_active)<x-badge variant="success">{{ __('Active') }}</x-badge>
                @else<x-badge variant="danger">{{ __('Inactive') }}</x-badge>@endif
            </dd></div>
            <div class="sm:col-span-2">
                <dt class="form-label">{{ __('Roles') }}</dt>
                <dd class="space-x-1">
                    @foreach($user->roles as $r)<x-badge variant="muted">{{ __($r->name) }}</x-badge>@endforeach
                </dd>
            </div>
        </dl>
    </x-card>
</x-app-layout>
