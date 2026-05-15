<x-app-layout>
    <x-page-header :title="$turma->classe->nome . ' ' . $turma->nome" :subtitle="__('Edit')" />
    <x-card>
        <form method="POST" action="{{ route('turmas.update', $turma) }}">@csrf @method('PUT') @include('turmas._form', ['turma' => $turma])</form>
    </x-card>
</x-app-layout>
