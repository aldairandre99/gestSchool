<x-app-layout>
    <x-page-header :title="__('Schedule')">
        <x-slot name="subtitleSlot">
            <x-turma-label :turma="$turma" :showAno="true" />
        </x-slot>
        <x-slot name="actions">
            @hasanyrole('director_geral|director_pedagogico|secretario')
                <x-btn variant="primary" icon="grid-3x3" :href="route('horarios.bulk-turma', $turma)">{{ __('Edit schedule') }}</x-btn>
            @endhasanyrole
            <x-btn variant="danger" icon="file-down" :href="route('horarios.turma.pdf', $turma)">{{ __('Export PDF') }}</x-btn>
            <x-btn variant="secondary" :href="route('horarios.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    @include('horarios._grid', ['modo' => 'turma'])
</x-app-layout>
