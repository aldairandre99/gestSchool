<x-app-layout>
    <x-page-header :title="$disciplina->nome" :subtitle="__('Edit')" />
    <x-card>
        <form method="POST" action="{{ route('disciplinas.update', $disciplina) }}">@csrf @method('PUT') @include('disciplinas._form', ['disciplina' => $disciplina])</form>
    </x-card>
</x-app-layout>
