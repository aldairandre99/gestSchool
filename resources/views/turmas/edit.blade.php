<x-app-layout>
    <x-page-header :title="__('Edit') . ' — ' . __('Class Groups')">
        <x-slot name="subtitleSlot">
            <x-turma-label :turma="$turma" :showAno="true" />
        </x-slot>
    </x-page-header>
    <x-card>
        <form method="POST" action="{{ route('turmas.update', $turma) }}">@csrf @method('PUT') @include('turmas._form', ['turma' => $turma])</form>
    </x-card>
</x-app-layout>
