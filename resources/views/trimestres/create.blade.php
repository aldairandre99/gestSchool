<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Term')" />
    <x-card>
        <form method="POST" action="{{ route('trimestres.store') }}">@csrf @include('trimestres._form', ['trimestre' => null])</form>
    </x-card>
</x-app-layout>
