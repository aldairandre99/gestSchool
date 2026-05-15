<x-app-layout>
    <x-page-header :title="__('Schedule')">
        <x-slot name="subtitleSlot">
            <x-turma-label :turma="$turma" :showAno="true" />
        </x-slot>
        <x-slot name="actions">
            <x-btn variant="primary" icon="printer" href="javascript:print()">{{ __('Print') }}</x-btn>
            <x-btn variant="secondary" :href="route('horarios.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    @include('horarios._grid', ['modo' => 'turma'])
</x-app-layout>
