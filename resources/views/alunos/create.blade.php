<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Student')" />
    <x-card>
        <form method="POST" action="{{ route('alunos.store') }}">@csrf @include('alunos._form', ['aluno' => null])</form>
    </x-card>
</x-app-layout>
