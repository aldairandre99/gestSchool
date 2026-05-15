<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Teacher')" />
    <x-card>
        <form method="POST" action="{{ route('professores.store') }}">@csrf @include('professores._form', ['professor' => null])</form>
    </x-card>
</x-app-layout>
