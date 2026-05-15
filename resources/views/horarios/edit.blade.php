<x-app-layout>
    <x-page-header :title="__('Edit') . ' — ' . __('Schedule')" />
    <x-card>
        <form method="POST" action="{{ route('horarios.update', $horario) }}">@csrf @method('PUT') @include('horarios._form', ['horario' => $horario])</form>

        <form action="{{ route('horarios.destroy', $horario) }}" method="POST" class="mt-4" onsubmit="return confirm('{{ __('Delete?') }}');">
            @csrf @method('DELETE')
            <x-btn variant="danger" type="submit" icon="trash-2">{{ __('Delete') }}</x-btn>
        </form>
    </x-card>
</x-app-layout>
