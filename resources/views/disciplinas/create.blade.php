<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Subjects List')" />
    <x-card>
        <form method="POST" action="{{ route('disciplinas.store') }}">@csrf @include('disciplinas._form', ['disciplina' => null])</form>
    </x-card>
</x-app-layout>
