<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ $user->name }}</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <x-flash />
            <div class="bg-white shadow rounded-lg p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-500">{{ __('Name') }}</dt><dd class="font-medium">{{ $user->name }}</dd></div>
                    <div><dt class="text-gray-500">{{ __('Email') }}</dt><dd class="font-medium">{{ $user->email }}</dd></div>
                    <div><dt class="text-gray-500">{{ __('Phone') }}</dt><dd class="font-medium">{{ $user->phone ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500">{{ __('Status') }}</dt><dd class="font-medium">{{ $user->is_active ? __('Active') : __('Inactive') }}</dd></div>
                    <div class="sm:col-span-2">
                        <dt class="text-gray-500">{{ __('Roles') }}</dt>
                        <dd class="mt-1">
                            @foreach($user->roles as $r)
                                <span class="inline-block bg-gray-100 rounded px-2 py-0.5 text-xs">{{ str_replace('_', ' ', $r->name) }}</span>
                            @endforeach
                        </dd>
                    </div>
                </dl>
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('users.edit', $user) }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('Edit') }}</a>
                    <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-100 text-sm rounded">{{ __('Back') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
