<x-app-layout>
    <x-page-header :title="__('Schedule')" :subtitle="$professor->user->name">
        <x-slot name="actions">
            @hasanyrole('director_geral|director_pedagogico|secretario')
                <x-btn variant="primary" icon="pencil" :href="route('horarios.bulk-professor', $professor)">{{ __('Edit teacher schedule') }}</x-btn>
            @endhasanyrole
            <x-btn variant="danger" icon="file-down" :href="route('horarios.professor.pdf', $professor)">{{ __('Export PDF') }}</x-btn>
            <x-btn variant="secondary" :href="route('horarios.index')">{{ __('Back') }}</x-btn>
        </x-slot>
    </x-page-header>

    @include('horarios._grid', ['modo' => 'professor'])
</x-app-layout>
