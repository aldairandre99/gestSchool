<x-app-layout>
    <x-page-header :title="$evento->titulo" :subtitle="__('Edit')" />
    <x-card>
        <form method="POST" action="{{ route('eventos.update', $evento) }}">@csrf @method('PUT') @include('eventos._form', ['evento' => $evento])</form>

        <form action="{{ route('eventos.destroy', $evento) }}" method="POST" class="mt-6 pt-6 border-t border-gray-100" onsubmit="return confirm('{{ __('Delete?') }}');">
            @csrf @method('DELETE')
            <x-btn variant="danger" type="submit" icon="trash-2">{{ __('Delete') }}</x-btn>
        </form>
    </x-card>
</x-app-layout>
