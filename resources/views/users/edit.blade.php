<x-app-layout>
    <x-page-header :title="$user->name" :subtitle="__('Edit')" />
    <x-card>
        <form method="POST" action="{{ route('users.update', $user) }}">@csrf @method('PUT') @include('users._form', ['user' => $user])</form>
    </x-card>
</x-app-layout>
