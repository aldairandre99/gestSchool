<x-app-layout>
    <x-page-header title="Novo curso" />
    <x-card>
        <form method="POST" action="{{ route('cursos.store') }}">@csrf @include('cursos._form', ['curso' => null])</form>
    </x-card>
</x-app-layout>
