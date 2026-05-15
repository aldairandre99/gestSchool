<x-app-layout>
    <x-page-header :title="__('New') . ' — ' . __('Classes')" />
    <x-card>
        <form method="POST" action="{{ route('classes.store') }}">@csrf @include('classes._form', ['classe' => null])</form>
    </x-card>
</x-app-layout>
