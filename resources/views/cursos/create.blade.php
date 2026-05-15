<x-app-layout>
    <x-page-header :title="__('New course')" />
    <x-card>
        <form method="POST" action="{{ route('cursos.store') }}">@csrf @include('cursos._form', ['curso' => null])</form>
    </x-card>
</x-app-layout>
