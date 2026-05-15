<x-app-layout>
    <x-page-header :title="__('New') . ' ' . __('User')" />
    <x-card>
        <form method="POST" action="{{ route('users.store') }}">@csrf @include('users._form', ['user' => null])</form>
    </x-card>
</x-app-layout>
