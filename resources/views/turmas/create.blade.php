<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Class Groups')" />
    <x-card>
        <form method="POST" action="{{ route('turmas.store') }}">@csrf @include('turmas._form', ['turma' => null])</form>
    </x-card>
</x-app-layout>
