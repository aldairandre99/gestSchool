<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">{{ __('Classes') }}</h2></x-slot>
    <div class="py-8"><div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4"><x-flash />
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex justify-end mb-3"><a href="{{ route('classes.create') }}" class="px-4 py-2 bg-gray-800 text-white text-sm rounded">{{ __('New') }}</a></div>
            <table class="min-w-full text-sm"><thead class="text-left text-gray-500 border-b"><tr>
                <th class="py-2 pr-3">{{ __('Order') }}</th>
                <th class="py-2 pr-3">{{ __('Name') }}</th>
                <th class="py-2 pr-3">{{ __('Level') }}</th>
                <th class="py-2 pr-3 text-right">{{ __('Actions') }}</th>
            </tr></thead><tbody>
                @forelse($classes as $c)
                    <tr class="border-b last:border-0">
                        <td class="py-2 pr-3 text-gray-500">{{ $c->ordem }}</td>
                        <td class="py-2 pr-3 font-medium">{{ $c->nome }}</td>
                        <td class="py-2 pr-3">{{ $c->nivel ?? '—' }}</td>
                        <td class="py-2 pr-3 text-right">
                            <a href="{{ route('classes.show', $c) }}" class="text-blue-600 text-xs">View</a>
                            <a href="{{ route('classes.edit', $c) }}" class="text-gray-700 text-xs ms-2">{{ __('Edit') }}</a>
                            <form action="{{ route('classes.destroy', $c) }}" method="POST" class="inline" onsubmit="return confirm('?');">@csrf @method('DELETE')<button class="text-red-600 text-xs ms-2">{{ __('Delete') }}</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-4 text-center text-gray-500">{{ __('No records found.') }}</td></tr>
                @endforelse
            </tbody></table>
            <div class="mt-4">{{ $classes->links() }}</div>
        </div>
    </div></div>
</x-app-layout>
