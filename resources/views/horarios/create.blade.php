<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Schedule')" />
    <x-card>
        <form method="POST" action="{{ route('horarios.store') }}">@csrf @include('horarios._form', ['horario' => null])</form>
    </x-card>
</x-app-layout>
