<x-app-layout>
    <x-page-header :title="$curso->nome" :subtitle="$curso->sigla" />
    <x-card>
        <form method="POST" action="{{ route('cursos.update', $curso) }}">@csrf @method('PUT') @include('cursos._form', ['curso' => $curso])</form>
    </x-card>
</x-app-layout>
