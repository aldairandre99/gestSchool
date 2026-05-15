<x-app-layout>
    <x-page-header :title="$classe->nome" :subtitle="__('Edit')" />
    <x-card>
        <form method="POST" action="{{ route('classes.update', $classe) }}">@csrf @method('PUT') @include('classes._form', ['classe' => $classe])</form>
    </x-card>
</x-app-layout>
