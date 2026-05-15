<x-app-layout>
    <x-page-header :title="__('New lesson')" />
    <x-card>
        <form method="POST" action="{{ route('aulas.store') }}">@csrf @include('aulas._form', ['aula' => null])</form>
    </x-card>
</x-app-layout>
