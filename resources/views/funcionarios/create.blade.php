<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Staff')" />
    <x-card>
        <form method="POST" action="{{ route('funcionarios.store') }}">@csrf @include('funcionarios._form', ['funcionario' => null])</form>
    </x-card>
</x-app-layout>
