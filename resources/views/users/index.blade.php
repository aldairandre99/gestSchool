<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">{{ __('Users') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <x-flash />

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4 gap-2">
                    <form method="GET" class="flex gap-2 grow">
                        <input type="text" name="q" value="{{ $q }}" placeholder="{{ __('Search') }}"
                               class="border-gray-300 rounded-md text-sm w-full sm:w-72">
                        <button class="px-3 py-2 bg-gray-100 text-sm rounded">{{ __('Search') }}</button>
                    </form>
                    <a href="{{ route('users.create') }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded whitespace-nowrap">{{ __('New') }} {{ __('User') ?? 'user' }}</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-gray-500 border-b">
                            <tr>
                                <th class="py-2 pr-3">{{ __('Name') }}</th>
                                <th class="py-2 pr-3">{{ __('Email') }}</th>
                                <th class="py-2 pr-3">{{ __('Roles') }}</th>
                                <th class="py-2 pr-3">{{ __('Status') }}</th>
                                <th class="py-2 pr-3 text-right">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr class="border-b last:border-0">
                                    <td class="py-2 pr-3 font-medium text-gray-800">{{ $user->name }}</td>
                                    <td class="py-2 pr-3 text-gray-600">{{ $user->email }}</td>
                                    <td class="py-2 pr-3">
                                        @foreach($user->roles as $r)
                                            <span class="inline-block bg-gray-100 rounded px-2 py-0.5 text-xs">{{ str_replace('_', ' ', $r->name) }}</span>
                                        @endforeach
                                    </td>
                                    <td class="py-2 pr-3">
                                        @if($user->is_active)
                                            <span class="text-green-700 text-xs">{{ __('Active') }}</span>
                                        @else
                                            <span class="text-red-600 text-xs">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td class="py-2 pr-3 text-right">
                                        <a href="{{ route('users.show', $user) }}" class="text-blue-600 hover:underline text-xs">{{ __('Edit') === 'Editar' ? 'Ver' : 'View' }}</a>
                                        <a href="{{ route('users.edit', $user) }}" class="text-gray-700 hover:underline text-xs ms-2">{{ __('Edit') }}</a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('?');">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:underline text-xs ms-2">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-4 text-center text-gray-500">{{ __('No records found.') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">{{ $users->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
